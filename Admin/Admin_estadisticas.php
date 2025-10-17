<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <link rel="stylesheet" href="general.css">
    <?php 
        include "Admin_nav_bar.php";  
    ?>
<div class="content">
  <div class="top-bar">
    <div class="admin-profile">
      <span class="admin-name">Admin - Luis Perez Ponzo</span>
      <img src="img/admin.png" alt="Perfil Admin">
    </div>
  </div>

<h1>Estadísticas de ventas.</h1>
<?php include 'date.php' ?>


<div class="filtros">
  
  <div class="filtro">
    <label>CATEGORÍA</label>
    <select class="inputoptional">
      <option>FRAPPES</option>
      <option>Smoothies</option>
      <option>Cafés</option>
    </select>
  </div>
  <div class="filtro">
    <label>TIEMPO</label>
    <select class="inputoptional">
      <option>ÚLTIMO AÑO</option>
      <option>Último mes</option>
      <option>Última semana</option>
    </select>
  </div>
</div>

<div class="grafico">
  <div class="eje-y">
    <span>$1,000,000</span>
    <span>$900,000</span>
    <span>$800,000</span>
    <span>$700,000</span>
    <span>$600,000</span>
    <span>$500,000</span>
    <span>$400,000</span>
    <span>$300,000</span>
    <span>$200,000</span>
    <span>$100,000</span>
    <span>$0</span>
  </div>

  <div class="barras">
    <div class="barra" style="--altura:12%; background:#666;"><span>GALLETAS OREO</span></div>
    <div class="barra" style="--altura:100%; background:#c7b185;"><span>MAZAPÁN</span></div>
    <div class="barra" style="--altura:100%; background:#8b5e3c;"><span>CHOCOLATE</span></div>
    <div class="barra" style="--altura:100%; background:#c62828;"><span>FRUTOS ROJOS</span></div>
    <div class="barra" style="--altura:55%; background:#ff8a80;"><span>FRESA</span></div>
    <div class="barra" style="--altura:30%; background:#4caf50;"><span>CHOCO MENTA</span></div>
    <div class="barra" style="--altura:45%; background:#6d4c41;"><span>CAFÉ</span></div>
    <div class="barra" style="--altura:65%; background:#2e7d32;"><span>MATCHA</span></div>
    <div class="barra" style="--altura:60%; background:#cddc39;"><span>MANGO</span></div>
  </div>
</div>

</div>

</body>
