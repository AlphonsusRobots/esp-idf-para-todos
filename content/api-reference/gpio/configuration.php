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

<p>EPara configurar los pines en el entorno de desarrollo ESP-IDF, la herramienta principal es la función gpio_config(). Su gran ventaja es que permite configurar uno o varios pines a la vez de forma organizada y eficiente.</p>

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
      <td>Sirve para seleccionar los pines que quieres configurar. Cada bit representa un número de pin.</td>
    </tr>
    <tr>
      <td>mode</td>
      <td>&nbspGPIO_MODE_INPUT, GPIO_MODE_OUTPUT</td>
      <td>Define si el pin actuará como una entrada (para leer datos) o como una salida (para enviar señales)</td>
    </tr>
    <tr>
      <td>pull_up_en</td>
      <td>&nbsp1 (para activar) o 0 (para &nbspdesactivar)</td>
      <td>Activa una resistencia interna que conecta el pin a un nivel de voltaje alto (pull-up).
pull_down_en	1 (para activar) o 0 (para desactivar)	</td>
    </tr>
  </tbody>
</table>

<p>Con esta estructura en mente, ya estamos listos para ponerla en práctica. Empecemos configurando un pin como salida..</p>

<div class="code-block-container">
  <pre><code>gpio_config_t io_conf = {};
io_conf.intr_type = GPIO_INTR_DISABLE;        // Sin interrupciones
io_conf.mode = GPIO_MODE_OUTPUT;              // Modo salida
io_conf.pin_bit_mask = (1ULL << GPIO_NUM_2);  // Pin GPIO2
io_conf.pull_down_en = GPIO_PULLDOWN_DISABLE;
io_conf.pull_up_en = GPIO_PULLUP_DISABLE;
gpio_config(&io_conf);</code></pre>
  <button class="copy-btn">Copiar</button>
</div>

<div class="note">
  <strong>⚠️ Advertencia:</strong> <code>gpio_config()</code> sobrescribe todas las configuraciones previas del pin. Úsala con cuidado si el pin tiene múltiples funciones (ej. GPIO + ADC).
</div>

<h2>Pines con limitaciones</h2>
<ul>
  <li><strong>GPIO34–GPIO39</strong>: Solo pueden usarse como <em>entradas</em>. No tienen pull-up/down habilitables por software.</li>
  <li><strong>Strapping pins</strong> (<code>GPIO0, 2, 5, 12, 15</code>): Su estado durante el arranque afecta el modo de inicio del chip.</li>
  <li><strong>ADC2</strong>: No usable cuando Wi-Fi está activo. Prefiere ADC1 (GPIO32–39) en aplicaciones con Wi-Fi.</li>
</ul>

<h2>Verificación de configuración</h2>
<p>Usa <code>gpio_dump_io_configuration()</code> para depurar:</p>

<div class="code-block-container">
  <pre><code>// Imprimir configuración de GPIO2
gpio_dump_io_configuration(NULL, (1ULL << GPIO_NUM_2));</code></pre>
  <button class="copy-btn">Copiar</button>
</div>

<h2>Ejercicio: Autoevaluación</h2>
<p>Responde las siguientes preguntas para comprobar tu comprensión.</p>

<div class="quiz-container" id="quiz">
  <div class="quiz-question">
    <p><strong>1. ¿Qué función configura múltiples aspectos de un GPIO en un solo paso?</strong></p>
    <label><input type="radio" name="q1" value="0"> gpio_set_direction</label>
    <label><input type="radio" name="q1" value="1"> gpio_config</label>
    <label><input type="radio" name="q1" value="2"> gpio_pad_select</label>
    <label><input type="radio" name="q1" value="3"> gpio_reset</label>
    <div class="feedback" id="f1"></div>
  </div>

  <div class="quiz-question">
    <p><strong>2. ¿Qué pines solo pueden usarse como entrada?</strong></p>
    <label><input type="radio" name="q2" value="0"> GPIO0–GPIO5</label>
    <label><input type="radio" name="q2" value="1"> GPIO12–GPIO15</label>
    <label><input type="radio" name="q2" value="2"> GPIO34–GPIO39</label>
    <label><input type="radio" name="q2" value="3"> GPIO25–GPIO27</label>
    <div class="feedback" id="f2"></div>
  </div>

  <div class="quiz-question">
    <p><strong>3. ¿Qué ocurre si usas ADC2 con Wi-Fi activo?</strong></p>
    <label><input type="radio" name="q3" value="0"> Funciona normalmente</label>
    <label><input type="radio" name="q3" value="1"> No puede usarse</label>
    <label><input type="radio" name="q3" value="2"> Requiere calibración</label>
    <label><input type="radio" name="q3" value="3"> Se desactiva automáticamente</label>
    <div class="feedback" id="f3"></div>
  </div>

  <button id="check-answers" class="btn-primary">Verificar respuestas</button>
</div>

<?php include __DIR__ . '/../../../includes/footer.php'; ?>
