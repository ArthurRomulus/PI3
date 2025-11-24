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
    $sql = "SELECT * FROM usuarios 
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
  <style>
    /* Page transition: fade in on load, fade out on navigation to registro/login */
    body.page-transition { opacity: 0; transform: translateY(8px); transition: opacity 420ms ease, transform 420ms ease; }
    body.page-transition.page-enter { opacity: 1; transform: none; }
    body.page-transition.page-exit { opacity: 0; transform: translateY(-8px); }
  </style>
  <script>
    // Animate when navigating between login and registro pages
    document.addEventListener('DOMContentLoaded', function () {
      // enable transitions
      document.body.classList.add('page-transition');
      // trigger enter
      requestAnimationFrame(function () { document.body.classList.add('page-enter'); });

      // Attach click handlers to links that target registro.php or login.php
      document.querySelectorAll('a[href]').forEach(function(a){
        var href = a.getAttribute('href');
        if (!href) return;
        // match links to registro.php or login.php (relative or absolute)
        if (href.indexOf('registro.php') !== -1 || href.indexOf('login.php') !== -1) {
          a.addEventListener('click', function(ev){
            ev.preventDefault();
            var url = a.href;
            // start exit animation
            document.body.classList.remove('page-enter');
            document.body.classList.add('page-exit');
            setTimeout(function(){ window.location.href = url; }, 420);
          });
        }
      });
    });
    // If the page is shown from bfcache, ensure animation class state
    window.addEventListener('pageshow', function(e){ if (e.persisted) { document.body.classList.add('page-enter'); } });
    // Intercept form submit to animate exit before sending POST
    (function(){
      var loginForm = document.getElementById('loginForm');
      if (loginForm) {
        loginForm.addEventListener('submit', function(ev){
          ev.preventDefault();
          // play exit animation
          document.body.classList.remove('page-enter');
          document.body.classList.add('page-exit');
          // delay actual submit to allow animation
          setTimeout(function(){ loginForm.submit(); }, 420);
        });
      }
    })();
  </script>
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
        <h2 id="formTitle" data-translate="☕ Iniciar Sesión">☕ Iniciar Sesión</h2>

        <div class="lang-flags">
        <img src="../Images/es_flag.png" id="btn-es" class="lang-flag active" alt="Español" title="Español">
        <span class="lang-divider"></span>
        <img src="../Images/uk_flag.png" id="btn-en" class="lang-flag" alt="English" title="English">
    </div>
    <style>
      /* ====== IDIOMAS ====== */
.lang-switch {
  display: flex;
  align-items: center;
  gap: 6px;
}

.lang-divider {
  width: 1px;
  height: 18px;
  background: #531607;
  opacity: 0.6;
  margin-top:15px;
}

.lang-flag {
  width: 35px;
  height: 25px;
  cursor: pointer;
  opacity: 0.7;
  border-radius: 3px;
  transition: transform 0.2s ease, opacity 0.3s ease;
  margin-top: 18px;
}

.lang-flag:hover {
  opacity: 1;
  transform: scale(1.08);
}

.lang-flag.active {
  opacity: 1;
  box-shadow: 0 0 6px rgba(133, 73, 5, 0.8);
}

      </style>

        <?php if (!empty($error_message)): ?>
          <div class="mensaje-error"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form id="loginForm" method="POST" action="login.php">
          <input type="email" name="email" data-translate-placeholder="Correo electrónico" placeholder="Correo electrónico" required>
          <input type="password" name="password" data-translate-placeholder="Contraseña" placeholder="Contraseña" required>
          <button type="submit" data-translate="Iniciar Sesión">Iniciar Sesión</button>
        </form>

        <a href="AskEmail.php" class="toggle-btn" data-translate="Olvide la contraseña"> Olvide la contraseña</a>
        <a href="registro.php" class="toggle-btn" data-translate="¿No tienes cuenta? Regístrate"> ¿No tienes cuenta? Regístrate </a>

      </div>
    </div>
  </div>

  <a href="../coffeeShop/inicio/index.php" class="logo-fijo">
    <img src="../images/home.png" alt="Logo Blackwood Coffee">
  </a>
</body>
</html>
<script src="../translate.js"></script>

