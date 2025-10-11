<?php
// get_productos.php
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

require_once __DIR__ . '/db.php';

$categoria = isset($_GET['categoria']) ? trim($_GET['categoria']) : '';
$q         = isset($_GET['q']) ? trim($_GET['q']) : '';

$sql = "SELECT
          id_producto,                           -- nombre original
          id_producto               AS id,       -- alias para compatibilidad
          nombre_producto,
          descripcion_producto,
          foto_producto,
          precio_producto,
          cantidadProducto          AS cantidad_producto,  -- nombre que pediste
          cantidadProducto,                        -- también el original
          categoria
        FROM productos
        WHERE 1=1";

$params = [];
$types  = '';

if ($categoria !== '') {
  $sql     .= " AND categoria = ?";
  $params[] = $categoria;
  $types   .= 's';
}

if ($q !== '') {
  $sql     .= " AND (id_producto = ? OR nombre_producto LIKE ? OR descripcion_producto LIKE ?)";
  $params[] = $q;
  $params[] = "%$q%";
  $params[] = "%$q%";
  $types   .= 'sss';
}

$sql .= " ORDER BY nombre_producto ASC";

$stmt = $mysqli->prepare($sql);
if (!$stmt) {
  http_response_code(500);
  echo json_encode(['ok' => false, 'error' => $mysqli->error]);
  exit;
}

if (!empty($params)) {
  $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$res = $stmt->get_result();

$items = [];
while ($row = $res->fetch_assoc()) {
  $items[] = $row;
}

echo json_encode(['ok' => true, 'items' => $items]);
