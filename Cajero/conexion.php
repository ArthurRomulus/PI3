<?php
// --- Configuración de la Base de Datos --- //
$host = 'localhost';
$dbname = 'tienda_db'; 
$user = 'root';
$password = '';
$charset = 'utf8mb4';

// --- Creación de la Conexión --- //
try {
    // DSN (Data Source Name) - La cadena de conexión
    $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
    
    // Opciones de PDO para un mejor manejo de errores y resultados
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Lanza excepciones en errores
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Devuelve resultados como arrays asociativos
        PDO::ATTR_EMULATE_PREPARES   => false,                  // Usa preparaciones nativas del motor de DB
    ];

    // Crear la instancia de PDO
    $pdo = new PDO($dsn, $user, $password, $options);
    
    // (Opcional) Si llegamos aquí, la conexión fue exitosa.
    // Puedes descomentar la siguiente línea para probar, pero luego bórrala en producción.
    echo "Conexión a la base de datos '$dbname' establecida correctamente.";

} catch (\PDOException $e) {
    // Si algo sale mal, capturamos la excepción y mostramos un error genérico.
    // En un entorno de producción, no deberías mostrar los detalles del error al usuario.
    // Podrías registrar el error en un archivo de logs.
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

// La variable $pdo ya está lista para ser usada en otros archivos.
?>