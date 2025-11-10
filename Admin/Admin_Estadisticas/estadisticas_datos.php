<?php
header("Content-Type: application/json");
include "../../conexion.php";

$periodo = $_POST['periodo'] ?? 'todo';
$categoria = $_POST['categoria'] ?? 'todo';
$tipo = $_POST['tipo'] ?? 'ventas';
$inicio = $_POST['inicio'] ?? '';
$fin = $_POST['fin'] ?? '';

$where = "WHERE 1";
$hoy = date("Y-m-d");

// Filtrado por periodo
if ($periodo == "1semana") {
  $desde = date("Y-m-d", strtotime("-7 days"));
  $where .= " AND DATE(pedidos.fecha_pedido) BETWEEN '$desde' AND '$hoy'";
} elseif ($periodo == "1mes") {
  $desde = date("Y-m-d", strtotime("-30 days"));
  $where .= " AND DATE(pedidos.fecha_pedido) BETWEEN '$desde' AND '$hoy'";
} elseif ($periodo == "personalizado" && $inicio && $fin) {
  $where .= " AND DATE(pedidos.fecha_pedido) BETWEEN '$inicio' AND '$fin'";
}

// Necesitamos saber cómo se relaciona pedido_items con productos.
// Comprobamos las columnas de pedido_items para decidir la estrategia de JOIN
$colsRes = $conn->query("SHOW COLUMNS FROM pedido_items");
$cols = [];
if ($colsRes) {
  while ($c = $colsRes->fetch_assoc()) $cols[] = $c['Field'];
}

// Por defecto supondremos que pedido_items tiene 'producto_nombre' (según lo que mostraste)
$has_product_id_field = in_array('id_producto', $cols) || in_array('producto_id', $cols) || in_array('idp', $cols);
$has_producto_nombre = in_array('producto_nombre', $cols) || in_array('producto_name', $cols);

// Resolver filtro de categoría:
// tu select de categorías devuelve id_categoria; si el campo productos.categoria guarda el nombre
// traducimos id_categoria -> nombrecategoria para comparar correctamente.
if ($categoria != "todo") {
  // intentamos obtener el nombre de la categoría desde la tabla categorias
  $catName = null;
  $catQ = $conn->query("SELECT nombrecategoria FROM categorias WHERE id_categoria = '". $conn->real_escape_string($categoria) ."' LIMIT 1");
  if ($catQ && $catQ->num_rows) {
    $catRow = $catQ->fetch_assoc();
    $catName = $catRow['nombrecategoria'];
  }
  // Si conseguimos un nombre, filtramos por productos.categoria = nombre
  if ($catName) {
    $where .= " AND productos.categoria = '". $conn->real_escape_string($catName) ."'";
  } else {
    // Si no encontramos nombre, quizá el campo categoria de productos guarda el id; entonces intentamos filtrar por id directo
    $where .= " AND productos.categoria = '". $conn->real_escape_string($categoria) ."'";
  }
}

// Construcción de la consulta dependiendo de cómo se relacione pedido_items con productos
if ($has_product_id_field) {
  // Caso: pedido_items guarda un id de producto (id_producto, producto_id, idp, etc.)
  // Buscamos cuál es exactamente el nombre de campo a usar
  $possibleFields = ['id_producto','producto_id','idp'];
  $productIdField = null;
  foreach($possibleFields as $f) {
    if (in_array($f, $cols)) { $productIdField = $f; break; }
  }
  // Consulta agrupada por producto (por cantidad o por valor)
  if ($tipo == "ventas") {
    $query = "
      SELECT productos.namep AS producto, SUM(pedido_items.cantidad * productos.precio) AS total_ventas
      FROM pedido_items
      JOIN pedidos ON pedidos.id_pedido = pedido_items.id_pedido
      JOIN productos ON productos.idp = pedido_items.$productIdField
      $where
      GROUP BY productos.namep
      ORDER BY total_ventas DESC
    ";
  } else {
    $query = "
      SELECT productos.namep AS producto, SUM(pedido_items.cantidad) AS cantidad
      FROM pedido_items
      JOIN pedidos ON pedidos.id_pedido = pedido_items.id_pedido
      JOIN productos ON productos.idp = pedido_items.$productIdField
      $where
      GROUP BY productos.namep
      ORDER BY cantidad DESC
    ";
  }

} elseif ($has_producto_nombre) {
  // Caso: pedido_items guarda el nombre del producto en 'producto_nombre'
  $productoNombreField = in_array('producto_nombre', $cols) ? 'producto_nombre' : (in_array('producto_name', $cols) ? 'producto_name' : 'producto_nombre');

  if ($tipo == "ventas") {
    $query = "
      SELECT productos.namep AS producto, SUM(pedido_items.cantidad * productos.precio) AS total_ventas
      FROM pedido_items
      JOIN pedidos ON pedidos.id_pedido = pedido_items.id_pedido
      JOIN productos ON productos.namep = pedido_items.$productoNombreField
      $where
      GROUP BY productos.namep
      ORDER BY total_ventas DESC
    ";
  } else {
    $query = "
      SELECT productos.namep AS producto, SUM(pedido_items.cantidad) AS cantidad
      FROM pedido_items
      JOIN pedidos ON pedidos.id_pedido = pedido_items.id_pedido
      JOIN productos ON productos.namep = pedido_items.$productoNombreField
      $where
      GROUP BY productos.namep
      ORDER BY cantidad DESC
    ";
  }

} else {
  // No se reconoce la relación; devolvemos vacío con mensaje en consola (para debug)
  echo json_encode([
    "barras" => [],
    "totalVentas" => 0,
    "totalProductos" => 0,
    "error" => "No se pudo detectar relación entre pedido_items y productos. Campos encontrados: " . implode(',', $cols)
  ]);
  $conn->close();
  exit;
}

// Ejecutar la consulta preparada
$result = $conn->query($query);
if (!$result) {
  // Si hay error en la consulta, devolver mensaje de depuración (no sensible)
  echo json_encode([
    "barras" => [],
    "totalVentas" => 0,
    "totalProductos" => 0,
    "error" => "Error en consulta: " . $conn->error,
    "query" => $query
  ]);
  $conn->close();
  exit;
}

$barras = [];
$totalVentas = 0;
$totalProductos = 0;

while ($row = $result->fetch_assoc()) {
  if ($tipo == "ventas") {
    $valor = (float)$row["total_ventas"];
    $totalVentas += $valor;
  } else {
    $valor = (int)$row["cantidad"];
    $totalProductos += $valor;
  }

  $barras[] = [
    "etiqueta" => $row["producto"],
    "valor" => $valor
  ];
}

echo json_encode([
  "barras" => $barras,
  "totalVentas" => $totalVentas,
  "totalProductos" => $totalProductos
]);

$conn->close();
?>
