<?php
include "../conexion.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Datos del formulario
    $nombrePromo = $_POST['nombre'];          // Nombre de la promoción
    $fechaInicio = $_POST['fecha_inicio'];    // Fecha de inicio
    $fechaFin = $_POST['fecha_final'];        // Fecha final
    $valor_descuento = $_POST['valor_descuento'];  // Valor del descuento
    $tipo_descuento = $_POST['tipo_descuento'];    // 'fijo' o 'porcentaje'
    $condiciones = $_POST['condiciones'];     // Condiciones del descuento

    // Código único para código de barras
    $codigoPromo = uniqid();

    // Manejo de imagen
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
        $carpeta = "img/";
        $nombreImagen = time() . "_" . basename($_FILES['imagen']['name']);
        $rutaDestino = $carpeta . $nombreImagen;

        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)) {
            $imagen = $rutaDestino;
        } else {
            $imagen = "img/default.png";
        }
    } else {
        $imagen = "img/default.png";
    }

    // Insertar en la base de datos
    $stmt = $conn->prepare("INSERT INTO promocion 
        (nombrePromo, imagen_url, codigo_promo, condiciones, tipo_descuento, valor_descuento, fechaInicio, fechaFin, activo) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1)");

    if ($stmt === false) {
        die("Error en la preparación: " . $conn->error);
    }

    $stmt->bind_param(
        "sssssdss",
        $nombrePromo,
        $imagen,
        $codigoPromo,
        $condiciones,
        $tipo_descuento,
        $valor_descuento,
        $fechaInicio,
        $fechaFin
    );

    if ($stmt->execute()) {
        header("Location: index.php");
        exit;
    } else {
        echo "Error al guardar la promoción: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

} else {
    echo "Método no permitido.";
}
?>
