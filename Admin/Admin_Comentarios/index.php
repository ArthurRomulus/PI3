<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coffee Shop - Comentarios</title>

    <link rel="stylesheet" href="../general.css">
    <link rel="stylesheet" href="comentario.css">
</head>
<body>

    <?php include '../Admin_nav_bar.php'; ?>

    <div class="content">
        <?php include "../AdminProfileSesion.php"; ?>

        <h1>Coffee Shop</h1> 
        <div class="topbar">
            <?php include '../date.php'; ?>
        </div>

        <div class="Comentarios">
        <?php
        include "../../conexion.php";

        $result = $conn->query("SELECT * FROM resena");

        while ($row = $result->fetch_assoc()) {
            // Obtener datos del usuario
            $pfp = $conn->query("SELECT profilescreen, username FROM usuarios WHERE userid = '" . $conn->real_escape_string($row['userid']) . "'");
            $pfp_row = $pfp->fetch_assoc();

            echo "<div class='comentario' id='" . htmlspecialchars($row['idr']) . "'>";

            // Imagen de perfil
            echo "<img class='profileimgco' src='" . htmlspecialchars($pfp_row['profilescreen']) . "' alt='Profile Image'>";

            // Contenedor principal del texto
            echo "<div class='contenido-comentario'>";
            
            // Cabecera: nombre + fecha
            echo "<div class='cabecera'>";
            echo "<p class='usernameco'>" . htmlspecialchars($pfp_row['username']) . "</p>";
            echo "<span class='fecha'>" . htmlspecialchars($row['date']) . "</span>";
            echo "</div>";

            // Calificación en estrellas (dinámica)
            $estrellas = intval($row['estrellas']);
            echo "<div class='estrellas'>";
            for ($i = 1; $i <= 5; $i++) {
                if ($i <= $estrellas) {
                    echo "<span class='estrella llena'>&#9733;</span>"; // Estrella llena
                } else {
                    echo "<span class='estrella vacia'>&#9734;</span>"; // Estrella vacía
                }
            }
            echo "</div>";

            // Comentario debajo del nombre
            echo "<p class='descriptioncoment'>" . htmlspecialchars($row['comentario']) . "</p>";

            // Producto asociado
            $pro = $conn->query("SELECT * FROM productos WHERE idp = ". $row['producto']);
            $pro_row = $pro->fetch_assoc();
            echo "<p class='producto-coment'> Producto o Servicio: " . htmlspecialchars($pro_row['namep']) . "</p>";

            // Botón eliminar
            echo "<form method='POST' action='delete_comentario.php'>";
            echo "<input type='hidden' name='idr' value='" . htmlspecialchars($row['idr']) . "'>";
            echo "<button class='deletebutton'>Eliminar</button>";
            echo "</form>";

            echo "</div>"; // cierre contenido-comentario
            echo "</div>"; // cierre comentario
        }
        ?>
        </div>
    </div>
    
</body>
</html>

<?php
// Eliminar comentario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['idr'])) {
        $idr = $_POST['idr'];

        include "../../conexion.php";

        $stmt = $conn->prepare("DELETE FROM resena WHERE idr = ?");
        $stmt->bind_param("i", $idr);

        if ($stmt->execute()) {
            header("Location: index.php");
            exit();
        } else {
            echo "Error al eliminar el comentario: " . $conn->error;
        }

        $stmt->close();
        $conn->close();
    } else {
        echo "ID de comentario no proporcionado.";
    }
} else {
    // No mostrar mensaje si solo se carga la página
}
?>
