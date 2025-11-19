<?php
header('Content-Type: application/json; charset=utf-8');

// Conectar a la BD
require_once '../../conexion.php';   // aquí se define la conexión

// Normalizar la variable de conexión:
// si no existe $conexion pero sí existe $conn o $link, las usamos.
if (!isset($conexion)) {
    if (isset($conn)) {
        $conexion = $conn;
    } elseif (isset($link)) {
        $conexion = $link;
    }
}

// Validar conexión (mysqli)
if (!isset($conexion) || !($conexion instanceof mysqli)) {
    echo json_encode([
        'success' => false,
        'error'   => 'No hay conexión a la base de datos (revísar conexion.php y nombre de la variable)'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

if ($conexion->connect_errno) {
    echo json_encode([
        'success' => false,
        'error'   => 'Error de conexión: ' . $conexion->connect_error
    ], JSON_UNESCAPED_UNICODE);
    exit;
}
try {
    // TOP 3 productos fijos
    $sql = "
        SELECT 
            idp          AS id,
            namep        AS nombre,
            precio,
            ruta_imagen  AS imagen,
            descripcion
        FROM productos
        WHERE idp IN (143, 152, 147)
        LIMIT 3
    ";

    $result = $conexion->query($sql);

    if (!$result) {
        echo json_encode([
            'success' => false,
            'error'   => 'Error en la consulta: ' . $conexion->error
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $productos = [];
    while ($row = $result->fetch_assoc()) {
        $productos[] = $row;
    }

    echo json_encode([
        'success' => true,
        'data'    => $productos
    ], JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {
    echo json_encode([
        'success' => false,
        'error'   => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
