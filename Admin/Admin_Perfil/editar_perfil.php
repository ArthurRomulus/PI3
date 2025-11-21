<?php
session_start();
include '../../conexion.php';

if (!isset($_SESSION['userid'])) {
    header("Location: ../../General/login.php");
    exit();
}

$userid = $_SESSION['userid'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_completo = $_POST['nombre_completo'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $telefono_emergencia = $_POST['telefono_emergencia'];
    $direccion = $_POST['direccion'];

    // Subida de imagen
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0) {
        $targetDir = "../../Images/";
        $targetFile = $targetDir . basename($_FILES['profile_pic']['name']);
        move_uploaded_file($_FILES['profile_pic']['tmp_name'], $targetFile);

        $sql = "UPDATE usuarios u
                INNER JOIN administradores a ON u.userid = a.userid
                SET u.profilescreen = ?, u.email = ?, a.nombre_completo = ?, a.telefono = ?, a.telefono_emergencia = ?, a.direccion = ?
                WHERE u.userid = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssi", $targetFile, $email, $nombre_completo, $telefono, $telefono_emergencia, $direccion, $userid);
    } else {
        $sql = "UPDATE usuarios u
                INNER JOIN administradores a ON u.userid = a.userid
                SET u.email = ?, a.nombre_completo = ?, a.telefono = ?, a.telefono_emergencia = ?, a.direccion = ?
                WHERE u.userid = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssi", $email, $nombre_completo, $telefono, $telefono_emergencia, $direccion, $userid);
    }


    $stmt->execute();
    $stmt->close();
    $conn->close();

    if ($targetFile !== null) {
        $_SESSION['profilescreen'] = $targetFile;
    }

    header("Location: index.php");
    exit();
}
?>
