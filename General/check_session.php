<?php
session_start();

function isLoggedIn() {
    // ahora coincide con tu login.php
    return isset($_SESSION['userid']) && isset($_SESSION['logueado']) && $_SESSION['logueado'] === true;
}

function redirectToProfile() {
    if (isLoggedIn()) {
        header("Location: ../coffeeShop/perfil/perfil_usuario.php");
        exit();
    }
    header("Location: ../General/login.php");
    exit();
}

// Para uso en AJAX
if (isset($_GET['check'])) {
    echo json_encode(['logged_in' => isLoggedIn()]);
    exit();
}
?>
