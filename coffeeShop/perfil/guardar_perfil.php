<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// si no está logueado, fuera
if (empty($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
    header("Location: /PI3/General/login.php");
    exit;
}

require_once "../../conexion.php"; // AJUSTA esta ruta si tu conexion.php está en otro lado

$userid = $_SESSION['userid'] ?? null;
if (!$userid) {
    header("Location: editar_perfil.php?err=session");
    exit;
}

// 1. agarrar datos del form
$email        = $_POST['email']         ?? '';
$nombre       = $_POST['nombre']        ?? '';
$apellido     = $_POST['apellido']      ?? '';
$telefono     = $_POST['telefono']      ?? '';
$fecha_nac    = $_POST['fecha_nac']     ?? '';
$zona_horaria = $_POST['zona_horaria']  ?? '';

// (opcional: sanitizar un poco)
$email        = trim($email);
$nombre       = trim($nombre);
$apellido     = trim($apellido);
$telefono     = trim($telefono);
$fecha_nac    = trim($fecha_nac);
$zona_horaria = trim($zona_horaria);

// 2. actualizar en BD
$sql = "UPDATE usuarios
        SET email = ?,
            username = ?,
            apellido = ?,
            telefono = ?,
            fecha_nac = ?,
            zona_horaria = ?
        WHERE userid = ?";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    header("Location: editar_perfil.php?err=stmt");
    exit;
}

$stmt->bind_param(
    "ssssssi",
    $email,
    $nombre,
    $apellido,
    $telefono,
    $fecha_nac,
    $zona_horaria,
    $userid
);

if ($stmt->execute()) {

    // 3. refrescar sesión con los nuevos datos
    $_SESSION['email']        = $email;
    $_SESSION['username']     = $nombre;
    $_SESSION['apellido']     = $apellido;
    $_SESSION['telefono']     = $telefono;
    $_SESSION['fecha_nac']    = $fecha_nac;
    $_SESSION['zona_horaria'] = $zona_horaria;

    $stmt->close();
    $conn->close();

    header("Location: editar_perfil.php?ok=1");
    exit;
} else {
    $stmt->close();
    $conn->close();
    header("Location: editar_perfil.php?err=updatefail");
    exit;
}
