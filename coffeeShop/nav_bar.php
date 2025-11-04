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
      <a href="/PI3/coffeeShop/inicio/index.php">Inicio</a>
      <a href="/PI3/coffeeShop/catalogo/catalogo.php">Catálogo</a>
      <a href="/PI3/coffeeShop/comentarios/comentarios.php">Comentarios</a>
      <a href="/PI3/coffeeShop/acercade/acercade.php">Acerca de</a>
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

      <span class="lang">ESP | ING</span>
    </div>
  </div>
</footer>