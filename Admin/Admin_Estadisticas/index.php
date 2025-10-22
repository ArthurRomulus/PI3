<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estadísticas de ventas</title>
    <link rel="stylesheet" href="../Admin_nav_bar.css">
    <link rel="stylesheet" href="../general.css">
  
</head>
<body>

<?php 
    include "../Admin_nav_bar.php";  
    include "../../conexion.php";
?>

<div class="content">
    <div class="top-bar">
        <?php include "../AdminProfileSesion.php"; ?>
    </div>

    <h1>📊 Estadísticas de ventas</h1>

    <?php
    // Obtener las categorías únicas desde la base de datos
    $catQuery = "SELECT DISTINCT categoria FROM productos";
    $catResult = $conn->query($catQuery);
    ?>

    <form method="GET" class="filtros">
        <div class="filtro">
            <label>CATEGORÍA</label>
            <select name="categoria" class="inputoptional">
                <option value="">Todas</option>
                <?php while ($cat = $catResult->fetch_assoc()) { ?>
                    <option value="<?= $cat['categoria'] ?>" 
                        <?= (isset($_GET['categoria']) && $_GET['categoria'] == $cat['categoria']) ? 'selected' : '' ?>>
                        <?= $cat['categoria'] ?>
                    </option>
                <?php } ?>
            </select>
        </div>
        <button type="submit" class="inputoptional">Filtrar</button>
    </form>

    <?php
    // Filtro por categoría
    $filtro = "";
    if (isset($_GET['categoria']) && $_GET['categoria'] != "") {
        $categoria = $conn->real_escape_string($_GET['categoria']);
        $filtro = "WHERE categoria = '$categoria'";
    }

    // Consulta de productos
    $query = "SELECT namep, VENTAS FROM productos $filtro ORDER BY VENTAS DESC";
    $result = $conn->query($query);

    // Obtener valor máximo para escalar alturas
    $maxVentasQuery = "SELECT MAX(VENTAS) as maxVentas FROM productos $filtro";
    $maxResult = $conn->query($maxVentasQuery);
    $maxVentas = $maxResult->fetch_assoc()['maxVentas'] ?? 1;
    ?>

    <div class="grafico">
        <?php 
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) { 
                $altura = $maxVentas > 0 ? ($row['VENTAS'] / $maxVentas) * 100 : 0;
        ?>
            <div class="barra" style="height: <?= $altura ?>%;">
                <span><?= htmlspecialchars($row['namep']) ?></span>
                <div class="valor"><?= $row['VENTAS'] ?></div>
            </div>
        <?php 
            } 
        } else { 
        ?>
            <div class="no-datos">No hay datos para mostrar.</div>
        <?php } ?>
    </div>
</div>

</body>
</html>
