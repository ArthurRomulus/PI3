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
  font-size: 24px;      /* Antes 40px */
  line-height: 1.4;
  color: white;
  margin-top: 10px;
  margin-bottom: 20px;
  text-align: center;
  opacity: 0.85;        /* Más suave visualmente */
  font-weight: 400;     /* Menos pesado */
}

    /* === CONTENEDOR DE ESTADÍSTICAS IZQ + TARJETAS DERECHA === */
    .stats-container {
      display: flex;
      gap: 25px;
      width: 95%;
      margin: 0 auto;
      margin-top: 40px;
    }

    /* === CAJA IZQUIERDA (GRÁFICA) === */
    .stats-box {
      padding: 25px;
      background: rgba(255,255,255,0.08);
      backdrop-filter: blur(8px);
      border-radius: 20px;
      width: 65%;
      color: white;
      box-shadow: 0 0 20px rgba(0,0,0,0.25);
    }

    .stats-box h2 {
      margin-bottom: 15px;
      font-size: 28px;
      font-weight: 600;
      text-align: center;
    }

    /* === TARJETAS DERECHA === */
    .side-cards {
      width: 35%;
      display: flex;
      flex-direction: column;
      gap: 20px;
    }

    .card {
      padding: 20px;
      background: rgba(255,255,255,0.08);
      color: white;
      border-radius: 18px;
      text-align: center;
      font-size: 22px;
      font-weight: 500;
      box-shadow: 0 0 15px rgba(0,0,0,0.25);
    }

    .card span {
      display: block;
      font-size: 35px;
      margin-top: 8px;
      font-weight: 700;
    }
  </style>
</head>
<body>

<?php
include "../../conexion.php";

/* ============================
   ESTADÍSTICA – PRODUCTOS
============================ */
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


/* ============================
   TARJETAS – CONSULTAS
   (Tú las ajustas si tus tablas usan otros nombres)
============================ */

/* Total usuarios */
/* ============================
   TARJETAS – CONSULTAS CORREGIDAS
============================ */

/* Total usuarios */
$totalUsuarios = $conn->query("SELECT COUNT(*) AS total FROM usuarios")->fetch_assoc()['total'];

/* Total productos */
$totalProductos = $conn->query("SELECT COUNT(*) AS total FROM productos")->fetch_assoc()['total'];

/* Total admins (tabla admins) */
$admins = $conn->query("SELECT COUNT(*) AS total FROM administradores")->fetch_assoc()['total'];

/* Total cajeros (tabla cajeros) */
$cajeros = $conn->query("SELECT COUNT(*) AS total FROM empleados_cajeros")->fetch_assoc()['total'];

?>

<?php include "../Admin_nav_bar.php"; ?>

<div class="content">
  <?php include "../AdminProfileSesion.php"; include "../date.php"; ?>

  <div class="text">
    <p data-translate="Se le da la bienvenida al panel de control!">¡Se le da la bienvenida al panel de control!</p>
    <p data-translate="¿Retomamos desde donde lo dejamos?">¿Retomamos desde donde lo dejamos?</p>
  </div>

  <!-- CONTENEDOR COMPLETO -->
  <div class="stats-container">

    <!-- IZQUIERDA (Gráfica) -->
    <div class="stats-box">
      <h2 data-translate="Ventas por Producto">Ventas por Producto</h2>
      <canvas id="chartProductos"></canvas>
    </div>

    <!-- DERECHA (Tarjetas) -->
    <div class="side-cards">
      <div class="card">
        <span data-translate="Usuarios Totales">Usuarios Totales</span>
        <span><?php echo $totalUsuarios; ?></span>
      </div>

      <div class="card">
        <span data-translate="Productos Totales">Productos Totales</span>
        <span><?php echo $totalProductos; ?></span>
      </div>

<div class="card">
      <span data-translate="Administradores">Administradores</span>
      <span><?php echo $admins; ?></span>
  </div>

  <div class="card">
      <span data-translate="Cajeros">Cajeros</span>
      <span><?php echo $cajeros; ?></span>
  </div>

    </div>

  </div>
</div>

<span id="label-cantidad-vendida" data-translate="Cantidad vendida" style="display:none;">
  Cantidad vendida
</span>

<script>
window.addEventListener("load", async () => {
    // Esperar a que la traducción termine
    if (window.applyTranslation) {
        await window.applyTranslation(window.currentLang);
    }

    const ctx = document.getElementById('chartProductos').getContext('2d');
    const labelTraducido = document.getElementById('label-cantidad-vendida').textContent;

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($productos); ?>,
            datasets: [{
                label: labelTraducido,
                data: <?php echo json_encode($vendidos, JSON_NUMERIC_CHECK); ?>,
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: { ticks: { color: 'white' } },
                y: { beginAtZero: true, ticks: { color: 'white' } }
            },
            plugins: {
                legend: { labels: { color: 'white' } }
            }
        }
    });
});
</script>

<script src="../../translate.js"></script>
</body>
</html>
