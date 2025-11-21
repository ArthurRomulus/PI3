<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// si no está logueado, fuera
if (empty($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
    header("Location: /PI3/General/login.php");
    exit;
}

require_once "../../conexion.php"; // AJUSTA si tu conexion.php está en otro lado

$userid = $_SESSION['userid'] ?? null;
if (!$userid) {
    header("Location: editar_perfil.php?err=session");
    exit;
}

// 1. datos del form
$email        = trim($_POST['email']        ?? '');
$nombre       = trim($_POST['nombre']       ?? '');
$apellido     = trim($_POST['apellido']     ?? '');
$telefono     = trim($_POST['telefono']     ?? '');
$fecha_nac    = trim($_POST['fecha_nac']    ?? '');
$zona_horaria = trim($_POST['zona_horaria'] ?? '');

// 2. procesar foto si el user subió una
$nuevaRutaAvatar = null;

if (
    isset($_FILES['nueva_foto']) &&
    $_FILES['nueva_foto']['error'] === UPLOAD_ERR_OK
) {
    // Carpeta física destino en el servidor
    // Estamos dentro de /Perfil/
    // ../images/profiles/ = /coffeeShop/images/profiles/
     $carpetaDestinoFS = realpath(__DIR__ . "/../../") . "/images/Profiles/";

    if (!is_dir($carpetaDestinoFS)) {
        mkdir($carpetaDestinoFS, 0777, true);
    }

    $tmpName   = $_FILES['nueva_foto']['tmp_name'];
    $origName  = $_FILES['nueva_foto']['name'];
    $ext       = pathinfo($origName, PATHINFO_EXTENSION); // jpg/png/webp/etc

    // Nombre limpio único por usuario
    $fileNameFinal = "avatar_user_" . $userid . "." . strtolower($ext);

    // Ruta FÍSICA en disco
    $rutaFS = $carpetaDestinoFS . $fileNameFinal;

    if (move_uploaded_file($tmpName, $rutaFS)) {
        // Esta ruta es la que el navegador usará en <img src="">
        // Desde /Perfil/ hacia /images/profiles es: ../images/profiles/...
         $nuevaRutaAvatar = "../../images/Profiles/" . $fileNameFinal;
    }
}

// 3. construir la query
if ($nuevaRutaAvatar !== null) {
    // con nueva imagen
    $sql = "UPDATE usuarios
            SET email = ?,
                username = ?,
                apellido = ?,
                telefono = ?,
                fecha_nac = ?,
                zona_horaria = ?,
                profilescreen = ?
            WHERE userid = ?";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        header("Location: editar_perfil.php?err=stmtAvatar");
        exit;
    }

    $stmt->bind_param(
        "sssssssi",
        $email,
        $nombre,
        $apellido,
        $telefono,
        $fecha_nac,
        $zona_horaria,
        $nuevaRutaAvatar,
        $userid
    );

} else {
    // sin nueva imagen
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
        header("Location: editar_perfil.php?err=stmtNoAvatar");
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
}

// 4. ejecutar y refrescar sesión
if ($stmt->execute()) {

    // refrescar sesión con los nuevos datos básicos
    $_SESSION['email']        = $email;
    $_SESSION['username']     = $nombre;
    $_SESSION['apellido']     = $apellido;
    $_SESSION['telefono']     = $telefono;
    $_SESSION['fecha_nac']    = $fecha_nac;
    $_SESSION['zona_horaria'] = $zona_horaria;

    // refrescar avatar en sesión si se cambió
    if ($nuevaRutaAvatar !== null) {
        $_SESSION['profilescreen'] = $nuevaRutaAvatar;
    }

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

