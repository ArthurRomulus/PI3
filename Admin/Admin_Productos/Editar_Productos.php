<?php
include "../../conexion.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['id'];
    $namep = trim($_POST['name']);
    $descripcion = trim($_POST['descripcion'] ?? '');
    $precioBase = (float)$_POST['precio'];
    $categorias = $_POST['categoria'] ?? [];
    $stock = isset($_POST['stock']) ? (int)$_POST['stock'] : 0;

    // --- Subida de imagen ---
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
        $sql = "UPDATE productos SET namep=?, descripcion=?, precio=?, ruta_imagen=?, STOCK=? WHERE idp=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdssi", $namep, $descripcion, $precioBase, $imagen, $stock, $id);
    } else {
        $sql = "UPDATE productos SET namep=?, descripcion=?, precio=?, STOCK=? WHERE idp=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdii", $namep, $descripcion, $precioBase, $stock, $id);
    }
    $stmt->execute();
    $stmt->close();

    // --- Actualizar categorías ---
    $conn->query("DELETE FROM producto_categorias WHERE idp = $id");
    $categorias = array_map('intval', $categorias);
    $categorias = array_filter($categorias);
    if (!empty($categorias)) {
        $stmtCat = $conn->prepare("INSERT INTO producto_categorias (idp, id_categoria) VALUES (?, ?)");
        foreach ($categorias as $id_categoria) {
            $stmtCat->bind_param("ii", $id, $id_categoria);
            $stmtCat->execute();
        }
        $stmtCat->close();
    }

    // --- Actualizar listboxes ---
    $conn->query("DELETE FROM producto_listbox WHERE producto_id = $id");
    if (isset($_POST['listbox_selected']) && is_array($_POST['listbox_selected'])) {
        $stmt_lb = $conn->prepare("INSERT INTO producto_listbox (producto_id, listbox_id) VALUES (?, ?)");
        foreach ($_POST['listbox_selected'] as $listbox_id) {
            $listbox_id = (int)$listbox_id;
            $stmt_lb->bind_param("ii", $id, $listbox_id);
            $stmt_lb->execute();
        }
        $stmt_lb->close();
    }

    header("Location: index.php");
    exit;
}
?>
