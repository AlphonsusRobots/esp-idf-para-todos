<?php
require_once __DIR__ . '/config.php';
include __DIR__ . '/includes/header.php';
?>

<h1>✅ Sitio Mínimo Funcional</h1>
<p>Si ves este mensaje, ¡todo funciona correctamente!</p>

<ul>
  <li><span class="success">PHP está activo</span></li>
  <li><span class="success">CSS se carga correctamente</span></li>
  <li><span class="success">Rutas están bien configuradas</span></li>
</ul>

<p><strong>BASE_URL:</strong> <?php echo BASE_URL; ?></p>
<p><strong>Ruta física:</strong> <?php echo __DIR__; ?></p>

<?php
echo '</div></body></html>';
?>
