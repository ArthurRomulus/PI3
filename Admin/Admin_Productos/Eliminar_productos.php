<?php
include "../../conexion.php";

if (isset($_GET['id'])) { // Cambiar POST por GET
    $id = $_GET['id'];

    // Primero, eliminamos la imagen si existe
    $queryImg = $conn->prepare("SELECT ruta_imagen FROM productos WHERE idp=?");
    $queryImg->bind_param("i", $id);
    $queryImg->execute();
    $result = $queryImg->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (!empty($row['ruta_imagen']) && file_exists($row['ruta_imagen'])) {
            unlink($row['ruta_imagen']); // elimina el archivo físico
        }
    }

    // Eliminamos el producto
    $stmt = $conn->prepare("DELETE FROM productos WHERE idp=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    // Redirigir de vuelta al index
    header("Location: index.php");
    exit;
} else {
    echo "no_id";
}
?>
