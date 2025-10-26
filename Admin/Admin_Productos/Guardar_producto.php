<?php
include "../../conexion.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $namep = $_POST['name'];
    $precio = $_POST['precio'];
    $categorias = $_POST['categoria']; // ahora contiene IDs
    $sabor = $_POST['sabor'];

    // --- Subida de imagen ---
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
        $targetDir = "../../Images/";
        $fileName = uniqid() . "_" . basename($_FILES['imagen']['name']);
        $targetFile = $targetDir . $fileName;

        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $targetFile)) {
            $imagen = $targetFile;
        } else {
            $imagen = "../../Images/default.png";
        }
    } else {
        $imagen = "../../Images/default.png";
    }

    // --- Insertar producto principal ---
    $sql = "INSERT INTO productos (namep, precio, sabor, ruta_imagen) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Error en la preparación del producto: " . $conn->error);
    }

    // s = string, d = double, s = string, s = string
    $stmt->bind_param("sdss", $namep, $precio, $sabor, $imagen);

    if ($stmt->execute()) {
        $id_producto = $stmt->insert_id;

        // --- Insertar todas las categorías seleccionadas ---
        foreach ($categorias as $id_categoria) {
            $relacion = $conn->prepare("INSERT INTO producto_categorias (idp, id_categoria) VALUES (?, ?)");
            if ($relacion === false) {
                die("Error en preparación de relación: " . $conn->error);
            }
            $relacion->bind_param("ii", $id_producto, $id_categoria);
            $relacion->execute();
            $relacion->close();
        }

        // Redirigir de vuelta al panel
        header("Location: index.php");
        exit;
    } else {
        echo "Error al guardar el producto: " . $stmt->error;
    }

    $stmt->close();
}
?>
