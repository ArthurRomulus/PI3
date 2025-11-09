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
// Mostrar el rango de fechas si seleccionan "personalizado"
const filtroPeriodo = document.getElementById("filtro-periodo");
const rangoDiv = document.getElementById("rango-personalizado");

filtroPeriodo.addEventListener("change", () => {
  rangoDiv.style.display = filtroPeriodo.value === "personalizado" ? "flex" : "none";
});

// Llamar a la función cuando cambian los filtros
const filtros = document.querySelectorAll("#filtro-periodo, #filtro-categoria, #filtro-tipo, #fecha-inicio, #fecha-fin");
filtros.forEach(f => f.addEventListener("change", cargarDatos));

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

function renderGrafica(data) {
  const grafica = document.getElementById("grafica");
  grafica.innerHTML = "";

  if (!data.barras || data.barras.length === 0) {
    grafica.innerHTML = "<p style='text-align:center;width:100%'>No hay datos disponibles</p>";
    document.getElementById("total-ventas").textContent = "$0";
    document.getElementById("total-productos").textContent = "0";
    return;
  }

  const maxValor = Math.max(...data.barras.map(b => b.valor));
  data.barras.forEach(b => {
    const barra = document.createElement("div");
    barra.className = "barra";
    barra.style.height = (b.valor / maxValor * 350 + 20) + "px";
    barra.innerHTML = `<span>${b.etiqueta}</span><div style="background:#4e79a7;color:white;border-radius:6px 6px 0 0;padding-top:5px;">${b.valor}</div>`;
    grafica.appendChild(barra);
  });

  document.getElementById("total-ventas").textContent = "$" + data.totalVentas;
  document.getElementById("total-productos").textContent = data.totalProductos;
}

cargarDatos(); // Carga inicial
</script>

</body>
</html>
