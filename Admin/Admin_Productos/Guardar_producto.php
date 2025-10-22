<?php
include "../../conexion.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibir datos del formulario
    $namep = $_POST['name'];        // Asegúrate que coincida con el name del input
    $precio = $_POST['precio'];
    $categoria = $_POST['categoria'];
    $sabor = $_POST['sabor'];

    // Subida de imagen
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
        $targetDir = "../img/"; // para mover el archivo físicamente
        $fileName = uniqid() . "_" . basename($_FILES['imagen']['name']);
        $targetFile = $targetDir . $fileName;

        // Mover archivo
        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $targetFile)) {
            $imagen = $targetFile; // <<--- Guardar exactamente la ruta que funciona desde index.php
        } else {
            $imagen = "../img/default.png";
        }

    } else {
        $imagen = "img/default.png"; // Imagen por defecto si no se sube nada
    }

    // Insertar en la base de datos
    $sql = "INSERT INTO productos (namep, precio, categoria, sabor, ruta_imagen) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Error en la preparación: " . $conn->error);
    }

    $stmt->bind_param("sisis", $namep, $precio, $categoria, $sabor, $imagen);

    if ($stmt->execute()) {
        // Redirigir de nuevo a la página de productos si todo salió bien
        header("Location: index.php");
        exit;
    } else {
        echo "Error al guardar el producto: " . $stmt->error;
    }

    $stmt->close();
}
?>
