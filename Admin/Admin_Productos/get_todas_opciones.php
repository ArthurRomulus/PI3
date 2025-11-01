<?php
include "../../conexion.php";

// Trae todos los listboxes con sus opciones
$query = "
    SELECT 
        l.id AS listbox_id,
        l.nombre AS listbox_nombre,
        o.id AS opcion_id,
        o.valor,
        o.precio
    FROM listboxes l
    LEFT JOIN listbox_opciones o ON o.listbox_id = l.id
    ORDER BY l.id, o.id
";

$result = $conn->query($query);

$listboxes = [];

while ($row = $result->fetch_assoc()) {
    $listbox_id = $row['listbox_id'];

    // Si no existe el grupo todavía, lo creamos
    if (!isset($listboxes[$listbox_id])) {
        $listboxes[$listbox_id] = [
            'listbox_id' => $listbox_id,
            'listbox_nombre' => $row['listbox_nombre'],
            'opciones' => []
        ];
    }

    // Agregamos la opción si existe
    if ($row['opcion_id']) {
        $listboxes[$listbox_id]['opciones'][] = [
            'opcion_id' => $row['opcion_id'],
            'valor' => $row['valor'],
            'precio' => $row['precio']
        ];
    }
}

// Reindexamos para devolver un array limpio
echo json_encode(array_values($listboxes), JSON_UNESCAPED_UNICODE);
?>
