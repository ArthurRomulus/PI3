<?php
include "../database.php";
session_start();

$email = $_POST['email'] ?? null;
$pass  = $_POST['password'] ?? null;

if (!$email || !$pass) {
    header("Location: ../General/login.php?error=campos");
    exit();
}

// Preparar consulta
$stmt = $db->prepare("SELECT userid, username, password, role, status, profilescreen, archived FROM usuarios WHERE email = ? LIMIT 1");
if (!$stmt) {
    die("Error en consulta: " . $db->error);
}

$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: ../General/login.php?error=no_user");
    exit();
}

$user = $result->fetch_assoc();
$stmt->close();

// Verificar estado
if ($user['archived'] || !$user['status']) {
    header("Location: ../General/login.php?error=cuenta_inactiva");
    exit();
}

// Verificar contraseña
if (!password_verify($pass, $user['password'])) {
    header("Location: ../General/login.php?error=contraseña_incorrecta");
    exit();
}

// Login exitoso
session_regenerate_id(true);
$_SESSION['userid'] = $user['userid'];
$_SESSION['username'] = $user['username'];
$_SESSION['email'] = $email;
$_SESSION['role'] = $user['role'];
$_SESSION['profilescreen'] = $user['profilescreen'];


if ($_SESSION['role'] == 2) {
    header("Location: verify_sesion.php");
}


exit();
?>
