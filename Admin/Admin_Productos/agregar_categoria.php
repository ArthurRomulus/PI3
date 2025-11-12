<?php
include "../../conexion.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombrecategoria']);

    if (!empty($nombre)) {
        $stmt = $conn->prepare("INSERT INTO categorias (nombrecategoria) VALUES (?)");
        $stmt->bind_param("s", $nombre);
        $stmt->execute();
        $stmt->close();

        header("Location: index.php?categoria_agregada=1");
        exit;
    } else {
        echo "Error: El nombre de la categoría no puede estar vacío.";
    }
}
?>
