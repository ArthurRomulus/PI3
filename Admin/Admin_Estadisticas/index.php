<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="estadisticas.css">
<title>Estadísticas de Ventas</title>
</head>
<body>

<div>
  <?php include "../Admin_nav_bar.php"; ?> 
  <div class="content" style="margin-left: 220px; padding: 20px; flex: 1;">
    <?php include "../AdminProfileSesion.php"; ?>

    <div class="contenedor-estadisticas">

      <h2>📊 Estadísticas de Ventas</h2>

      <div class="filtros">
        <div class="filtro">
          <label for="filtro-periodo">Periodo:</label>
          <select id="filtro-periodo">
            <option value="todo">Todo</option>
            <option value="1semana">Última semana</option>
            <option value="1mes">Último mes</option>
            <option value="personalizado">Personalizado</option>
          </select>
        </div>

        <div class="filtro">
          <label for="filtro-categoria">Categoría:</label>
          <select id="filtro-categoria">
            <option value="todo">Todas</option>
            <?php 
              include "../../conexion.php";
              $categorias = $conn->query("SELECT * FROM categorias");
              while ($cat = $categorias->fetch_assoc()){
                echo "<option value='{$cat['id_categoria']}'>{$cat['nombrecategoria']}</option>";
              }
            ?>
          </select>
        </div>

        <div class="filtro" id="rango-personalizado" style="display: none;">
          <label>Desde:</label>
          <input type="date" id="fecha-inicio">
          <label>Hasta:</label>
          <input type="date" id="fecha-fin">
        </div>

        <div class="filtro">
          <label for="filtro-tipo">Tipo de estadística:</label>
          <select id="filtro-tipo">
            <option value="ventas">Ventas ($)</option>
            <option value="conteo">Conteo de productos</option>
          </select>
        </div>
      </div>

      <div class="grafica-barras" id="grafica"></div>

      <!-- 📘 Leyenda de productos -->
      <div id="leyenda" style="display: flex; flex-wrap: wrap; gap: 10px; margin-top: 15px;"></div>

      <div class="resumen">
        <div>
          <h3>Total ventas</h3>
          <p id="total-ventas">$0</p>
        </div>
        <div>
          <h3>Total productos</h3>
          <p id="total-productos">0</p>
        </div>
      </div>

    </div>
  </div>
</div>

<script>
// 📅 Mostrar el rango de fechas si seleccionan "personalizado"
const filtroPeriodo = document.getElementById("filtro-periodo");
const rangoDiv = document.getElementById("rango-personalizado");

filtroPeriodo.addEventListener("change", () => {
  rangoDiv.style.display = filtroPeriodo.value === "personalizado" ? "flex" : "none";
});

// 🔄 Escuchar cambios en filtros
const filtros = document.querySelectorAll("#filtro-periodo, #filtro-categoria, #filtro-tipo, #fecha-inicio, #fecha-fin");
filtros.forEach(f => f.addEventListener("change", cargarDatos));

// 📊 Cargar datos desde PHP
async function cargarDatos() {
  const periodo = document.getElementById("filtro-periodo").value;
  const categoria = document.getElementById("filtro-categoria").value;
  const tipo = document.getElementById("filtro-tipo").value;
  const inicio = document.getElementById("fecha-inicio").value;
  const fin = document.getElementById("fecha-fin").value;

  const response = await fetch("estadisticas_datos.php", {
    method: "POST",
    headers: {"Content-Type": "application/x-www-form-urlencoded"},
    body: `periodo=${periodo}&categoria=${categoria}&tipo=${tipo}&inicio=${inicio}&fin=${fin}`
  });

  const data = await response.json();
  renderGrafica(data);
}

// 🎨 Renderizar gráfica con colores únicos
function renderGrafica(data) {
  const grafica = document.getElementById("grafica");
  const leyenda = document.getElementById("leyenda");
  grafica.innerHTML = "";
  leyenda.innerHTML = "";

  if (!data.barras || data.barras.length === 0) {
    grafica.innerHTML = "<p style='text-align:center;width:100%'>No hay datos disponibles</p>";
    document.getElementById("total-ventas").textContent = "$0";
    document.getElementById("total-productos").textContent = "0";
    return;
  }

  // --- 🎨 Generador de colores únicos ---
  const generarColor = () => {
    const hue = Math.floor(Math.random() * 360);
    const saturation = 60 + Math.random() * 20; // 60–80%
    const lightness = 45 + Math.random() * 10; // 45–55%
    return `hsl(${hue}, ${saturation}%, ${lightness}%)`;
  };

  const coloresUsados = new Set();
  const obtenerColorUnico = () => {
    let color;
    do {
      color = generarColor();
    } while (coloresUsados.has(color));
    coloresUsados.add(color);
    return color;
  };

  // --- 📈 Dibujar barras ---
  const maxValor = Math.max(...data.barras.map(b => b.valor));
  data.barras.forEach(b => {
    const color = obtenerColorUnico();

    const barra = document.createElement("div");
    barra.className = "barra";
    barra.style.height = (b.valor / maxValor * 350 + 20) + "px";
    barra.innerHTML = `
      <span>${b.etiqueta}</span>
      <div style="
        background:${color};
        color:white;
        border-radius:6px 6px 0 0;
        padding-top:5px;">
        ${b.valor}
      </div>`;
    grafica.appendChild(barra);

    // 🏷️ Leyenda
    const itemLeyenda = document.createElement("div");
    itemLeyenda.style.display = "flex";
    itemLeyenda.style.alignItems = "center";
    itemLeyenda.innerHTML = `
      <div style="
        width:15px;
        height:15px;
        background:${color};
        margin-right:5px;
        border-radius:3px;">
      </div> ${b.etiqueta}`;
    leyenda.appendChild(itemLeyenda);
  });

  // 💰 Totales
  document.getElementById("total-ventas").textContent = "$" + data.totalVentas;
  document.getElementById("total-productos").textContent = data.totalProductos;
}

cargarDatos(); // Carga inicial
</script>

</body>
</html>
