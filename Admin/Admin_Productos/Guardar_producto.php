<?php
include "../../conexion.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $namep = trim($_POST['name']);
    $descripcion = trim($_POST['descripcion'] ?? ''); // Nueva línea para la descripción
    $precioBase = (float)$_POST['precio']; // precio que ingresó el usuario
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

    // --- Calcular precio final sumando opciones ---
    $precioFinal = $precioBase;

    if (isset($_POST['listbox']) && is_array($_POST['listbox'])) {
        foreach ($_POST['listbox'] as $listbox) {
            $opciones_raw = $listbox['opciones'] ?? [];
            $opciones = [];

            foreach ($opciones_raw as $subArray) {
                if (is_array($subArray)) {
                    foreach ($subArray as $op) {
                        $opTrim = trim($op);
                        if ($opTrim !== '') $opciones[] = $opTrim;
                    }
                } else {
                    $opTrim = trim($subArray);
                    if ($opTrim !== '') $opciones[] = $opTrim;
                }
            }

            // Sumar precios de cada opción seleccionada
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

    // --- Insertar producto principal con precio final y descripción ---
    $sql = "INSERT INTO productos (namep, precio, sabor, ruta_imagen, descripcion) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Error en la preparación del producto: " . $conn->error);
    }
    $stmt->bind_param("sdsss", $namep, $precioFinal, $sabor, $imagen, $descripcion);

    if ($stmt->execute()) {
        $id_producto = $stmt->insert_id;

        // --- Insertar todas las categorías seleccionadas ---
        if (!empty($categorias)) {
            $stmtCat = $conn->prepare("INSERT INTO producto_categorias (idp, id_categoria) VALUES (?, ?)");
            foreach ($categorias as $id_categoria) {
                $id_categoria = (int)$id_categoria;
                $stmtCat->bind_param("ii", $id_producto, $id_categoria);
                $stmtCat->execute();
            }
            $stmtCat->close();
        }

        // --- Insertar listboxes y opciones ---
        if (isset($_POST['listbox']) && is_array($_POST['listbox'])) {
            foreach ($_POST['listbox'] as $listbox) {
                $categoriaNombre = $listbox['nombre'] ?? '';

                $opciones_raw = $listbox['opciones'] ?? [];
                $opciones = [];
                foreach ($opciones_raw as $subArray) {
                    if (is_array($subArray)) {
                        foreach ($subArray as $op) {
                            $opTrim = trim($op);
                            if ($opTrim !== '') $opciones[] = $opTrim;
                        }
                    } else {
                        $opTrim = trim($subArray);
                        if ($opTrim !== '') $opciones[] = $opTrim;
                    }
                }

                $opciones_json = json_encode($opciones, JSON_UNESCAPED_UNICODE);

                if ($categoriaNombre && !empty($opciones)) {
                    $stmt_op = $conn->prepare("INSERT INTO producto_opciones (idp, nombre, opciones) VALUES (?, ?, ?)");
                    $stmt_op->bind_param("iss", $id_producto, $categoriaNombre, $opciones_json);
                    $stmt_op->execute();
                    $stmt_op->close();
                }
            }
        }

        header("Location: index.php");
        exit;
    } else {
        echo "Error al guardar el producto: " . $stmt->error;
    }

    $stmt->close();
}
?>
