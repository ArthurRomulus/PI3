<?php
session_start();
header('Content-Type: application/json; charset=UTF-8');

if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

function ok($data = []) { echo json_encode(['ok'=>true] + $data); exit; }
function fail($msg = 'Error') { http_response_code(400); echo json_encode(['ok'=>false,'error'=>$msg]); exit; }

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

switch($action){

  case 'list': {
    $items = []; $total = 0;
    foreach($_SESSION['cart'] as $it){
      $sub = (float)$it['precio'] * (int)$it['qty'];
      $total += $sub;
      $items[] = [
        'id'=>$it['id'], 'nombre'=>$it['nombre'], 'precio'=>(float)$it['precio'],
        'qty'=>(int)$it['qty'], 'foto'=>$it['foto'] ?? null, 'subtotal'=>$sub
      ];
    }
    ok(['items'=>$items, 'total'=>$total]);
  }


  case 'add': {
  // Espera: id (obligatorio). Si no llegan nombre/precio, consulta en DB (mysqli).
  $id     = isset($_POST['id']) ? (string)$_POST['id'] : null;
  $qty    = isset($_POST['qty']) ? max(1, (int)$_POST['qty']) : 1;
  $nombre = isset($_POST['nombre']) ? trim((string)$_POST['nombre']) : '';
  $precio = isset($_POST['precio']) ? (float)$_POST['precio'] : 0.0;
  $foto   = isset($_POST['foto']) ? (string)$_POST['foto'] : null;

  if(!$id) fail('ID faltante');

  if($precio <= 0){
    require_once __DIR__ . '/db.php'; // Debe definir $mysqli
    if(!$mysqli || $mysqli->connect_errno){ fail('DB no disponible'); }

    $sql = "SELECT nombre_producto, precio_producto, foto_producto
            FROM productos WHERE id_producto = ? LIMIT 1";
    if(!$stmt = $mysqli->prepare($sql)) fail('Error preparando consulta');

    $stmt->bind_param('s', $id);
    if(!$stmt->execute()) fail('Error ejecutando consulta');

    $res = $stmt->get_result();
    $row = $res ? $res->fetch_assoc() : null;
    $stmt->close();

    if(!$row) fail('Producto no encontrado');
    $nombre = $nombre ?: ($row['nombre_producto'] ?? 'Producto');
    $precio = (float)($row['precio_producto'] ?? 0);
    $foto   = $foto ?: ($row['foto_producto'] ?? null);
    if($precio <= 0) fail('Precio inválido en catálogo');
  }

  if (!isset($_SESSION['cart'][$id])) {
    $_SESSION['cart'][$id] = [
      'id'=>$id, 'nombre'=>$nombre ?: 'Producto', 'precio'=>$precio, 'qty'=>0, 'foto'=>$foto
    ];
  }
  $_SESSION['cart'][$id]['qty'] += $qty;

  ok(['message'=>'Añadido','id'=>$id]);
}


  case 'update': {
    $id  = isset($_POST['id']) ? (string)$_POST['id'] : null;
    $qty = isset($_POST['qty']) ? (int)$_POST['qty'] : null;
    if(!$id || $qty===null) fail('Parámetros faltantes');

    if(!isset($_SESSION['cart'][$id])) ok(['message'=>'Nada que actualizar']);
    if($qty<=0){ unset($_SESSION['cart'][$id]); ok(['message'=>'Eliminado','id'=>$id]); }

    $_SESSION['cart'][$id]['qty'] = $qty;
    ok(['message'=>'Actualizado','id'=>$id,'qty'=>$qty]);
  }

  case 'clear': {
    $_SESSION['cart'] = [];
    ok(['message'=>'Carrito vacío']);
  }

  // Útil para el badge del icono
  case 'count': {
    $count = 0;
    foreach($_SESSION['cart'] as $it){ $count += (int)$it['qty']; }
    ok(['count'=>$count]);
  }

  default:
    fail('Acción no soportada');
}
