<?php
session_start();

if (!isset($_SESSION['registro_exitoso'])) {
    header("Location: registro.php");
    exit();
}

$email = $_SESSION['email_verificacion'] ?? '';
unset($_SESSION['registro_exitoso']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifica tu Email - Blackwood Coffee</title>
    <link rel="stylesheet" href="registro.css">
    <style>
        .success-container {
            text-align: center;
            padding: 40px 20px;
            max-width: 600px;
            margin: 100px auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .icon { font-size: 80px; color: #8B4513; margin-bottom: 20px; }
        h1 { color: #8B4513; margin-bottom: 20px; }
        p { color: #666; line-height: 1.6; margin: 15px 0; }
        .email-highlight { font-weight: bold; color: #8B4513; background: #f5f5f5; padding: 10px; border-radius: 5px; }
        .button {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 30px;
            background: #8B4513;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s;
        }
        .button:hover { background: #6d3410; }
    </style>
</head>
<body>
    <div class="success-container">
        <div class="icon">📧</div>
        <h1>¡Revisa tu correo!</h1>
        <p>Hemos enviado un correo de verificación a:</p>
        <p class="email-highlight"><?php echo htmlspecialchars($email); ?></p>
        <p>Por favor, abre tu correo y haz clic en el enlace de verificación para activar tu cuenta.</p>
        <p style="font-size: 14px; color: #999; margin-top: 30px;">
            Si no ves el correo, revisa tu carpeta de <strong>spam</strong> o <strong>correo no deseado</strong>.
        </p>
        <a href="login.php" class="button">Ir al Login</a>
    </div>
</body>
</html>