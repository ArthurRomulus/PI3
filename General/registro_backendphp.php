<?php
include "../database.php";
session_start();

$user  = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$pass  = trim($_POST['password'] ?? '');

// Validación básica
if (empty($user) || empty($email) || empty($pass)) {
    header("Location: ../General/registro_usuarios.php?error=campos");
    exit();
}

// Encriptar la contraseña
$hashedPass = password_hash($pass, PASSWORD_DEFAULT);

// Verificar si el usuario o email ya existen
$check = $db->prepare("SELECT username, email FROM usuarios WHERE username = ? OR email = ?");
$check->bind_param("ss", $user, $email);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
    header("Location: ../General/registro_usuarios.php?error=existente");
    exit();
}
$check->close();

// Verificar que exista el rol 1 (usuario normal)
$roleId = 1;
$roleCheck = $db->prepare("SELECT id_rol FROM roles WHERE id_rol = ?");
$roleCheck->bind_param("i", $roleId);
$roleCheck->execute();
$roleResult = $roleCheck->get_result();

if ($roleResult->num_rows == 0) {
    // Si no existe, lo crea
    $db->query("INSERT INTO roles (rolename) VALUES ('Usuario')");
}
$roleCheck->close();

// Preparar inserción
$stmt = $db->prepare("INSERT INTO usuarios (username, email, password, profilescreen, role, status, archived)
                      VALUES (?, ?, ?, NULL, ?, 1, 0)");

if (!$stmt) {
    die("Error en la preparación: " . $db->error);
}

$stmt->bind_param("sssi", $user, $email, $hashedPass, $roleId);

if ($stmt->execute()) {
    // Registro exitoso → iniciar sesión automáticamente
    $_SESSION['userid']   = $db->insert_id;
    $_SESSION['username'] = $user;
    $_SESSION['email']    = $email;
    $_SESSION['role']     = $roleId;

    header("Location: verify_sesion.php");
    exit();
} else {
    die("Error al registrar: " . $stmt->error);
}

$stmt->close();
$db->close();
?>
