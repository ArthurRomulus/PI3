<?php
// update_perfil_data.php

session_start();
header('Content-Type: application/json');

// Verificar sesión y método POST
if ($_SERVER["REQUEST_METHOD"] != "POST" || !isset($_SESSION['userid']) || $_SESSION['role'] != 2) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Acceso denegado o rol incorrecto.']);
    exit();
}

include '../../conexion.php'; // Ajusta la ruta

$userid = $_SESSION['userid'];
$conn->begin_transaction(); // Iniciar transacción

try {
    // 1. Obtener los datos del cuerpo de la solicitud (asumiendo formato form-urlencoded/FormData)
    // NOTA: El numero_empleado es de solo lectura y no se debe actualizar aquí.
    $email = trim($_POST['email'] ?? '');
    $nombre_completo = trim($_POST['nombre_completo'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $telefono_emergencia = trim($_POST['telefono_emergencia'] ?? '');
    $direccion = trim($_POST['direccion'] ?? '');

    // 2. Validar campos obligatorios (opcional, pero buena práctica)
    if (empty($email) || empty($nombre_completo) || empty($telefono) || empty($telefono_emergencia) || empty($direccion)) {
        throw new Exception("Todos los campos obligatorios del empleado deben ser llenados.");
    }

    // 3. Actualizar el EMAIL en la tabla USUARIOS
    $sql_user = "UPDATE usuarios SET email = ? WHERE userid = ?";
    $stmt_user = $conn->prepare($sql_user);
    $stmt_user->bind_param("si", $email, $userid);
    if (!$stmt_user->execute()) {
        throw new Exception("Error al actualizar correo: " . $stmt_user->error);
    }
    $stmt_user->close();

    // 4. Actualizar los datos del EMPLEADO en la tabla empleados_cajeros
    $sql_employee = "
        UPDATE empleados_cajeros 
        SET nombre_completo = ?, telefono = ?, telefono_emergencia = ?, direccion = ? 
        WHERE userid = ?
    ";
    $stmt_employee = $conn->prepare($sql_employee);
    $stmt_employee->bind_param("ssssi", $nombre_completo, $telefono, $telefono_emergencia, $direccion, $userid);
    if (!$stmt_employee->execute()) {
        throw new Exception("Error al actualizar datos de empleado: " . $stmt_employee->error);
    }
    $stmt_employee->close();
    
    // 5. Si todo fue bien, confirmar los cambios
    $conn->commit();
    $_SESSION['email'] = $email; // Actualizar la sesión con el nuevo email
    
    echo json_encode(['success' => true, 'message' => 'Datos de perfil actualizados correctamente.']);

} catch (Exception $e) {
    // Si algo falla, revertir los cambios y devolver el error
    $conn->rollback();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error al actualizar: ' . $e->getMessage()]);
    
} finally {
    if (isset($conn)) {
        $conn->close();
    }
}
?>