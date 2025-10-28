<?php
include "../../conexion.php";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["userid"])) {
    $userid = intval($_POST["userid"]);

    // Verificar si el usuario existe
    $check = $conn->prepare("SELECT userid FROM usuarios WHERE userid = ?");
    $check->bind_param("i", $userid);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        // Eliminar registros dependientes si es necesario (ejemplo: cajeros, admins)
        $conn->query("DELETE FROM empleados_cajeros WHERE userid = $userid");
        $conn->query("DELETE FROM administradores WHERE userid = $userid");

        // Eliminar usuario
        $stmt = $conn->prepare("DELETE FROM usuarios WHERE userid = ?");
        $stmt->bind_param("i", $userid);
        $stmt->execute();

        echo "success";
    } else {
        echo "not_found";
    }
}
?>
