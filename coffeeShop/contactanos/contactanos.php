<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$usuarioLogueado = !empty($_SESSION['logueado']) && $_SESSION['logueado'] === true;

if ($usuarioLogueado) {
    $userid   = $_SESSION['userid']        ?? null;
    $username = $_SESSION['username']      ?? 'Usuario';
    $email    = $_SESSION['email']         ?? '';
    $avatar   = $_SESSION['profilescreen'] ?? null;
} else {
    $userid = $username = $email = $avatar = null;
}
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Coffee-Shop | Contáctanos</title>
    <link rel="stylesheet" href="../inicio/Style.css" />
    <link rel="stylesheet" href="../general.css" />
    <link rel="stylesheet" href="contactanos.css" />
    <link rel="icon" href="../../Images/logotipocafes.png" />
  </head>

  <body class="coffee">
    <?php include "../nav_bar.php"; ?>

    <section class="contacto-wrap">
      <div class="contacto-container">
        <h2 class="contacto-title" data-translate="Contáctanos">Contáctanos</h2>

        <div class="contacto-card">
          <!-- Lado izquierdo -->
          <aside class="contacto-info">
            <ul class="info-list">
              <li class="info-item">
                <span class="info-icon">
                  <svg viewBox="0 0 24 24">
                    <path
                      d="M20 4H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2Zm0 4-8 5L4 8V6l8 5 8-5Z"
                    />
                  </svg>
                </span>
                <div>
                  <p class="info-ttl">Nuestro Email</p>
                  <p class="info-txt">coffeeshopPI3E@gmail.com</p>
                </div>
              </li>

              <li class="info-item">
                <span class="info-icon">
                  <svg viewBox="0 0 24 24">
                    <path
                      d="M6.6 10.79a15.054 15.054 0 0 0 6.61 6.61l2.2-2.2a1 1 0 0 1 1.02-.24 11.4 11.4 0 0 0 3.58.57 1 1 0 0 1 1 1V20a1 1 0 0 1-1 1C11.3 21 3 12.7 3 2a1 1 0 0 1 1-1h3.47a1 1 0 0 1 1 1c0 1.26.2 2.46.57 3.58a1 1 0 0 1-.25 1.02l-2.19 2.19Z"
                    />
                  </svg>
                </span>
                <div>
                  <p class="info-ttl">Teléfono</p>
                  <p class="info-txt">3141495067</p>
                </div>
              </li>

              <li class="info-item">
                <span class="info-icon">
                  <svg viewBox="0 0 24 24">
                    <path
                      d="M12 2a7 7 0 0 0-7 7c0 5.25 7 13 7 13s7-7.75 7-13a7 7 0 0 0-7-7Zm0 9.5A2.5 2.5 0 1 1 12 6a2.5 2.5 0 0 1 0 5.5Z"
                    />
                  </svg>
                </span>
                <div>
                  <p class="info-ttl">Ubicación</p>
                  <p class="info-txt">
                    Manzanillo, Colima. Universidad de Colima
                  </p>
                </div>
              </li>
            </ul>

            <div class="social">
              <p class="social-ttl">Síguenos en Redes Sociales</p>
              <div class="social-row">
                <a href="#" class="social-btn"><i>f</i></a>
                <a href="#" class="social-btn"><i>▣</i></a>
                <a href="#" class="social-btn"><i>x</i></a>
                <a href="#" class="social-btn"><i>♪</i></a>
              </div>
            </div>
          </aside>

          <!-- FORM -->
          <form
            class="contacto-form"
            action="contactanos/procesar_contacto.php"
            method="post"
          >
            <h3 class="form-ttl"></h3>

            <div class="form-grid">
              <label class="fld">
                <span class="lbl">Nombre</span>
                <input type="text" name="nombre" placeholder="Tu nombre..." required />
              </label>

              <label class="fld">
                <span class="lbl">Apellido</span>
                <input type="text" name="apellido" placeholder="Tu apellido" />
              </label>

              <label class="fld fld-span2">
                <span class="lbl">Email</span>
                <input type="email" name="email" placeholder="Tu email...." required />
              </label>

              <label class="fld fld-span2">
                <span class="lbl">Mensaje</span>
                <textarea name="mensaje" rows="6" placeholder="Escribenos un mensaje..."></textarea>
              </label>
            </div>

            <button class="btn-grad" type="submit">Enviar Mensaje</button>
          </form>
        </div>
      </div>
    </section>

    <?php include "../footer.php"; ?>

    <!-- Mini carrito y scripts -->
    <div class="mc-overlay" id="mcOverlay" hidden></div>
    <aside class="mini-cart" id="miniCart" role="dialog">
      <header class="mc-header">
        <h3 id="mcTitle">Tu carrito</h3>
        <button class="mc-close" id="mcClose">✕</button>
      </header>
      <div class="mc-body">
        <ul class="mc-list" id="mcList"></ul>
        <div class="mc-empty" id="mcEmpty">Tu carrito está vacío.</div>
      </div>
      <footer class="mc-footer">
        <div class="mc-total">
          <span>Total</span>
          <strong id="mcTotal">$0.00 MXN</strong>
        </div>
        <a href="../catalogo/carrito.php" class="mc-btn">Ir a pagar</a>
      </footer>
    </aside>

    <script>
      window.CART_API_URL = "../catalogo/cart_api.php";
    </script>
    <script src="../catalogo/app.js"></script>
    <script src="../../translate.js"></script>
  </body>
</html>
