<?php
// db.php — conexión con MySQL
$DB_HOST = 'localhost';
$DB_USER = 'root';        // usuario de MySQL
$DB_PASS = '123456789';            // tu contraseña (si no pusiste, déjala vacía)
$DB_NAME = 'tienda_db'; // nombre de la base de datos

$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($mysqli->connect_errno) {
  http_response_code(500);
  die('❌ Error de conexión: ' . $mysqli->connect_error);
}
$mysqli->set_charset('utf8mb4');
