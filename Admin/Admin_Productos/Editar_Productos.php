<?php
include "../../conexion.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['id'];
    $name = trim($_POST['name']);
    $precio = (float)$_POST['precio'];
    $sabor = (int)$_POST['sabor'];
    $categorias = isset($_POST['categoria']) ? $_POST['categoria'] : []; // array de IDs

    // --- Manejar imagen ---
    $imagen = null;
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
        $targetDir = "../../Images/";
        $fileName = uniqid() . "_" . basename($_FILES['imagen']['name']);
        $targetFile = $targetDir . $fileName;

        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $targetFile)) {
            $imagen = $targetFile;
        }
    }

    // --- Actualizar tabla productos ---
    if ($imagen) {
        $sql = "UPDATE productos SET namep=?, precio=?, sabor=?, ruta_imagen=? WHERE idp=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sdssi", $name, $precio, $sabor, $imagen, $id);
    } else {
        $sql = "UPDATE productos SET namep=?, precio=?, sabor=? WHERE idp=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sdsi", $name, $precio, $sabor, $id);
    }
    $stmt->execute();
    $stmt->close();

    // --- Actualizar categorías ---
    $conn->query("DELETE FROM producto_categorias WHERE idp = $id");

    // Asegurar que solo IDs válidos lleguen a la tabla
    $categorias = array_map('intval', $categorias);
    $categorias = array_filter($categorias); // eliminar ceros o vacíos

    if (!empty($categorias)) {
        $stmtCat = $conn->prepare("INSERT INTO producto_categorias (idp, id_categoria) VALUES (?, ?)");
        foreach ($categorias as $id_categoria) {
            $stmtCat->bind_param("ii", $id, $id_categoria);
            $stmtCat->execute();
        }
        $stmtCat->close();
    }

    // --- Actualizar listboxes ---
    $conn->query("DELETE FROM producto_opciones WHERE idp = $id");

    if (isset($_POST['listbox']) && is_array($_POST['listbox'])) {
        foreach ($_POST['listbox'] as $listbox) {
            $nombre = trim($listbox['nombre']);
            $opciones = array_filter(array_map('trim', $listbox['opciones'])); // eliminar vacíos
            if ($nombre && !empty($opciones)) {
                $opciones_json = json_encode($opciones, JSON_UNESCAPED_UNICODE);

                $stmt_op = $conn->prepare("INSERT INTO producto_opciones (idp, nombre, opciones) VALUES (?, ?, ?)");
                $stmt_op->bind_param("iss", $id, $nombre, $opciones_json);
                $stmt_op->execute();
                $stmt_op->close();
            }
        }
    }

    header("Location: index.php");
    exit;
}
?>
