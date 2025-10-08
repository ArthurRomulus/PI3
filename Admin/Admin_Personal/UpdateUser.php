<?php
include "../../database.php";

$userid   = $_POST['id'] ?? null;
$username = $_POST['username'] ?? null;
$password = $_POST['password'] ?? null;
$role     = $_POST['role'] ?? null; // <── Nuevo campo

if (!$userid || !$username) {
    exit("❌ Faltan datos requeridos.");
}

$imagePath = null;
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = "../../Images/"; // ✅ Agregar barra al final
    $imageName = basename($_FILES['image']['name']);
    $imagePath = $uploadDir . $imageName;

    if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
        // Ruta que guardarás en la base de datos (relativa a tu proyecto)
        $imagePath = "../../Images/" . $imageName;
    } else {
        echo "❌ Error al mover la imagen.";
    }
}

$query = "UPDATE usuarios SET username = ?";
$params = [$username];

if (!empty($password)) {
    $hashedPass = password_hash($password, PASSWORD_DEFAULT);
    $query .= ", password = ?";
    $params[] = $hashedPass;
}

if ($imagePath) {
    $query .= ", profilescreen = ?";
    $params[] = $imagePath;
}

if ($role) {
    $query .= ", role = ?";
    $params[] = $role;
}

$query .= " WHERE userid = ?";
$params[] = $userid;

try {
    $stmt = $conn->prepare($query);
    $stmt->execute($params);
    echo "✅ Usuario actualizado correctamente.";
} catch (Exception $e) {
    echo "❌ Error al actualizar: " . $e->getMessage();
}
?>
    