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
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Coffee-Shop</title>
    <link rel="stylesheet" href="../inicio/Style.css" />
    <link rel="stylesheet" href="acercade.css">
    <link rel="icon" href="../images/logotipocafes.png" />
    <link href="../general.css" rel="stylesheet"/>
  </head>
  <body>
  <?php include "../nav_bar.php"; ?>

    <!--seccion 1  acerca de nosotros-->
    <section class="about">
      <div class="about-wrap">
        <div class="about-text">
          <h3 data-translate="Acerca de Nosotros">Acerca de<br />Nosotros</h3>
          <p data-translate="Iniciamos con una pasión por el café y el deseo de compartir
            momentos únicos. No solo servimos bebidas, también ofrecemos
            experiencias: un espacio tranquilo, acogedor y lleno de sabor para
            disfrutar a tu manera.">
            Iniciamos con una pasión por el café y el deseo de compartir
            momentos únicos. No solo servimos bebidas, también ofrecemos
            experiencias: un espacio tranquilo, acogedor y lleno de sabor para
            disfrutar a tu manera.
          </p>

          <div class="stats-row">
            <div class="stat-box">
              <span class="stat-number">1</span>
              <span class="stat-label" data-translate="Año">Año</span>
            </div>
            <div class="stat-box">
              <span class="stat-number">+40</span>
              <span class="stat-label" data-translate="Recetas">Recetas</span>
            </div>
            <div class="stat-box">
              <span class="stat-number">1</span>
              <span class="stat-label" data-translate="Sucursal">Sucursal</span>
            </div>
          </div>
        </div>

        <div class="about-photo">
          <img src="../../images/negroscofee.jpeg" alt="Barista en cafetería" />
        </div>
      </div>
    </section>
    <!-- seccion2 (Mision, Vision) -->
    <section class="mision-vision">
      <div class="mv-wrap">
        <div class="mv-card">
          <!-- Columna Misión -->
          <div class="mv-col">
            <h3 class="mv-title" data-translate="Misión">Misión</h3>
            <p class="mv-text" data-translate="Ofrecer la mejor experiencia cafetera con productos de alta
              calidad, atención genuina y un ambiente que invite a volver
              siempre.">
              Ofrecer la mejor experiencia cafetera con productos de alta
              calidad, atención genuina y un ambiente que invite a volver
              siempre.
            </p>
            <div class="mv-icon">
              <img src="../../images/mision.png" alt="Icono pulgar arriba" />
            </div>
          </div>

          <!-- Columna Visión -->
          <div class="mv-col">
            <h3 class="mv-title" data-translate="Visión">Visión</h3>
            <p class="mv-text" data-translate="Convertirnos en un referente local en la cultura del café,
              compartiendo nuestro conocimiento y pasión con cada cliente.">
              Convertirnos en un referente local en la cultura del café,
              compartiendo nuestro conocimiento y pasión con cada cliente.
            </p>
            <div class="mv-icon">
              <img src="../../images/vision.png" alt="Icono cohete" />
            </div>
          </div>
        </div>
      </div>
    </section>

        <!-- Seccion 3 cuadrado con iconos -->
    <section class="features">
      <div class="features-box">
        <div class="feature-item">
          <img src="../../images/icon1.png" alt="Sustainability" />
          <p data-translate="Sostenibilidad">Sostenibilidad</p>
        </div>

        <div class="feature-item">
          <img src="../../images/icon2.png" alt="No Plastics" />
          <p data-translate="No plasticos">No Plasticos</p>
        </div>

        <div class="feature-item">
          <img src="../../images/icon3.png" alt="Aporta Local" />
          <p data-translate="Aporta Local">Aporta<br />Local</p>
        </div>

        <div class="feature-item">
          <img src="../../images/icon4.png" alt="Local Apoyo" />
          <p data-translate="Apoyo local">Apoyo<br />Local</p>
        </div>

        <div class="feature-item">
          <img src="../../images/icon5.png" alt="Calidad de benas" />
          <p data-translate="Calidad de benas">Calidad<br />de benas</p>
        </div>
      </div>
    </section>


    <!-- Seccion 4 Nuestro equipo -->
<section class="coffee-stories" id="coffee-stories">
  <div class="cs-wrap">

    <!-- Frase superior -->
    <p class="cs-intro" 
      data-translate="Somos un grupo #CoffeeLovers y productores de Manzanillo, Tecomán, buscando nuevas formas de compartir nuestra pasión por el café.">
      Somos un grupo <strong translate="no">#CoffeeLovers</strong> y productores de
      <strong>Manzanillo</strong>, Tecomán, buscando nuevas formas de compartir nuestra
      <strong translate="no">pasión por el café</strong>.
    </p>


    <!-- Grid de 3 columnas -->
    <div class="cs-grid">
      <!-- Card 1 -->
      <article class="cs-card">
        <div class="cs-media">
          <img src="../../images/agricultor2.png" alt="Cosecha de café en Finca La Roca" loading="lazy">
        </div>
        <h3 class="cs-title" data-translate="FINCA LA ROCA (COATEPEC, VER)">FINCA LA ROCA (COATEPEC, VER)</h3>
        <p class="cs-text" data-translate="Nuestro café se cultiva en Finca La Roca a 1,250 m s. n. m., con una
          selección de granos de productores invitados que cuidan cada detalle para
          que tu café reciba el respeto que se merece.">
          Nuestro café se cultiva en Finca La Roca a 1,250 m s. n. m., con una
          selección de granos de productores invitados que cuidan cada detalle para
          que tu café reciba el respeto que se merece.
        </p>
      </article>

      <!-- Card 2 -->
      <article class="cs-card">
        <div class="cs-media">
          <img src="../../images/agricultor.png" alt="Selección manual de granos de café" loading="lazy">
        </div>
        <h3 class="cs-title" data-translate="SELECCIÓN ESPECIAL">SELECCIÓN ESPECIAL</h3>
        <p class="cs-text" data-translate="Una selección de granos cultivados con el cuidado de los cafés de altura
          y la dedicación en el proceso de los granos de especialidad. Tostamos
          cada semana para que recibas tu café siempre fresco.">
          Una selección de granos cultivados con el cuidado de los cafés de altura
          y la dedicación en el proceso de los granos de especialidad. Tostamos
          cada semana para que recibas tu café siempre fresco.
        </p>
      </article>

      <!-- Card 3 -->
      <article class="cs-card">
        <div class="cs-media">
          <img src="../../images/agricultor3.jpg" alt="Granos de café tostados con reconocimientos" loading="lazy">
        </div>
        <h3 class="cs-title"data-translate="CAFÉ DE ALTURA">CAFÉ DE ALTURA</h3>
        <p class="cs-text" data-translate="Gracias al trabajo de nuestros productores, las fincas donde se cultiva
          tu café han sido reconocidas en certámenes de relevancia como la
          Cup of Excellence (México 2018 y 2019).">
          Gracias al trabajo de nuestros productores, las fincas donde se cultiva
          tu café han sido reconocidas en certámenes de relevancia como la
          <em>Cup of Excellence</em> (México 2018 y 2019).
        </p>
      </article>
    </div>
  </div>
</section>
    


    <!-- =================seccion 5 descubre el sbr y la exp============= -->
    <section class="hero-cafe">
  <!-- Íconos de fondo con <img> -->
  <img class="hero-decor decor-1" src="../../images/iconoplanta1.png" alt="" aria-hidden="true">
  <img class="hero-decor decor-2" src="../../images/icono_planta2.png" alt="" aria-hidden="true">

  <div class="hero-wrap">
    <!-- Lado izquierdo: textos -->
    <div class="hero-copy">
      <h1 class="hero-title" data-translate="Descubre el sabor y la experiencia">
        Descubre el sabor<br>
        y la experiencia
      </h1>
      <p class="hero-sub" data-translate="Sumérgete en el aroma, disfruta cada sorbo y vive la experiencia única que solo nuestra cafetería puede ofrecerte.
        Cada rincón, cada taza… una historia que te inspira.">
        Sumérgete en el aroma, disfruta cada sorbo y vive la experiencia única que solo nuestra cafetería puede ofrecerte.
        Cada rincón, cada taza… una historia que te inspira.
      </p>

      <div class="hero-cta">
        <a href="#visitanos" class="btn btn-primary" data-translate="Visítanos">Visítanos</a>
        <a href="#menu" class="btn btn-ghost" data-translate="Conoce el menú">Conoce el menú</a>
      </div>
    </div>

    <!-- Lado derecho: tarjetas de imagen (SIN títulos) -->
    <div class="hero-media">
      <figure class="media-card card-bottom">
        <img src="../../images/negro_programndo.jpg" alt="Productor seleccionando granos" loading="lazy">
      </figure>
    </div>
  </div>
</section>


      <!-- Decoración inferior -->
      <div class="cta-decor">
        <div class="decor-item">
          <img src="../../images/iconcofe.png" alt="" aria-hidden="true" />
          <span class="decor-line"></span>
        </div>
        <div class="decor-item">
          <img src="../../images/iconcofe2.png" alt="" aria-hidden="true" />
          <span class="decor-line"></span>
        </div>
        <div class="decor-item">
          <img src="../../images/iconcofe.png" alt="" aria-hidden="true" />
        </div>
      </div>

      
<?php include "../footer.php"; ?>

     <!-- === OVERLAY & DRAWER MINI-CARRITO === -->
<div class="mc-overlay" id="mcOverlay" hidden></div>

<aside
  class="mini-cart"
  id="miniCart"
  aria-hidden="true"
  aria-labelledby="mcTitle"
  role="dialog"
>
  <header class="mc-header">
    <h3 id="mcTitle">Tu carrito</h3>
    <button class="mc-close" id="mcClose" aria-label="Cerrar carrito">
      ✕
    </button>
  </header>

  <div class="mc-body">
    <ul class="mc-list" id="mcList">
      <!-- items por JS -->
    </ul>
    <div class="mc-empty" id="mcEmpty" data-translate="Tu carrito está vacío.">Tu carrito está vacío.</div>
  </div>

  <footer class="mc-footer">
    <div class="mc-total">
      <span>Total</span>
      <strong id="mcTotal">$0.00 MXN</strong>
    </div>
    <a href="../catalogo/carrito.php" class="mc-btn" data-translate="Ir a pagar">Ir a pagar</a>
  </footer>
</aside>


<script>
  window.CART_API_URL = '../catalogo/cart_api.php';
</script>
<script src="../catalogo/app.js"></script>
<script src="../../translate.js"></script>
</body>
</html>

