<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel de Control</title>

  <link rel="stylesheet" href="../Admin_nav_bar.css">
  <link rel="stylesheet" href="../general.css">

  <style>
    .cards-container { position: relative; }
    .img-logo-bg {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      width: 450px;
      opacity: 0.05;
      pointer-events: none;
      z-index: 0;
    }
    .cards {
      position: relative;
      z-index: 1;
      display: flex;
      gap: 25px;
      flex-wrap: wrap;
    }
    .dashboard { margin-left: 220px; padding: 20px; color: white; }
    .card {
      background: rgba(255,255,255,0.15);
      backdrop-filter: blur(6px);
      padding: 25px;
      border-radius: 18px;
      width: 300px;
      height: 160px;
      box-shadow: 0 0 10px #0007;
      transition: 0.25s;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      cursor: pointer;
    }
    .card:hover {
      transform: translateY(-5px);
      background: rgba(255,255,255,0.25);
    }
    .card-title { font-size: 22px; font-weight: bold; }
    .card-number { font-size: 34px; font-weight: bolder; margin-top: 10px; }
    .img-logo { width: 450px; opacity: 0.1; margin-top: 50px; display: block; }
  </style>
</head>
<body>

<?php  
  include "../Admin_nav_bar.php";  
  include "../../conexion.php";  
  include "../AdminProfileSesion.php";

  // Personal
  $totalUsuarios = $conn->query("SELECT COUNT(*) AS u FROM usuarios")->fetch_assoc()['u']; // todos los usuarios
  $cajeros = $conn->query("SELECT COUNT(*) AS cj FROM usuarios WHERE role = 2")->fetch_assoc()['cj']; // solo cajeros
  $admins = $conn->query("SELECT COUNT(*) AS ad FROM usuarios WHERE role = 4")->fetch_assoc()['ad']; // solo admins
?>

<div class="dashboard">
    <div class="cards-container">
        <img src="../../Images/logo.png" class="img-logo-bg">
        <div class="cards">
          <div class="card" onclick="location.href='../Admin_Estadisticas/'">
            <div class="card-title" id="titulo-ventas" data-translate="Total Ventas">Total Ventas</div>
            <div class="card-number" id="total-ventas">$0</div>
          </div>
          <div class="card" onclick="location.href='../Admin_Estadisticas/'">
            <div class="card-title" id="titulo-productos" data-translate="Total Productos">Total Productos</div>
            <div class="card-number" id="total-productos">0</div>
          </div>
          <div class="card" onclick="location.href='../Admin_Personal/'">
            <div class="card-title" data-translate="Usuarios Totales">Usuarios Totales</div>
            <div class="card-number"><?php echo $totalUsuarios; ?></div>
          </div>
          <div class="card" onclick="location.href='../Admin_Personal/'">
            <div class="card-title" data-translate="Personal Registrado">Personal Registrado</div>
            <div class="card-number"><?php echo $cajeros; ?></div>
          </div>
          <div class="card" onclick="location.href='../Admin_Personal/'">
            <div class="card-title" data-translate="Administradores">Administradores</div>
            <div class="card-number"><?php echo $admins; ?></div>
          </div>
        </div>
    </div>

    <img src="../../Images/logo.png" class="img-logo">
</div>

<script src="../../translate.js"></script>
<script>
async function actualizarTarjetas() {
  // Valores por defecto
  let periodo = "todo", categoria = "todo", metodopago = "t", tipo = "ventas";

  // Revisar si hay filtros guardados en sessionStorage
  const filtrosGuardados = JSON.parse(sessionStorage.getItem("estadisticasFiltros"));
  if(filtrosGuardados){
      periodo = filtrosGuardados.periodo;
      categoria = filtrosGuardados.categoria;
      metodopago = filtrosGuardados.metodopago;
      tipo = filtrosGuardados.tipo || "ventas";
  }

  // Llamada AJAX a estadisticas_datos.php
  const response = await fetch("../Admin_Estadisticas/estadisticas_datos.php", {
    method: "POST",
    headers: {"Content-Type": "application/x-www-form-urlencoded"},
    body: `periodo=${periodo}&categoria=${categoria}&tipo=${tipo}&inicio=&fin=&metodopago=${metodopago}`
  });

  const data = await response.json();

  // Actualizar tarjetas según el tipo
  if(tipo === "ventas"){
    document.getElementById("total-ventas").textContent = "$" + (data.totalVentas ?? 0);
    document.getElementById("total-productos").textContent = data.totalProductos ?? 0;
    document.getElementById("titulo-ventas").textContent = "Total Ventas";
    document.getElementById("titulo-productos").textContent = "Total Productos";
  } else if(tipo === "conteo"){
    document.getElementById("total-ventas").textContent = "$0";
    document.getElementById("total-productos").textContent = data.totalProductos ?? 0;
    document.getElementById("titulo-ventas").textContent = "Total Ventas";
    document.getElementById("titulo-productos").textContent = "Conteo de productos";
  }
}

// Ejecutar al cargar la página
window.addEventListener("load", actualizarTarjetas);
</script>

</body>
</html>
