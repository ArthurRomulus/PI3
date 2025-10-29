<?php
include "../../conexion.php";

if (isset($_GET['id_categoria'])) {
    $categoriaId = intval($_GET['id_categoria']);

    $sql = "SELECT op.nombre_opcion, op.valor, op.precio
            FROM opciones_predefinidas op
            JOIN opciones_categoria oc ON op.id_opcion_predefinida = oc.id_opcion_predefinida
            WHERE oc.id_categoria = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $categoriaId);
    $stmt->execute();
    $result = $stmt->get_result();

    $options = [];
    while($row = $result->fetch_assoc()){
        $options[] = [
            "nombre_opcion" => $row['nombre_opcion'], // <- agregado
            "valor" => $row['valor'],
            "precio" => $row['precio']
        ];
    }

    echo json_encode($options);
} else {
    echo json_encode([]);
}
?>
