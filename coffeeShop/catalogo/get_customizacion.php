<?php
// get_customizacion.php
header('Content-Type: application/json; charset=UTF-8');

mysqli_report(MYSQLI_REPORT_OFF);
function jfail($msg, $detail = null, $code = 500) {
  http_response_code($code);
  echo json_encode(['ok'=>false,'error'=>$msg,'detail'=>$detail], JSON_UNESCAPED_UNICODE);
  exit;
}

require "../../conexion.php";
if (!isset($conn) || !($conn instanceof mysqli)) jfail("DB no inicializada", "conexion.php no creó \$conn");

$idp = isset($_GET['idp']) ? (int)$_GET['idp'] : 0;
if ($idp <= 0) jfail("idp inválido", null, 400);

// 1) Categorías del producto
$cats = '';
$sqlCat = "SELECT GROUP_CONCAT(c.nombrecategoria SEPARATOR ',') AS cats
           FROM producto_categorias pc
           JOIN categorias c ON c.id_categoria = pc.id_categoria
           WHERE pc.idp = ?";
if ($st = $conn->prepare($sqlCat)) {
  $st->bind_param("i", $idp);
  if ($st->execute()) {
    $cats = (string)($st->get_result()->fetch_assoc()['cats'] ?? '');
  }
  $st->close();
}

// 2) Tamaños disponibles (ajusta si usas producto_tamano)
$tamanos = [];
$qTm = "SELECT t.id_tamano AS id, t.nombre, t.precio_extra
        FROM tamanos t
        WHERE t.activo = 1
        ORDER BY t.orden ASC, t.id_tamano ASC";
if ($rs = $conn->query($qTm)) {
  while($r = $rs->fetch_assoc()){
    $tamanos[] = [
      'id' => (int)$r['id'],
      'nombre' => $r['nombre'],
      'precio_extra' => (float)$r['precio_extra']
    ];
  }
}

// 3) Grupos de opciones según categoría
$cats_lc = mb_strtolower($cats, 'UTF-8');
$groups = [];

$esCafe      = (strpos($cats_lc,'café')!==false || strpos($cats_lc,'cafe')!==false);
$esTe        = (strpos($cats_lc,'té')!==false   || preg_match('/\bte\b/u',$cats_lc));
$esLimonada  = (strpos($cats_lc,'limonada')!==false);
$esComida    = (strpos($cats_lc,'panini')!==false || strpos($cats_lc,'pan')!==false || strpos($cats_lc,'ensalada')!==false);

// A) Bebidas: Café => LECHES (single)
if ($esCafe) {
  $items = [];
  if ($rs = $conn->query("SELECT id_leche AS id, nombre FROM leches WHERE activo=1 ORDER BY orden ASC")) {
    while($r=$rs->fetch_assoc()) $items[] = ['id'=>(int)$r['id'], 'nombre'=>$r['nombre']];
  }
  $groups[] = ['key'=>'leche','label'=>'Leche','type'=>'single','items'=>$items];
}

// B) Té => SABOR (single)
if ($esTe) {
  // Si tienes tabla sabores_tea, úsala; aquí un fallback fijo:
  $items = [
    ['id'=>101,'nombre'=>'Manzanilla'],
    ['id'=>102,'nombre'=>'Limón'],
    ['id'=>103,'nombre'=>'Camellia'],
    ['id'=>104,'nombre'=>'Té verde'],
    ['id'=>105,'nombre'=>'Hierbabuena'],
  ];
  $groups[] = ['key'=>'sabor','label'=>'Sabor','type'=>'single','items'=>$items];
}

// C) Limonadas => SABOR (single)
if ($esLimonada) {
  $items = [
    ['id'=>201,'nombre'=>'Limón'],
    ['id'=>202,'nombre'=>'Fresa'],
    ['id'=>203,'nombre'=>'Piña'],
    ['id'=>204,'nombre'=>'Pepino'],
  ];
  $groups[] = ['key'=>'sabor','label'=>'Sabor','type'=>'single','items'=>$items];
}

// D) Comida => VERDURAS (multi) filtradas por productos_verduras
if ($esComida) {
  $items = [];
  // Solo verduras permitidas para este producto:
  $qV = "SELECT v.id_verdura AS id, v.nombre
         FROM productos_verduras pv
         JOIN verduras v ON v.id_verdura = pv.id_verdura
         WHERE pv.idp = ? AND v.activo = 1
         ORDER BY v.orden ASC, v.id_verdura ASC";
  if ($st = $conn->prepare($qV)) {
    $st->bind_param("i", $idp);
    if ($st->execute()) {
      $rs = $st->get_result();
      while($r=$rs->fetch_assoc()){
        $items[] = ['id'=>(int)$r['id'], 'nombre'=>$r['nombre']];
      }
    }
    $st->close();
  }
  // Si por alguna razón no hay filas, puedes optar por no crear el grupo o dar fallback:
  // if (empty($items)) { /* opcional: fallback */ }
  if (!empty($items)) {
    $groups[] = ['key'=>'verduras','label'=>'Verduras','type'=>'multi','items'=>$items];
  }
}

// 4) Variantes por leche (opcional, si usas esa tabla)
$variantes = [];
$sqlVar = "SELECT pv.idp_variante, pv.id_leche, p.namep AS nombre
           FROM producto_variantes pv
           JOIN productos p ON p.idp = pv.idp_variante
           WHERE pv.idp_base = ?";
if ($st = $conn->prepare($sqlVar)) {
  $st->bind_param("i", $idp);
  if ($st->execute()) {
    $resV = $st->get_result();
    while($r=$resV->fetch_assoc()){
      $variantes[(int)$r['id_leche']] = [
        'idp_variante' => (int)$r['idp_variante'],
        'nombre' => $r['nombre']
      ];
    }
  }
  $st->close();
}

echo json_encode([
  'ok'=>true,
  'categoriaTags'=>$cats,
  'tamanos'=>$tamanos,
  'groups'=>$groups,
  'variantes'=>$variantes
], JSON_UNESCAPED_UNICODE);
