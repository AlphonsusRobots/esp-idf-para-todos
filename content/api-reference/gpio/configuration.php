<?php
require_once __DIR__ . '/../../../config.php';
$page_title = "Configuración de GPIO - ESP-IDF Documentation";
$lesson_id = "gpio-config-01";
include __DIR__ . '/../../../includes/header.php';
?>

<h1>Configuración de GPIO</h1>

<p class="intro">En ESP-IDF, la función <code>gpio_config()</code> es la forma principal de configurar los pines GPIO. Esta guía explica cómo usarla correctamente y qué limitaciones debes conocer.</p>
<h2>Introducción: ¿Qué es un Pin GPIO?</h2>
<p>¡Bienvenido al fascinante mundo de la electrónica y los sistemas embebidos con el ESP32! Si alguna vez has querido que un microcontrolador interactúe con el mundo real, estás en el lugar correcto. El primer paso para lograrlo es dominar los pines GPIO.

Piensa en un pin GPIO (General Purpose Input/Output o Entrada/Salida de Propósito General) como un conector multifuncional que tu ESP32 puede controlar. Puede actuar como un interruptor de luz que tu programa enciende y apaga (Salida), o como un sensor que lee si una ventana está abierta o cerrada (Entrada). El chip ESP32 cuenta con 34 pines GPIO físicos (con números que van del 0 al 39, aunque no todos los números en ese rango están disponibles), lo que le otorga una increíble versatilidad para conectar LEDs, botones, sensores y todo tipo de componentes.

En esta guía, aprenderás los fundamentos para configurar y controlar estos pines. Comencemos por el paso más importante: elegir el pin correcto para tu primer proyecto.
</p>
<img 
  src="<?php echo BASE_URL; ?>/assets/images/diagrams/gpio/ESP32-DOIT-DEV-KIT-v-PINOUT.png" 
  alt="Arquitectura de GPIO en ESP32"
  class="diagram"
>

<h2>¡Cuidado! Eligiendo el Pin GPIO Correcto para tu Proyecto</h2>

<p>Aunque el ESP32 ofrece muchos pines, no todos son iguales. Algunos tienen funciones especiales durante el arranque o están reservados para tareas internas. Para un principiante, es crucial evitar estos pines para no encontrarse con comportamientos inesperados.</p>

<p>Aquí tienes una guía de los pines que debes tratar con precaución en tus primeros experimentos:</p>
<ul>
<li> <strong>Pines de Strapping (GPIO 0, 2, 5, 12, 15)</strong>: Estos pines son especiales porque el <em>ESP32 lee su estado al arrancar</em> para determinar su modo de funcionamiento (por ejemplo, el modo de programación). Si conectas algo que altere su estado al encender el microcontrolador, podrías impedir que se inicie correctamente.</li><br>

<li> <strong>Pines de la Memoria Flash (GPIO 6-11, 16-17)</strong>: Estos pines ya están ocupados. <em>Se utilizan internamente para comunicarse con la memoria flash</em> donde se almacena tu programa. <em>¡No los uses para ningún otro propósito!</em></li><br>

<li> <strong>Pines de Depuración (JTAG) (GPIO 12-15)</strong>: Estos pines <em>están designados para herramientas de depuración avanzadas </em>que permiten analizar el código en tiempo real. Aunque se pueden usar como GPIO, es mejor dejarlos libres para su propósito original.</li><br>

<li> <strong>Pines de Solo Entrada (GPI) (GPIO 34-39)</strong>: Como su nombre indica, <em>estos pines son únicamente para entrada</em>. No puedes usarlos para encender un LED o activar un relé. Además, una limitación muy importante es que no tienen resistencias pull-up o pull-down internas, un concepto que exploraremos más adelante.</li><br>
</ul>



Como habrás notado, algunos pines como el <strong>GPIO 12 y 15</strong> aparecen en múltiples categorías. Esto subraya su naturaleza sensible y por qué es mejor que los principiantes los eviten para usos generales.

Para evitar complicaciones, te recomendamos empezar con los siguientes pines, que son seguros y versátiles para la mayoría de los proyectos de iniciación:

<strong>Pines Seguros para Principiantes: GPIO 4, GPIO 18, GPIO 19, GPIO 21, GPIO 22, GPIO 23, GPIO 25, GPIO 26, GPIO 27, GPIO 32, GPIO 33</strong>

Ahora que sabes qué pines elegir, veamos cómo configurarlos.</p>

<h2>El Corazón de la Configuración: La Estructura </h2>

<p>Para configurar los pines en el entorno de desarrollo ESP-IDF, la herramienta principal es la función <strong>gpio_config()</strong>. Su gran ventaja es que permite configurar uno o varios pines a la vez de forma organizada y eficiente.</p>

<p>Esta función no recibe parámetros sueltos, sino que utiliza una estructura llamada gpio_config_t para agrupar todas las opciones de configuración. A continuación, desglosamos sus miembros más importantes.</p>

<table class="reference-table">
  <thead>
    <tr>
      <th>Parámetro</th>
      <th>Tipo de Valor</th>
      <th>Descripción Sencilla</th>
    </tr>
  </thead>
  <tbody>
     <tr>
      <td>pin_bit_mask</td>
      <td> &nbspMáscara de bits (un número)</td>
      <td>Sirve para seleccionar los pines que quieres configurar. Cada bit representa un número de pin. Se pueden configurar varios a la vez</td>
    </tr>
    <tr>
      <td>mode</td>
      <td>&nbspGPIO_MODE_INPUT, GPIO_MODE_OUTPUT</td>
      <td>Define si el pin actuará como una entrada (para leer datos) o como una salida (para enviar señales)</td>
    </tr>
    <tr>
      <td>pull_up_en</td>
      <td>&nbsp1 (para activar) o 0 (para &nbspdesactivar)</td>
      <td>Activa una resistencia interna que conecta el pin a un nivel de voltaje alto (pull-up).pull_down_en	1 (para activar) o 0 (para desactivar)	</td>
    </tr>
     <tr>
      <td>pull_down_en</td>
      <td>&nbsp1 (para activar) o 0 (para &nbspdesactivar)</td>
      <td>Activa una resistencia interna que conecta el pin a un nivel de voltaje alto (pull_down_en)	1 (para activar) o 0 (para desactivar)	</td>
    </tr>
    
  </tbody>
</table>

<p>Con esta estructura en mente, ya estamos listos para ponerla en práctica. Empecemos configurando un pin como salida..</p>

<div class="code-block-container">
  <pre><code>gpio_config_t io_conf = {};
io_conf.pin_bit_mask = (1ULL << GPIO_NUM_2,); // Selecciona GPIO2
io_conf.mode = GPIO_MODE_OUTPUT;              // Modo salida
io_conf.intr_type = GPIO_INTR_DISABLE;        // Sin interrupciones
io_conf.pull_up_en = GPIO_PULLUP_DISABLE;     // Sin pull-up
io_conf.pull_down_en = GPIO_PULLDOWN_DISABLE; // Sin pull-down
gpio_config(&io_conf);</code></pre>
  <button class="copy-btn">Copiar</button>
</div>

<p>En caso que quisieramos configurar varios pines a la vez. Añadimos los pines necesarios..</p>

<div class="code-block-container">
  <pre><code>
io_conf.pin_bit_mask = (1ULL << GPIO_NUM_2,) 
                     | (1ULL << GPIO_NUM_3,) 
                     | (1ULL << GPIO_NUM_4,) ; // Selecciona GPIO2 GPIO03 y GPIO4
</code></pre>
  <button class="copy-btn">Copiar</button>
</div>

<div class="note">
  <strong>⚠️ Advertencia:</strong> <code>gpio_config()</code> sobrescribe todas las configuraciones previas del pin. Úsala con cuidado si el pin tiene múltiples funciones (ej. GPIO + ADC).
</div>

<p>En este ejemplo, hemos configurado el GPIO2 como una salida simple sin resistencias pull-up o pull-down. La máscara de bits (pin_bit_mask) utiliza un desplazamiento de bits para seleccionar el pin específico que queremos configurar.</p>

<h2>Verificación de configuración</h2>
<p>Usa <code>gpio_dump_io_configuration()</code> para depurar:</p>

<div class="code-block-container">
  <pre><code>// Imprimir configuración de GPIO2
gpio_dump_io_configuration(NULL, (1ULL << GPIO_NUM_2));</code></pre>
  <button class="copy-btn">Copiar</button>
</div>
<h2>Tutorial Práctico: Configurar un Pin como SALIDA</h2>
<h3>Encender un LED</h3>
<p>Nuestro objetivo será configurar un pin para que pueda enviar una señal de "encendido" (nivel alto) o "apagado" (nivel bajo), perfecto para controlar un LED.:</p>

<ul>
<li>Estamos trabajando en lenguaje C por lo tanto lo primero que vemos en el código son los archivos de cabecera, los cuales se definen con la directiva "#include" seguida del nombre del archivo de cabecera entre comillas "......" o corchetes <.......> en el caso de los sistemas enbebidos se pueden utilizar indistintamente.</li>
<div class="code-block-container">
  <pre><code>
#include "freertos/FreeRTOS.h"
#include "freertos/task.h"
#include "driver/gpio.h"
</code></pre>
</div>
<li>Seguidamente va la funcion principal del codigo "C" la funcion app_main() en ESP-IDF es el punto de entrada de la aplicación, similar a la función main() en un programa de C estándar. </li>
<div class="code-block-container">
  <pre><code>
void app_main(void) {
</code></pre>
</div>
<li> Definimos la Configuración: Declaramos una variable del tipo <strong>gpio_config_t</strong>. 
<div class="code-block-container">
  <pre><code>
gpio_config_t io_conf = {};
</code></pre>
</div>
Luego, rellenamos sus miembros. Para una salida simple, queremos el modo <strong>GPIO_MODE_OUTPUT</strong> y no necesitamos las resistencias <strong>pull-up</strong> o <strong>pull-down</strong> ni tampoco necesitamos habilitar las interrupciones.
<div class="code-block-container">
  <pre><code>
io_conf.pin_bit_mask = (1ULL << GPIO_NUM_2,); // Selecciona GPIO2
io_conf.mode = GPIO_MODE_OUTPUT;              // Modo salida
io_conf.intr_type = GPIO_INTR_DISABLE;        // Sin interrupciones
io_conf.pull_up_en = GPIO_PULLUP_DISABLE;     // Sin pull-up
io_conf.pull_down_en = GPIO_PULLDOWN_DISABLE; // Sin pull-down
</code></pre>
</div></li>
<li> Aplicar la Configuración: Una vez que nuestra estructura io_conf está lista, se la pasamos a la función <strong>gpio_config()</strong>para que aplique los cambios a los pines seleccionados.</li>
<div class="code-block-container">
  <pre><code>
gpio_config(&io_conf); // Como se ha explicado antes es lafuncion que aplique los cambios a los pines seleccionados 
</code></pre>
</div>
<li> Creamos el bucle infinito,con el bucle<strong>while(true){codigo que se repite}</strong> Imagina que el bucle while es como "mientras ("while" en ingles) algo sea verdad "(true) verdad en ingles"", sigue haciendo esto".</li>
<div class="code-block-container">
  <pre><code>
 while(true) {codigo del loop}; // Repetimos el bloque de codigo contenido entre corchetes indefinidamente
</code></pre>
</div>
<li> Controlamos el Nivel Lógico: Con el pin ya configurado como salida, usamos la función gpio_set_level(para poner el pin en el nivel que necesitamos en este caso alto 1 )<strong>gpio_set_level()</strong> para controlar su estado. Le pasamos el número del pin y el nivel deseado: 1 para alto (encender) y 0 para bajo (apagar).</li>
<div class="code-block-container">
  <pre><code>
 gpio_set_level(GPIO_NUM_2, 1); // Pone GPIO2 en estado alto (enciende el LED)
</code></pre>
</div>
<li>Con vTaskDelay() detenemos la tarea actual. Es la forma de ESP-IDF de hacer pausas. No es un delay normal en C. Es la forma MÁS fácil y correcta de decir: "Espera 1 segundo" 1000 TICKS = 1 segundo por lo tanto 1 TICK equvaldrá a 1 milisegundo</li>
<div class="code-block-container">
  <pre><code>
 vTaskDelay(pdMS_TO_TICKS(1000));// Espera 1 segundo 
</code></pre>
</div>

<li> Volvemos a usar la función gpio_set_level() para poner el pin en el nivel que necesitamos en este otro caso bajo 0 )</li>
<div class="code-block-container">
  <pre><code>
 gpio_set_level(GPIO_NUM_2, 0); // Pone GPIO2 en estado bajo (apaga el LED)
</code></pre>
</div>
<li>Con vTaskDelay() detenemos la tarea actual. 1000 TICKS = 1 segundo por lo tanto 1 TICK equivaldrá a 1 milisegundo</li>
<div class="code-block-container">
  <pre><code>
 vTaskDelay(pdMS_TO_TICKS(1000));// Espera 1 segundo 
</code></pre>
</div>
</ul>

<h3>Codigo completo operativo 1</h3>
<p>Con <strong>vTaskDelay()</strong> detenemos la tarea actual. 1000 TICKS = 1 segundo por lo tanto 1 TICK equivaldrá a 1 milisegundo</p>
<div class="code-block-container">
  <pre><code>
#include "freertos/FreeRTOS.h"
#include "freertos/task.h"
#include "driver/gpio.h"

extern "C" void app_main(void){
    gpio_config_t io_config = {};
    io_config.pin_bit_mask = (1ULL << GPIO_NUM_2);// Selecciona GPIO2       
    io_config.mode = GPIO_MODE_OUTPUT;              // Modo salida
    io_config.intr_type = GPIO_INTR_DISABLE;// Sin interrupciones
    io_config.pull_up_en = GPIO_PULLUP_DISABLE;// sin
    io_config.pull_down_en = GPIO_PULLDOWN_DISABLE;// Sin pull-down
    // Aplicar la configuración
    gpio_config(&io_config);

   while(true){
        //Encendemos el LED (nivel del pin número 2 alto = 1)
        gpio_set_level(GPIO_NUM_2, 1);
        vTaskDelay(pdMS_TO_TICKS(1000));// Esperar 1 segundo

        //Apagamos el LED (nivel del pin número 2 bajo = 0)
        gpio_set_level(GPIO_NUM_2, 0);
        vTaskDelay(pdMS_TO_TICKS(1000));// Esperar 1 segundo
   }

}
</code></pre>
</div>
</ul>
<h2>Configurar un pin como entrada</h2>
<h3>Leer estado de un botón</h3>
<p>Vamos a añadir un botón para controlar el estado del LED. El botón cambiará el estado del LED cada vez que se presione. Para ello necesitamos:</p>
<ol>
  <li>Configurar un pin como entrada para el botón</li>
  <li>Leer el estado del botón y cambiar el estado del LED cuando se presione</li>
</ol>
<p>Una cosa a tener en cuenta es el rebote (debounce en ingles) para evitar multiples detecciones por una sola pulsación</p>
<p>En este ejemplo, vamos a hacer un control básico sin debounce muy elaborado, solo con un retardo simple. </p>
<p>Vamos a conectar el botón en un pin (por ejemplo GPIO_NUM_4) y usaremos una resistencia pull-up interna, por lo que cuando se presione el botón, leeremos un nivel bajo.Para ello vamos a realizar los siguientes pasos:</p>
<ul>
<li>Configurar el pin del botón como entrada con resistencia pull-up interna que incluye el propio esp32. Colocarlo a continuacion de la configuracione del pin del LED</li>
<div class="code-block-container">
  <pre><code>
  // Configurar botón como entrada con resistencia pull-up
    gpio_config_t config_boton = {};
        config_boton.pin_bit_mask = (1ULL << BOTON_PIN);// Selecciona el pin del botón
        config_boton.mode = GPIO_MODE_INPUT;// Modo entrada
        config_boton.pull_up_en = GPIO_PULLUP_ENABLE;// Activar pull-up interno
        config_boton.pull_down_en = GPIO_PULLDOWN_DISABLE;// Sin pull-down
        config-boton.intr_type = GPIO_INTR_DISABLE;// Sin interrupciones
    // Aplicar la configuración
    gpio_config(&config_boton);
</code></pre>
</div>
<li>Creamos una variable entera para poner el estado del led a 0 o sea apagado </li>
<div class="code-block-container">
  <pre><code>
int estado_led = 0; // Variable para el estado del LED (0 = apagado, 1 = encendido)
</code></pre>
</div>
<li>En el bucle "while()" Leer el estado del botón.</li>
<div class="code-block-container">
  <pre><code>
int estado_boton = gpio_get_level(BOTON_PIN); // Leer el estado del botón
</code></pre>
</div>
<li>Si (condicional if()) el botón está resionado (nIvel bajo) cambiar el estado del led y esperar un poco par evitar rebotes.</li>
<div class="code-block-container">
  <pre><code>
if (estado_boton == 0) { // Botón presionado (nivel bajo)
    estado_led = !estado_led; // Cambiar el estado del LED
    gpio_set_level(LED_PIN, estado_led); // Actualizar el estado del LED
    vTaskDelay(pdMS_TO_TICKS(300)); // Retardo para evitar rebotes
}
</code></pre>
</div>
<li>Realizamos una breve espera antes de leer el botón otra vez </li>
<div class="code-block-container">
  <pre><code>
vTaskDelay(pdMS_TO_TICKS(50)); // Pequeña espera antes de la siguiente lectura
</code></pre>
</div>
</ul>
<h3>Codigo completo operativo 2</h3>
<p>El código completo quedaría así:</p>
<div class="code-block-container">
  <pre><code>
#include "freertos/FreeRTOS.h"
#include "freertos/task.h"
#include "driver/gpio.h"

#define LED_PIN GPIO_NUM_2 // Pin del LED
#define BOTON_PIN GPIO_NUM_4 // Pin del botón

extern "C" void app_main(void){
    // Configurar LED como salida
    gpio_config_t io_config = {};
    io_config.pin_bit_mask = (1ULL << LED_PIN);// Selecciona GPIO2       
    io_config.mode = GPIO_MODE_OUTPUT;              // Modo salida
    io_config.intr_type = GPIO_INTR_DISABLE;// Sin interrupciones
    io_config.pull_up_en = GPIO_PULLUP_DISABLE;// sin
    io_config.pull_down_en = GPIO_PULLDOWN_DISABLE;// Sin pull-down
    // Aplicar la configuración
    gpio_config(&io_config);

    // Configurar botón como entrada con resistencia pull-up
    gpio_config_t config_boton = {};
        config_boton.pin_bit_mask = (1ULL << BOTON_PIN);// Selecciona el pin del botón
        config_boton.mode = GPIO_MODE_INPUT;// Modo entrada
        config_boton.pull_up_en = GPIO_PULLUP_ENABLE;// Activar pull-up interno
        config_boton.pull_down_en = GPIO_PULLDOWN_DISABLE;// Sin pull-down
        config_boton.intr_type = GPIO_INTR_DISABLE;// Sin interrupciones
    // Aplicar la configuración
    gpio_config(&config_boton);

   int estado_led = 0; // Variable para el estado del LED (0 = apagado, 1 = encendido)

   while(true){
        int estado_boton = gpio_get_level(BOTON_PIN); // Leer el estado del botón

        if (estado_boton == 0) { // Botón presionado (nivel bajo)
            estado_led = !estado_led; // Cambiar el estado del LED
            gpio_set_level(LED_PIN, estado_led); // Actualizar el estado del LED
            vTaskDelay(pdMS_TO_TICKS(300)); // Retardo para evitar rebotes
        }

        vTaskDelay(pdMS_TO_TICKS(50)); // Pequeña espera antes de la siguiente lectura
   }

}
</code></pre>
</div>
<!-- Botón al cuestionario -->
<div class="quiz-button-container">
  <a href="<?php echo BASE_URL; ?>/content/api-reference/gpio/quiz/configuration-quiz.php" class="btn-primary">
    📝 Ir al cuestionario
  </a>
</div>


