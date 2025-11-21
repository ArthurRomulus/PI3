<?php
session_start();
include "../../conexion.php"; // conexión a la DB

$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Consulta usando la tabla de usuarios
    $sql = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {

            // ✅ Solo permitir admin
            if ($user['role'] == 4) { // 4 = admin
                $_SESSION['userid']       = $user['userid'];
                $_SESSION['email']        = $user['email'];
                $_SESSION['username']     = $user['username'];
                $_SESSION['role']         = $user['role'];
                $_SESSION['profilescreen'] = $user['profilescreen'];

                $_SESSION['logueado'] = true;

                header("Location: ../../Admin/Admin_Inicio/index.php"); // Inicio admin
                exit();
            } else {
                $error_message = "No tienes permisos de administrador.";
            }

        } else {
            $error_message = "Contraseña incorrecta.";
        }

    } else {
        $error_message = "Usuario no encontrado.";
    }

    $stmt->close();
}
$conn->close();
?>
