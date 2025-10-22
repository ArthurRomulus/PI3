<?php
// update_perfil_picture.php
// Maneja solo la subida y actualización de la foto de perfil (profilescreen)

session_start();
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] != "POST" || !isset($_SESSION['userid']) || $_SESSION['role'] != 2) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Acceso denegado o rol incorrecto.']);
    exit();
}

// RUTA CORREGIDA
include '../../conexion.php'; 

$userid = $_SESSION['userid'];
$conn->begin_transaction(); 

try {
    // Verificar si se subió un archivo con el nombre 'profile_file'
    if (!isset($_FILES['profile_file']) || $_FILES['profile_file']['error'] !== UPLOAD_ERR_OK) {
        throw new Exception("No se recibió ninguna imagen válida o hubo un error en la subida.");
    }

    $db_path_prefix = '../../Images/Profiles/'; 
    // La ruta absoluta para que PHP mueva el archivo (Asume que Images está en PI3/)
    $upload_dir_absolute = __DIR__ . '/../../Images/Profiles/'; 

    
    // 1. Configuración y validación
    $file_tmp_name = $_FILES['profile_file']['tmp_name'];
    $file_info = pathinfo($_FILES['profile_file']['name']);
    $file_ext = strtolower($file_info['extension']);
    $allowed_ext = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

    if (!is_dir($upload_dir_absolute)) {
        if (!mkdir($upload_dir_absolute, 0777, true)) {
             throw new Exception("No se pudo crear el directorio de subida. Verifica permisos (CHMOD 777).");
        }
    }

    if (!in_array($file_ext, $allowed_ext)) {
        throw new Exception("Tipo de archivo no permitido. Solo se aceptan imágenes.");
    }
    
    // 2. Generar nombre de archivo único
    $file_name = $userid . '_' . uniqid() . '.' . $file_ext;
    $file_destination_absolute = $upload_dir_absolute . $file_name;
    $new_profile_path = $db_path_prefix . $file_name; // Ruta para la BD

    // 3. Mover el archivo subido
    if (!move_uploaded_file($file_tmp_name, $file_destination_absolute)) {
        throw new Exception("Error al mover el archivo subido. Revisa los permisos de la carpeta 'Profiles'.");
    }

    // 4. Actualizar PROFILESCREEN en USUARIOS
    $sql_user = "UPDATE usuarios SET profilescreen = ? WHERE userid = ?";
    $stmt_user = $conn->prepare($sql_user);
    $stmt_user->bind_param("si", $new_profile_path, $userid);

    if (!$stmt_user->execute()) {
        throw new Exception("Error al actualizar la base de datos: " . $stmt_user->error);
    }
    $stmt_user->close();
    
    // 5. Actualizar la sesión y confirmar
    $_SESSION['profilescreen'] = $new_profile_path; 
    $conn->commit();
    
    echo json_encode(['success' => true, 'message' => 'Foto de perfil actualizada correctamente.']);

} catch (Exception $e) {
    $conn->rollback();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error al subir la imagen: ' . $e->getMessage()]);
    
} finally {
    if (isset($conn)) {
        $conn->close();
    }
}
?>