<?php
include "../../conexion.php";

// Verificar que se haya enviado el ID del producto
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo json_encode([]);
    exit;
}

$idp = (int)$_GET['id'];

// Obtener listboxes del producto
$stmt = $conn->prepare("SELECT nombre, opciones FROM producto_opciones WHERE idp = ?");
$stmt->bind_param("i", $idp);
$stmt->execute();
$result = $stmt->get_result();

$listboxes = [];

while ($row = $result->fetch_assoc()) {
    $opciones = json_decode($row['opciones'], true); // Convertir JSON a array
    if (!is_array($opciones)) $opciones = [];
    $listboxes[] = [
        'nombre' => $row['nombre'],
        'opciones' => $opciones
    ];
}

$stmt->close();

// Devolver JSON
header('Content-Type: application/json');
echo json_encode($listboxes, JSON_UNESCAPED_UNICODE);
