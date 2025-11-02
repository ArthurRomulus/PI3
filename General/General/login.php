<?php
// Inicia la sesión para poder guardar variables de usuario
session_start();

// Incluye el archivo de conexión a la base de datos
include '../conexion.php';

// Variable para almacenar mensajes de error
$error_message = '';

// Verifica si el formulario ha sido enviado (método POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 1. Obtener y limpiar los datos del formulario
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // 2. Preparar la consulta para evitar inyección SQL
    //    >> IMPORTANTE: ahora pedimos también apellido, telefono, fecha_nac, zona_horaria
    $sql = "SELECT 
                userid,
                email,
                password,
                username,
                role,
                profilescreen,
                apellido,
                telefono,
                fecha_nac,
                zona_horaria
            FROM usuarios 
            WHERE email = ?";

    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        $error_message = "Error interno del servidor. Inténtalo más tarde.";
    } else {
        // 3. Vincular el parámetro (email)
        $stmt->bind_param("s", $email);

        // 4. Ejecutar la consulta
        $stmt->execute();

        // 5. Obtener el resultado
        $result = $stmt->get_result();

        // 6. Verificar si se encontró un usuario
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            // Verificamos contraseña con password_verify
            if (password_verify($password, $user['password'])) {
                // ✅ Login correcto

                // Guardamos datos del usuario en la sesión
                $_SESSION['userid']         = $user['userid'];
                $_SESSION['email']          = $user['email'];
                $_SESSION['username']       = $user['username'];
                $_SESSION['role']           = $user['role'];
                $_SESSION['profilescreen']  = $user['profilescreen'];

                // NUEVOS CAMPOS → también a la sesión
                $_SESSION['apellido']       = $user['apellido'] ?? '';
                $_SESSION['telefono']       = $user['telefono'] ?? '';
                $_SESSION['fecha_nac']      = $user['fecha_nac'] ?? '';
                $_SESSION['zona_horaria']   = $user['zona_horaria'] ?? '';

                // ✅ Bandera de sesión iniciada
                $_SESSION['logueado']       = true;

                // Redirección según rol
                if ($user['role'] == 4) {
                    header("Location: ../Admin/Admin_Inicio/index.php");
                } elseif ($user['role'] == 2) {
                    header("Location: ../Cajero/Inicio/Inicio.html");
                } else {
                    // Usuario normal / cliente
                    header("Location: ../coffeeShop/inicio/index.php");
                }

                exit(); // Detener ejecución después de redirigir
            } else {
                // Contraseña incorrecta
                $error_message = "Contraseña incorrecta o email incorrecto.";
            }
        } else {
            // Usuario no encontrado
            $error_message = "Contraseña incorrecta o email incorrecto.";
        }

        $stmt->close();
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Coffee & Frappé - Iniciar Sesión</title>
  <link rel="stylesheet" href="login.css">
  <style>
    .mensaje-error {
      background-color: #ffdddd;
      border: 1px solid #f44336;
      color: #f44336;
      padding: 15px;
      margin-bottom: 20px;
      text-align: center;
      border-radius: 5px;
      font-family: system-ui, sans-serif;
      font-size: 0.9rem;
    }
  </style>
</head>
<body>
  <div class="cup-wrapper">
    <div class="cup" id="cup">
      <div class="coffee-top"></div>
      <div class="foam">
        <div class="bubble bubble1"></div>
        <div class="bubble bubble2"></div>
        <div class="bubble bubble3"></div>
      </div>

      <div class="handle"></div>
      <div class="straw"></div>

      <div class="steam"></div>
      <div class="steam"></div>
      <div class="steam"></div>

      <div class="form-container" id="formContainer">
        <h2 id="formTitle">☕ Iniciar Sesión</h2>

        <?php if (!empty($error_message)): ?>
          <div class="mensaje-error"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form id="loginForm" method="POST" action="login.php">
          <input type="email" name="email" placeholder="Email" required>
          <input type="password" name="password" placeholder="Contraseña" required>
          <button type="submit">Iniciar Sesión</button>
        </form>

        <a href="registro.php" class="toggle-btn">¿No tienes cuenta? Regístrate</a>
      </div>
    </div>
  </div>

  <a href="../coffeeShop/inicio/index.php" class="logo-fijo">
    <img src="../images/logo.png" alt="Logo Blackwood Coffee">
  </a>
</body>
</html>
