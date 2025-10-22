<?php
// get_perfil_data.php

session_start();
header('Content-Type: application/json');

// Verificar sesión y rol (solo empleados - rol 2)
if (!isset($_SESSION['userid']) || $_SESSION['role'] != 2) {
    // Si no está logueado o no es rol 2, devolver error de acceso
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Acceso no autorizado para este recurso.']);
    exit();
}

include '../../conexion.php'; // Asegúrate de que esta ruta sea la correcta

$userid = $_SESSION['userid'];

try {
    // Consulta JOIN para obtener datos de usuarios y empleados_cajeros (solo rol 2)
    $sql = "
        SELECT 
            u.email, 
            u.profilescreen, 
            e.nombre_completo,
            e.telefono,
            e.telefono_emergencia,
            e.direccion,
            e.numero_empleado
        FROM 
            usuarios u
        JOIN 
            empleados_cajeros e ON u.userid = e.userid
        WHERE 
            u.userid = ? AND u.role = 2
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userid);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($user = $result->fetch_assoc()) {
        echo json_encode(['success' => true, 'data' => $user]);
    } else {
        // Esto podría pasar si el usuario es rol 2 pero el registro en empleados_cajeros falta
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Datos de empleado no encontrados.']);
    }

    $stmt->close();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
} finally {
    $conn->close();
}
?>