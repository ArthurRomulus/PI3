<?php
include "../conexion.php";

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Primero, eliminamos la imagen si existe
    $queryImg = $conn->prepare("SELECT ruta_imagen FROM productos WHERE idp=?");
    $queryImg->bind_param("i", $id);
    $queryImg->execute();
    $result = $queryImg->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (!empty($row['imagen']) && file_exists($row['imagen'])) {
            unlink($row['imagen']); // elimina el archivo físico
        }
    }

    // Eliminamos el producto
    $stmt = $conn->prepare("DELETE FROM productos WHERE idp=?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "no_id";
}
?>
