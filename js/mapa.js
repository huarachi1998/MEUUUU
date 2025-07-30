
let datosClientes = {};
let marcadores = [];

document.addEventListener("DOMContentLoaded", () => {
  const mapa = L.map("mapa").setView([-16.5, -68.13], 13);
  L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    attribution: '&copy; OpenStreetMap contributors'
  }).addTo(mapa);

  fetch("datos.json")
    .then(res => res.json())
    .then(data => {
      datosClientes = data;
      renderizarClientes();
      inicializarFiltros();
    });

  function renderizarClientes(filtro = null) {
    marcadores.forEach(m => mapa.removeLayer(m));
    marcadores = [];

    const ahora = Date.now() / 1000;

    for (const key in datosClientes) {
      const c = datosClientes[key];
      if (!c || !c.x || !c.y) continue;

      // Aplicar filtros
      if (filtro && !filtro(c, ahora)) continue;

      const icono = L.icon({
        iconUrl: c.estado === "online" ? "img/cliente_online.png" : "img/cliente_offline.png",
        iconSize: [32, 32],
        iconAnchor: [16, 32],
        popupAnchor: [0, -30],
      });

      const marcador = L.marker([c.y, c.x], { icon: icono }).addTo(mapa);
      marcador.bindPopup(`
        <b>${c.nombre}</b><br>
        IP: ${c.ip || "N/D"}<br>
        Estado: ${c.estado === "online" ? "🟢 Conectado" : "🔴 Desconectado"}<br>
        NAP: ${c.nap}<br>
        <button onclick="editarCliente('${c.pppoe}')">✏️ Editar</button>
      `);
      marcadores.push(marcador);
    }
  }

  function inicializarFiltros() {
    document.getElementById("busqueda").addEventListener("input", e => {
      const texto = e.target.value.toLowerCase();
      renderizarClientes(c => c.nombre.toLowerCase().includes(texto) || c.pppoe.toLowerCase().includes(texto));
    });

    document.getElementById("filtroNAP").addEventListener("change", e => {
      const nap = e.target.value;
      if (!nap) renderizarClientes();
      else renderizarClientes(c => c.nap === nap);
    });

    document.getElementById("filtroEstado").addEventListener("change", e => {
      const estado = e.target.value;
      if (!estado) renderizarClientes();
      else renderizarClientes(c => c.estado === estado);
    });

    document.getElementById("filtro2dias").addEventListener("click", () => {
      renderizarClientes((c, ahora) => c.estado === "offline" && ahora - c.ultima > 2 * 24 * 3600);
    });

    document.getElementById("filtro2semanas").addEventListener("click", () => {
      renderizarClientes((c, ahora) => c.estado === "offline" && ahora - c.ultima > 14 * 24 * 3600);
    });

    document.getElementById("filtro1mes").addEventListener("click", () => {
      renderizarClientes((c, ahora) => c.estado === "offline" && ahora - c.ultima > 30 * 24 * 3600);
    });

    document.getElementById("filtroMorosos").addEventListener("click", () => {
      renderizarClientes(c => c.estado === "offline" && c.moroso === true);
    });

    document.getElementById("filtroRecientes").addEventListener("click", () => {
      renderizarClientes((c, ahora) => ahora - c.ultima < 3 * 24 * 3600);
    });

    document.getElementById("filtroNunca").addEventListener("click", () => {
      renderizarClientes(c => !c.ultima || c.ultima === 0);
    });
  }
});

function editarCliente(pppoe) {
  alert("Editar cliente " + pppoe);
}
