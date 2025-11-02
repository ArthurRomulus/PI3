<?php
session_start();
header('Content-Type: application/json; charset=UTF-8');

if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

function ok($data = []) {
  echo json_encode(['ok'=>true] + $data, JSON_UNESCAPED_UNICODE);
  exit;
}
function fail($msg = 'Error', $code = 400) {
  http_response_code($code);
  echo json_encode(['ok'=>false,'error'=>$msg], JSON_UNESCAPED_UNICODE);
  exit;
}

/** Normaliza la ruta de imagen a ../../images/ salvo que sea URL absoluta */
function resolve_img($raw) {
  $s = trim((string)($raw ?? ''));
  if ($s === '') return '../../images/placeholder.png';
  if (preg_match('~^https?://~i', $s)) return $s;
  if (strpos($s, '../../images/') === 0) return $s;
  // cuelga el archivo directamente de ../../images/
  return '../../images/' . ltrim($s, '/');
}

/** Calcula nombre, foto y precio_total desde DB, con overrides opcionales */
function fetch_product_info($idp, $saborIdOverride = null, $tamanoIdOverride = null) {
  require_once __DIR__ . '/db.php'; // debe definir $mysqli (mysqli)
  global $mysqli;
  if (!$mysqli || $mysqli->connect_errno) fail('DB no disponible', 500);

  $id = (int)$idp;

  // Traer base + defaults
  $sql = "SELECT 
            p.namep AS nombre,
            p.precio AS precio_base,
            p.ruta_imagen AS foto,
            p.sabor AS sabor_def,
            s.nombre_sabor AS sabor_nombre,
            s.precio_extra AS sabor_extra,
            s.tipo_modificador AS sabor_tipo,
            p.tamano_defecto AS tam_def,
            t.nombre_tamano AS tam_nombre,
            t.precio_aumento AS tam_extra
          FROM productos p
          LEFT JOIN sabores s ON p.sabor = s.id_sabor
          LEFT JOIN tamanos t ON p.tamano_defecto = t.tamano_id
          WHERE p.idp = ? LIMIT 1";
  if (!$stmt = $mysqli->prepare($sql)) fail('Error preparando consulta', 500);
  $stmt->bind_param('i', $id);
  if (!$stmt->execute()) fail('Error ejecutando consulta', 500);
  $row = $stmt->get_result()->fetch_assoc();
  $stmt->close();

  if (!$row) fail('Producto no encontrado', 404);

  $nombre   = $row['nombre'] ?? 'Producto';
  $foto     = resolve_img($row['foto'] ?? '');
  $precio   = (float)($row['precio_base'] ?? 0.0);

  // Usar defaults del producto
  $sabor = [
    'id'               => isset($row['sabor_def']) ? (int)$row['sabor_def'] : null,
    'nombre'           => $row['sabor_nombre'] ?? 'Sin Modificador',
    'precio_extra'     => isset($row['sabor_extra']) ? (float)$row['sabor_extra'] : 0.0,
    'tipo_modificador' => $row['sabor_tipo'] ?? null,
  ];
  $tamano = [
    'id'              => isset($row['tam_def']) ? (int)$row['tam_def'] : null,
    'nombre'          => $row['tam_nombre'] ?? 'Chico',
    'precio_aumento'  => isset($row['tam_extra']) ? (float)$row['tam_extra'] : 0.0,
  ];

  // Overrides si vienen
  if ($saborIdOverride !== null) {
    $sid = (int)$saborIdOverride;
    $stmt = $mysqli->prepare("SELECT id_sabor, nombre_sabor, precio_extra, tipo_modificador FROM sabores WHERE id_sabor = ?");
    $stmt->bind_param('i', $sid);
    if ($stmt->execute()) {
      $r = $stmt->get_result()->fetch_assoc();
      if ($r) {
        $sabor = [
          'id'               => (int)$r['id_sabor'],
          'nombre'           => $r['nombre_sabor'],
          'precio_extra'     => (float)$r['precio_extra'],
          'tipo_modificador' => $r['tipo_modificador'],
        ];
      }
    }
    $stmt->close();
  }

  if ($tamanoIdOverride !== null) {
    $tid = (int)$tamanoIdOverride;
    $stmt = $mysqli->prepare("SELECT tamano_id, nombre_tamano, precio_aumento FROM tamanos WHERE tamano_id = ?");
    $stmt->bind_param('i', $tid);
    if ($stmt->execute()) {
      $r = $stmt->get_result()->fetch_assoc();
      if ($r) {
        $tamano = [
          'id'              => (int)$r['tamano_id'],
          'nombre'          => $r['nombre_tamano'],
          'precio_aumento'  => (float)$r['precio_aumento'],
        ];
      }
    }
    $stmt->close();
  }

  $precio_total = $precio + ($sabor['precio_extra'] ?? 0) + ($tamano['precio_aumento'] ?? 0);

  return [
    'nombre'       => $nombre,
    'foto'         => $foto,
    'precio_total' => $precio_total,
    'sabor'        => $sabor,
    'tamano'       => $tamano,
  ];
}

$action = $_GET['action'] ?? null;

// Parseo JSON para POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $raw = file_get_contents('php://input');
  if ($raw) {
    $json = json_decode($raw, true);
    if (json_last_error() === JSON_ERROR_NONE) {
      $action = $json['action'] ?? $action;
      $_POST = $json + $_POST;
    }
  }
}
if (!$action) $action = 'list';

switch ($action) {

  case 'list': {
    $items = []; $total = 0.0;
    foreach ($_SESSION['cart'] as $it) {
      $precioUnit = (float)$it['precio'];
      $qty        = (int)$it['qty'];
      $sub        = $precioUnit * $qty;
      $total     += $sub;

      $items[] = [
        'id'       => $it['id'],
        'nombre'   => $it['nombre'],
        'precio'   => $precioUnit,
        'qty'      => $qty,
        'foto'     => resolve_img($it['foto'] ?? null),
        'subtotal' => $sub,
        // opcional: expone lo que guardamos
        'sabor'    => $it['sabor'] ?? null,
        'tamano'   => $it['tamano'] ?? null,
      ];
    }
    ok(['items'=>$items, 'total'=>$total]);
    break;
  }

  case 'add': {
    // Espera: id (obligatorio). Si no llegan nombre/precio, se consultan y se calcula precio_total.
    $id       = isset($_POST['id']) ? (string)$_POST['id'] : null;
    $qty      = isset($_POST['qty']) ? max(1, (int)$_POST['qty']) : 1;
    $nombre   = isset($_POST['nombre']) ? trim((string)$_POST['nombre']) : '';
    $precio   = isset($_POST['precio']) ? (float)$_POST['precio'] : 0.0; // app.js manda precio_total
    $foto     = isset($_POST['foto']) ? (string)$_POST['foto'] : null;

    // Overrides opcionales (por si luego los usas desde el front)
    $saborId  = isset($_POST['sabor_id']) && ctype_digit((string)$_POST['sabor_id']) ? (int)$_POST['sabor_id'] : null;
    $tamanoId = isset($_POST['tamano_id']) && ctype_digit((string)$_POST['tamano_id']) ? (int)$_POST['tamano_id'] : null;

    if (!$id) fail('ID faltante');

    // Si falta precio o es inválido, calcular desde DB con overrides
    $sabor = null; $tamano = null;
    if ($precio <= 0 || $nombre === '' || $foto === null) {
      $info   = fetch_product_info($id, $saborId, $tamanoId);
      $precio = $precio > 0 ? $precio : (float)$info['precio_total'];
      $nombre = $nombre !== '' ? $nombre : $info['nombre'];
      $foto   = $foto !== null ? $foto : $info['foto'];
      $sabor  = $info['sabor'];
      $tamano = $info['tamano'];
    } else {
      // Normaliza imagen y deja sabor/tamano nulos si no vienen
      $foto = resolve_img($foto);
    }

    if (!isset($_SESSION['cart'][$id])) {
      $_SESSION['cart'][$id] = [
        'id'     => $id,
        'nombre' => $nombre ?: 'Producto',
        'precio' => $precio, // unitario (total con extras)
        'qty'    => 0,
        'foto'   => $foto,
        'sabor'  => $sabor,
        'tamano' => $tamano,
      ];
    }
    $_SESSION['cart'][$id]['qty'] += $qty;

    ok(['message'=>'Añadido','id'=>$id]);
    break;
  }

  case 'update': {
    $id  = isset($_POST['id']) ? (string)$_POST['id'] : null;
    $qty = isset($_POST['qty']) ? (int)$_POST['qty'] : null;
    if (!$id || $qty === null) fail('Parámetros faltantes');

    if (!isset($_SESSION['cart'][$id])) ok(['message'=>'Nada que actualizar']);
    if ($qty <= 0) { unset($_SESSION['cart'][$id]); ok(['message'=>'Eliminado','id'=>$id]); }

    $_SESSION['cart'][$id]['qty'] = $qty;
    ok(['message'=>'Actualizado','id'=>$id,'qty'=>$qty]);
    break;
  }

  case 'clear': {
    $_SESSION['cart'] = [];
    ok(['message'=>'Carrito vacío']);
    break;
  }

  case 'count': {
    $count = 0;
    foreach ($_SESSION['cart'] as $it) { $count += (int)$it['qty']; }
    ok(['count'=>$count]);
    break;
  }

  default:
    fail('Acción no soportada', 404);
}
