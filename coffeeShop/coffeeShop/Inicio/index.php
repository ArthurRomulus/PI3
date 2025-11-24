<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// bandera limpia para usar después en el footer
$usuarioLogueado = !empty($_SESSION['logueado']) && $_SESSION['logueado'] === true;

?><?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include "../../conexion.php"; // ajusta la ruta si cambia

// TOP 3 productos (por VENTAS; si aún no usas esa columna, puedes ordenar por idp)
$sqlTop = "SELECT p.*, GROUP_CONCAT(c.nombrecategoria SEPARATOR ', ') AS categorias
           FROM productos p
           LEFT JOIN producto_categorias pc ON p.idp = pc.idp
           LEFT JOIN categorias c ON pc.id_categoria = c.id_categoria
           GROUP BY p.idp
           ORDER BY p.VENTAS DESC
           LIMIT 3";

$resTop = $conn->query($sqlTop);

$topVendidos = [];
if ($resTop) {
    while ($row = $resTop->fetch_assoc()) {
        $topVendidos[] = $row;
    }
}
?>

<?php
include "../../conexion.php";

function getListboxesProducto(mysqli $conn, int $idp): array {
    $listboxes = [];

    // Listboxes asociados al producto
    $qLB = $conn->prepare("
        SELECT lb.id AS id, lb.nombre AS nombre
        FROM producto_listbox pl
        INNER JOIN listboxes lb ON pl.listbox_id = lb.id
        WHERE pl.producto_id = ?
    ");
    $qLB->bind_param('i', $idp);
    $qLB->execute();
    $resLB = $qLB->get_result();

    while ($lb = $resLB->fetch_assoc()) {

        // Opciones de cada listbox
        $qOps = $conn->prepare("
            SELECT id, valor AS opcion, precio
            FROM listbox_opciones
            WHERE listbox_id = ?
            ORDER BY valor ASC
        ");
        $qOps->bind_param('i', $lb['id']);
        $qOps->execute();
        $resOps = $qOps->get_result();

        $opciones = [];
        while ($op = $resOps->fetch_assoc()) {
            $opciones[] = [
                'id'     => (int)$op['id'],
                'opcion' => $op['opcion'],
                'precio' => (float)$op['precio'],
            ];
        }
        $qOps->close();

        $listboxes[] = [
            'id'       => (int)$lb['id'],
            'nombre'   => $lb['nombre'], // aquí tienes nombres como "Tipo de café", "Tipo de leche"
            'opciones' => $opciones
        ];
    }
    $qLB->close();

    return $listboxes;
}
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
    <link rel="stylesheet" href="../catalogo/catalogo.css" />
    <link rel="stylesheet" href="../catalogo/catalogo.css" />
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

<div class="hotdrinks__grid" id="topSellingGrid">


  <?php if (count($topVendidos) > 0): ?>
    <?php foreach ($topVendidos as $producto): 
      $img = $producto['ruta_imagen'] ?? '../../images/placeholder.png';
    ?>
      <article class="ts-card"
        data-id="<?= htmlspecialchars($producto['idp']) ?>"
        data-name="<?= htmlspecialchars($producto['namep']) ?>"
        data-price="<?= htmlspecialchars($producto['precio']) ?>"
        data-foto="<?= htmlspecialchars($img) ?>"
      >
        <div class="ts-stage">
          <img 
            src="<?= htmlspecialchars($img) ?>"
            alt="<?= htmlspecialchars($producto['namep']) ?>"
            onerror="this.onerror=null;this.src='../../images/placeholder.png';"
          />
        </div>

        <h4 class="ts-name" data-translate="<?= htmlspecialchars($producto['namep'] ?? '', ENT_QUOTES) ?>">
          <?= htmlspecialchars($producto['namep'] ?? '') ?>
        </h4>

        <p class="ts-desc" data-translate="<?= htmlspecialchars($producto['descripcion'] ?? '', ENT_QUOTES) ?>">
          <?= htmlspecialchars($producto['descripcion'] ?? '') ?>
        </p>

        <span data-translate="<?= htmlspecialchars($producto['categorias'] ?? 'Sin categoría') ?>">
          <?= htmlspecialchars($producto['categorias'] ?? 'Sin categoría') ?>
        </span>

       <!-- STOCK (igual que catálogo) -->
<p class="ts-stock">
  <strong>Stock:</strong> <?= htmlspecialchars($producto['STOCK']) ?>
</p>

<!-- FILA INFERIOR: disponible + precio + carrito -->
<div class="ts-info">
  <span>
    <?= ($producto['STOCK'] > 0) ? 'Disponible' : 'No disponible' ?>
  </span>

  <span class="ts-price">
    $<?= number_format($producto['precio'], 2) ?> MXN
  </span>

  <button class="ts-cart" <?= ($producto['STOCK'] > 0) ? '' : 'disabled' ?>>
    🛒
  </button>
</div>

      </article>
    <?php endforeach; ?>

  <?php else: ?>
    <p style="grid-column:1/-1; text-align:center; opacity:.7; padding:16px;" data-translate="No hay productos para mostrar.">
      No hay productos para mostrar.
    </p>
  <?php endif; ?>

</div>

<!-- MODAL DE OPCIONES DINÁMICO -->
<div class="modal-opciones" id="modalOpciones" style="display:none;">
  <div class="modal-content">
    
    <h3 id="modalProductoNombre" data-translate="Personalizar producto">Personalizar producto</h3>

    <!-- AQUÍ SE GENERAN LAS LISTBOXES DESDE LA BD -->
    <div id="modalOpcionesWrap"></div>
    <!-- SELECT DE TAMAÑO -->
    <label style="font-weight:bold; margin-top:10px;" data-translate="Tamaño">Tamaño</label>
    <select id="selectTamaño" style="width:100%; padding:8px; border-radius:6px; margin-top:5px;">
        <option value="Chico" data-translate="Pequeño">Pequeño</option>
        <option value="Mediano" data-translate="Mediano">Mediano</option>
        <option value="Grande" data-translate="Grande">Grande</option>
    </select>


    <button id="btnAgregarModal" data-translate="Agregar al carrito">Agregar al carrito</button>
    <button id="btnCerrarModal" data-translate="Cancelar">Cancelar</button>

  </div>
</section>


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
        <a href="../acercade/acercade.php" class="btn btn--dark" data-translate="Visítanos">Visítanos</a>
        <a href="../../Images/menu_pi3.png" class="btn btn--light" data-translate="Conoce el menú">Conoce el menú</a>
      </div>

      <!-- Sticker debajo de los botones -->
      <img src="../../Images/iconcofe.png" alt="Sticker decorativo" class="sticker sticker--bottom">
    </div>

    <!-- Sticker esquina superior -->
    <img src="../../Images/iconcofe2.png" alt="Sticker esquina" class="sticker sticker--corner">

    <!-- Imagen bebida -->
    <div class="promo__img">
      <img src="../../Images/frappe_moka.png" alt="Frappé Moka" loading="lazy">
    </div>

    <!-- Sticker extra al fondo -->
    <img src="../../Images/tazaicon.png" alt="Sticker inferior" class="sticker sticker--footer">

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

      <a href="../acercade/acercade.php" class="btn btn--dark" data-translate="ver ubicaciones de entrega">
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
  <img src="../../Images/coffee.png" alt="" class="delivery_icon delivery_icon--left" aria-hidden="true" />
  <img src="../../Images/bean1.png"   alt="" class="delivery_icon delivery_icon--bottom" aria-hidden="true" />

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