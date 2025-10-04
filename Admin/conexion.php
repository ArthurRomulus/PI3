<?php
$host = "localhost";
$user = "root";        // tu usuario de MySQL
$pass = "";            // tu contraseña de MySQL (si no tienes, dejar vacío)
$db   = "tienda_db";   // nombre de tu base de datos

// Crear conexión
$conn = new mysqli($host, $user, $pass, $db);

// Revisar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
