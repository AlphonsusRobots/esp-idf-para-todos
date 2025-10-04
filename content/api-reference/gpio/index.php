<?php
require_once __DIR__ . '/../../../config.php';
$page_title = "GPIO & RTC GPIO - ESP-IDF Documentation";
include __DIR__ . '/../../../includes/header.php';
?>

<h1>GPIO & RTC GPIO</h1>

<p class="intro">
  Los pines de Entrada/Salida de Propósito General (GPIO) son fundamentales para interactuar con el mundo físico en el ESP32. 
  Esta sección cubre la configuración, control, interrupciones y el uso de GPIO en modos de bajo consumo (RTC GPIO).
</p>

<div class="card-grid">
  <a href="<?php echo BASE_URL; ?>/content/api-reference/gpio/configuration.php" class="lesson-card">
    <h3>1. Introducción: ¿Qué es un Pin GPIO?</h3>
    <p>Aprende a usar <code>gpio_config()</code> para definir modos, pull-ups/downs e interrupciones.</p>
  </a>

  <a href="#" class="lesson-card disabled">
    <h3>2. Control de GPIO</h3>
    <p>Encender, apagar y leer el estado de los pines GPIO.</p>
  </a>

  <a href="#" class="lesson-card disabled">
    <h3>3. Interrupciones GPIO</h3>
    <p>Manejo de eventos asíncronos con interrupciones por flanco o nivel.</p>
  </a>

  <a href="#" class="lesson-card disabled">
    <h3>4. RTC GPIO</h3>
    <p>Uso de GPIO en modos de bajo consumo como Deep-sleep y con el co-procesador ULP.</p>
  </a>
</div>

<h2>¿Por qué aprender GPIO en ESP32?</h2>
<ul>
  <li>El ESP32 tiene <strong>34 pines GPIO físicos</strong> altamente configurables.</li>
  <li>Los GPIO pueden conectarse a periféricos internos mediante la <strong>matriz GPIO</strong>.</li>
  <li>Los <strong>RTC GPIO</strong> permiten funcionalidad crítica durante el modo de bajo consumo.</li>
  <li>Esencial para sensores, actuadores, comunicación y control en proyectos IoT.</li>
</ul>

<div class="note">
  <strong>💡 Consejo:</strong> Comienza con la lección de <a href="<?php echo BASE_URL; ?>/content/api-reference/gpio/configuration.php">Configuración de GPIO</a> para entender la base antes de avanzar.
</div>

<?php include __DIR__ . '/../../../includes/footer.php'; ?>
