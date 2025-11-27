<?php
session_start();
include 'conexion.php';

$mensaje = '';
$tipo = 'error'; // 'error' o 'success'

if (isset($_GET['token'])) {
    $token = trim($_GET['token']);
    
    // Buscar el token
    $sql = "SELECT t.userid, t.usado, t.expira_en, u.username, u.email 
            FROM tokens_verificacion t 
            INNER JOIN usuarios u ON t.userid = u.userid 
            WHERE t.token = ? AND t.usado = 0";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Verificar si el token expiró
        if (strtotime($row['expira_en']) < time()) {
            $mensaje = "Este enlace de verificación ha expirado. Por favor, solicita uno nuevo.";
            $tipo = 'error';
        } else {
            // Token válido, verificar usuario
            $userid = $row['userid'];
            
            // Actualizar usuario como verificado
            $sql_update_user = "UPDATE usuarios SET verificado = 1 WHERE userid = ?";
            $stmt_update = $conn->prepare($sql_update_user);
            $stmt_update->bind_param("i", $userid);
            
            // Marcar token como usado
            $sql_update_token = "UPDATE tokens_verificacion SET usado = 1 WHERE token = ?";
            $stmt_token = $conn->prepare($sql_update_token);
            $stmt_token->bind_param("s", $token);
            
            if ($stmt_update->execute() && $stmt_token->execute()) {
                $mensaje = "¡Cuenta verificada exitosamente! Ya puedes iniciar sesión.";
                $tipo = 'success';
                
                // Guardar info para mostrar mensaje en login
                $_SESSION['email_verificado'] = $row['email'];
            } else {
                $mensaje = "Error al verificar la cuenta. Inténtalo de nuevo.";
            }
        }
    } else {
        $mensaje = "Token inválido o ya fue utilizado.";
    }
} else {
    $mensaje = "No se proporcionó un token de verificación.";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación de Email - Blackwood Coffee</title>
    <link rel="stylesheet" href="registro.css">
    <style>
        .verification-container {
            text-align: center;
            padding: 40px 20px;
            max-width: 600px;
            margin: 100px auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .icon-success { font-size: 100px; color: #4CAF50; margin-bottom: 20px; }
        .icon-error { font-size: 100px; color: #f44336; margin-bottom: 20px; }
        h1 { color: #8B4513; margin: 20px 0; }
        .message { color: #666; font-size: 18px; line-height: 1.6; margin: 20px 0; }
        .button {
            display: inline-block;
            margin-top: 20px;
            padding: 15px 40px;
            background: #8B4513;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            transition: background 0.3s;
        }
        .button:hover { background: #6d3410; }
    </style>
</head>
<body>
    <div class="verification-container">
        <?php if ($tipo === 'success'): ?>
            <div class="icon-success">✓</div>
            <h1>¡Verificación Exitosa!</h1>
        <?php else: ?>
            <div class="icon-error">✗</div>
            <h1>Error de Verificación</h1>
        <?php endif; ?>
        
        <p class="message"><?php echo htmlspecialchars($mensaje); ?></p>
        
        <a href="login.php" class="button">Ir a Iniciar Sesión</a>
    </div>
</body>
</html>