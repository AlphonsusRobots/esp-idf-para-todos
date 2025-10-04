// /opt/lampp/htdocs/esp-idf-documentation/assets/js/modules/code-copy.js
document.addEventListener('click', function(e) {
  if (e.target.classList.contains('copy-btn')) {
    const code = e.target.previousElementSibling.textContent;
    navigator.clipboard.writeText(code).then(() => {
      e.target.textContent = 'Copiado!';
      setTimeout(() => e.target.textContent = 'Copiar', 2000);
    });
  }
});
