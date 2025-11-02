<?php
// Inicia sesión si no existe (importante para detectar si el usuario ya inició)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificamos si hay sesión activa
$usuarioLogueado = !empty($_SESSION['logueado']) && $_SESSION['logueado'] === true;

// Si hay sesión, podemos leer algunos datos
if ($usuarioLogueado) {
    $userid   = $_SESSION['userid']        ?? null;
    $username = $_SESSION['username']      ?? 'Usuario';
    $email    = $_SESSION['email']         ?? '';
    $avatar   = $_SESSION['profilescreen'] ?? null;
} else {
    // Si no hay sesión, inicializamos vacíos para evitar errores
    $userid = $username = $email = $avatar = null;
}
?>


<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Coffee-Shop</title>
    <link rel="stylesheet" href="../inicio/Style.css" />
    <link rel="icon" href="../../images/logocafe.png" />
    <link rel="stylesheet" href="catalogo.css" />

    <style>
      .hotdrinks__search{
        display:flex; align-items:center; gap:8px; flex-wrap:wrap;
      }
      .hotdrinks__search select{
        padding: 6px 10px; border-radius: 8px; border: 1px solid #c9b2a7; outline: none;
      }
    </style>
  </head>
  <body>
    <footer class="site-footer">
      <div class="footer-container">
        <!-- LOGO -->
        <div class="footer-logo">
          <a href="index.php">
            <img src="../../images/logocafe.png" alt="Coffee Shop logo" />
          </a>
          <span>COFFEE SHOP</span>
        </div>

        <!-- MENÚ -->
        <nav class="footer-menu">
          <a href="../inicio/index.php">Inicio</a>
          <a href="bebidas_frias.php">Catálogo</a>
          <a href="../comentarios/comentarios.php">Comentarios</a>
          <a href="../acercade/acercade.php">Acerca de</a>
        </nav>

        <!-- ACCIONES -->
        <div class="footer-actions">
          <a
  href="<?php echo $usuarioLogueado
    ? '/PI3/coffeeShop/perfil/perfil_usuario.php'
    : '/PI3/General/login.php'; ?>"
  class="icon-btn"
  aria-label="Cuenta"
  title="<?php echo $usuarioLogueado ? 'Mi perfil' : 'Iniciar sesión'; ?>">
  👤
</a>

          <!-- Carrito con contador (IMPORTANTE: id en el botón y en el badge) -->
          <a href="#" id="open-cart" class="icon-btn" aria-label="Carrito" title="Carrito">
  🛒 <span></span>
</a>
          <span class="lang">ESP | ING</span>
        </div>
      </div>
    </footer>

    <!-- ========== CATALOGO (cabecera) ========== -->
    <section class="catalogo" aria-labelledby="catalogo-title">
      <div class="catalogo__wrap">
        <h2 id="catalogo-title">Catálogo</h2>

        <div class="catalogo__grid">
          <a class="item" href="catalogo.php">
            <img src="../../images/icon_bebidas_calientes.png" alt="Bebidas calientes">
            <span>Bebidas calientes</span>
          </a>

          <a class="item" href="bebidas_frias.php">
            <img src="../../images/icon_bebidas_ffrias.png" alt="Bebidas frías">
            <span>Bebidas frías</span>
          </a>

          <a class="item" href="paninis(catalogo).php">
            <img src="../../images/seccion_paninis.png" alt="Paninis">
            <span>Paninis</span>
          </a>

          <a class="item" href="postres(catalogo).php">
            <img src="../../images/seecion_postres.png" alt="Postres">
            <span>Postres</span>
          </a>

          <a class="item" href="productos(catalogo).php">
            <img src="../../images/ensalada_seccion.png" alt="Ensaladas">
            <span>Ensaladas</span>
          </a>
        </div>

        <div class="catalogo__divider">
          <span class="line"></span>
          <img src="../../images/iconcofe2.png" alt="" aria-hidden="true">
          <span class="line"></span>
        </div>
      </div>
    </section>
    <link rel="stylesheet" href="catalogo.css">

    <!-- ========== BEBIDAS FRÍAS (dinámico) ========== -->
    <section class="hotdrinks" aria-labelledby="hotdrinks-title">
      <div class="hotdrinks__wrap">
        <h2 id="hotdrinks-title">Bebidas Frías</h2>

     <!-- === BUSCADOR + BOTÓN FILTRAR CON MENÚ FLOTANTE === -->
<div class="hotdrinks__search" id="filtro-wrap" style="position:relative;">
  
  <!-- Tu input (decorativo por ahora) -->
  <input type="text" placeholder="Ingresa nombre de bebida o snack" />

  <!-- Tu mismo botón (mismos estilos inline que ya usas) -->
  <button id="btn-filtrar" class="icon" aria-label="Filtrar" type="button"
    style="background:#7a4b34; color:#fff; border-radius:8px; padding:6px 14px;
    border:none; font-weight:bold; font-size:15px; margin-left:8px; display:flex;
    align-items:center; gap:6px; cursor:pointer; margin-right:-20px;">
    <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true">
      <path fill="currentColor"
        d="M3 5h18v2H3V5zm4 6h10v2H7v-2zm-2 6h14v2H5v-2z"/>
    </svg>
    <span>Filtrar</span>
  </button>

  <!-- Menú flotante (mismo look & feel de tu paleta) -->
  <div id="menu-filtrar" style="
    display:none; position:absolute; right:0; top:48px; /* se alinea al botón */
    background:#fff; border:2px solid #7a4b34; border-radius:10px;
    box-shadow:0 6px 16px rgba(0,0,0,0.15); overflow:hidden; z-index:1000;
    min-width:220px;">
    <a href="catalogo.php"            class="f-item">☕ Bebidas calientes</a>
    <a href="bebidas_frias.php"       class="f-item">🧊 Bebidas frías</a>
    <a href="paninis.php"             class="f-item">🥪 Paninis</a>
    <a href="postres(catalogo).php"   class="f-item">🍰 Postres</a>
    <a href="productos(catalogo).php" class="f-item">🥗 Ensaladas</a>
  </div>
</div>
<!-- GRID donde se insertan las tarjetas -->
<div class="hotdrinks__grid" data-autoload="ajax" data-categoria="2">
  <p style="grid-column:1/-1; text-align:center; opacity:.7; padding:16px;">
    Cargando productos…
  </p>
</div>


<!-- Estilos mínimos, súper específicos para no tocar tu theme -->
<style>
  #menu-filtrar .f-item{
    display:block; padding:10px 14px; color:#7a4b34; text-decoration:none;
    font-weight:600;
  }
  #menu-filtrar .f-item:hover{ background:#f2e1d0; }
  /* Si tu input y botón están dentro de un contenedor redondeado,
     este margen ayuda a que el menú no se “pegue” visualmente */
  #filtro-wrap{ gap:8px; }
</style>


        <div class="hotdrinks__divider">
          <span class="line"></span>
          <img src="../../images/icon_bebidas_calientes.png" alt="" aria-hidden="true" />
          <span class="line"></span>
        </div>
      </div>
    </section>

    <!-- ===================== FOOTER ===================== -->
    <footer class="cs-footer" aria-labelledby="footer-title">
      <h2 id="footer-title" class="sr-only">Información del sitio</h2>

      <div class="cs-footer__wrap">
        <!-- Marca -->
        <aside class="cs-brand">
          <img class="cs-brand__logo" src="../../images/logocafe.png" alt="Coffee Shop">
        </aside>

        <!-- Tarjetas -->
        <div class="cs-cards">
          <!-- Newsletter -->
          <section class="cs-card">
            <h3>News & updates</h3>
            <form class="cs-news" action="#" method="post">
              <label class="sr-only" for="newsletter">Correo electrónico</label>
              <input id="newsletter" type="email" placeholder="correo electrónico" required>
              <button type="submit" class="cs-btn">Suscribir</button>
            </form>
          </section>

          <!-- Contacto -->
          <section class="cs-card">
            <h3>Contáctanos</h3>
            <ul class="cs-list">
              <li>
                <span class="cs-ico" aria-hidden="true">
                  <svg viewBox="0 0 24 24"><path d="M20 4H4a2 2 0 0 0-2 2v12a2
                  2 0 0 0 2 2h16a2 2 0 0 0 2-2V6a2 2
                  0 0 0-2-2Zm0 4-8 5L4 8V6l8 5 8-5Z" fill="currentColor"/></svg>
                </span>
                <a href="mailto:coffee_shop@gmail.com">coffee_shop@gmail.com</a>
              </li>
              <li>
                <span class="cs-ico" aria-hidden="true">
                  <svg viewBox="0 0 24 24"><path d="M6.6 10.8a15.1 15.1 0 0 0 6.6 6.6l2.2-2.2a1.5 1.5 0 0 1 1.6-.36 12.3 12.3 0 0 0 3.8.6 1.5 1.5 0 0 1 1.5 1.5V20a1.5 1.5 0 0 1-1.5 1.5A18.5 18.5 0 0 1 3 7.5 1.5 1.5 0 0 1 4.5 6H7a1.5 1.5 0 0 1 1.5 1.5c0 1.3.2 2.6.6 3.8a1.5 1.5 0 0 1-.36 1.6Z" fill="currentColor"/></svg>
                </span>
                <a href="tel:+523141495067">+52 314 149 5067</a>
              </li>
              <li>
                <span class="cs-ico" aria-hidden="true">
                  <svg viewBox="0 0 24 24"><path d="M12 2a7 7 0 0 0-7 7c0 5.25 7 13 7 13s7-7.75 7-13a7 7 0 0 0-7-7Zm0 9.5A2.5 2.5 0 1 1 12 6a2.5 2.5 0 0 1 0 5Z" fill="currentColor"/></svg>
                </span>
                <span>Manzanillo, Col. • Campus Naranjo</span>
              </li>
            </ul>
          </section>

          <!-- Enlaces -->
          <nav class="cs-card" aria-label="Conoce más">
            <h3>Conoce más</h3>
            <ul class="cs-links">
              <li><a href="../inicio/index.php"><span class="chev" aria-hidden="true">›</span> Inicio</a></li>
              <li><a href="bebidas_frias.php"><span class="chev" aria-hidden="true">›</span> Catálogo</a></li>
              <li><a href="../comentarios/comentarios.php"><span class="chev" aria-hidden="true">›</span> Comentarios</a></li>
              <li><a href="../acercade/acercade.php"><span class="chev" aria-hidden="true">›</span> Acerca de</a></li>
            </ul>
          </nav>

          <!-- Redes + Horarios -->
          <section class="cs-card">
            <h3>Síguenos</h3>
            <div class="cs-social">
              <a href="https://facebook.com" aria-label="Facebook" class="circle">
                <svg viewBox="0 0 24 24"><path d="M13 22v-9h3l1-4h-4V7a1 1 0 0 1 1-1h3V2h-3a5 5 0 0 0-5 5v2H6v4h3v9h4Z" fill="currentColor"/></svg>
              </a>
              <a href="https://instagram.com" aria-label="Instagram" class="circle">
                <svg viewBox="0 0 24 24"><path d="M7 2h10a5 5 0 0 1 5 5v10a5 5 0 0 1-5 5H7a5 5 0 0 1-5-5V7a5 5 0 0 1 5-5Zm5 5a5 5 0 1 0 0 10 5 5 0 0 0 0-10Zm6.5-.9a1.1 1.1 0 1 0 0 2.2 1.1 1.1 0 0 0 0-2.2Z" fill="currentColor"/></svg>
              </a>
            </div>

            <div class="cs-hours">
              <h4>Horarios</h4>
              <p>Lun–Vier: 9:00–21:00</p>
              <p>Sab–Dom: 10:00–20:00</p>
            </div>
          </section>
        </div>

        <!-- === OVERLAY & DRAWER MINI-CARRITO === -->
<div class="mc-overlay" id="mcOverlay" hidden></div>

<aside class="mini-cart" id="miniCart" aria-hidden="true" aria-labelledby="mcTitle" role="dialog">
  <header class="mc-header">
    <h3 id="mcTitle">Tu carrito</h3>
    <button class="mc-close" id="mcClose" aria-label="Cerrar carrito">✕</button>
  </header>

  <div class="mc-body">
    <ul class="mc-list" id="mcList"><!-- items por JS --></ul>
    <div class="mc-empty" id="mcEmpty">Tu carrito está vacío.</div>
  </div>

  <footer class="mc-footer">
    <div class="mc-total">
      <span>Total</span>
      <strong id="mcTotal">$0.00 MXN</strong>
    </div>
    <a href="carrito.php" class="mc-btn">Ir a pagar</a>
  </footer>
</aside>
        <!-- Línea inferior -->
        <div class="cs-bottom">
          <span class="cs-line"></span>
          <span class="cs-bean" aria-hidden="true">
            <img src="../../images/iconcofe.png" alt="icono café" style="width:32px; height:32px; object-fit:contain;" />
          </span>
          <span class="cs-line"></span>
        </div>

        <div class="cs-legal"></div>
      </div>
    </footer>


    <script src="app.js"></script>
  </body>
</html>
