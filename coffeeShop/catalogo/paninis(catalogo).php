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
    <title>Coffee-Shop • Paninis</title>
    <link rel="stylesheet" href="../inicio/Style.css" />
    <link rel="icon" href="../../images/logotipocafes.png" />
    <link rel="stylesheet" href="catalogo.css" />
  </head>
  <body>
    <!-- Top bar -->
<?php include "../nav_bar.php"; ?>

    <!-- Cabecera catálogo -->
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

    <!-- PANINIS (dinámico) -->
    <section class="hotdrinks" aria-labelledby="paninis-title">
      <div class="hotdrinks__wrap">
        <h2 id="paninis-title">Paninis</h2>

        <!-- === BUSCADOR + BOTÓN FILTRAR CON MENÚ FLOTANTE === -->
        <div class="hotdrinks__search" id="filtro-wrap" style="position:relative;">
          <input type="text" placeholder="Ingresa nombre de bebida o snack" />
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

          <!-- Menú flotante -->
          <div id="menu-filtrar" style="
            display:none; position:absolute; right:0; top:48px;
            background:#fff; border:2px solid #7a4b34; border-radius:10px;
            box-shadow:0 6px 16px rgba(0,0,0,0.15); overflow:hidden; z-index:1000;
            min-width:220px;">
            <a href="catalogo.php"            class="f-item">☕ Bebidas calientes</a>
            <a href="bebidas_frias.php"       class="f-item">🧊 Bebidas frías</a>
            <a href="paninis(catalogo).php"   class="f-item">🥪 Paninis</a>
            <a href="postres.php"             class="f-item">🍰 Postres</a>
            <a href="productos(catalogo).php" class="f-item">🥗 Ensaladas</a>
          </div>
        </div>

<!-- GRID DINÁMICA: usa ID de categoría (4 = Comida). Cambia si usas otra. -->
      <div class="hotdrinks__grid" data-autoload="ajax" data-categoria="4">
        <p style="grid-column:1/-1; text-align:center; opacity:.7; padding:16px;">
          Cargando productos…
        </p>
      </div>


        <!-- Contenedor de tarjetas -->
        <div class="hotdrinks__grid" id="grid-paninis"></div>

        <div class="hotdrinks__divider">
          <span class="line"></span>
          <img src="../../images/icon_bebidas_calientes.png" alt="" aria-hidden="true" />
          <span class="line"></span>
        </div>
      </div>
    </section>

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

   <?php include "../footer.php"; ?>
    <!-- ===== JS: menú filtrar + carga Paninis desde MySQL ===== -->
    <script>
      // Menú Filtrar
      (function(){
        const btn  = document.getElementById('btn-filtrar');
        const menu = document.getElementById('menu-filtrar');
        const wrap = document.getElementById('filtro-wrap');
        if(!btn || !menu || !wrap) return;

        function toggleMenu(){ menu.style.display = (menu.style.display === 'block') ? 'none' : 'block'; }
        function closeMenu(){ menu.style.display = 'none'; }

        btn.addEventListener('click', (e) => { e.stopPropagation(); toggleMenu(); });
        document.addEventListener('click', (e) => { if (!wrap.contains(e.target)) closeMenu(); });
        document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeMenu(); });

        // Hover visual
        menu.querySelectorAll('.f-item').forEach(a => {
          a.addEventListener('mouseenter', () => a.style.background = '#f2e1d0');
          a.addEventListener('mouseleave', () => a.style.background = '');
        });
      })();

      // Carga dinámica de Paninis
      const grid = document.getElementById('grid-paninis');

      function renderCard(p) {
        const sku  = (p.id ?? p.id_producto ?? '').toString();
        const cant = Number(p.cantidad_producto ?? p.cantidadProducto ?? 0);
        const disponible = cant > 0;
        const precioNum = Number(p.precio_producto ?? 0);
        const precio = isNaN(precioNum) ? '0.00' : precioNum.toFixed(2);
        const foto = (p.foto_producto && p.foto_producto.trim()) ? p.foto_producto : 'assest/placeholder.png';

        return `
          <article class="ts-card" data-id="${sku}">
            <div class="ts-stage">
              <img src="${foto}"
                   alt="${p.nombre_producto}"
                   onerror="this.onerror=null; this.src='assest/placeholder.png';" />
              <div class="ts-rate"><strong>4.6</strong> ★</div>
            </div>
            <h4 class="ts-name">${p.nombre_producto}
              <small style="opacity:.7">• SKU ${sku || '—'}</small>
            </h4>
            <p class="ts-desc">${p.descripcion_producto || ''}</p>
            <div class="ts-info">
              <span>${disponible ? 'Disponible' : 'Agotado'}</span>
              <span class="ts-price">$${precio} MXN</span>
              <button class="ts-cart" ${disponible ? '' : 'disabled'}>🛒</button>
            </div>
          </article>
        `;
      }

      async function cargarPaninis() {
        try {
          const res  = await fetch('get_productos.php?categoria=paninis', { cache: 'no-store' });
          const data = await res.json();
          if (!data.ok) throw new Error(data.error || 'Error al cargar');
          grid.innerHTML = data.items.map(renderCard).join('') || `
            <p style="grid-column:1/-1; text-align:center; padding:16px;">
              No hay productos en Paninis.
            </p>`;
        } catch (e) {
          console.error(e);
          grid.innerHTML = `<p style="grid-column:1/-1; color:#b00020;">Ocurrió un error al cargar los productos.</p>`;
        }
      }

      cargarPaninis();
    </script>
        <script src="app.js"></script>
  </body>
</html>
