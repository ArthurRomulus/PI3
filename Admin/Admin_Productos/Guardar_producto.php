<?php
include "../../conexion.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $namep = trim($_POST['name']);
    $descripcion = trim($_POST['descripcion'] ?? '');
    $precioBase = (float)$_POST['precio'];
    $categorias = $_POST['categoria'] ?? [];
    $stock = isset($_POST['stock']) ? (int)$_POST['stock'] : 0;

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
    $sql = "INSERT INTO productos (namep, precio, ruta_imagen, descripcion, STOCK) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdssi", $namep, $precioBase, $imagen, $descripcion, $stock);

    if ($stmt->execute()) {
        $id_producto = $stmt->insert_id;

        // --- Guardar categorías ---
        if (!empty($categorias)) {
            $stmtCat = $conn->prepare("INSERT INTO producto_categorias (idp, id_categoria) VALUES (?, ?)");
            foreach ($categorias as $id_categoria) {
                $id_categoria = (int)$id_categoria;
                $stmtCat->bind_param("ii", $id_producto, $id_categoria);
                $stmtCat->execute();
            }
            $stmtCat->close();
        }

        // --- Guardar listboxes seleccionados ---
        if (isset($_POST['listbox_selected']) && is_array($_POST['listbox_selected'])) {
            $stmt_lb = $conn->prepare("INSERT INTO producto_listbox (producto_id, listbox_id) VALUES (?, ?)");
            foreach ($_POST['listbox_selected'] as $listbox_id) {
                $listbox_id = (int)$listbox_id;
                $stmt_lb->bind_param("ii", $id_producto, $listbox_id);
                $stmt_lb->execute();
            }
            $stmt_lb->close();
        }

        // --- Precio final igual al base ---
        $precioFinal = $precioBase;
        $stmt_update = $conn->prepare("UPDATE productos SET precio = ? WHERE idp = ?");
        $stmt_update->bind_param("di", $precioFinal, $id_producto);
        $stmt_update->execute();
        $stmt_update->close();

        // --- Redirección ---
        header("Location: index.php");
        exit;

    } else {
        echo "Error al guardar el producto: " . $stmt->error;
    }

    $stmt->close();
}
?>
