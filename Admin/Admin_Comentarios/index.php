<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blackwood Coffee - Comentarios</title>

    <link rel="stylesheet" href="../general.css">
    <link rel="stylesheet" href="comentario.css">

    

    <style>
        .estrellas-mostradas-admin {
          margin-top: 5px;
          font-size: 1.1rem;
        }
        .estrellas-mostradas-admin .estrella {
          color: #CCC;
        }
        .estrellas-mostradas-admin .estrella.rellena {
          color: #FFD700;
        }
    </style>
</head>
<body>

    <?php include '../Admin_nav_bar.php'; ?>

    <div class="content">
        <?php include "../AdminProfileSesion.php"; ?>
        <h1>Blackwood Coffee</h1> 
        <div class="topbar">
            <?php include '../date.php'; ?>
        </div>

        <div class="Comentarios">
        <?php
        include "../../conexion.php";

        // 1. Leemos la nueva estructura (con 'calificacion', 'fecha', etc.)
        $result = $conn->query("SELECT * FROM resena ORDER BY fecha DESC");

        while ($row = $result->fetch_assoc()) {

            echo "<div class='comentario' id='" . htmlspecialchars($row['idr']) . "'>";

            // --- INICIO DE CAMBIOS ---
            
            // 2. Obtener datos del usuario (SIEMPRE Y CUANDO HAYA UN userid)
            $avatar_src = '../../images/DefaultProfile.png'; // Avatar por defecto (asumiendo que tienes uno)
            $nombre_usuario = htmlspecialchars($row['nombre']); // Nombre de la reseña (invitado)

            if ($row['userid']) { // Si el comentario fue hecho por un usuario logueado
                // Preparamos la consulta para evitar inyecciones
                $pfp_stmt = $conn->prepare("SELECT profilescreen, username FROM usuarios WHERE userid = ?");
                $pfp_stmt->bind_param("i", $row['userid']);
                $pfp_stmt->execute();
                $pfp_result = $pfp_stmt->get_result();
                
                if ($pfp_row = $pfp_result->fetch_assoc()) {
                    if (!empty($pfp_row['profilescreen'])) {
                        // La ruta sube 2 niveles (desde Admin/Comentarios/ hasta la raíz) y luego baja a images
                        $avatar_src = htmlspecialchars($pfp_row['profilescreen']); 
                    }
                    $nombre_usuario = htmlspecialchars($pfp_row['username']); // Usamos el nombre de la tabla usuarios
                }
                $pfp_stmt->close();
            }

            // Imagen de perfil
            echo "<img class='profileimgco' src='" . $avatar_src . "' alt='Avatar'>";
            
            echo "<div class='contenido-comentario'>";
            
            // Info del comentario (Nombre y Fecha)
            echo "<div class='info-coment'>"; // (Necesitarás esta clase en tu CSS)
                echo "<p class='username'>" . $nombre_usuario . "</p>";
                echo "<p class='fecha-coment'>" . date('d/m/Y', strtotime($row['fecha'])) . "</p>";
            echo "</div>";


            // 3. Mostrar estrellas (si no es una respuesta)
            if ($row['parent_id'] == NULL) { // Solo las reseñas padres tienen calificación
                echo "<div class='estrellas-mostradas-admin'>";
                for ($i = 1; $i <= 5; $i++) {
                    echo "<span class='estrella " . ($i <= $row['calificacion'] ? 'rellena' : '') . "'>★</span>"; 
                }
                echo "</div>";
            } else {
                echo "<p style='font-style:italic; color:#777; margin-top: 8px;'>Es una respuesta.</p>";
            }

            // 4. Texto del comentario
            echo "<p class='descriptioncoment'>" . htmlspecialchars($row['comentario']) . "</p>";

            // 5. (ELIMINADO) Ya no existe la columna 'producto', así que no se puede mostrar.
            
            // --- FIN DE CAMBIOS ---

            // Botón eliminar (esto estaba bien, posts to delete_comentario.php)
            echo "<form method='POST' action='delete_comentario.php'>";
            echo "<input type='hidden' name='idr' value='" . htmlspecialchars($row['idr']) . "'>";
            echo "<button class='deletebutton' data-translate='Eliminar'>Eliminar</button>";
            echo "</form>";

            echo "</div>"; // cierre contenido-comentario
            echo "</div>"; // cierre comentario
        }
        ?>
        </div>
    </div>
<script src="../../translate.js"></script>
</body>
</html>

<?php
// (ELIMINADO: El bloque PHP de eliminación que estaba aquí después de </html>)
// La eliminación ya se maneja en 'delete_comentario.php'
?>
