<?php
include "../../conexion.php";

$data = json_decode(file_get_contents('php://input'), true);
$listbox_id = $data['listbox_id'];
$opciones = $data['opciones'];

foreach ($opciones as $op) {
    if(isset($op['opcion_id'])) {
        // Actualizar existente
        $stmt = $conn->prepare("UPDATE listbox_opciones SET valor=?, precio=? WHERE id=? AND listbox_id=?");
        $stmt->bind_param("sdii", $op['valor'], $op['precio'], $op['opcion_id'], $listbox_id);
        $stmt->execute();
    } else {
        // Insertar nueva
        $stmt = $conn->prepare("INSERT INTO listbox_opciones (listbox_id, valor, precio) VALUES (?, ?, ?)");
        $stmt->bind_param("isd", $listbox_id, $op['valor'], $op['precio']);
        $stmt->execute();
    }
}

echo json_encode(['success' => true]);
