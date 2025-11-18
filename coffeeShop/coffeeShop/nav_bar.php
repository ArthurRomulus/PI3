<script src="/PI3/theme-toggle.js" defer></script>

<footer class="site-footer">
  <div class="footer-container">

    <!-- LOGO -->
    <div class="footer-logo header-card">
      <a href="/PI3/coffeeShop/inicio/index.php">
        <img src="../../Images/logo.png" alt="Coffee Shop logo" />
      </a>
      <span>BLACKWOOD COFFEE</span>
    </div>

    <!-- MENÚ -->
    <nav class="footer-menu header-card">
      <a href="/PI3/coffeeShop/inicio/index.php" data-translate="Inicio">Inicio</a>
      <a href="/PI3/coffeeShop/catalogo/catalogo.php" data-translate="Catálogo">Catálogo</a>
      <a href="/PI3/coffeeShop/comentarios/comentarios.php" data-translate="Comentarios">Comentarios</a>
      <a href="/PI3/coffeeShop/acercade/acercade.php" data-translate="Acerca de ">Acerca de</a>
      <a href="/PI3/coffeeShop/contactanos/contactanos.php" data-translate="Contactanos">Contactanos</a>
    </nav>

    <!-- ACCIONES (switch + login + carrito + banderas) -->
    <div class="footer-actions header-card">

      <!-- SWITCH MODO CLARO/OSCURO -->
      <div class="theme-switch-container">
        <input type="checkbox" id="theme-toggle" class="theme-toggle-checkbox">
        <label for="theme-toggle" class="theme-toggle-label">
          <span class="theme-track"></span>
          <span class="theme-thumb"></span>
        </label>
      </div>

      <!-- LOGIN -->
      <a
  href="<?php echo $usuarioLogueado
    ? '/PI3/coffeeShop/perfil/perfil_usuario.php'
    : '/PI3/General/login.php'; ?>"
  class="icon-btn login-icon login-btn"
  aria-label="Cuenta"
  title="<?php echo $usuarioLogueado ? 'Mi perfil' : 'Iniciar sesión'; ?>"
>
  <img src="../../Images/iconavatar1.png"
       alt="icono usuario"
       class="login-img">

  <span class="login-text">Login</span>
</a>


      <!-- CARRITO -->
      <a href="#" id="open-cart" class="icon-btn cart-btn" aria-label="Carrito" title="Carrito">
  <span class="cart-icon">🛒</span>
  <span class="cart-text">Carrito</span>
</a>

      <!-- IDIOMA -->
      <div class="lang-switch lang-btn header-card">

    <div class="lang-flags">
        <img src="../../Images/es_flag.png" id="btn-es" class="lang-flag active" alt="Español" title="Español">
        <span class="lang-divider"></span>
        <img src="../../Images/uk_flag.png" id="btn-en" class="lang-flag" alt="English" title="English">
    </div>

    <span class="lang-text">Idioma</span>
</div>




    </div><!-- /footer-actions -->

  </div><!-- /footer-container -->
</footer>

<style>

  .login-text,
.cart-text,
.lang-text {
  font-size: 12px;
  font-weight: 600;
  color: #3b2a25;
  margin-top: 2px;

  /* pastillita crema */
  background-color: #fbeee2;   /* tono crema */
  padding: 2px 10px;
  border-radius: 999px;
  box-shadow: 0 1px 2px rgba(0,0,0,.15);
}

  

/* Contenedor vertical tipo login/carrito */
.lang-btn {
  display: flex;
  flex-direction: column;   /* banderas arriba, texto abajo */
  align-items: center;
  gap: 50px;                 
}

/* Banderas + división */
.lang-flags {
  display: flex;
  align-items: center;
  gap: 10px;
}

/* Texto debajo */
.lang-text {
  font-size: 12px;
  font-weight: 600;
  color: #3b2a25;
  margin-top: 2px;
  margin-top: 8px;
}

  /* Botón vertical del carrito */
.cart-btn {
  display: flex;
  flex-direction: column;   /* icono arriba, texto abajo */
  align-items: center;
  gap: 11px;
  text-decoration: none;
  margin-top:19px;
}





  /* Botón vertical para login */
.login-btn {
  display: flex;
  flex-direction: column;   /* icono arriba, texto abajo */
  align-items: center;
  gap: 2px;                 /* espacio pequeño entre icono y texto */
  text-decoration: none;
}

.login-img {
  width: 32px;
  height: 32px;
  object-fit: contain;
  margin-top: 15px;
}
/* Mover login más a la izquierda */
.login-icon {
  margin-left: 40px;   /* AJUSTA EL VALOR QUE QUIERAS */
}




/* ====== HEADER GENERAL ====== */
.site-footer {
  background-color: #DCC0B9;      /* fondo rosita */
  padding: 12px 40px 20px;
  font-family: Arial, sans-serif;
  position: relative;
}

/* Línea inferior */
.site-footer::after {
  content: "";
  position: absolute;
  bottom: 6px;
  left: 40px;
  right: 40px;
  height: 1px;
  background: #531607;
}

/* CONTENEDOR INTERNO: logo | menú | acciones */
.footer-container {
  max-width: 1200px;
  margin: 0 auto;
  display: flex;
  align-items: center;
  gap: 40px;
}

/* ====== LOGO (IZQUIERDA) ====== */
.footer-logo {
  display: flex;
  align-items: center;
  gap: 10px;
  color: #531607;
  font-weight: 700;
  margin-left: -240px; /* mueve solo el logo hacia la izquierda */
}


.footer-logo img {
  width: 100px;
  height: auto;
}

.footer-logo span {
  letter-spacing: .06em;
  font-size: 14px;
}

/* ====== MENÚ (CENTRO) ====== */
.footer-menu {
  display: flex;
  align-items: center;
  gap: 48px;                 /* espacio entre Inicio, Catálogo, etc */
  flex: 1;                   /* ocupa el centro */
  justify-content: center;   /* centrado visual */
}

.footer-menu a {
  text-decoration: none;
  font-size: 18px;
  font-weight: 700;          /* más gorditas */
  letter-spacing: .4px;
  color: #531607;
  transition: .25s ease;
}

.footer-menu a:hover {
  opacity: .7;
}

/* ====== ACCIONES (DERECHA) ====== */
.footer-actions {
  display: flex;
  align-items: center;
  gap: 16px;           /* espacio entre switch, login, carrito, flags */
    margin-right: -150px; 
}

/* Botones ícono */
.icon-btn {
  font-size: 20px;
  text-decoration: none;
  color: #3b2a25;
  display: flex;
  align-items: center;
}

.icon-btn:hover {
  color: #8a4b2c;
}

/* ====== SWITCH TEMA ====== */
.theme-switch-container{
  display:flex;
  align-items:center;
  justify-content:center;
  margin-left: 25px;

}

.theme-toggle-checkbox{
  position:absolute;
  opacity:0;
  pointer-events:none;
}

.theme-toggle-label{
  position:relative;
  width:64px;
  height:32px;
  cursor:pointer;
  display:inline-flex;
  align-items:center;
  justify-content:center;
}

.theme-track{
  position:absolute;
  inset:0;
  border-radius:999px;
  background: linear-gradient(90deg, #F3E8E2, #B9AAB8);
  box-shadow:0 4px 10px rgba(0,0,0,.15);
  transition:background .25s ease, box-shadow .25s ease;
}

.theme-thumb{
  position:absolute;
  width:26px;
  height:26px;
  border-radius:50%;
  background:#FFFDF9;
  box-shadow:0 4px 10px rgba(0,0,0,.25);
  left:4px;
  top:50%;
  transform:translateY(-50%);
  display:flex;
  align-items:center;
  justify-content:center;
  transition:transform .25s ease, background .25s ease;
}

.theme-thumb::before{
  content:"☀";
  font-size:15px;
  color:#F6B94F;
  text-shadow:0 0 4px rgba(0,0,0,.25);
}

.theme-toggle-checkbox:checked + .theme-toggle-label .theme-track{
  background: linear-gradient(90deg, #2a0f0b, #4B1E17);
  box-shadow:0 4px 12px rgba(0,0,0,.35);
}

.theme-toggle-checkbox:checked + .theme-toggle-label .theme-thumb{
  transform:translate(30px, -50%);
  background:#0f0b10;
}

.theme-toggle-checkbox:checked + .theme-toggle-label .theme-thumb::before{
  content:"🌙";
  color:#E4E1F5;
}

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
  width: 26px;
  height: 16px;
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
