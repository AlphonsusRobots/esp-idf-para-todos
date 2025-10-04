document.addEventListener('DOMContentLoaded', function() {
  const checkBtn = document.getElementById('check-answers');
  if (!checkBtn) return;

  const answers = {
    q1: '1',
    q2: '2',
    q3: '1'
  };

  checkBtn.addEventListener('click', () => {
    Object.keys(answers).forEach(key => {
      const selected = document.querySelector(`input[name="${key}"]:checked`);
      const feedback = document.getElementById(`f${key.slice(1)}`);
      
      if (selected && selected.value === answers[key]) {
        feedback.textContent = '✅ Correcto';
        feedback.className = 'feedback correct';
      } else {
        feedback.textContent = '❌ Incorrecto';
        feedback.className = 'feedback incorrect';
      }
    });
  });
});
