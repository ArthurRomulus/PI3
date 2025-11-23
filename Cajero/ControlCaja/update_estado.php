<?php
session_start();
// Importante: forzar UTF-8 para que la 'ó' de preparación no rompa el JSON
header('Content-Type: application/json; charset=utf-8'); 
require_once __DIR__ . '/../conexion.php'; 

if (!isset($_SESSION['userid'])) {
    echo json_encode(['success' => false, 'error' => 'No autorizado']);
    exit;
}

// Leer JSON
$json = file_get_contents('php://input');
$input = json_decode($json, true);

$id_pedido = $input['id_pedido'] ?? null;
$nuevo_estado = $input['nuevo_estado'] ?? null;

if (!$id_pedido || !$nuevo_estado) {
    echo json_encode(['success' => false, 'error' => 'Datos incompletos']);
    exit;
}

// Lista exacta permitida en tu BD (ENUM)
$estados_validos = ['En espera', 'En preparación', 'Completado'];

if (!in_array($nuevo_estado, $estados_validos)) {
    echo json_encode(['success' => false, 'error' => 'Estado no válido: ' . $nuevo_estado]);
    exit;
}

try {
    $sql = "UPDATE pedidos SET estado = ? WHERE id_pedido = ?";
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([$nuevo_estado, $id_pedido]);

    if ($result) {
        echo json_encode(['success' => true, 'mensaje' => 'Estado actualizado']);
    } else {
        echo json_encode(['success' => false, 'error' => 'No se realizaron cambios en la BD']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => 'Error SQL: ' . $e->getMessage()]);
}
?>