<?php
include "../conexion.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $precio = $_POST['precio'];
    $categoria = $_POST['categoria'];
    $sabor = $_POST['sabor'];

    // Manejar imagen
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
        $targetDir = "img/";
        $targetFile = $targetDir . basename($_FILES['imagen']['name']);
        move_uploaded_file($_FILES['imagen']['tmp_name'], $targetFile);
        $imagen = $targetFile;

        $sql = "UPDATE productos SET namep=?, precio=?, categoria=?, sabor=?, ruta_imagen=? WHERE idp=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sisssi", $name, $precio, $categoria, $sabor, $imagen, $id);
    } else {
        $sql = "UPDATE productos SET namep=?, precio=?, categoria=?, sabor=? WHERE idp=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sissi", $name, $precio, $categoria, $sabor, $id);
    }

    $stmt->execute();
    $stmt->close();

    header("Location: index.php");
    exit;
}
?>
