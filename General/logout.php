<?php
// Inicia o continúa la sesión actual
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Limpia todas las variables de sesión
$_SESSION = [];

// Destruye la sesión completamente
session_unset();
session_destroy();

// Elimina la cookie de sesión (opcional pero más limpio)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 🔹 Redirige al login principal (ruta absoluta)
header("Location: /PI3/General/login.php");
exit();
?>
