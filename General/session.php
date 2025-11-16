<?php
session_start();
include '../conexion.php';

header("Content-Type: application/json"); // Importante

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $sql = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        echo json_encode(["status" => "error", "message" => "Error interno"]);
        exit;
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {

        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {

            // Guardar sesión
            $_SESSION = [
                'userid' => $user['userid'],
                'email' => $user['email'],
                'username' => $user['username'],
                'role' => $user['role'],
                'profilescreen' => $user['profilescreen'],
                'apellido' => $user['apellido'] ?? '',
                'telefono' => $user['telefono'] ?? '',
                'fecha_nac' => $user['fecha_nac'] ?? '',
                'zona_horaria' => $user['zona_horaria'] ?? '',
                'logueado' => true,
            ];

            echo json_encode([
                "status" => "ok",
                "role" => $user['role'],
                "session" => $_SESSION
            ]);
            exit;
        }
    }

    echo json_encode(["status" => "error", "message" => "Credenciales inválidas"]);
}
