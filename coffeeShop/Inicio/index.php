<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// bandera limpia para usar después en el footer
$usuarioLogueado = !empty($_SESSION['logueado']) && $_SESSION['logueado'] === true;


?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Coffee-Shop</title>
    <link rel="stylesheet" href="Style.css" />
    <link rel="stylesheet" href="style_index.css">
    <link rel="icon" href="../../Images/logocafe.png" />
    <link rel="icon" href="../../Images/logotipocafes.png" />
    <link href="https://fonts.googleapis.com/css2?family=ADLaM+Display&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=ADLaM+Display&family=Montaga&display=swap" rel="stylesheet"/>
    <link href="../general.css" rel="stylesheet"/>
    
  </head>
  <body>
  <?php include "../nav_bar.php"; ?>
    <!--Primera parte(sabor que inspira)-->
    <section class="hero-sabor">
      <div class="hero-wrap">
        <div class="hero-texto">
          <h2 data-translate="SABOR QUE INSPIRA">SABOR QUE INSPIRA</h2>

          <p data-translate= "En nuestra cafetería hacemos de cada momento algo especial. No solo
            servimos café de calidad, también tenemos una variedad de postres
            para acompañar tus días. Un espacio tranquilo, acogedor y lleno de
            sabor, pensado para que disfrutes a tu manera">
            En nuestra cafetería hacemos de cada momento algo especial. No solo
            servimos café de calidad, también tenemos una variedad de postres
            para acompañar tus días. Un espacio tranquilo, acogedor y lleno de
            sabor, pensado para que disfrutes a tu manera.
          </p>
          <div class="hero-icon-bg">
            <img src="../../Images/iconoplanta1.png" alt="Icono decorativo" />
          </div>
        </div>

        <div class="hero-imagen">
          <img
            src="../../Images/tazatirandocafe.png"
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
        <span class="ts-title-text" data-translate="Más Vendidos">Más Vendidos</span>
        <span class="ts-line"></span>
      </div>
      <!-- Iconos sueltos en el lienzo de Más Vendidos -->
      <img src="../../Images/iconcofe.png" alt="Icono café" style="width:40px; margin-right:12px;" />
      <img src="../../Images/iconcofe2.png" alt="Icono café 2" style="width:40px;" />

      <div class="ts-grid">
        <!-- Tarjeta 1 -->
        <article class="ts-card"
  data-id="frappe_clasico"
  data-name="Frappe Clásico"
  data-price="20.00"
  data-foto="../../Images/FrappeClasic.png">
  <div class="ts-stage">
    <img src="../../Images/FrappeClasic.png" alt="Frappe Clásico" />
    <div class="ts-rate"><strong>4.9</strong> ★</div>
  </div>
  <h4 class="ts-name">Frappe Clásico</h4>
  <p class="ts-desc">20% Expresso<br>40% Milk</p>
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
              src="../../Images/Panini_pavo_queso.png"
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
            <img src="../../Images/FrapCaramel.png" alt="Frappe Caramel" />
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
        <a href="../catalogo/catalogo.php">
          <span data-translate="Catalogo">Catalogo</span>
          <img src="../../Images/catalogicon.png" alt="Icono Catalogo" />
        </a>
      </div>

    </section>
    <!--tercera parte(nuestros servicios)-->
    <!-- ================== NUESTROS SERVICIOS ================== -->
     <section class="ts-section">
      <div class="ts-title-line">
        <span class="ts-line"></span>
        <span class="ts-title-text" data-translate="Nuestros Servicios">Nuestros Servicios</span>
        <span class="ts-line"></span>
      </div>
      <!-- Iconos sueltos en el lienzo de Más Vendidos -->
      <img src="../../Images/iconcofe.png" alt="Icono café" style="width:40px; margin-right:12px;" />
      <img src="../../Images/iconcofe2.png" alt="Icono café 2" style="width:40px;" />



<div class="hero-texto">
  <div class="hero-line">

    <p class="hero-texto__contenido" data-translate="Contamos con los mejores servicios para que disfrute <br> unas tardes de café de calidad y snacks deliciosos">
      Contamos con los mejores servicios para que disfrute <br> unas tardes de café de calidad y snacks deliciosos.
    </p>

  </div>
</div>


</header>

    <!-- … resto de la sección … -->


        <ul class="svc__grid">
          <!-- 1 -->
          <li class="svc__card">
            <figure class="svc__icon">
              <img
                src="../../Images/family.png"
                alt="Espacio 100% familiar"
                loading="lazy"
              />
            </figure>
            <h3 class="svc__label" data-translate="Espacio 100% Familiar">Espacio 100% Familiar</h3>
          </li>
          <!-- 2 -->
          <li class="svc__card">
            <figure class="svc__icon">
              <img
                src="../../Images/camera.png"
                alt="Vigilancia todo el día"
                loading="lazy"
              />
            </figure>
            <h3 class="svc__label" data-translate="Vigilancia todo el dia">Vigilancia todo el día</h3>
          </li>
          <!-- 3 -->
          <li class="svc__card">
            <figure class="svc__icon">
              <img
                src="../../Images/microphone.png"
                alt="Espacio libre de ruido"
                loading="lazy"
              />
            </figure>
            <h3 class="svc__label" data-translate="Espacio ibre de ruido">Espacio libre de ruido</h3>
          </li>
          <!-- 4 -->
          <li class="svc__card">
            <figure class="svc__icon">
              <img src="../../Images/wifi.png" alt="Wi-Fi gratuito" loading="lazy" />
            </figure>
            <h3 class="svc__label" data-translate="Wi-Fi Gratuito">Wi-FI Gratuito</h3>
          </li>
          <!-- 5 -->
          <li class="svc__card">
            <figure class="svc__icon">
              <img
                src="../../Images/descuentos.png"
                alt="Grandes descuentos"
                loading="lazy"
              />
            </figure>
            <h3 class="svc__label" data-translate="Grandes Descuentos">Grandes Descuentos</h3>
          </li>
          <!-- 6 -->
          <li class="svc__card">
            <figure class="svc__icon">
              <img
                src="../../Images/accesibilyty.png"
                alt="Accesibilidad a todo público"
                loading="lazy"
              />
            </figure>
            <h3 class="svc__label" data-translate="Accesibilidad a todo publico">Accesibilidad a todo público</h3>
          </li>
        </ul>

      <div class="catalogo-btn">
        <a href="../acercade/acercade.php">
          <span data-translate="Acerca de Nosotros">Acerca de Nosotros</span>
        </a>
      </div>

      
    </section>
<!-- ================== PROMO PEDIDO ================== -->
<section class="promo" aria-labelledby="promo-title">
  <div class="promo__wrap">

    <!-- Texto -->
    <div class="promo__text">
      <h2 id="promo-title" class="promo__title" data-translate="¡Haz tu pedido hoy!">¡Haz tu pedido hoy!</h2>
      <p class="promo__desc" data-translate="No esperes más para disfrutar el sabor que despierta tus sentidos y te llena de energía.
Cada taza está preparada con pasión, utilizando granos seleccionados que reflejan el esfuerzo de nuestros caficultores y el amor por el buen café.
Aquí no solo servimos una bebida: creamos momentos, compartimos historias y transformamos lo cotidiano en algo especial.
Empieza tu día con el aroma que inspira, con el sabor que reconforta y con la calidad que mereces.
Ven, siéntate, disfruta y deja que cada sorbo te recuerde que los mejores días comienzan con una buena taza de café">No esperes más para disfrutar el sabor que despierta tus sentidos y te llena de energía.
Cada taza está preparada con pasión, utilizando granos seleccionados que reflejan el esfuerzo de nuestros caficultores y el amor por el buen café.
Aquí no solo servimos una bebida: creamos momentos, compartimos historias y transformamos lo cotidiano en algo especial.
Empieza tu día con el aroma que inspira, con el sabor que reconforta y con la calidad que mereces.
Ven, siéntate, disfruta y deja que cada sorbo te recuerde que los mejores días comienzan con una buena taza de café.
      </p>
      <h1 class="promo__price" data-translate="Desde $45 MXN">Desde $45 MXN</h1>

      <div class="promo__cta">
        <a href="#visit" class="btn btn--dark" data-translate="Visítanos">Visítanos</a>
        <a href="#menu" class="btn btn--light" data-translate="Conoce el menú">Conoce el menú</a>
      </div>

      <!-- Sticker debajo de los botones -->
      <img src="../../Images/iconcofe.png" alt="Sticker decorativo" class="sticker sticker--bottom">
    </div>

    <!-- Sticker esquina superior -->
    <img src="../../Images/iconcofe2.png" alt="Sticker esquina" class="sticker sticker--corner">

    <!-- Imagen bebida -->
    <div class="promo__img">
      <img src="../../Images/FrappMoka.png" alt="Frappé Moka" loading="lazy">
    </div>

    <!-- Sticker extra al fondo -->
    <img src="../../Images/tazaicon.png" alt="Sticker inferior" class="sticker sticker--footer">

  </div>
</section>


    <!-- ================== Otros PRODUCTOS ================== -->

<section class="our-products" id="our-products">
  <div class="op-wrap">
    <h2 class="op-title">
      <span data-translate="Otros Productos">Otros Productos</span>
    </h2>

    <div class="op-grid">
  <!-- Card 1 -->
  <article class="op-card">
    <div class="op-media"
         style="--bg-opacity:.22; --bg-scale:1.15; --bg-rotate:-6deg; --bg-x:0%; --bg-y:20%; --bg-blur:1.5px; --bag-width:62%;">
      <!-- Fondo -->
      <img class="op-bg" src="../../Images/flor.png" alt="" aria-hidden="true" />
      <!-- Bolsa -->
      <img class="op-bag" src="../../Images/empaque_capucchino.png" alt="Cappuccino bag" loading="lazy" />
    </div>

    <div class="op-body">
      <h3 class="op-name">Cappuccino</h3>
      <p class="op-desc">Bolsa de 250 g con notas de cacao y toque cremoso.</p>
      <div class="op-buttons">
        <a class="op-btn" href="#">Ver más</a>
        <button class="op-cart-btn">Agregar al carrito</button>
      </div>
    </div>
  </article>

  <!-- Card 2 -->
  <article class="op-card">
    <div class="op-media"
         style="--bg-opacity:.18; --bg-scale:1.05; --bg-rotate:5deg; --bg-x:-10%; --bg-y:10%; --bg-blur:1px; --bag-width:58%;">
      <img class="op-bg" src="../../Images/fondo_granos.png" alt="" aria-hidden="true" />
      <img class="op-bag" src="../../Images/empaque_blackcoffee.png" alt="Black Coffee bag" loading="lazy" />
    </div>

    <div class="op-body">
      <h3 class="op-name">Black Coffee</h3>
      <p class="op-desc">Tostado intenso 100% arábica, aroma profundo.</p>
      <div class="op-buttons">
        <a class="op-btn" href="#">Ver más</a>
        <button class="op-cart-btn">Agregar al carrito</button>
      </div>
    </div>
  </article>

      <!-- Card 3 -->
<article class="op-card">
  <div class="op-media"
       style="--bg-opacity:.20; --bg-scale:1.10; --bg-rotate:2deg; --bg-x:-5%; --bg-y:15%; --bg-blur:1px; --bag-width:60%;">
    <!-- Fondo -->
    <img class="op-bg" src="../../Images/fondo_pods.png" alt="" aria-hidden="true" />
    <!-- Bolsa -->
    <img class="op-bag" src="../../Images/empaque_pods.png" alt="Pods bag" loading="lazy" />
  </div>

  <div class="op-body">
    <h3 class="op-name">Pods</h3>
    <p class="op-desc">
      Bolsa de 300 g de café en pods, compatibles con máquinas de espresso.
      Café de origen premium con tueste medio, manteniendo la frescura.
    </p>
    <div class="op-buttons">
      <a class="op-btn" href="#" data-translate="Ver más">Ver más</a>
      <button class="op-cart-btn" data-translate="Agregar al carrito">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.6 8H19M7 13l1.5-6h9.6M9 21a1 1 0 100-2 1 1 0 000 2zm10 0a1 1 0 100-2 1 1 0 000 2z"/>
        </svg>
        Agregar al carrito
      </button>
    </div>
  </div>
</article>

<!-- Card 4 -->
<article class="op-card">
  <div class="op-media"
       style="--bg-opacity:.18; --bg-scale:1.08; --bg-rotate:-4deg; --bg-x:0%; --bg-y:12%; --bg-blur:1px; --bag-width:60%;">
    <!-- Fondo -->
    <img class="op-bg" src="../../Images/flor5.png" alt="" aria-hidden="true" />
    <!-- Bolsa -->
    <img class="op-bag" src="../../Images/empaque_moccaa.png" alt="Mokka bag" loading="lazy" />
  </div>

  <div class="op-body">
    <h3 class="op-name">Mokka</h3>
    <p class="op-desc">
      Bolsa de 340 g sabor Mokka, perfil dulce con notas de chocolate oscuro.
      Ideal para postres o para disfrutar frío con leche vegetal.
    </p>
    <div class="op-buttons">
      <a class="op-btn" href="#" data-translate="Ver más">Ver más</a>
      <button class="op-cart-btn" data-translate="Agregar al carrito">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.6 8H19M7 13l1.5-6h9.6M9 21a1 1 0 100-2 1 1 0 000 2z"/>
        </svg>
        Agregar al carrito
      </button>
    </div>
  </div>
</article>


    </div>
  </div>
</section>


<!-- ================== ENVIOS MANZANILLO ================== -->
<section class="delivery" aria-labelledby="delivery-title">
  <div class="delivery__wrap">
    <!-- Tarjeta de texto -->
    <div class="delivery__card">
      <header class="delivery__header">
        <h2 id="delivery-title" class="delivery__title" data-translate="Envíos a Manzanillo">
          Envíos a<br>Manzanillo
          <img src="../../Images/envios.png" alt="Sticker decorativo" class="delivery__sticker">
        </h2>
        <hr class="delivery__line" />
      </header>

      <p class="delivery__desc" data-translate="Llevamos el mejor café hasta tu puerta en Manzanillo">
  Llevamos el mejor café hasta<br>tu puerta en Manzanillo
      </p>
      <p class="delivery__small" data-translate="Rápido, fresco y con la misma calidad que en tienda">
  Rápido, fresco y con la misma<br>calidad que en tienda
      </p>

      <a href="#ubicaciones" class="btn btn--dark" data-translate="ver ubicaciones de entrega">
        <img src="../../Images/locationicon.png" alt="" class="btn__icon" />
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
  <img src="../../Images/coffee.png" alt="" class="delivery__icon delivery__icon--left" aria-hidden="true" />
  <img src="../../Images/bean1.png"   alt="" class="delivery__icon delivery__icon--bottom" aria-hidden="true" />

       <!-- Decoración inferior -->
      <div class="cta-decor">
        <div class="decor-item">
          <img src="../../Images/iconcofe.png" alt="" aria-hidden="true" />
          <span class="decor-line"></span>
        </div>
        <div class="decor-item">
          <img src="../../Images/iconcofe2.png" alt="" aria-hidden="true" />
          <span class="decor-line"></span>
        </div>
        <div class="decor-item">
          <img src="../../Images/iconcofe.png" alt="" aria-hidden="true" />
        </div>
      </div>
</section>

<?php include "../footer.php"; ?>

<script>
  window.CART_API_URL = '../catalogo/cart_api.php';
</script>
<script src="../catalogo/app.js"></script>
<script src="../../translate.js"></script>
</html>
