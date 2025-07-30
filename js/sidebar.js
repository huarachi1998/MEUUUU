document.addEventListener("DOMContentLoaded", () => {
  const btn = document.getElementById("btnSidebar");
  const sidebar = document.getElementById("sidebar");

  btn.addEventListener("click", () => {
    sidebar.classList.toggle("active");
  });

  const botones = document.querySelectorAll(".filtro");
  botones.forEach(b => {
    b.addEventListener("click", () => {
      const tipo = b.dataset.filtro;
      alert("Filtro activado: " + tipo);
    });
  });
});