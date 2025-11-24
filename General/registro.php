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
    elseif (strlen($username) < 6 && strlen($username) < 150) {
        $error_message = "El nombre de usuario debe de tener entre 6 y 150 caracteres.";

    }
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
                header("Location: ../coffeeShop/inicio/index.php");
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
  <style>
    /* Page transition: fade in on load, fade out on navigation to login/registro */
    body.page-transition { opacity: 0; transform: translateY(8px); transition: opacity 420ms ease, transform 420ms ease; }
    body.page-transition.page-enter { opacity: 1; transform: none; }
    body.page-transition.page-exit { opacity: 0; transform: translateY(-8px); }
  </style>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      document.body.classList.add('page-transition');
      requestAnimationFrame(function () { document.body.classList.add('page-enter'); });
      document.querySelectorAll('a[href]').forEach(function(a){
        var href = a.getAttribute('href');
        if(!href) return;
        if (href.indexOf('login.php') !== -1 || href.indexOf('registro.php') !== -1) {
          a.addEventListener('click', function(ev){
            ev.preventDefault();
            var url = a.href;
            document.body.classList.remove('page-enter');
            document.body.classList.add('page-exit');
            setTimeout(function(){ window.location.href = url; }, 420);
          });
        }
      });
    });
    window.addEventListener('pageshow', function(e){ if (e.persisted) { document.body.classList.add('page-enter'); } });
  </script>
</head>
  <script>
    // Intercept register form submit to animate exit before sending POST
    (function(){
      var regForm = document.getElementById('registerForm');
      if (regForm) {
        regForm.addEventListener('submit', function(ev){
          ev.preventDefault();
          document.body.classList.remove('page-enter');
          document.body.classList.add('page-exit');
          setTimeout(function(){ regForm.submit(); }, 420);
        });
      }
    })();
  </script>
<body>

  <div class="cup-wrapper">
    <div class="cup" id="cup">
      <div class="coffee-top"></div>
      <div class="handle"></div>
      <div class="form-container" id="formContainer">
        <h2 id="formTitle" data-translate="☕ Registrarse">☕ Registrarse</h2>

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

        <form id="registerForm" method="POST" action="registro.php">

          <?php if (!empty($error_message)): ?>
            <div class="mensaje-error"><?php echo $error_message; ?></div>
          <?php endif; ?>

          <input type="text" name="username" data-translate-placeholder="Usuario" placeholder="Usuario" required>
          <input type="email" name="email" data-translate-placeholder="Correo electrónico" placeholder="Correo electrónico" required>
          <input type="password" name="password" data-translate-placeholder="Contraseña" placeholder="Contraseña" required>
          <input type="password" name="confirm_password" data-translate-placeholder="Confirmar contraseña" placeholder="Confirmar contraseña" required>
          <button type="submit" data-translate="Registrarse">Registrarse</button>
          <div class="terms-container">
            <input type="checkbox" name="terms" required>
             <span data-translate="Acepto los">Acepto los</span>

    <a href="#" id="openModal" 
       style="color:#f0a474; text-decoration:underline;"
       data-translate="términos y condiciones">
       términos y condiciones
    </a>
          </div>
        </form>
        <a href="login.php" class="toggle-btn" data-translate="¿Ya tienes cuenta? Inicia sesión">¿Ya tienes cuenta? Inicia sesión</a>
      </div>
    </div>
  </div>
  <a href="../coffeeShop/Inicio/" class="logo-fijo">
    <img src="../images/home.png" alt="Logo Blackwood Coffee">
  </a>
  <!-- Modal de Términos y Condiciones -->
<div id="termsModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <div class="modal-body">
      <h2 data-translate="Términos y Condiciones">Términos y Condiciones</h2>
      <p>
        <h3 data-translate="AVISO DE PRIVACIDAD INTEGRAL">AVISO DE PRIVACIDAD INTEGRAL</h3>
  <p>
  <strong>Blackwood Coffee</strong><br>

  <span data-translate="Fecha de actualización:">
      Fecha de actualización:
  </span>

  <strong data-translate="22 de octubre de 2025">
      22 de octubre de 2025
  </strong>
</p>


  <h3 data-translate="I. IDENTIDAD Y DOMICILIO DEL RESPONSABLE">I. IDENTIDAD Y DOMICILIO DEL RESPONSABLE</h3>
  <p data-translate="Blackwood Coffee, con domicilio en Universidad de Colima, Campus El Naranjo, Facultad de Ingeniería Electromecánica, es responsable del tratamiento y protección de los datos personales que se recaben, conforme a la Ley Federal de Protección de Datos Personales en Posesión de los Particulares (LFPDPPP).">
  Blackwood Coffee, con domicilio en Universidad de Colima, Campus El Naranjo, Facultad de Ingeniería Electromecánica, es responsable del tratamiento y protección de los datos personales que se recaben, conforme a la Ley Federal de Protección de Datos Personales en Posesión de los Particulares (LFPDPPP).
  </p>

  <h3 data-translate="II. DATOS PERSONALES OBJETO DE TRATAMIENTO">II. DATOS PERSONALES OBJETO DE TRATAMIENTO</h3>
  <p data-translate="los datos personales recabados son:">Los datos personales recabados son:</p>
  <ul>
  <li data-translate="Identificación y contacto: nombre, correo electrónico, teléfono y usuario.">Identificación y contacto: nombre, correo electrónico, teléfono y usuario.</li>
  <li data-translate="Datos fiscales: RFC (solo si solicita factura).">Datos fiscales: RFC (solo si solicita factura).</li>
  <li data-translate="Datos administrativos del sistema interno.">Datos administrativos del sistema interno.</li>
  </ul>
  <p data-translate="No se solicitan datos sensibles.">No se solicitan datos sensibles.</p>

  <h3 data-translate="III. FINALIDADES DEL TRATAMIENTO">III. FINALIDADES DEL TRATAMIENTO</h3>
  <p><strong data-translate="Primas (obligatorias):">Primarias (obligatorias):</strong></p>
  <ul>
  <li data-translate="Gestionar pedidos y compras.">Gestionar pedidos y compras.</li>
  <li data-translate="Emitir comprobantes fiscales.">Emitir comprobantes fiscales.</li>
  <li data-translate="Identificar al usuario.">Identificar al usuario.</li>
  <li data-translate="Gestionar pagos y operaciones comerciales.">Gestionar pagos y operaciones comerciales.</li>
  <li data-translate="Administración interna.">Administración interna.</li>

  </ul>

  <p><strong data-translate="Secundarias (opcionales):">Secundarias (opcionales):</strong></p>
  <ul>
  <li data-translate="Promociones y descuentos.">Promociones y descuentos.</li>
  <li data-translate="Encuestas y análisis estadísticos.">Encuestas y análisis estadísticos.</li>
  <li data-translate="Uso de contenido visual en eventos.">Uso de contenido visual en eventos.</li>

  </ul>
  <p>
  <span data-translate="Puede negar el uso secundario enviando correo a:">
    Puede negar el uso secundario enviando correo a:
  </span>
  <strong>coffeeshopPIE3@gmail.com</strong>
</p>


  <h3 data-translate="IV. FUNDAMENTO LEGAL">IV. FUNDAMENTO LEGAL</h3>
  <p data-translate="Artículos aplicables de LFPDPPP y su Reglamento.">Artículos aplicables de LFPDPPP y su Reglamento.</p>

  <h3 data-translate="V. TRANSFERENCIA DE DATOS PERSONALES">V. TRANSFERENCIA DE DATOS PERSONALES</h3>
  <p data-translate="Únicamente a:">Únicamente a:</p>
  <ul>
  <li data-translate="Autoridades fiscales y administrativas."> Autoridades fiscales y administrativas.</li>
  <li data-translate="Proveedores de servicios tecnológicos y administrativos.">Proveedores de servicios tecnológicos y administrativos. </li>

  </ul>
  <p data-translate="No habrá transferencias adicionales sin autorización.">No habrá transferencias adicionales sin autorización.</p>

  <h3 data-translate="VI. DERECHOS ARCO">VI. DERECHOS ARCO</h3>
  <p>
    <span data-translate="Puede solicitar acceso, rectificación, cancelación u oposición mediante correo:">
    Puede solicitar acceso, rectificación, cancelación u oposición mediante correo:
  </span>
  <strong>coffeeshopPIE3@gmail.com</strong>
  </p>

  <h3 data-translate="VII. REVOCACIÓN DEL CONSENTIMIENTO">VII. REVOCACIÓN DEL CONSENTIMIENTO</h3>
  <p data-translate="Puede revocar su consentimiento enviando solicitud vía correo.">Puede revocar su consentimiento enviando solicitud vía correo.</p>

  <h3 data-translate="VIII. LIMITACIÓN DE USO O DIVULGACIÓN">VIII. LIMITACIÓN DE USO O DIVULGACIÓN</h3>
  <p data-translate="Puede solicitarlo por correo indicando nombre y medio a excluir.">Puede solicitarlo por correo indicando nombre y medio a excluir.</p>

  <h3 data-translate="IX. MEDIDAS DE SEGURIDAD">IX. MEDIDAS DE SEGURIDAD</h3>
  <p data-translate="Blackwood Coffee aplica medidas técnicas, administrativas y físicas para proteger sus datos.">Blackwood Coffee aplica medidas técnicas, administrativas y físicas para proteger sus datos.</p>

  <h3 data-translate="X. USO DE COOKIES">X. USO DE COOKIES</h3>
  <p data-translate="Este sitio utiliza cookies para mejorar la experiencia del usuario. Puede desactivarlas en su navegador.">Este sitio utiliza cookies para mejorar la experiencia del usuario. Puede desactivarlas en su navegador.</p>

  <h3 data-translate="XI. CAMBIOS AL AVISO DE PRIVACIDAD">XI. CAMBIOS AL AVISO DE PRIVACIDAD</h3>
  <p data-translate="El documento podrá actualizarse y se notificará mediante medios oficiales.">El documento podrá actualizarse y se notificará mediante medios oficiales.</p>

  <h3 data-translate="XII. CONSENTIMIENTO">XII. CONSENTIMIENTO</h3>
  <p data-translate="Al utilizar nuestro servicio, usted reconoce que ha leído y aceptado este aviso de privacidad.">Al utilizar nuestro servicio, usted reconoce que ha leído y aceptado este aviso de privacidad.</p>

  <p>
  <strong data-translate="Última actualización:">Última actualización:</strong> 
  <span data-translate="22 de octubre de 2025">22 de octubre de 2025</span>
</p>


  <hr>

  <p>
  <strong data-translate="Por favor, lea cuidadosamente antes de registrar su cuenta.">
    Por favor, lea cuidadosamente antes de registrar su cuenta.
  </strong>
</p>

      </p> 
    </div>
  </div>
</div>

<script>
  const modal = document.getElementById("termsModal");
  const openModal = document.getElementById("openModal");
  const closeModal = document.querySelector(".close");

  // Abrir modal
  openModal.addEventListener("click", function(e){
    e.preventDefault();
    modal.style.display = "block";
  });

  // Cerrar Modal
  closeModal.addEventListener("click", function(){
    modal.style.display = "none";
  });

  // Cerrar si se hace clic afuera
  window.addEventListener("click", function(e){
    if(e.target == modal){
      modal.style.display = "none";
    }
  });
</script>
<script src="../translate.js"></script>
</body>
</html>
