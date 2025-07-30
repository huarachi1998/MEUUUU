
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>FTTH - Mapa con Panel Lateral</title>
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
  <style>
    body, html { margin: 0; padding: 0; height: 100%; }
    #mapa { height: 100%; width: 100%; }
    #topbar {
      position: absolute; top: 10px; left: 60px; right: 10px;
      background: white; padding: 10px; border-radius: 8px;
      box-shadow: 0 0 5px rgba(0,0,0,0.3); z-index: 1001;
      display: flex; flex-wrap: wrap; gap: 10px; align-items: center;
    }
    #panelBtn {
      position: absolute; top: 10px; left: 10px; z-index: 1002;
      font-size: 22px; background: white; border: 1px solid #ccc;
      padding: 5px 10px; cursor: pointer; border-radius: 5px;
    }
    #panelLateral {
      position: absolute; left: -250px; top: 0; bottom: 0;
      width: 250px; background: #f9f9f9; box-shadow: 2px 0 5px rgba(0,0,0,0.3);
      z-index: 1000; padding: 20px; transition: left 0.3s ease;
    }
    #panelLateral.abierto { left: 0; }
    #panelLateral h3 { margin-top: 0; }
    #panelLateral button {
      width: 100%; margin-bottom: 10px; padding: 8px;
      font-size: 14px; cursor: pointer; border: none; border-radius: 4px;
      background: #e0e0e0;
    }
    #panelLateral button:hover { background: #d0d0d0; }
    input, select {
      padding: 6px 10px; font-size: 14px;
      border: 1px solid #ccc; border-radius: 4px;
    }
  </style>
</head>
<body>
  <button id="panelBtn">≡</button>

  <div id="panelLateral">
    <h3>📊 Filtros Avanzados</h3>
    <button id="filtro2dias">🕒 +2 días desconectados</button>
    <button id="filtro2semanas">📆 +2 semanas</button>
    <button id="filtro1mes">🗓️ +1 mes</button>
    <button id="filtroMorosos">💰 Morosos desconectados</button>
    <button id="filtroRecientes">🆕 Recién añadidos</button>
    <button id="filtroNunca">❌ Nunca conectados</button>
    <button onclick="window.location.reload()">🔄 Mostrar todos</button>
  </div>

  <div id="topbar">
    <input type="text" id="busqueda" placeholder="🔍 Buscar cliente..." />
    <select id="filtroNAP"><option value="">📦 Filtrar por NAP</option></select>
    <select id="filtroEstado">
      <option value="">📶 Todos</option>
      <option value="online">🟢 Conectado</option>
      <option value="offline">🔴 Desconectado</option>
    </select>
  </div>

  <div id="mapa"></div>

  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
  <script>
    document.getElementById("panelBtn").addEventListener("click", () => {
      document.getElementById("panelLateral").classList.toggle("abierto");
    });
  </script>
  <script src="js/mapa.js"></script>
</body>
</html>
