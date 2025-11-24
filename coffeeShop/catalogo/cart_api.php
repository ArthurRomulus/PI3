<?php
session_start();
header('Content-Type: application/json; charset=UTF-8');

// --- 1. CONEXIÓN GLOBAL AL INICIO ---
// Usamos la MISMA conexion.php que en catalogo.php
require_once __DIR__ . "/../../conexion.php";

// Aseguramos que exista $conn y lo aliasamos a $mysqli para reutilizar el código
if (!isset($conn) || !($conn instanceof mysqli)) {
    http_response_code(500);
    echo json_encode([
        'ok'    => false,
        'error' => 'Error de conexión: DB no inicializada desde conexion.php'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$mysqli = $conn; // 👉 ahora todo el código que usa $mysqli funciona igual que catalogo.php

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

function resolve_img($raw) {
  $s = trim((string)($raw ?? ''));
  if ($s === '') return '../../Images/placeholder.png';
  if (preg_match('~^https?://~i', $s)) return $s;
  if (strpos($s, '../../Images/') === 0) return $s;
  return '../../Images/' . ltrim($s, '/');
}

// --- FUNCIÓN DE STOCK (Usa la conexión global) ---
function get_real_stock($idp) {
    global $mysqli; // Usamos la conexión abierta arriba
    
    if (!$mysqli || $mysqli->connect_errno) return 9999; // Si falla DB, no bloqueamos venta

    $stmt = $mysqli->prepare("SELECT STOCK FROM productos WHERE idp = ?");
    if (!$stmt) return 9999;

    $stmt->bind_param('i', $idp);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    
    // Retornamos el stock o 0 si no existe
    return $res ? (int)$res['STOCK'] : 0;
}

function fetch_product_info($idp, $saborIdOverride = null, $tamanoIdOverride = null) {
  global $mysqli; // Usamos conexión global
  if (!$mysqli || $mysqli->connect_errno) fail('DB no disponible', 500);

  $id = (int)$idp;

  $sql = "SELECT p.namep AS nombre, p.precio AS precio_base, p.ruta_imagen AS foto, 
                 p.sabor AS sabor_def, s.nombre_sabor AS sabor_nombre, s.precio_extra AS sabor_extra, s.tipo_modificador AS sabor_tipo,
                 p.tamano_defecto AS tam_def, t.nombre_tamano AS tam_nombre, t.precio_aumento AS tam_extra
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

  $sabor = [
    'id' => isset($row['sabor_def']) ? (int)$row['sabor_def'] : null,
    'nombre' => $row['sabor_nombre'] ?? 'Sin Modificador',
    'precio_extra' => isset($row['sabor_extra']) ? (float)$row['sabor_extra'] : 0.0,
    'tipo_modificador' => $row['sabor_tipo'] ?? null,
  ];
  $tamano = [
    'id' => isset($row['tam_def']) ? (int)$row['tam_def'] : null,
    'nombre' => $row['tam_nombre'] ?? 'Chico',
    'precio_aumento' => isset($row['tam_extra']) ? (float)$row['tam_extra'] : 0.0,
  ];

  if ($saborIdOverride !== null) {
    $sid = (int)$saborIdOverride;
    $stmt = $mysqli->prepare("SELECT id_sabor, nombre_sabor, precio_extra, tipo_modificador FROM sabores WHERE id_sabor = ?");
    $stmt->bind_param('i', $sid);
    if ($stmt->execute()) {
      $r = $stmt->get_result()->fetch_assoc();
      if ($r) $sabor = [ 'id' => (int)$r['id_sabor'], 'nombre' => $r['nombre_sabor'], 'precio_extra' => (float)$r['precio_extra'], 'tipo_modificador' => $r['tipo_modificador'] ];
    }
    $stmt->close();
  }

  if ($tamanoIdOverride !== null) {
    $tid = (int)$tamanoIdOverride;
    $stmt = $mysqli->prepare("SELECT tamano_id, nombre_tamano, precio_aumento FROM tamanos WHERE tamano_id = ?");
    $stmt->bind_param('i', $tid);
    if ($stmt->execute()) {
      $r = $stmt->get_result()->fetch_assoc();
      if ($r) $tamano = [ 'id' => (int)$r['tamano_id'], 'nombre' => $r['nombre_tamano'], 'precio_aumento' => (float)$r['precio_aumento'] ];
    }
    $stmt->close();
  }

  $precio_total = $precio + ($sabor['precio_extra'] ?? 0) + ($tamano['precio_aumento'] ?? 0);

  return [ 'nombre' => $nombre, 'foto' => $foto, 'precio_total' => $precio_total, 'sabor' => $sabor, 'tamano' => $tamano ];
}

$action = $_GET['action'] ?? null;

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
        'id' => $it['id'], 'nombre' => $it['nombre'], 'precio' => $precioUnit, 'qty' => $qty,
        'foto' => resolve_img($it['foto'] ?? null), 'subtotal' => $sub,
        'sabor' => $it['sabor'] ?? null, 'tamano' => $it['tamano'] ?? null,
      ];
    }
    ok(['items'=>$items, 'total'=>$total]);
    break;
  }

  case 'add': {
    $id = isset($_POST['id']) ? (string)$_POST['id'] : null;
    $qty = isset($_POST['qty']) ? max(1, (int)$_POST['qty']) : 1;
    $nombre = isset($_POST['nombre']) ? trim((string)$_POST['nombre']) : '';
    $precio = isset($_POST['precio']) ? (float)$_POST['precio'] : 0.0;
    $foto = isset($_POST['foto']) ? (string)$_POST['foto'] : null;
    $tamanoFront = isset($_POST['tamano']) ? trim($_POST['tamano']) : null;

    if (!$id) fail('ID faltante');

    // --- VALIDACIÓN DE STOCK ---
    $stock = get_real_stock($id);
    $en_carrito = isset($_SESSION['cart'][$id]) ? (int)$_SESSION['cart'][$id]['qty'] : 0;
    
    if (($en_carrito + $qty) > $stock) {
        fail("No puedes agregar más. Solo hay $stock disponibles.");
    }
    // ---------------------------

    if ($precio <= 0 || $nombre === '' || $foto === null) {
      $info = fetch_product_info($id, null, null);
      $precio = $precio > 0 ? $precio : (float)$info['precio_total'];
      $nombre = $nombre !== '' ? $nombre : $info['nombre'];
      $foto = $foto !== null ? $foto : $info['foto'];
      $tamano = $tamanoFront ?: ($info['tamano']['nombre'] ?? 'Chico');
    } else {
      $foto = resolve_img($foto);
      $tamano = $tamanoFront ?: 'Chico';
    }

    if (!isset($_SESSION['cart'][$id])) {
      $_SESSION['cart'][$id] = [
        'id' => $id, 'nombre' => $nombre ?: 'Producto', 'precio' => $precio,
        'qty' => 0, 'foto' => $foto, 'tamano' => $tamano
      ];
    }

    $_SESSION['cart'][$id]['qty'] += $qty;
    ok(['message'=>'Añadido','id'=>$id]);
    break;
  }

  case 'update': {
    $id = isset($_POST['id']) ? (string)$_POST['id'] : null;
    $qty = isset($_POST['qty']) ? (int)$_POST['qty'] : null;
    if (!$id || $qty === null) fail('Parámetros faltantes');

    if (!isset($_SESSION['cart'][$id])) ok(['message'=>'Nada que actualizar']);
    
    // --- VALIDACIÓN STOCK (UPDATE) ---
    if ($qty > 0) {
        $stock = get_real_stock($id);
        if ($qty > $stock) fail("Límite de stock alcanzado ($stock).");
    }
    // --------------------------------

    if ($qty <= 0) { unset($_SESSION['cart'][$id]); ok(['message'=>'Eliminado','id'=>$id]); }
    else { $_SESSION['cart'][$id]['qty'] = $qty; }

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
?>
