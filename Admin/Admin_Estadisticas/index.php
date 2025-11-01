<?php
include "../../conexion.php"; // conexión a la base de datos
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Coffee Shop - Estadísticas</title>

  <link rel="stylesheet" href="../general.css">

  <link rel="stylesheet" href="estadisticas.css">
  <link rel="stylesheet" href="../Admin_nav_bar.css">
  <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>

  <?php include "../Admin_nav_bar.php"; ?> 
  <div class="content">
    <?php include "../AdminProfileSesion.php"; ?>

    <h1>📈 Estadísticas de ventas</h1>
    <div class="topbar">
      <?php include '../date.php'; ?>
    </div>

    <!-- 🔹 Filtros modernos -->
    <div class="filtros-modern">
      <div class="filtro">
        <label for="tiempo">Periodo:</label>
        <select id="tiempo" onchange="filtrarGrafica()">
          <option value="hoy">Hoy</option>
          <option value="semana">Última semana</option>
          <option value="mes">Último mes</option>
          <option value="anio">Último año</option>
          <option value="Todo" selected>Todo</option>
        </select>
      </div>


    <div class="contenedor-grafico">
      <h2 class="titulo-grafica">📊 Ventas por producto</h2>
      <div class="grafica-barras" id="graficaBarras">
        <?php 
          $query = "SELECT namep, VENTAS, categoria FROM productos ORDER BY VENTAS DESC";
          $result = $conn->query($query);
          $maxVentas = $conn->query("SELECT MAX(VENTAS) as max FROM productos")->fetch_assoc()['max'] ?? 1;
          while ($row = $result->fetch_assoc()) {
              $altura = $maxVentas > 0 ? ($row['VENTAS'] / $maxVentas) * 100 : 0;
              echo '
                <div class="barra" data-categoria="'.htmlspecialchars($row['categoria'] ?? 'General').'" data-ventas="'.htmlspecialchars($row['VENTAS']).'">
                  <div class="barra-fill" style="height: '.$altura.'%;">
                    <span class="valor">'.htmlspecialchars($row['VENTAS']).'</span>
                  </div>
                  <span class="nombre">'.htmlspecialchars($row['namep']).'</span>
                </div>';
          }
        ?>
      </div>
    </div>
  </div>

  <script>
  function filtrarGrafica() {
    const categoria = document.getElementById("categoria").value;
    const barras = document.querySelectorAll(".barra");

    barras.forEach(barra => {
      const cat = barra.getAttribute("data-categoria");
      if (categoria === "" || cat === categoria) {
        barra.style.display = "flex";
      } else {
        barra.style.display = "none";
      }
    });
  }
  </script>
</body>
</html>
