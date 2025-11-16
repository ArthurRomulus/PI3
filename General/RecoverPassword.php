<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Coffee & Frappé - Reestablecer contraseña</title>
  <link rel="stylesheet" href="login.css">

  <!-- Librería SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
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
      <div class="steam"></div><div class="steam"></div><div class="steam"></div>

      <div class="form-container" id="formContainer">
        <h2 id="formTitle">☕ Reestablecer contraseña</h2>
        
        <form id="loginForm" method="POST" action="updatepass.php">
          <input type="password" name="password" placeholder="Contraseña" required minlength="6">
          <input type="password" name="passwordConfirm" placeholder="Confirmar Contraseña" required minlength="6">
          <input type="hidden" name="Token" value="<?php if (isset($_GET['token'])) echo htmlspecialchars($_GET['token']); ?>">
          <button type="submit">Reestablecer contraseña</button>
        </form>

        <?php 
        if (isset($_GET["s"])) {
              if ($_GET["s"] == "error1") {
                echo "<p style='color: red;'>Las contraseñas no coinciden.</p>";
              } else if ($_GET["s"] == "error2") {
                echo "<p style='color: red;'>No puedes volver a usar la misma contraseña.</p>";
              } else if ($_GET["s"] == "error3" or $_GET["s"] == "error_token") {
                echo "<p style='color: red;'>El token no existe o ha expirado.</p>";
              } else if ($_GET["s"] == "error4") {
                echo "<p style='color: red;'>La contraseña debe tener al menos 6 caracteres.</p>";
              }
        }
        ?>

        <script>
          const params = new URLSearchParams(window.location.search);
          const estado = params.get("s");
          if (estado === "success") {
            Swal.fire({
              icon: "success",
              title: "Éxito",
              text: "Contraseña actualizada correctamente",
              confirmButtonText: "Ir al login"
            }).then(() => {
              window.location.href = "login.php";
            });
          }
        </script>

        <a href="login.php" class="toggle-btn">Regresar</a>
      </div>
    </div>
  </div>

  <a href="../Usuario/index.php" target="_blank" class="logo-fijo">
    <img src="../images/logo.png" alt="Logo Blackwood Coffee">
  </a>
</body>
</html>
