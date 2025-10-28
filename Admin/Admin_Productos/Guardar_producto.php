<?php
include "../../conexion.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $namep = trim($_POST['name']);
    $precio = (float)$_POST['precio'];
    $categorias = isset($_POST['categoria']) ? $_POST['categoria'] : []; // IDs de categorías
    $sabor = (int)$_POST['sabor'];

    // --- Subida de imagen ---
    $imagen = "../../Images/default.png";
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
        $targetDir = "../../Images/";
        $fileName = uniqid() . "_" . basename($_FILES['imagen']['name']);
        $targetFile = $targetDir . $fileName;

        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $targetFile)) {
            $imagen = $targetFile;
        }
    }

    // --- Insertar producto principal ---
    $sql = "INSERT INTO productos (namep, precio, sabor, ruta_imagen) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Error en la preparación del producto: " . $conn->error);
    }
    $stmt->bind_param("sdss", $namep, $precio, $sabor, $imagen);

    if ($stmt->execute()) {
        $id_producto = $stmt->insert_id;

        // --- Insertar todas las categorías seleccionadas ---
        if (!empty($categorias)) {
            $stmtCat = $conn->prepare("INSERT INTO producto_categorias (idp, id_categoria) VALUES (?, ?)");
            foreach ($categorias as $id_categoria) {
                $id_categoria = (int)$id_categoria; // asegurar entero
                $stmtCat->bind_param("ii", $id_producto, $id_categoria);
                $stmtCat->execute();
            }
            $stmtCat->close();
        }

        // --- Insertar listboxes y opciones ---
        if (isset($_POST['listbox']) && is_array($_POST['listbox'])) {
            foreach ($_POST['listbox'] as $listbox) {
                $nombre = trim($listbox['nombre']);
                $opciones = array_filter(array_map('trim', $listbox['opciones'])); // limpiar y eliminar vacíos
                if ($nombre && !empty($opciones)) {
                    $opciones_json = json_encode($opciones, JSON_UNESCAPED_UNICODE);

                    $stmt_op = $conn->prepare("INSERT INTO producto_opciones (idp, nombre, opciones) VALUES (?, ?, ?)");
                    $stmt_op->bind_param("iss", $id_producto, $nombre, $opciones_json);
                    $stmt_op->execute();
                    $stmt_op->close();
                }
            }
        }

        // Redirigir al panel
        header("Location: index.php");
        exit;
    } else {
        echo "Error al guardar el producto: " . $stmt->error;
    }

    $stmt->close();
}
?>
