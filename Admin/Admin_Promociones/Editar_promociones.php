<?php
include "../../conexion.php";

// Verificamos que se reciba el ID
if (!isset($_POST['id'])) {
    die("ID de promoción no proporcionado");
}

$id = $_POST['id'];
$nombrePromo = $_POST['nombre'];
$fechaInicio = $_POST['fecha_inicio'];
$fechaFin = $_POST['fecha_final'];
$valor_descuento = $_POST['valor_descuento'];
$tipo_descuento = $_POST['tipo_descuento'];
$condiciones = $_POST['condiciones'] ?? '';

// Primero obtenemos la ruta actual de la imagen
$sql = "SELECT imagen_url FROM promocion WHERE idPromo = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
if($result->num_rows === 0){
    die("Promoción no encontrada");
}
$row = $result->fetch_assoc();
$imagen_ruta = $row['imagen_url'];

// Si se sube una nueva imagen, procesarla
if(isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0){
    $file = $_FILES['imagen'];
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $nuevo_nombre = time() . "_" . basename($file['name']);
    $ruta_destino = "../../Images/" . $nuevo_nombre;  // <-- subimos un nivel y apuntamos a la carpeta img

    if(move_uploaded_file($file['tmp_name'], $ruta_destino)){
        $imagen_ruta = "../../Images/" . $nuevo_nombre; // <-- ruta que se guardará en la DB
    } else {
        die("Error al subir la imagen");
    }
}

// Actualizar la promoción
$sql_update = "UPDATE promocion SET nombrePromo=?, fechaInicio=?, fechaFin=?, valor_descuento=?, tipo_descuento=?, condiciones=?, imagen_url=? WHERE idPromo=?";
$stmt_update = $conn->prepare($sql_update);
$stmt_update->bind_param(
    "sssdsssi",
    $nombrePromo,
    $fechaInicio,
    $fechaFin,
    $valor_descuento,
    $tipo_descuento,
    $condiciones,
    $imagen_ruta,
    $id
);

if($stmt_update->execute()){
    header("Location: index.php");
    exit;
} else {
    echo "Error al actualizar la promoción: " . $stmt_update->error;
}

$stmt->close();
$stmt_update->close();
$conn->close();
?>
