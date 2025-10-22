<?php
// Inicia la sesión para poder guardar las variables del usuario
session_start();

// Incluye el archivo de conexión a la base de datos
include '../conexion.php';

// Variable para almacenar mensajes de error
$error_message = '';

// Verificar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 1. Recoger y limpiar los datos del formulario
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // 2. Validaciones
    // a) Verificar que las contraseñas coincidan
    if ($password !== $confirm_password) {
        $error_message = "Las contraseñas no coinciden.";
    } 
    // b) Verificar que la contraseña tenga una longitud mínima
    elseif (strlen($password) < 6) {
        $error_message = "La contraseña debe tener al menos 6 caracteres.";
    } else {
        // c) Verificar si el email ya existe
        $sql = "SELECT userid FROM usuarios WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result(); // Necesario para comprobar num_rows

        if ($stmt->num_rows > 0) {
            $error_message = "El correo electrónico ya está registrado.";
        } else {
            // d) Verificar si el nombre de usuario ya existe
            $sql_user = "SELECT userid FROM usuarios WHERE username = ?";
            $stmt_user = $conn->prepare($sql_user);
            $stmt_user->bind_param("s", $username);
            $stmt_user->execute();
            $stmt_user->store_result();

            if ($stmt_user->num_rows > 0) {
                $error_message = "El nombre de usuario ya está en uso.";
            }
        }
        
        // Cerrar las sentencias de verificación
        $stmt->close();
        if (isset($stmt_user)) {
            $stmt_user->close();
        }
    }

    // 3. Si no hay errores, proceder a insertar el usuario
    if (empty($error_message)) {
        
        // Cifrar la contraseña ANTES de guardarla
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        
        // Preparar la sentencia de inserción
        // Asignamos rol '2' (usuario normal) y status '1' (activo) por defecto
        $sql_insert = "INSERT INTO usuarios (username, email, password, role, status) VALUES (?, ?, ?, 1, 1)";
        $stmt_insert = $conn->prepare($sql_insert);
        
        if ($stmt_insert) {
            // Vincular parámetros: s = string
            $stmt_insert->bind_param("sss", $username, $email, $hashed_password);
            
            // Ejecutar la inserción
            if ($stmt_insert->execute()) {
                // --- INICIO DE SESIÓN AUTOMÁTICO ---
                
                // 1. Obtenemos el ID del usuario que acabamos de crear
                $new_user_id = $conn->insert_id;

                // 2. Creamos las variables de sesión para el usuario
                $_SESSION['userid'] = $new_user_id;
                $_SESSION['username'] = $username;
                $_SESSION['email'] = $email;
                $_SESSION['role'] = 1; // Rol asignado por defecto

                // 3. Redirigimos al usuario a la página de bienvenida
                header("Location: ../Usuario/index.php");
                exit();
                // --- FIN DE INICIO DE SESIÓN AUTOMÁTICO ---
                
            } else {
                $error_message = "Hubo un error al crear la cuenta. Inténtalo de nuevo.";
            }
            $stmt_insert->close();
        } else {
            $error_message = "Error interno del servidor.";
        }
    }
    
    // Cerrar la conexión principal
    if (isset($conn)) {
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Coffee & Frappé Registro</title>
  <link rel="stylesheet" href="registro.css">
  <style>
    /* Estilos para mensajes (puedes moverlos a tu CSS) */
    .mensaje-error {
      background-color: #ffdddd; border: 1px solid #f44336; color: #f44336;
      padding: 15px; margin-bottom: 20px; text-align: center; border-radius: 5px;
    }
    .mensaje-exito {
      background-color: #d4edda; border: 1px solid #c3e6cb; color: #155724;
      padding: 15px; margin-bottom: 20px; text-align: center; border-radius: 5px;
    }
  </style>
</head>
<body>

  <div class="cup-wrapper">
    <div class="cup" id="cup">
      <div class="coffee-top"></div>
      <div class="handle"></div>
      <div class="form-container" id="formContainer">
        <h2 id="formTitle">☕ Registrarse</h2>

        <form id="registerForm" method="POST" action="registro.php">

          <?php if (!empty($error_message)): ?>
            <div class="mensaje-error"><?php echo $error_message; ?></div>
          <?php endif; ?>

          <input type="text" name="username" placeholder="Usuario" required>
          <input type="email" name="email" placeholder="Correo" required>
          <input type="password" name="password" placeholder="Contraseña" required>
          <input type="password" name="confirm_password" placeholder="Confirmar contraseña" required>
          <button type="submit">Registrar</button>
          <div class="terms-container">
            <input type="checkbox" name="terms" required>
            <span>Acepto los <a href="#" style="color:#f0a474; text-decoration:underline;">términos y condiciones</a></span>
          </div>
        </form>
        <a href="login.php" class="toggle-btn">¿Ya tienes cuenta? Inicia sesión</a>
      </div>
    </div>
  </div>
</body>
</html>
