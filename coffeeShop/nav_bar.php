<script src="../../theme-toggle.js" defer></script>
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
      </style>

    </div>
  </div>
</footer>