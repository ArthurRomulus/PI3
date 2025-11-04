<?php
// Inicia sesión si no existe
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include "../../conexion.php"; // Conexión a BD

// Verificamos si hay sesión activa (por si muestras el nombre del usuario)
$usuarioLogueado = !empty($_SESSION['logueado']) && $_SESSION['logueado'] === true;

// Obtenemos la categoría seleccionada (si no hay, mostramos todas)
$categoria = isset($_GET['categoria']) ? $_GET['categoria'] : '';
$buscar = isset($_GET['buscar']) ? $_GET['buscar'] : '';

// Consulta de productos con filtrado por categoría o búsqueda
$sql = "SELECT p.*, GROUP_CONCAT(c.nombrecategoria SEPARATOR ', ') AS categorias
        FROM productos p
        LEFT JOIN producto_categorias pc ON p.idp = pc.idp
        LEFT JOIN categorias c ON pc.id_categoria = c.id_categoria";

$params = [];
$types = "";
$conditions = [];

if ($buscar) {
    $conditions[] = "p.namep LIKE ?";
    $params[] = "%$buscar%";
    $types .= "s";
}

if ($categoria) {
    $conditions[] = "c.nombrecategoria = ?";
    $params[] = $categoria;
    $types .= "s";
}

if ($conditions) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}

$sql .= " GROUP BY p.idp ORDER BY p.idp ASC";

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

// Obtenemos todas las categorías para los botones
$categoria_query = "SELECT nombrecategoria FROM categorias ORDER BY nombrecategoria ASC";
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
</head>
<body>
<?php include "../nav_bar.php"; ?>

<section class="catalogo" aria-labelledby="catalogo-title">
  <div class="catalogo__wrap">
    <h2 id="catalogo-title">Catálogo</h2>

    <!-- Botones de categorías -->
    <div class="button-group" style="display:flex; flex-wrap:wrap; gap:8px; margin-bottom:16px;">
      <a href="catalogo.php">
        <button style="padding:8px 14px; border:none; background:#7a4b34; color:#fff; border-radius:6px; cursor:pointer;">
          Todo
        </button>
      </a>
      <?php
      if ($categoria_result->num_rows > 0) {
          while ($cat = $categoria_result->fetch_assoc()) {
              $cat_nombre = $cat['nombrecategoria'];
              $activo = ($categoria === $cat_nombre) ? "background:#d8b597;color:#000;font-weight:bold;" : "";
              echo '<a href="catalogo.php?categoria=' . urlencode($cat_nombre) . '">
                      <button style="padding:8px 14px; border:none; background:#7a4b34; color:#fff; border-radius:6px; cursor:pointer;' . $activo . '">' 
                      . htmlspecialchars($cat_nombre) . '</button>
                    </a>';
          }
      }
      ?>
    </div>

    <!-- Buscador -->
    <form method="GET" action="catalogo.php" style="margin-bottom:20px;">
      <input type="text" name="buscar" placeholder="Buscar producto..." 
             value="<?php echo htmlspecialchars($buscar); ?>"
             style="padding:8px; border:1px solid #ccc; border-radius:6px; width:60%;">
      <?php if($categoria): ?>
        <input type="hidden" name="categoria" value="<?php echo htmlspecialchars($categoria); ?>">
      <?php endif; ?>
      <button type="submit" style="padding:8px 14px; background:#7a4b34; color:#fff; border:none; border-radius:6px; cursor:pointer;">Buscar</button>
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
    <h2 id="hotdrinks-title">
      <?php echo $categoria ? htmlspecialchars($categoria) : "Todos los productos"; ?>
    </h2>

    <!-- GRID DE PRODUCTOS -->
    <div class="hotdrinks__grid">
      <?php if (count($productos) > 0): ?>
        <?php foreach ($productos as $producto): ?>
          <article class="ts-card" data-id="<?= htmlspecialchars($producto['idp']) ?>">
            <div class="ts-stage">
              <img src="<?= htmlspecialchars($producto['ruta_imagen'] ?? '../../images/placeholder.png') ?>"
                   alt="<?= htmlspecialchars($producto['namep']) ?>"
                   onerror="this.onerror=null;this.src='../../images/placeholder.png';" />
            </div>
            <h4 class="ts-name"><?= htmlspecialchars($producto['namep']) ?></h4>
            <p class="ts-desc"><?= htmlspecialchars($producto['descripcion'] ?? '') ?></p>
            <div class="ts-info">
              <span><?= htmlspecialchars($producto['categorias'] ?? 'Sin categoría') ?></span>
              <span class="ts-price">$<?= number_format($producto['precio'], 2) ?> MXN</span>
              <button class="ts-cart">🛒</button>
            </div>
          </article>
        <?php endforeach; ?>
      <?php else: ?>
        <p style="grid-column:1/-1; text-align:center; opacity:.7; padding:16px;">
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
</body>
</html>
