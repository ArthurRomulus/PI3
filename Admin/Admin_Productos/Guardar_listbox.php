<?php
include "../../conexion.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre_listbox']);
    $valores = $_POST['opciones_valor'];
    $precios = $_POST['opciones_precio'];

    if ($nombre && !empty($valores)) {
        // Insertar el nuevo listbox
        $stmt = $conn->prepare("INSERT INTO listboxes (nombre) VALUES (?)");
        $stmt->bind_param("s", $nombre);
        $stmt->execute();
        $listbox_id = $conn->insert_id;
        $stmt->close();

        // Insertar opciones del listbox
        $stmt = $conn->prepare("INSERT INTO listbox_opciones (listbox_id, valor, precio) VALUES (?, ?, ?)");
        foreach ($valores as $i => $valor) {
            $precio = floatval($precios[$i] ?? 0);
            $stmt->bind_param("isd", $listbox_id, $valor, $precio);
            $stmt->execute();
        }
        $stmt->close();

        header("Location: index.php?success=1");
        exit;
    } else {
        echo "Error: faltan datos.";
    }
}
?>
