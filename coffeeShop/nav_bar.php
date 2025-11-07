<script src="/PI3/theme-toggle.js" defer></script>
<footer class="site-footer">
  <div class="footer-container">
    <!-- LOGO -->
    <div class="footer-logo">
      <a href="/PI3/coffeeShop/inicio/index.php">
        <img src="../../Images/logo.png" alt="Coffee Shop logo" />
      </a>
      <span>COFFEE SHOP</span>
    </div>

    <!-- MENÚ -->
    <nav class="footer-menu">
      <a href="/PI3/coffeeShop/inicio/index.php" data-translate="Inicio">Inicio</a>
      <a href="/PI3/coffeeShop/catalogo/catalogo.php" data-translate="Catálogo">Catálogo</a>
      <a href="/PI3/coffeeShop/comentarios/comentarios.php" data-translate="Comentarios">Comentarios</a>
      <a href="/PI3/coffeeShop/acercade/acercade.php" data-translate="Acerca de ">Acerca de</a>
    </nav>

      <div class="theme-switch-container">
        <input type="checkbox" id="theme-toggle" class="theme-toggle-checkbox">
        <label for="theme-toggle" class="theme-toggle-label"></label>
      </div>

    <!-- ACCIONES -->
    <div class="footer-actions">
      <a
        href="<?php echo $usuarioLogueado
          ? '/PI3/coffeeShop/perfil/perfil_usuario.php'
          : '/PI3/General/login.php'; ?>"
        class="icon-btn"
        aria-label="Cuenta"
        title="<?php echo $usuarioLogueado ? 'Mi perfil' : 'Iniciar sesión'; ?>"
        style="display:flex;align-items:center;gap:6px;"
      >
        👤
        <?php if ($usuarioLogueado): ?>
          <span style="font-size:.7rem;color:#4CAF50;font-weight:600;line-height:1;">
            sesión
          </span>
        <?php endif; ?>
      </a>

      <!-- carrito -->
      <a href="#" id="open-cart" class="icon-btn" aria-label="Carrito" title="Carrito">
        🛒 <span></span>
      </a>

      <div class="lang-switch">
        <img src="../../Images/es_flag.png" id="btn-es" class="lang-flag active" alt="Español" title="Español">
        <img src="../../Images/uk_flag.png" id="btn-en" class="lang-flag" alt="English" title="English">
      </div>
      <style>
        .lang-switch {
  display: flex;
  align-items: center;
  gap: 8px;
}

.lang-flag {
  width: 28px;
  height: 18px;
  cursor: pointer;
  opacity: 0.6;
  transition: transform 0.2s ease, opacity 0.3s ease;
  border-radius: 3px;
}

.lang-flag:hover {
  opacity: 1;
  transform: scale(1.1);
}

.lang-flag.active {
  opacity: 1;
  box-shadow: 0 0 6px rgba(133, 73, 5, 0.8);
}
/* FOOTER */
.site-footer {
  background-color: #DCC0B9;
  padding: 20px 40px;
  font-family: Arial, sans-serif;
  position: relative;
}


/* Línea inferior recortada */
.site-footer::after {
  content: "";
  position: absolute;
  bottom: 30px;
  left: 100px;  
  right: 50px;  
  height: 1px;
  background: #531607;
  border-radius: 2px;
}


.footer-container {
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
}

/* LOGO */
.footer-logo {
  display: flex;
  align-items: center;
  gap: 8px;
  font-weight: bold;
  color: #531607;
  margin-left: -40px;
}

.footer-logo img {
  width: 100px;
  height: auto;
}

/* MENÚ */
.footer-menu {
  display: flex;
  gap: 60px;
}

/* MENÚ */
.footer-menu a {
  text-decoration: none;
  font-size: 18px;
  font-weight: 400;
  color: #531607;             
  transition: all 0.3s ease;
  padding: 8px 14px;
  border-radius: 4px;
  border: 2px solid transparent; 
}

/* Al pasar el mouse */
.footer-menu a:hover {
  border-color: #531607;       
  color: #531607;             
  background-color: transparent; 
}

/* Al hacer clic */
.footer-menu a:active {
  border-color: #8a4b2c;     
  color: #8a4b2c;            
  background-color: transparent;
}

/* ACCIONES */
.footer-actions {
  display: flex;
  align-items: center;
  gap: 25px;
  font-weight: 400;
}

.footer-actions .icon-btn {
  font-size: 18px;
  text-decoration: none;
  color: #3b2a25;
}

.footer-actions .icon-btn:hover {
  color: #8a4b2c;
}

.footer-actions .lang {
  font-size: 14px;
  color: #531607;
}

/* COPYRIGHT */
.footer-copy {
  text-align: center;
  margin-top: 15px;
  font-size: 12px;
  color: #555;
}
      </style>

    </div>
  </div>
</footer>