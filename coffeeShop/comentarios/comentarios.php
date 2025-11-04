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
    <title>Blackwood Coffee - Comentarios</title>
    
    <link rel="stylesheet" href="Style.css" /> 
    <link rel="stylesheet" href="comentarios.css" />
    <link rel="icon" href="/images/logo.png" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="../general.css" rel="stylesheet"/>
  </head>
  
  <body>
    <script src="../../theme-toggle.js" defer></script>
    <footer class="site-footer">
      <div class="footer-container">
        <!-- LOGO -->
        <div class="footer-logo">
          <a href="index.php">
            <img src="../../images/logo.png" alt="Blackwood Coffee logo" />
          </a>
          <span>BLACKWOOD COFFEE</span>
        </div>

        <!-- MENÚ -->
        <nav class="footer-menu">
          <a href="/PI3/coffeeShop/inicio/index.php">Inicio</a>
          <a href="/PI3/coffeeShop/catalogo/catalogo.php">Catálogo</a>
          <a href="/PI3/coffeeShop/comentarios/comentarios.php">Comentarios</a>
          <a href="/PI3/coffeeShop/acercade/acercade.php">Acerca de</a>

          <div class="theme-switch-container">
            <input type="checkbox" id="theme-toggle" class="theme-toggle-checkbox">
            <label for="theme-toggle" class="theme-toggle-label"></label>
        </div>

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

    <main class="panel-comentarios">
      
      <section class="panel" id="panel-formulario">
        <h2>¡Déjanos tu opinión!</h2>
        
        <form id="form-comentario" class="form-comentario-nuevo" data-username="<?php echo htmlspecialchars($nombre_usuario_logueado); ?>" enctype="multipart/form-data">
            
            <div class="form-grupo-rating-central">
                <label>Calificación de nuestros servicios:</label>
                <div class="rating-estrellas">
                    <input type="radio" id="estrella5" name="calificacion" value="5" required><label for="estrella5" title="5 estrellas">★</label>
                    <input type="radio" id="estrella4" name="calificacion" value="4"><label for="estrella4" title="4 estrellas">★</label>
                    <input type="radio" id="estrella3" name="calificacion" value="3"><label for="estrella3" title="3 estrellas">★</label>
                    <input type="radio" id="estrella2" name="calificacion" value="2"><label for="estrella2" title="2 estrellas">★</label>
                    <input type="radio" id="estrella1" name="calificacion" value="1"><label for="estrella1" title="1 estrella">★</label>
                </div>
            </div>

            <div class="form-grupo-grande">
                <textarea id="comentario" name="comentario" rows="5" placeholder="Escribe aquí tu comentario..." required></textarea>
            </div>

            <div class="form-fila-media">
                <div class="form-grupo-upload">
                    <label for="imagen" class="btn-upload">
                        <i class="fas fa-camera"></i> Subir Foto
                    </label>
                    <input type="file" id="imagen" name="imagen" accept="image/png, image/jpeg">
                    <span id="file-name" class="file-name-display">Ningún archivo</span>
                </div>

                <div class="form-grupo-tags">
                    <label for="etiquetas" class="label-listbox">Selecciona etiquetas:</label>
                    <select id="etiquetas" name="etiquetas[]" multiple>
                        <option value="café">Café</option>
                        <option value="postre">Postre</option>
                        <option value="comida">Comida</option>
                        <option value="servicio">Servicio</option>
                        <option value="ambiente">Ambiente</option>
                        <option value="bueno">Bueno</option>
                        <option value="malo">Malo</option>
                    </select>
                </div>
                </div>

            <div class="form-fila-botones">
                <button type="reset" class="btn-cancelar">Borrar</button>
                <button type="submit" class="btn-enviar">Subir</button>
            </div>
            
            <p id="form-mensaje"></p> 
            
          </form> </section> <section class="panel-resena-general">
          <div class="resena-container">
              <div class="resena-promedio">
                  <div class="promedio-numero" id="promedio-numero-display">0.0</div>
                  <div class="promedio-estrellas" id="promedio-estrellas-display"></div>
                  <div class="promedio-total" id="promedio-total-display">(0 reseñas)</div>
              </div>
              <div class="resena-desglose" id="desglose-barras-container"></div>
            </div>
        </section> 
        <div class="coffee-separator"></div>


      <section class="reviews" aria-labelledby="reviews-title">
        <div class="reviews__wrap">
          <header class="reviews__header">
            <h2 id="reviews-title">Comentarios</h2>
            <div class="reviews__controls">
              <div class="pill-group">
                <button class="pill pill--ghost">Más recientes</button>
                <button class="pill pill--ghost">Con foto</button>
              </div>
            </div>
          </header>

          <div class="reviews__subhead">
            <span class="score-pill" id="review-score-pill">0.0</span> <div class="select-pill" role="listbox" aria-label="Clasificación">
              <span>Clasificación</span>
              <svg width="16" height="16" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M7 10l5 5 5-5z" /></svg>
            </div>
          </div>

          <div class="reviews__grid" id="reviews-grid-container">
            <p>Cargando comentarios...</p>
          </div>
          
        </div>
      </section>
      </main> 
          <!-- ===================== FOOTER ===================== -->
<footer class="cs-footer" aria-labelledby="footer-title">
  <h2 id="footer-title" class="sr-only">Información del sitio</h2>

  <div class="cs-footer__wrap">
    <!-- Marca -->
    <aside class="cs-brand">
      <img class="cs-brand__logo" src="../../Images/logo.png" alt="Blackwood Coffee">
    </aside>

    <!-- Tarjetas -->
    <div class="cs-cards">
      <!-- Newsletter / Pago -->
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
              <!-- mail -->
              <svg viewBox="0 0 24 24"><path d="M20 4H4a2 2 0 0 0-2 2v12a2
              2 0 0 0 2 2h16a2 2 0 0 0 2-2V6a2 2
              0 0 0-2-2Zm0 4-8 5L4 8V6l8 5 8-5Z" fill="currentColor"/></svg>
            </span>
            <a href="mailto:coffee_shop@gmail.com">coffee_shop@gmail.com</a>
          </li>
          <li>
            <span class="cs-ico" aria-hidden="true">
              <!-- phone -->
              <svg viewBox="0 0 24 24"><path d="M6.6 10.8a15.1 15.1 0 0 0 6.6 6.6l2.2-2.2a1.5 1.5 0 0 1 1.6-.36 12.3 12.3 0 0 0 3.8.6 1.5 1.5 0 0 1 1.5 1.5V20a1.5 1.5 0 0 1-1.5 1.5A18.5 18.5 0 0 1 3 7.5 1.5 1.5 0 0 1 4.5 6H7a1.5 1.5 0 0 1 1.5 1.5c0 1.3.2 2.6.6 3.8a1.5 1.5 0 0 1-.36 1.6Z" fill="currentColor"/></svg>
            </span>
            <a href="tel:+523141495067">+52 314 149 5067</a>
          </li>
          <li>
            <span class="cs-ico" aria-hidden="true">
              <!-- pin -->
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
          <li><a href="index.php"><span class="chev" aria-hidden="true">›</span> Inicio</a></li>
          <li><a href="../catalogo/bebidas_frias.php"><span class="chev" aria-hidden="true">›</span> Catálogo</a></li>
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

    <!-- Línea inferior -->
    <div class="cs-bottom">
      <span class="cs-line"></span>
      <span class="cs-bean" aria-hidden="true">
        <img src="../../images/iconcofe.png" alt="icono café" style="width:32px; height:32px; object-fit:contain;" />
      </span>
      <span class="cs-line"></span>
    </div>

    <script src="comentarios.js" defer></script>

  </body>
</html>
