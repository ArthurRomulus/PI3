

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Coffee & Frappé - Iniciar Sesión</title>
  <link rel="stylesheet" href="login.css">
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
        <h2 id="formTitle" data-translate="☕ Envia un correo al email.">☕ Envia un correo al email.</h2>

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
        
        <form id="loginForm" method="POST" action="EmailSent.php">
          <input type="email" name="email" placeholder="Email" required>
          <button type="submit" data-translate="Enviar correo">Enviar correo</button>
        </form>

        <?php 
        
        if (isset($_GET["s"])){
          if ($_GET["s"] == "success"){
            echo "<p style='color: green;'>Revisa tu correo electronico y entra al link que te enviamos.</p>";
          } else if ($_GET["s"]  == "failed") {
            echo "<p style='color: red ;'>Error al mandar el correo.</p>";
          } else if ($_GET["s"]  == "failedNoexist") {
            echo "<p style='color: red ;'>La cuenta del correo no existe.</p>";

          }
        }

        ?>

        <a href="login.php" class="toggle-btn" data-translate="Regresar">Regresar</a>
      </div>
    </div>
  </div>
  <a href="../Usuario/index.php" target="_blank" class="logo-fijo">
    <img src="../images/logo.png" alt="Logo Blackwood Coffee">
  </a>
</body>
</html>
<script src="../translate.js"></script>



