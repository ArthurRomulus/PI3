<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// get_productos.php — con precio_total, sabores y tamanos
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

include "../../conexion.php";
$mysqli = $conn;

// --- Obtener todas las categorías de productos ---
$sql_categorias = "
SELECT DISTINCT
    CAST(c.id_categoria AS CHAR) AS categoria_id,
    c.nombrecategoria AS nombrecategoria
FROM categorias c
LEFT JOIN producto_categorias pc ON pc.id_categoria = c.id_categoria
LEFT JOIN productos p ON p.categoria = c.id_categoria
ORDER BY nombrecategoria ASC
";


$result_cats = $mysqli->query($sql_categorias);
$categorias = [];
if ($result_cats) {
    while ($row = $result_cats->fetch_assoc()) {
        $categorias[] = [
            'id'     => $row['categoria_id'],
            'nombre' => $row['nombrecategoria']
        ];
    }
}


// Filtros y opciones
$categoria = isset($_GET['categoria']) ? trim($_GET['categoria']) : '';
$q         = isset($_GET['q']) ? trim($_GET['q']) : '';
$page      = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$limit     = isset($_GET['limit']) ? max(1, min(100, (int)$_GET['limit'])) : 60;
$offset    = ($page - 1) * $limit;

// Overrides de combinación (aplican a TODOS los productos listados)
$saborOverrideId  = isset($_GET['sabor_id'])  && ctype_digit($_GET['sabor_id'])  ? (int)$_GET['sabor_id']  : null;
$tamanoOverrideId = isset($_GET['tamano_id']) && ctype_digit($_GET['tamano_id']) ? (int)$_GET['tamano_id'] : null;

// --- Pre-cargar overrides si vienen ---
$overrideSabor  = null;
$overrideTamano = null;

if ($saborOverrideId !== null) {
  $stmt = $mysqli->prepare("SELECT id_sabor, nombre_sabor, precio_extra, tipo_modificador FROM sabores WHERE id_sabor = ?");
  $stmt->bind_param('i', $saborOverrideId);
  if ($stmt->execute()) {
    $res = $stmt->get_result();
    $overrideSabor = $res->fetch_assoc() ?: null;
  }
  $stmt->close();
}
if ($tamanoOverrideId !== null) {
  $stmt = $mysqli->prepare("SELECT tamano_id, nombre_tamano, precio_aumento FROM tamanos WHERE tamano_id = ?");
  $stmt->bind_param('i', $tamanoOverrideId);
  if ($stmt->execute()) {
    $res = $stmt->get_result();
    $overrideTamano = $res->fetch_assoc() ?: null;
  }
  $stmt->close();
}

// --- SQL base con JOINs ---
$sql = "SELECT 
  p.idp,
  p.namep,
  p.ruta_imagen,
  p.precio,
  p.categoria AS categoria_principal_id,
  c.nombrecategoria AS categoria_principal_nombre,
  GROUP_CONCAT(DISTINCT c2.nombrecategoria) AS categorias_secundarias,
  p.sabor,
  s.nombre_sabor AS sabor_nombre,
  s.precio_extra AS sabor_precio_extra,
  s.tipo_modificador AS sabor_tipo_modificador,
  p.tamano_defecto,
  t.nombre_tamano AS tamano_nombre,
  t.precio_aumento AS tamano_precio_aumento,
  p.VENTAS,
  p.STOCK,
  p.descripcion
FROM productos p
LEFT JOIN categorias c 
       ON p.categoria = c.id_categoria
LEFT JOIN producto_categorias pc 
       ON p.idp = pc.idp
LEFT JOIN categorias c2 
       ON pc.id_categoria = c2.id_categoria
LEFT JOIN sabores s 
       ON p.sabor = s.id_sabor
LEFT JOIN tamanos t 
       ON p.tamano_defecto = t.tamano_id
WHERE 1=1
";




$params = [];
$types  = '';

// Filtro por categoría (acepta id o nombre)
if ($categoria !== '') {
  if (ctype_digit($categoria)) {
    $sql .= " AND (c.id_categoria = ? OR p.categoria = ?)";
    $params[] = (int)$categoria; $types .= 'i';
    $params[] = (int)$categoria; $types .= 'i';
  } else {
    $sql .= " AND (c.nombrecategoria = ? OR p.categoria = ?)";
    $params[] = $categoria; $types .= 's';
    $params[] = $categoria; $types .= 's';
  }
}

// Búsqueda libre
if ($q !== '') {
  if (ctype_digit($q)) {
    $sql .= " AND p.idp = ?";
    $params[] = (int)$q; $types .= 'i';
  } else {
    $like = "%$q%";
    $sql .= " AND (p.namep LIKE ? OR p.descripcion LIKE ? OR c.nombrecategoria LIKE ? OR s.nombre_sabor LIKE ?)";
    $params[] = $like; $types .= 's';
    $params[] = $like; $types .= 's';
    $params[] = $like; $types .= 's';
    $params[] = $like; $types .= 's';
  }
}

// Conteo total
$sql_count = "SELECT COUNT(*) AS total
              FROM productos p
              LEFT JOIN categorias c 
                ON (p.categoria = c.id_categoria OR p.categoria = c.nombrecategoria)
              LEFT JOIN sabores s 
                ON p.sabor = s.id_sabor
              LEFT JOIN tamanos t
                ON p.tamano_defecto = t.tamano_id
              WHERE 1=1";

$params_count = [];
$types_count  = '';
if ($categoria !== '') {
  if (ctype_digit($categoria)) {
    $sql_count .= " AND (c.id_categoria = ? OR p.categoria = ?)";
    $params_count[] = (int)$categoria; $types_count .= 'i';
    $params_count[] = (int)$categoria; $types_count .= 'i';
  } else {
    $sql_count .= " AND (c.nombrecategoria = ? OR p.categoria = ?)";
    $params_count[] = $categoria; $types_count .= 's';
    $params_count[] = $categoria; $types_count .= 's';
  }
}
if ($q !== '') {
  if (ctype_digit($q)) {
    $sql_count .= " AND p.idp = ?";
    $params_count[] = (int)$q; $types_count .= 'i';
  } else {
    $like = "%$q%";
    $sql_count .= " AND (p.namep LIKE ? OR p.descripcion LIKE ? OR c.nombrecategoria LIKE ? OR s.nombre_sabor LIKE ?)";
    $params_count[] = $like; $types_count .= 's';
    $params_count[] = $like; $types_count .= 's';
    $params_count[] = $like; $types_count .= 's';
    $params_count[] = $like; $types_count .= 's';
  }
}

// Orden y paginación
$sql .= " GROUP BY p.idp ORDER BY p.idp ASC";

// --- Ejecutar COUNT ---
$stmt_count = $mysqli->prepare($sql_count);
if (!$stmt_count) {
  http_response_code(500);
  echo json_encode(['ok'=>false,'error'=>'PREPARE_COUNT_FAILED','detail'=>$mysqli->error]);
  exit;
}
if ($types_count !== '') $stmt_count->bind_param($types_count, ...$params_count);
if (!$stmt_count->execute()) {
  http_response_code(500);
  echo json_encode(['ok'=>false,'error'=>'EXEC_COUNT_FAILED','detail'=>$stmt_count->error]);
  exit;
}
$total = (int)($stmt_count->get_result()->fetch_assoc()['total'] ?? 0);
$stmt_count->close();

// --- Ejecutar principal ---
$stmt = $mysqli->prepare($sql);
if (!$stmt) {
  http_response_code(500);
  echo json_encode(['ok'=>false,'error'=>'PREPARE_FAILED','detail'=>$mysqli->error]);
  exit;
}
if ($types !== '') $stmt->bind_param($types, ...$params);
if (!$stmt->execute()) {
  http_response_code(500);
  echo json_encode(['ok'=>false,'error'=>'EXEC_FAILED','detail'=>$stmt->error]);
  exit;
}

$res = $stmt->get_result();
$items = [];

while ($row = $res->fetch_assoc()) {
  // Casts base
  $idp          = (int)$row['idp'];
  $precioBase   = (float)$row['precio'];
  $ventas       = (int)$row['VENTAS'];
  $stock        = (int)$row['STOCK'];

  // Defaults del producto (desde JOIN)
  $saborDef = [
    'id'              => isset($row['sabor']) ? (int)$row['sabor'] : null,
    'nombre'          => $row['sabor_nombre'] ?? 'Sin Modificador',
    'precio_extra'    => isset($row['sabor_precio_extra']) ? (float)$row['sabor_precio_extra'] : 0.0,
    'tipo_modificador'=> $row['sabor_tipo_modificador'] ?? null,
  ];
  $tamanoDef = [
    'id'              => isset($row['tamano_defecto']) ? (int)$row['tamano_defecto'] : null,
    'nombre'          => $row['tamano_nombre'] ?? 'Chico',
    'precio_aumento'  => isset($row['tamano_precio_aumento']) ? (float)$row['tamano_precio_aumento'] : 0.0,
  ];

  // Aplicar overrides si vinieron por query
  $saborUsed  = $overrideSabor  ? [
    'id'              => (int)$overrideSabor['id_sabor'],
    'nombre'          => $overrideSabor['nombre_sabor'],
    'precio_extra'    => (float)$overrideSabor['precio_extra'],
    'tipo_modificador'=> $overrideSabor['tipo_modificador'],
  ] : $saborDef;

  $tamanoUsed = $overrideTamano ? [
    'id'              => (int)$overrideTamano['tamano_id'],
    'nombre'          => $overrideTamano['nombre_tamano'],
    'precio_aumento'  => (float)$overrideTamano['precio_aumento'],
  ] : $tamanoDef;

  // Calcular precio_total
  // Calcular precio_total
$precioTotal = $precioBase + ($saborUsed['precio_extra'] ?? 0) + ($tamanoUsed['precio_aumento'] ?? 0);

// Imagen placeholder si falta o ajustar ruta
$rutaImg = trim($row['ruta_imagen'] ?? '');
if ($rutaImg === '') {
  $rutaImg = '../../Images/placeholder.png';
} else if (!preg_match('~^https?://~i', $rutaImg)) {
  $rutaImg = str_replace('\\','/',$rutaImg);
  $rutaImg = preg_replace('~^(\./|(\.\./)+)~', '', $rutaImg);
  $rutaImg = preg_replace('~^(assest/|assets?/|images?/)+~i', '', $rutaImg);
  if (strpos($rutaImg, '../../Images/') !== 0) {
    $rutaImg = '../../Images/' . ltrim($rutaImg, '/');
  }
}


// Obtener listboxes del producto
$listboxes = [];
$qLB = $mysqli->prepare("
    SELECT lb.id AS id, lb.nombre AS nombre
    FROM producto_listbox pl
    INNER JOIN listboxes lb ON pl.listbox_id = lb.id
    WHERE pl.producto_id = ?
");
$qLB->bind_param('i', $idp);
$qLB->execute();
$resLB = $qLB->get_result();

while ($lb = $resLB->fetch_assoc()) {

    // Obtener opciones del listbox
    $qOps = $mysqli->prepare("
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

    $listboxes[] = [
        'id'       => (int)$lb['id'],
        'nombre'   => $lb['nombre'],
        'opciones' => $opciones
    ];

    $qOps->close();
}
$qLB->close();
error_log("LISTBOXES DEL PRODUCTO $idp => " . json_encode($listboxes, JSON_UNESCAPED_UNICODE));

// Categorías combinadas
$categoriasProd = [];

// Categoría principal
if (!empty($row['categoria_principal_nombre'])) {
    $categoriasProd[] = $row['categoria_principal_nombre'];
}

// Categorías secundarias (si existen)
if (!empty($row['categorias_secundarias'])) {
    $sec = explode(',', $row['categorias_secundarias']);
    $categoriasProd = array_merge($categoriasProd, $sec);
}

// Eliminar duplicados y limpiar espacios
$categoriasProd = array_unique(array_map('trim', $categoriasProd));


$items[] = [
  'idp'               => $idp,
  'namep'             => $row['namep'],
  'ruta_imagen'       => $rutaImg,
  'precio_base'       => $precioBase,
  'categorias' => array_map(fn($c) => ['nombre' => $c], $categoriasProd),
  'sabor'             => $saborUsed,   // objeto con id/nombre/precio_extra/tipo_modificador
  'tamano'            => $tamanoUsed,  // objeto con id/nombre/precio_aumento
  'precio_total'      => $precioTotal,
  'VENTAS'            => $ventas,
  'STOCK'             => $stock,
  'descripcion'       => $row['descripcion'],
  'listboxes' => $listboxes ?? [], 
];

}
$stmt->close();

echo json_encode([
  'ok'    => true,
  'page'  => $page,
  'limit' => $limit,
  'total' => $total,
  'items' => $items,
  'categorias'  => $categorias
], JSON_UNESCAPED_UNICODE);
