<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

      <link rel="stylesheet" href="../general.css">
        <link rel="stylesheet" href="comentario.css">


</head>
<body>

    <?php include '../Admin_nav_bar.php'; ?>

    <div class="content">
        <?php include "../AdminProfileSesion.php"; ?>

        <h1>Coffee shop</h1> 
        <div class="topbar">
            <?php include '../date.php'; ?>
        </div>

        <div class="Comentarios">
            <?php
                include "../../conexion.php";

                $result = $conn->query("SELECT * FROM resena");


                while ($row = $result->fetch_assoc()) {
                    echo "<div class='comentario' id = '" . htmlspecialchars($row['idr']) . "'>";
                    $pfp = $conn->query("SELECT profilescreen, username FROM usuarios WHERE userid = '" . $conn->real_escape_string($row['userid']) . "'");
                    $pfp_row = $pfp->fetch_assoc();
                    echo "<img class='profileimgco' src='" . htmlspecialchars($pfp_row['profilescreen']) . "' alt='Profile Image'>";
                    echo "<p class ='usernameco'>" . htmlspecialchars($pfp_row['username']) . "</p>";
                    echo "<p class='descriptioncoment'>" . htmlspecialchars($row['comentario']) . "</p>";
                    echo "<span class='fecha'>" . htmlspecialchars($row['date']) . "</span>";
                    echo "<form method='POST' action='delete_comentario.php' style='display:inline;'>";
                    echo    "<input type='hidden' name='idr' value=". htmlspecialchars($row['idr']) .">";
                    echo    "<button class='deletebutton'>Eliminar</button>";
                    echo "</form>";
                    echo "</div>";
                }
            ?>        


        </div>
        
    </div>
    
</body>
</html>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['idr'])) {
        $idr = $_POST['idr'];

        include "../../conexion.php";

        // Prepare and bind
        $stmt = $conn->prepare("DELETE FROM resena WHERE idr = ?");
        $stmt->bind_param("i", $idr);

        if ($stmt->execute()) {
            // Redirect back to the comments page after deletion
            header("Location: index.php");
            exit();
        } else {
            echo "Error deleting record: " . $conn->error;
        }

        $stmt->close();
        $conn->close();
    } else {
        echo "ID de comentario no proporcionado.";
    }
} else {
    echo "Método no permitido.";
}

?>