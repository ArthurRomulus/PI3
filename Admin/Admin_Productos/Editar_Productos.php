<?php
include "../../conexion.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['id'];
    $name = trim($_POST['name']);
    $descripcion = trim($_POST['descripcion'] ?? ''); // <-- Obtener descripción
    $precioBase = (float)$_POST['precio']; // precio base ingresado
    $sabor = (int)$_POST['sabor'];
    $categorias = isset($_POST['categoria']) ? $_POST['categoria'] : []; // array de IDs

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

    // --- Calcular precio final sumando opciones ---
    $precioFinal = $precioBase;
    if (isset($_POST['listbox']) && is_array($_POST['listbox'])) {
        foreach ($_POST['listbox'] as $listbox) {
            $opciones = [];
            if(isset($listbox['opciones']) && is_array($listbox['opciones'])){
                foreach($listbox['opciones'] as $subgroup){
                    if(is_array($subgroup)){
                        $opciones = array_merge($opciones, $subgroup);
                    } else {
                        $opciones[] = $subgroup;
                    }
                }
            }
            $opciones = array_filter(array_map('trim', $opciones));

            if (!empty($opciones)) {
                foreach ($opciones as $opValor) {
                    $stmt_precio = $conn->prepare("SELECT precio FROM opciones_predefinidas WHERE valor = ?");
                    $stmt_precio->bind_param("s", $opValor);
                    $stmt_precio->execute();
                    $res_precio = $stmt_precio->get_result();
                    if ($row_precio = $res_precio->fetch_assoc()) {
                        $precioFinal += (float)$row_precio['precio'];
                    }
                    $stmt_precio->close();
                }
            }
        }
    }

    // --- Actualizar tabla productos incluyendo descripción ---
    if ($imagen) {
        $sql = "UPDATE productos SET namep=?, descripcion=?, precio=?, sabor=?, ruta_imagen=? WHERE idp=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdssi", $name, $descripcion, $precioFinal, $sabor, $imagen, $id);
    } else {
        $sql = "UPDATE productos SET namep=?, descripcion=?, precio=?, sabor=? WHERE idp=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdsi", $name, $descripcion, $precioFinal, $sabor, $id);
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
    $conn->query("DELETE FROM producto_opciones WHERE idp = $id");

    if (isset($_POST['listbox']) && is_array($_POST['listbox'])) {
        foreach ($_POST['listbox'] as $listbox) {
            $nombre = trim($listbox['nombre']);

            $opciones = [];
            if(isset($listbox['opciones']) && is_array($listbox['opciones'])){
                foreach($listbox['opciones'] as $subgroup){
                    if(is_array($subgroup)){
                        $opciones = array_merge($opciones, $subgroup);
                    } else {
                        $opciones[] = $subgroup;
                    }
                }
            }

            $opciones = array_filter(array_map('trim', $opciones));
            $opciones_json = json_encode($opciones, JSON_UNESCAPED_UNICODE);

            if ($nombre && !empty($opciones)) {
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
