<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel de Control</title>
  <link rel="stylesheet" href="../Admin_nav_bar.css">
  <link rel="stylesheet" href="../general.css">
  <script src="../../theme-toggle.js" defer></script>

  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <style>
    .text {
      font-size: 40px;
      line-height: 1.3;
      color: white;
      margin-top: 20px;
      margin-bottom: 30px;
      text-align: center;
    }

    .stats-box {
      margin: 30px auto;
      padding: 25px;
      background: rgba(255,255,255,0.08);
      backdrop-filter: blur(8px);
      border-radius: 20px;
      width: 90%;
      max-width: 750px;
      color: white;
      text-align: center;
      box-shadow: 0 0 20px rgba(0,0,0,0.25);
    }

    .stats-box h2 {
      margin-bottom: 15px;
      font-size: 28px;
      font-weight: 600;
    }

    .stats-box canvas {
        margin-top: 20px;
        max-width: 100%;
    }
  </style>
</head>
<body>

<?php
// --- ESTADÍSTICAS DE PRODUCTOS (BARRAS) ---
include "../../conexion.php";

// Obtener cantidades vendidas por producto con nombre real
$query = $conn->query("
    SELECT 
        pedido_items.id_producto,
        productos.namep AS nombre_producto,
        SUM(pedido_items.cantidad) AS total_vendido
    FROM pedido_items
    INNER JOIN productos ON productos.idp = pedido_items.id_producto
    GROUP BY pedido_items.id_producto, productos.namep
");

$productos = [];
$vendidos = [];

while ($row = $query->fetch_assoc()) {
    $productos[] = $row['nombre_producto'];
    $vendidos[] = $row['total_vendido'];
}
?>

<?php include "../Admin_nav_bar.php"; ?>

<div class="content">
  <?php include "../AdminProfileSesion.php"; include "../date.php"; ?>

  <div class="text">
    <p>¡Se le da la bienvenida al panel de control!</p>
    <p>¿Retomamos desde donde lo dejamos?</p>
  </div>

  <div class="stats-box">
      <h2>Ventas por Producto</h2>
      <canvas id="chartProductos"></canvas>
  </div>

</div>

<script>
const ctx = document.getElementById('chartProductos').getContext('2d');

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($productos); ?>,
        datasets: [{
            label: 'Cantidad vendida',
            data: <?php echo json_encode($vendidos, JSON_NUMERIC_CHECK); ?>,
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        scales: {
            x: {
                ticks: { color: 'white' }
            },
            y: {
                beginAtZero: true,
                ticks: { color: 'white' }
            }
        },
        plugins: {
            legend: {
                labels: { color: 'white' }
            }
        }
    }
});
</script>

</body>
</html>
