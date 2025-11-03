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
    <?php include "../nav_bar.php"; ?>

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

    <?php include "../footer.php"; ?>
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
        <div class="cs-legal"></div>
      </div>
    </footer>


    <script src="app.js"></script>
  </body>
</html>
