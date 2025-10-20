<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['idr'])) {
        $idr = $_POST['idr'];

        include "../../conexion.php";

        // Prepare and bind
        $stmt = $conn->prepare("DELETE FROM resena WHERE idr = ?");
        $stmt->bind_param("i", $idr);

        // Execute the statement
        if ($stmt->execute()) {
            // Redirect back to the comments page after deletion
            header("Location: index.php");
            exit();
        } else {
            echo "Error deleting record: " . $conn->error;
        }

        // Close the statement and connection
        $stmt->close();
        $conn->close();
    }
} else {
    echo "ID de comentario no proporcionado.";
}

?>