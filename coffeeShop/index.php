<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Coffee-Shop</title>
    <link rel="stylesheet" href="Style.css" />
    <link rel="icon" href="assest/logocafe.png" />
    <link rel="icon" href="assest/logotipocafes.png" />
    <link
      href="https://fonts.googleapis.com/css2?family=ADLaM+Display&display=swap"
      rel="stylesheet"
    />
    <link
      href="https://fonts.googleapis.com/css2?family=ADLaM+Display&family=Montaga&display=swap"
      rel="stylesheet"
    />
  </head>
  <body>
    <footer class="site-footer">
      <div class="footer-container">
        <!-- LOGO -->
        <div class="footer-logo">
          <a href="index.php">
            <img src="assest/logocafe.png" alt="Coffee Shop logo" />
          </a>
          <span>COFFEE SHOP</span>
        </div>

        <!-- MENÚ -->
        <nav class="footer-menu">
          <a href="index.php">Inicio</a>
          <a href="catalogo.php">Catálogo</a>
          <a href="comentarios.php">Comentarios</a>
          <a href="acercade.php">Acerca de</a>
        </nav>

        <!-- ACCIONES -->
        <div class="footer-actions">
          <a href="#" class="icon-btn" aria-label="Cuenta">👤</a>
          <a href="#" class="icon-btn" aria-label="Carrito">🛒</a>
          <span class="lang">ESP | ING</span>
        </div>
      </div>
    </footer>
    <!--Primera parte(sabor que inspira)-->
    <section class="hero-sabor">
      <div class="hero-wrap">
        <div class="hero-texto">
          <h2>SABOR QUE INSPIRA</h2>
          <p>
            En nuestra cafetería hacemos de cada momento algo especial. No solo
            servimos café de calidad, también tenemos una variedad de postres
            para acompañar tus días. Un espacio tranquilo, acogedor y lleno de
            sabor, pensado para que disfrutes a tu manera.
          </p>
          <div class="hero-icon-bg">
            <img src="assest/tazaicon.png" alt="Icono decorativo" />
          </div>
        </div>

        <div class="hero-imagen">
          <img
            src="assest/tazatirandocafe.png"
            alt="Taza de café con granos"
          />
        </div>
      </div>
    </section>
<!-- Decoración de iconos y líneas arriba de envíos -->
    <!--segunda parte(mas vendidos)-->
    <section class="ts-section">
      <div class="ts-title-line">
        <span class="ts-line"></span>
        <span class="ts-title-text">Más Vendidos</span>
        <span class="ts-line"></span>
      </div>
      <!-- Iconos sueltos en el lienzo de Más Vendidos -->
      <img src="assest/iconcofe.png" alt="Icono café" style="width:40px; margin-right:12px;" />
      <img src="assest/iconcofe2.png" alt="Icono café 2" style="width:40px;" />

      <div class="ts-grid">
        <!-- Tarjeta 1 -->
        <article class="ts-card">
          <div class="ts-stage">
            <img src="assest/drinks/Frappé clasico.png" alt="Frappe Clásico" />
            <div class="ts-rate"><strong>4.9</strong> ★</div>
          </div>
          <h4 class="ts-name">Frappe Clásico</h4>
          <p class="ts-desc">20% Expresso<br />40% Milk</p>
          <div class="ts-info">
            <span>20 OZ.</span>
            <span class="ts-price">$20.00</span>
            <button class="ts-cart">🛒</button>
          </div>
        </article>

        <!-- Tarjeta 2 -->
        <article class="ts-card">
          <div class="ts-stage">
            <img
              src="assest/food/Panini pavo y queso.png"
              alt="Panini Pavo y Queso"
            />
            <div class="ts-rate"><strong>4.8</strong> ★</div>
          </div>
          <h4 class="ts-name">Panini Pavo y Queso</h4>
          <p class="ts-desc">
            Contiene: pan tostado, pavo, queso, lechuga, jitomate, cebolla
          </p>
          <div class="ts-info">
            <span>120 Gr.</span>
            <span class="ts-price">$20.00</span>
            <button class="ts-cart">🛒</button>
          </div>
        </article>

        <!-- Tarjeta 3 -->
        <article class="ts-card">
          <div class="ts-stage">
            <img src="assest/drinks/Frappé caramel.png" alt="Frappe Caramel" />
            <div class="ts-rate"><strong>4.7</strong> ★</div>
          </div>
          <h4 class="ts-name">Frappe Caramel</h4>
          <p class="ts-desc">20% Caramelo<br />40% Milk</p>
          <div class="ts-info">
            <span>20 OZ.</span>
            <span class="ts-price">$20.00</span>
            <button class="ts-cart">🛒</button>
          </div>
        </article>
      </div>

      <!-- Botón Catálogo -->
      <div class="catalogo-btn">
        <a href="catalogo.html">
          <span>Catalogo</span>
          <img src="assest/catalogicon.png" alt="Icono Catalogo" />
        </a>
      </div>

    </section>
    <!--tercera parte(nuestros servicios)-->
    <!-- ================== NUESTROS SERVICIOS ================== -->
    <section class="svc" aria-labelledby="svc-title">
      <div class="svc__wrap">
        <header class="svc__head">
          <div style="display: flex; align-items: center; justify-content: center; gap: 18px;">
            <span style="flex:1; border-top: 2.5px solid #531607; opacity: 0.7; min-width: 60px;"></span>
            <h2 id="svc-title" style="margin: 0 12px; white-space: nowrap;">Nuestros Servicios</h2>
            <span style="flex:1; border-top: 2.5px solid #531607; opacity: 0.7; min-width: 60px;"></span>
          </div>
          <div class="svc-icons">
            <img src="assest/iconcofe.png" alt="Icono café" style="width:38px;" />
            <img src="assest/iconcofe2.png" alt="Icono café 2" style="width:38px;" />
            <img src="assest/tazaicon.png" alt="Icono taza" style="width:38px;" />
          </div>
          <p style="margin-top: 40px;">
            <span style="color:#7a4b34; font-weight:900; font-size:1.25rem; letter-spacing:1px; text-shadow:0 2px 8px #dcc0b9;">Contamos con los mejores servicios para que disfrute unas tardes de café de calidad y snacks deliciosos.</span>
          </p>
        </header>

        <ul class="svc__grid">
          <!-- 1 -->
          <li class="svc__card">
            <figure class="svc__icon">
              <img
                src="assest/family.png"
                alt="Espacio 100% familiar"
                loading="lazy"
              />
            </figure>
            <h3 class="svc__label">Espacio 100% Familiar</h3>
          </li>
          <!-- 2 -->
          <li class="svc__card">
            <figure class="svc__icon">
              <img
                src="assest/camera.png"
                alt="Vigilancia todo el día"
                loading="lazy"
              />
            </figure>
            <h3 class="svc__label">Vigilancia todo el día</h3>
          </li>
          <!-- 3 -->
          <li class="svc__card">
            <figure class="svc__icon">
              <img
                src="assest/microphone.png"
                alt="Espacio libre de ruido"
                loading="lazy"
              />
            </figure>
            <h3 class="svc__label">Espacio libre de ruido</h3>
          </li>
          <!-- 4 -->
          <li class="svc__card">
            <figure class="svc__icon">
              <img src="assest/wifi.png" alt="Wi-Fi gratuito" loading="lazy" />
            </figure>
            <h3 class="svc__label">Wi-FI Gratuito</h3>
          </li>
          <!-- 5 -->
          <li class="svc__card">
            <figure class="svc__icon">
              <img
                src="assest/descuentos.png"
                alt="Grandes descuentos"
                loading="lazy"
              />
            </figure>
            <h3 class="svc__label">Grandes Descuentos</h3>
          </li>
          <!-- 6 -->
          <li class="svc__card">
            <figure class="svc__icon">
              <img
                src="assest/accesibilyty.png"
                alt="Accesibilidad a todo público"
                loading="lazy"
              />
            </figure>
            <h3 class="svc__label">Accesibilidad a todo público</h3>
          </li>
        </ul>

        <div class="svc__cta">
          <a class="btn" href="acercade.html">Acerca de nosotros</a>
        </div>
      </div>

      <!-- Franja de granos abajo -->
      <div class="svc__beans-img">
        <img
          src="assest/nueces.png"
          alt="Franja de granos de café"
          style="width: 100%; max-width: 2000px; display: block; margin: 0 auto"
        />
      </div>
    </section>
<!-- ================== PROMO PEDIDO ================== -->
<section class="promo" aria-labelledby="promo-title">
  <div class="promo__wrap">
    <!-- Texto -->
    <div class="promo__text">
      <h2 id="promo-title" class="promo__title">¡Haz tu pedido hoy!</h2>
      <p class="promo__desc">No esperes más para disfrutar el mejor café en grano</p>
      <h3 class="promo__price">Desde $45 MXN</h3>

      <div class="promo__cta">
        <a href="#visit" class="btn btn--dark">Visítanos</a>
        <a href="#menu" class="btn btn--light">Conoce el menú</a>
      </div>
    </div>

    <!-- Imagen bebida -->
    <div class="promo__img">
      <img src="assest/drinks/Frappé cookies and cream.png" alt="Frappé Oreo" loading="lazy">
    </div>
  </div>
</section>

<!-- ================== ENVIOS MANZANILLO ================== -->
<section class="delivery" aria-labelledby="delivery-title">
  <div class="delivery__wrap">
    <!-- Tarjeta de texto -->
    <div class="delivery__card">
      <header class="delivery__header">
        <h2 id="delivery-title" class="delivery__title">
          Envíos a<br>Manzanillo
        </h2>
        <hr class="delivery__line" />
      </header>

      <p class="delivery__desc">
  Llevamos el mejor café hasta<br>tu puerta en Manzanillo
      </p>
      <p class="delivery__small">
  Rápido, fresco y con la misma<br>calidad que en tienda
      </p>

      <a href="#ubicaciones" class="btn btn--dark">
        <img src="assest/locationicon.png" alt="" class="btn__icon" />
        ver ubicaciones de entrega
      </a>
    </div>

    <!-- Mapa -->
    <div class="delivery__map">
      <iframe
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d191626.75691105073!2d-104.400091!3d19.113809!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x84254550c3d08cf3%3A0x4016978679cdbd0!2sManzanillo%2C%20Col.!5e0!3m2!1ses!2smx!4v0000000000000"
        allowfullscreen
        loading="lazy"
        referrerpolicy="no-referrer-when-downgrade"
        title="Mapa de Manzanillo"
      ></iframe>
    </div>
  </div>

  <!-- Elementos decorativos opcionales -->
  <img src="assets/bg-icons/coffee.png" alt="" class="delivery__icon delivery__icon--left" aria-hidden="true" />
  <img src="assets/bg-icons/bean1.png"   alt="" class="delivery__icon delivery__icon--bottom" aria-hidden="true" />
</section>

       <!-- Decoración inferior -->
      <div class="cta-decor">
        <div class="decor-item">
          <img src="assest/iconcofe.png" alt="" aria-hidden="true" />
          <span class="decor-line"></span>
        </div>
        <div class="decor-item">
          <img src="assest/iconcofe2.png" alt="" aria-hidden="true" />
          <span class="decor-line"></span>
        </div>
        <div class="decor-item">
          <img src="assest/iconcofe.png" alt="" aria-hidden="true" />
        </div>
      </div>
    </section>

    <!-- ===================== FOOTER ===================== -->
<footer class="cs-footer" aria-labelledby="footer-title">
  <h2 id="footer-title" class="sr-only">Información del sitio</h2>

  <div class="cs-footer__wrap">
    <!-- Marca -->
    <aside class="cs-brand">
      <img class="cs-brand__logo" src="assest/logocafe.png" alt="Coffee Shop">
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
          <li><a href="index.html"><span class="chev" aria-hidden="true">›</span> Inicio</a></li>
          <li><a href="catalogo.html"><span class="chev" aria-hidden="true">›</span> Catálogo</a></li>
          <li><a href="comentarios.html"><span class="chev" aria-hidden="true">›</span> Comentarios</a></li>
          <li><a href="acercade.html"><span class="chev" aria-hidden="true">›</span> Acerca de</a></li>
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
        <img src="assest/iconcofe.png" alt="icono café" style="width:32px; height:32px; object-fit:contain;" />
      </span>
      <span class="cs-line"></span>
    </div>

    <div class="cs-legal">

  </body>
</html>
