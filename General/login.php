<?php
// Inicia la sesión para poder guardar variables de usuario
session_start();

// Incluye el archivo de conexión a la base de datos
include '../../conexion.php';

// Variable para almacenar mensajes de error
$error_message = '';

// Verifica si el formulario ha sido enviado (método POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 1. Obtener y limpiar los datos del formulario
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // 2. Preparar la consulta para evitar inyección SQL
    // Seleccionamos el id, el username y el password del usuario que coincida
    $sql = "SELECT userid, email, password, username, role, profilescreen FROM usuarios WHERE email = ?";
    
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        // Si la preparación de la consulta falla, es un error del servidor
        $error_message = "Error interno del servidor. Inténtalo más tarde.";
    } else {
        // 3. Vincular el parámetro (el username del formulario)
        $stmt->bind_param("s", $email);

        // 4. Ejecutar la consulta
        $stmt->execute();

        // 5. Obtener el resultado
        $result = $stmt->get_result();

        // 6. Verificar si se encontró un usuario
        if ($result->num_rows === 1) {
            // El usuario existe, ahora verificamos la contraseña
            $user = $result->fetch_assoc();
            
            // Comparamos la contraseña enviada con el hash guardado en la BD
            // password_verify() es la función segura para hacer esto
            if (password_verify($password, $user['password'])) {
                // ¡Contraseña correcta! El inicio de sesión es exitoso.
                
                // Guardamos los datos del usuario en la sesión
                $_SESSION['userid'] = $user['userid'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['profilescreen'] = $user['profilescreen'];

                if ($user['role'] == 4) {
                    header("Location: ../Admin/Admin_Inicio");

                } elseif ($user['role'] == 2) {
                                  header("Location: ../Cajero/Inicio/Inicio.html");

                } else {
                                  header("Location: ../Usuarios");

                }
                
                // Redirigimos al usuario a una página de bienvenida o al panel principal
                exit(); // Es importante terminar el script después de una redirección

            } else {
                // La contraseña es incorrecta
                echo "<div class='mensaje-error'>Contraseña incorrecta o email incorrecto.</div>";
            }
        } else {
            // El usuario no fue encontrado en la base de datos
            echo "<div class='mensaje-error'>Contraseña incorrecta o email incorrecto.</div>";
        }

        // Cerrar la sentencia preparada
        $stmt->close();
    }
    
    // Cerrar la conexión
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Coffee & Frappé - Iniciar Sesión</title>
  <link rel="stylesheet" href="registro.css">
  <style>
    /* Estilo para los mensajes de error */
    .mensaje-error {
      background-color: #ffdddd;
      border: 1px solid #f44336;
      color: #f44336;
      padding: 15px;
      margin-bottom: 20px;
      text-align: center;
      border-radius: 5px;
    }
  </style>


</head>
<body>
  <div class="cup-wrapper">
    <div class="cup" id="cup">
      <div class="coffee-top"></div>
      <div class="handle"></div>
      <div class="form-container" id="formContainer">
        <h2 id="formTitle">☕ Iniciar Sesión</h2>
        
        <form id="loginForm" method="POST" action="login.php">
          <input type="email" name="email" placeholder="Email" required>
          <input type="password" name="password" placeholder="Contraseña" required>
          <button type="submit">Iniciar Sesión</button>
        </form>

        <a href="registro.php" class="toggle-btn">¿No tienes cuenta? Regístrate</a>
      </div>
    </div>
  </div>

</body>
</html>


