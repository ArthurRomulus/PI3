<?php
// Conexión a la base de datos
include 'conexion.php'; // asegúrate de que este archivo existe y conecta correctamente

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar que se haya enviado el ID
    if (isset($_POST['id'])) {
        $id = intval($_POST['id']); // seguridad: convertir a número entero

        // Preparar y ejecutar la eliminación
        $stmt = $conn->prepare("DELETE FROM promocion WHERE idPromo = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo "success";
        } else {
            echo "error";
        }

        $stmt->close();
    } else {
        echo "missing_id";
    }
} else {
    echo "invalid_method";
}

$conn->close();
?>
