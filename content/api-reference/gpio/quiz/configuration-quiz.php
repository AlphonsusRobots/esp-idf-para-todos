<?php
require_once __DIR__ . '/../../../../config.php';
$page_title = "Cuestionario: Configuración de GPIO";
$lesson_url = BASE_URL . '/content/api-reference/gpio/configuration.php';
include __DIR__ . '/../../../../includes/header.php';
?>

<h1>Cuestionario: Configuración de GPIO</h1>
<p>Responde las siguientes preguntas para evaluar tu comprensión.</p>

<form id="quiz-form">
  <div class="quiz-question">
    <p><strong>1. ¿Qué función configura múltiples aspectos de un GPIO en un solo paso?</strong></p>
    <label><input type="radio" name="q1" value="0"> gpio_set_direction</label>
    <label><input type="radio" name="q1" value="1"> gpio_config</label>
    <label><input type="radio" name="q1" value="2"> gpio_pad_select</label>
    <label><input type="radio" name="q1" value="3"> gpio_reset</label>
  </div>

  <div class="quiz-question">
    <p><strong>2. ¿Qué pines solo pueden usarse como entrada?</strong></p>
    <label><input type="radio" name="q2" value="0"> GPIO0–GPIO5</label>
    <label><input type="radio" name="q2" value="1"> GPIO12–GPIO15</label>
    <label><input type="radio" name="q2" value="2"> GPIO34–GPIO39</label>
    <label><input type="radio" name="q2" value="3"> GPIO25–GPIO27</label>
  </div>

  <div class="quiz-question">
    <p><strong>3. ¿Qué ocurre si usas ADC2 con Wi-Fi activo?</strong></p>
    <label><input type="radio" name="q3" value="0"> Funciona normalmente</label>
    <label><input type="radio" name="q3" value="1"> No puede usarse</label>
    <label><input type="radio" name="q3" value="2"> Requiere calibración</label>
    <label><input type="radio" name="q3" value="3"> Se desactiva automáticamente</label>
  </div>

  <button type="button" onclick="gradeQuiz()" class="btn-primary">Enviar respuestas</button>
</form>

<div id="result" style="display:none; margin-top: 2em; padding: 20px; border-radius: 6px;">
  <h2 id="result-title"></h2>
  <p id="result-message"></p>
  <a href="<?php echo $lesson_url; ?>" class="btn-primary">← Volver a la lección</a>
</div>

<script>
function gradeQuiz() {
  const answers = { q1: '1', q2: '2', q3: '1' };
  let score = 0;
  let total = Object.keys(answers).length;

  for (let key in answers) {
    const selected = document.querySelector(`input[name="${key}"]:checked`);
    if (selected && selected.value === answers[key]) {
      score++;
    }
  }

  const resultDiv = document.getElementById('result');
  const title = document.getElementById('result-title');
  const message = document.getElementById('result-message');

  title.textContent = `Resultado: ${score} de ${total} correctas`;

  if (score === total) {
    resultDiv.style.backgroundColor = '#e6f4ea';
    message.innerHTML = '¡Excelente! Dominas la configuración de GPIO. Puedes avanzar a la siguiente lección con confianza.';
  } else if (score >= total / 2) {
    resultDiv.style.backgroundColor = '#fff8e1';
    message.innerHTML = 'Buen intento. Revisa los conceptos sobre pines especiales y la función <code>gpio_config()</code> antes de continuar.';
  } else {
    resultDiv.style.backgroundColor = '#ffebee';
    message.innerHTML = 'Necesitas repasar la lección. Vuelve a leer la sección sobre limitaciones de pines y configuración básica.';
  }

  resultDiv.style.display = 'block';
  document.getElementById('quiz-form').style.display = 'none';
}
</script>

<?php include __DIR__ . '/../../../../includes/footer.php'; ?>
