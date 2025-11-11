<?php
// === DEV: mostrar errores en local (quítalo en producción) ===
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include "../../conexion.php"; // Debe definir $conn (mysqli)

$usuarioLogueado = !empty($_SESSION['logueado']) && $_SESSION['logueado'] === true;

// Filtros
$categoria = isset($_GET['categoria']) ? trim($_GET['categoria']) : '';
$buscar    = isset($_GET['buscar'])    ? trim($_GET['buscar'])    : '';

// -------------------------------------------------------------------
// Consulta robusta sin romper con ONLY_FULL_GROUP_BY
// Subquery gc: arma la lista de categorías por producto
// Filtro por categoría usando EXISTS para no tocar la agregación
// -------------------------------------------------------------------
$sql = "
SELECT 
    p.idp,
    p.namep,
    p.ruta_imagen,
    p.precio,
    p.descripcion,
    p.STOCK,
    COALESCE(gc.categorias, '') AS categorias
FROM productos p
LEFT JOIN (
    SELECT 
        pc.idp,
        GROUP_CONCAT(c.nombrecategoria ORDER BY c.nombrecategoria SEPARATOR ', ') AS categorias
    FROM producto_categoria pc
    INNER JOIN categorias c ON c.id_categoria = pc.id_categoria
    GROUP BY pc.idp
) gc ON gc.idp = p.idp
";

$params = [];
$types  = "";
$conds  = [];

if ($buscar !== '') {
    $conds[]  = "p.namep LIKE ?";
    $params[] = "%{$buscar}%";
    $types   .= "s";
}

if ($categoria !== '') {
    // Filtra por categoría real sin alterar la agregación
    $conds[]  = "EXISTS (
                    SELECT 1
                    FROM producto_categoria pc2
                    INNER JOIN categorias c2 ON c2.id_categoria = pc2.id_categoria
                    WHERE pc2.idp = p.idp
                      AND c2.nombrecategoria = ?
                 )";
    $params[] = $categoria;
    $types   .= "s";
}

if ($conds) {
    $sql .= " WHERE " . implode(" AND ", $conds);
}

$sql .= " ORDER BY p.idp ASC";

// Ejecuta
$stmt = $conn->prepare($sql);
if ($params) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

$productos = [];
while ($row = $result->fetch_assoc()) {
    $productos[] = $row;
}

// Categorías para los botones
$categoria_query  = "SELECT nombrecategoria FROM categorias ORDER BY nombrecategoria ASC";
$categoria_result = $conn->query($categoria_query);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Coffee-Shop • Catálogo</title>

  <link rel="stylesheet" href="../inicio/Style.css" />
  <link rel="stylesheet" href="catalogo.css" />
  <link rel="icon" href="../../images/logotipocafes.png" />
  <link href="../general.css" rel="stylesheet"/>

  <!-- Estilos del mini-modal de personalización -->
  <style>
    .mwk-modal{position:fixed;inset:0;display:none;z-index:9999;font-family:system-ui,-apple-system,Segoe UI,Roboto,sans-serif}
    .mwk-modal[aria-hidden="false"]{display:block}
    .mwk-backdrop{position:absolute;inset:0;background:#0b0f1a99;backdrop-filter:saturate(120%) blur(2px)}
    .mwk-panel{position:relative;max-width:520px;margin:6vh auto;background:#0f1524;color:#e6e9f2;border:1px solid #1c2440;border-radius:20px;box-shadow:0 10px 40px #0008;overflow:hidden}
    .mwk-header{display:flex;align-items:center;justify-content:space-between;padding:14px 16px;border-bottom:1px solid #1b2442}
    .mwk-header h3{font-size:1.05rem;margin:0}
    .mwk-x{background:transparent;border:0;color:#9fb3ff;font-size:22px;line-height:1;cursor:pointer}
    .mwk-body{padding:14px 16px;display:grid;gap:14px}
    .mwk-product{display:flex;gap:12px;align-items:center}
    .mwk-product img{width:56px;height:56px;border-radius:12px;object-fit:cover;border:1px solid #1b2442}
    .mwk-name{font-weight:600}
    .mwk-price{opacity:.8;font-size:.95rem}
    .mwk-field{display:grid;gap:6px}
    .mwk-label{font-size:.9rem;opacity:.9}
    .mwk-seg{display:flex;gap:8px;flex-wrap:wrap}
    .mwk-seg button{border:1px solid #25305b;background:#121a31;color:#c9d2ff;padding:8px 10px;border-radius:12px;cursor:pointer;font-size:.92rem}
    .mwk-seg button.is-active{background:#1a2a6b;border-color:#3e5bd9;color:#fff;box-shadow:0 0 0 3px #3e5bd944}
    .mwk-note{display:block;color:#9ab1ff;opacity:.9}
    .mwk-footer{display:flex;gap:10px;justify-content:flex-end;padding:14px 16px;border-top:1px solid #1b2442}
    .mwk-btn{background:#3d61ff;border:0;color:#fff;padding:10px 14px;border-radius:12px;font-weight:600;cursor:pointer}
    .mwk-btn:hover{filter:brightness(1.05)}
    .mwk-secondary{background:#202b4d;color:#cfe0ff}
    .mwk-secondary:hover{filter:brightness(1.1)}
    @media (max-width:560px){.mwk-panel{margin:0 10px}}
  </style>
</head>
<body>
<?php include "../nav_bar.php"; ?>

<section class="catalogo" aria-labelledby="catalogo-title">
  <div class="catalogo__wrap">
    <h2 id="catalogo-title" data-translate="Catálogo">Catálogo</h2>

    <!-- Botones de categorías -->
    <div class="button-group" style="display:flex; flex-wrap:wrap; gap:8px; margin-bottom:16px;">
      <a href="catalogo.php">
        <button 
          style="padding:8px 14px; border:none; background:#7a4b34; color:#fff; border-radius:6px; cursor:pointer;"
          data-translate="Todo">Todo</button>
      </a>
      <?php if ($categoria_result && $categoria_result->num_rows > 0): ?>
        <?php while ($cat = $categoria_result->fetch_assoc()): ?>
          <?php
            $cat_nombre = $cat['nombrecategoria'];
            $activo = ($categoria === $cat_nombre) ? "background:#d8b597;color:#000;font-weight:bold;" : "";
          ?>
          <a href="catalogo.php?categoria=<?= urlencode($cat_nombre) ?>">
            <button 
              style="padding:8px 14px; border:none; background:#7a4b34; color:#fff; border-radius:6px; cursor:pointer; <?= $activo ?>"
              data-translate="<?= htmlspecialchars($cat_nombre) ?>">
              <?= htmlspecialchars($cat_nombre) ?>
            </button>
          </a>
        <?php endwhile; ?>
      <?php endif; ?>
    </div>

    <form method="GET" action="catalogo.php" style="margin-bottom:20px;">
      <input type="text" name="buscar" placeholder="Buscar producto..." 
             value="<?= htmlspecialchars($buscar) ?>"
             style="padding:8px; border:1px solid #ccc; border-radius:6px; width:60%;">
      <?php if($categoria): ?>
        <input type="hidden" name="categoria" value="<?= htmlspecialchars($categoria) ?>">
      <?php endif; ?>
      <button type="submit" style="padding:8px 14px; background:#7a4b34; color:#fff; border:none; border-radius:6px; cursor:pointer;" data-translate="Buscar">Buscar</button>
    </form>

    <div class="catalogo__divider">
      <span class="line"></span>
      <img src="../../images/iconcofe2.png" alt="" aria-hidden="true"/>
      <span class="line"></span>
    </div>
  </div>
</section>

<section class="hotdrinks" aria-labelledby="hotdrinks-title">
  <div class="hotdrinks__wrap">
    <h2 id="hotdrinks-title" data-translate="<?= htmlspecialchars($categoria ?: 'Todos los productos') ?>">
      <?= htmlspecialchars($categoria ?: 'Todos los productos') ?>
    </h2>

    <!-- GRID DE PRODUCTOS -->
    <div class="hotdrinks__grid">
      <?php if (!empty($productos)): ?>
        <?php foreach ($productos as $producto): ?>
          <?php
            $idp      = (string)$producto['idp'];
            $nombre   = $producto['namep'] ?? 'Producto';
            $desc     = $producto['descripcion'] ?? '';
            $precio   = (float)($producto['precio'] ?? 0);
            $img      = trim($producto['ruta_imagen'] ?? '');
            if ($img === '') $img = '../../Images/placeholder.png';
            $cats     = $producto['categorias'] ?? '';
          ?>
          <article
            class="ts-card"
            data-id="<?= htmlspecialchars($idp) ?>"
            data-name="<?= htmlspecialchars($nombre) ?>"
            data-price="<?= htmlspecialchars(number_format($precio, 2, '.', '')) ?>"
            data-foto="<?= htmlspecialchars($img) ?>"
            data-categoria-name="<?= htmlspecialchars($cats) ?>"
          >
            <div class="ts-stage">
              <img src="<?= htmlspecialchars($img) ?>"
                   alt="<?= htmlspecialchars($nombre) ?>"
                   loading="lazy" decoding="async"
                   onerror="this.onerror=null;this.src='../../Images/placeholder.png';" />
              <div class="ts-rate"><strong>4.6</strong> ★</div>
            </div>

            <h4 class="ts-name" data-translate="<?= htmlspecialchars($nombre) ?>">
              <?= htmlspecialchars($nombre) ?>
              <small style="opacity:.7">• SKU <?= htmlspecialchars($idp) ?></small>
            </h4>

            <p class="ts-desc" data-translate="<?= htmlspecialchars($desc) ?>">
              <?= htmlspecialchars($desc) ?>
            </p>

            <div class="ts-info" style="display:flex;justify-content:center;align-items:center;gap:10px;margin-top:8px;">
              <span style="font-weight:600;opacity:.85;">
                <?= (!empty($producto['STOCK']) && (int)$producto['STOCK'] > 0) ? 'Disponible' : 'Agotado'; ?>
              </span>

              <button class="pill ts-price" style="background:#7a4b34;color:#fff;border:none;padding:6px 14px;border-radius:999px;font-weight:700;">
                $<?= number_format($precio, 2) ?> MXN
              </button>

              <button class="ts-cart pill"
                  style="background:#f1d3c6;color:#4b2e25;border:none;padding:6px 10px;border-radius:999px;cursor:pointer;"
                  <?= (!empty($producto['STOCK']) && (int)$producto['STOCK'] > 0) ? "" : "disabled" ?>
                  title="Agregar al carrito">🛒</button>
            </div>
          </article>
        <?php endforeach; ?>
      <?php else: ?>
        <p style="grid-column:1/-1; text-align:center; opacity:.7; padding:16px;" data-translate="No hay productos en esta categoría.">
          No hay productos en esta categoría.
        </p>
      <?php endif; ?>
    </div>

    <div class="hotdrinks__divider">
      <span class="line"></span>
      <img src="../../images/icon_bebidas_calientes.png" alt="" aria-hidden="true"/>
      <span class="line"></span>
    </div>
  </div>
</section>

<?php include "../footer.php"; ?>

<!-- === OVERLAY & DRAWER MINI-CARRITO === -->
<div class="mc-overlay" id="mcOverlay" hidden></div>
<aside class="mini-cart" id="miniCart" aria-hidden="true" aria-labelledby="mcTitle" role="dialog">
  <header class="mc-header">
    <h3 id="mcTitle" data-translate="Tu carrito">Tu carrito</h3>
    <button class="mc-close" id="mcClose" aria-label="Cerrar carrito">✕</button>
  </header>
  <div class="mc-body">
    <ul class="mc-list" id="mcList"></ul>
    <div class="mc-empty" id="mcEmpty" data-translate="Tu carrito está vacío.">Tu carrito está vacío.</div>
  </div>
  <footer class="mc-footer">
    <div class="mc-total">
      <span data-translate="Total">Total</span>
      <strong id="mcTotal">$0.00 MXN</strong>
    </div>
    <a href="carrito.php" class="mc-btn" data-translate="Ir a pagar">Ir a pagar</a>
  </footer>
</aside>

<!-- Mini Modal de personalización -->
<div id="mwkModal" class="mwk-modal" aria-hidden="true">
  <div class="mwk-backdrop" data-close="true"></div>
  <div class="mwk-panel" role="dialog" aria-modal="true" aria-labelledby="mwkTitle">
    <header class="mwk-header">
      <h3 id="mwkTitle">Personaliza tu orden</h3>
      <button class="mwk-x" title="Cerrar" data-close="true">×</button>
    </header>

    <div class="mwk-body">
      <div class="mwk-product">
        <img id="mwkImg" alt="" />
        <div>
          <div id="mwkName" class="mwk-name">Producto</div>
          <div id="mwkPrice" class="mwk-price">$0.00 MXN</div>
        </div>
      </div>

      <div class="mwk-field">
        <label class="mwk-label">Tamaño</label>
        <div class="mwk-seg" id="mwkSize">
          <button type="button" data-value="Chico">Chico</button>
          <button type="button" data-value="Mediano" class="is-active">Mediano</button>
          <button type="button" data-value="Grande">Grande</button>
        </div>
      </div>

      <div class="mwk-field">
        <label class="mwk-label">Personalizar</label>
        <div class="mwk-seg" id="mwkMilk"></div>
      </div>
    </div>

    <footer class="mwk-footer">
      <button class="mwk-btn mwk-secondary" data-close="true">Cancelar</button>
      <button class="mwk-btn" id="mwkConfirm">Añadir</button>
    </footer>
  </div>
</div>

<script>
  window.CART_API_URL = 'cart_api.php';
</script>
<script src="../catalogo/app.js?v=17"></script>
<script src="../../translate.js"></script>
</body>
</html>
