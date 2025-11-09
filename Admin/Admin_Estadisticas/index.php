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

<script>
// ============ DATOS DE PRODUCTOS DESDE PHP ============
const productos = <?php
  $productos = $conn->query("SELECT * FROM productos");
  $lista = [];
  while ($p = $productos->fetch_assoc()) {
    $lista[] = $p;
  }
  echo json_encode($lista);
?>;

// ============ EVENTOS ============
document.getElementById("filtro-categoria").addEventListener("change", actualizarGrafica);
document.getElementById("filtro-tipo").addEventListener("change", actualizarGrafica);

document.getElementById("filtro-periodo").addEventListener("change", function() {
  const rango = document.getElementById("rango-personalizado");
  rango.style.display = this.value === "personalizado" ? "flex" : "none";
});

// ============ FUNCIÓN PRINCIPAL ============
function actualizarGrafica() {
  const categoria = document.getElementById("filtro-categoria").value;
  const tipo = document.getElementById("filtro-tipo").value;
  const contenedor = document.getElementById("grafica");
  contenedor.innerHTML = "";

  let filtrados = productos;
  if (categoria !== "todo") {
    filtrados = productos.filter(p => p.categoria == categoria);
  }

  // Totales
  let totalVentas = 0;
  let totalProductos = 0;

  filtrados.forEach(prod => {
    const valor = tipo === "ventas" ? parseFloat(prod.precio) : 1;
    totalVentas += parseFloat(prod.precio);
    totalProductos++;

    const barra = document.createElement("div");
    barra.classList.add("barra");
    barra.style.height = `${valor * 3}px`;
    barra.title = `${prod.namep} - $${prod.precio}`;
    barra.innerHTML = `<span>${prod.namep}</span>`;
    contenedor.appendChild(barra);
  });

  document.getElementById("total-ventas").textContent = "$" + totalVentas.toFixed(2);
  document.getElementById("total-productos").textContent = totalProductos;
}

// Inicializa
actualizarGrafica();
</script>

</div>
</div>
</body>
</html>
