<!-- TU CÓDIGO COMPLETO SIN CAMBIAR, SOLO AGREGO LO NECESARIO -->

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="estadisticas.css">
  <link rel="stylesheet" href="../general.css">
  
  <title>Estadísticas de Ventas</title>
</head>
<body>

  <?php include "../Admin_nav_bar.php"; ?> 
  <div class="content" style="margin-left: 220px; padding: 20px; flex: 1;">

          <div class="topbar">
                      <h2 style="color: white;">Blackwood Coffee</h2>
                      
            <?php include '../date.php'; ?>


        </div>

    <?php include "../AdminProfileSesion.php"; ?>

    

    <h2 data-translate="Estadísticas">Estadisticas</h2>

      <!-- 🔹 Filtros -->
      <div class="filtros">
        <div class="filtro">
          <label for="filtro-periodo" data-translate="Periodo:">Periodo:</label>
          <select id="filtro-periodo">
            <option value="todo" data-translate="Todo">Todo</option>
            <option value="1semana" data-translate="Última semana">Última semana</option>
            <option value="1mes" data-translate="Último mes">Último mes</option>
            <option value="personalizado" data-translate="Personalizado">Personalizado</option>
          </select>
        </div>

        <div class="filtro">
          <label for="filtro-metodopago" data-translate="Método de pago:">Metodo de pago:</label>
          <select id="f-mpago">
            <option value="t" data-translate="Todos">Todos</option>
            <option value="Tarjeta" data-translate="Tarjeta">Tarjeta</option>
            <option value="Efectivo" data-translate="Efectivo">Efectivo</option>
          </select>
        </div>

        <div class="filtro">
          <label for="filtro-categoria" data-translate="Categoría:">Categoría:</label>
          <select id="filtro-categoria">
            <option value="todo" data-translate="Todas">Todas</option>
            <?php 
              include "../../conexion.php";
              $categorias = $conn->query("SELECT * FROM categorias");
              while ($cat = $categorias->fetch_assoc()){
                echo "<option value='{$cat['id_categoria']}' data-translate='{$cat['nombrecategoria']}'>{$cat['nombrecategoria']}</option>";
              }
            ?>
          </select>
        </div>

        <div class="filtro" id="rango-personalizado" style="display: none;">
          <label data-translate="Desde:">Desde:</label>
          <input type="date" id="fecha-inicio">
          <label data-translate="Hasta:">Hasta:</label>
          <input type="date" id="fecha-fin">
        </div>

        <div class="filtro">
          <label for="filtro-tipo" data-translate="Tipo de estadística:">Tipo de estadística:</label>
          <select id="filtro-tipo">
            <option value="ventas" data-translate="Ventas ($)">Ventas ($)</option>
            <option value="conteo" data-translate="Conteo de productos">Conteo de productos</option>
          </select>
        </div>
      </div>

      <!-- 📊 Contenedor de gráfica -->
      <div class="grafica-container">
        <div class="grafica-ejes">
          <div class="eje-y" id="eje-y"></div>
          <div class="grafica-barras" id="grafica" ></div>
        </div>
        <div id="leyenda"></div>
      </div>

      <div class="resumen">
        <div>
          <h5 data-translate="Total de ventas">Total ventas</h5>
          <p id="total-ventas">$0</p>
        </div>
        <div>
          <h5 data-translate="Total de productos">Total productos</h5>
          <p id="total-productos">0</p>
        </div>
      </div>

    </div>
  </div>

<script>
const filtroPeriodo = document.getElementById("filtro-periodo");
const rangoDiv = document.getElementById("rango-personalizado");
filtroPeriodo.addEventListener("change", () => {
  rangoDiv.style.display = filtroPeriodo.value === "personalizado" ? "flex" : "none";
});

const filtros = document.querySelectorAll("#filtro-periodo, #filtro-categoria, #filtro-tipo, #fecha-inicio, #fecha-fin, #f-mpago");
filtros.forEach(f => f.addEventListener("change", cargarDatos));

async function cargarDatos() {
  const periodo = document.getElementById("filtro-periodo").value;
  const categoria = document.getElementById("filtro-categoria").value;
  const tipo = document.getElementById("filtro-tipo").value;
  const inicio = document.getElementById("fecha-inicio").value;
  const fin = document.getElementById("fecha-fin").value;
  const metodopago = document.getElementById("f-mpago").value;

  const response = await fetch("estadisticas_datos.php", {
    method: "POST",
    headers: {"Content-Type": "application/x-www-form-urlencoded"},
    body: `periodo=${periodo}&categoria=${categoria}&tipo=${tipo}&inicio=${inicio}&fin=${fin}&metodopago=${metodopago}`
  });

  const data = await response.json();
  renderGrafica(data);
}

function renderGrafica(data) {
  const grafica = document.getElementById("grafica");
  const ejeY = document.getElementById("eje-y");
  const leyenda = document.getElementById("leyenda");
  grafica.innerHTML = "";
  leyenda.innerHTML = "";
  ejeY.innerHTML = "";

  if (!data.barras || data.barras.length === 0) {
    grafica.innerHTML = "<p style='text-align:center;width:100%'>No hay datos disponibles</p>";
    document.getElementById("total-ventas").textContent = "$0";
    document.getElementById("total-productos").textContent = "0";
    return;
  }

  const maxValor = Math.max(...data.barras.map(b => b.valor));
  const maxEscala = Math.ceil(maxValor / 10) * 10;

  const numMarcas = 10;
  for (let i = numMarcas; i >= 0; i--) {
    const valor = Math.round((maxEscala / numMarcas) * i);
    const marca = document.createElement("div");
    marca.className = "marca";
    marca.textContent = valor;
    ejeY.appendChild(marca);
  }

  const generarColor = () => {
    const hue = Math.floor(Math.random() * 360);
    const saturation = 65 + Math.random() * 15;
    const lightness = 45 + Math.random() * 10;
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

  data.barras.forEach(b => {
    const color = obtenerColorUnico();
    const barra = document.createElement("div");
    barra.className = "barra";
    const altura = (b.valor / maxEscala) * 430;
    barra.innerHTML = `<div style="height:${altura}px;background:${color};"></div>`;
    grafica.appendChild(barra);

    const itemLeyenda = document.createElement("div");
    itemLeyenda.innerHTML = `<div class="color" style="background:${color}"></div>${b.etiqueta} ($${b.valor})`;
    leyenda.appendChild(itemLeyenda);
  });

  document.getElementById("total-ventas").textContent = "$" + data.totalVentas;
  document.getElementById("total-productos").textContent = data.totalProductos;
}

cargarDatos();
</script>
<script src="../../translate.js"></script>
</body>
</html>
