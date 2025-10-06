<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Coffee & Frappé Login</title>
  <link rel="stylesheet" href="registro_usuarios.css">
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
      <!-- Vapor -->
      <div class="steam"></div>
      <div class="steam"></div>
      <div class="steam"></div>

      <div class="form-container" id="formContainer">
        <h2 id="formTitle">☕ Registrarse</h2>
          <form method="POST" action="registro_backendphp.php" id="formaccount">
            <input type="text" name="username" placeholder="Nombre de usuario" id ="username" required>
            <input type="email" name="email" placeholder="Correo" required id="emailField">
            <input type="password" name="password" placeholder="Contraseña" required>
            <button type="submit" id="formButton">Registrar</button>
          </form>

        <button class="toggle-btn" onclick="toggleForm()" id="toggleText">¿Ya tienes cuenta? Inicia sesión</button>
      </div>
    </div>
  </div>

  <script>
    const cup = document.getElementById("cup");
    const formTitle = document.getElementById("formTitle");
    const formButton = document.getElementById("formButton");
    const toggleText = document.getElementById("toggleText");
    const emailField = document.getElementById("emailField");
    const usernameinput = document.getElementById("username");
    let isLogin = false;

    function toggleForm() {
      if (isLogin) {
        document.getElementById("formaccount").action = "regpistro_backendph.php"
        cup.classList.remove("frappe");
        formTitle.textContent = "☕ Registrarse";
        formButton.textContent = "Registrar";
        toggleText.textContent = "¿Ya tienes cuenta? Inicia sesión";
        usernameinput.style.display = "block";
      } else {
         document.getElementById("formaccount").action = "login_backendphp.php"
        cup.classList.add("frappe");
        formTitle.textContent = "🧋 Iniciar sesión";
        formButton.textContent = "Entrar";
        toggleText.textContent = "¿No tienes cuenta? Regístrate";
        usernameinput.style.display = "none";
      }
      isLogin = !isLogin;
    }
  </script>
</body>
</html>

