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

if ($periodo == "1semana") {
  $desde = date("Y-m-d", strtotime("-7 days"));
  $where .= " AND fecha_pedido BETWEEN '$desde' AND '$hoy'";
} elseif ($periodo == "1mes") {
  $desde = date("Y-m-d", strtotime("-30 days"));
  $where .= " AND fecha_pedido BETWEEN '$desde' AND '$hoy'";
} elseif ($periodo == "personalizado" && $inicio && $fin) {
  $where .= " AND fecha_pedido BETWEEN '$inicio' AND '$fin'";
}

if ($categoria != "todo") {
  $where .= " AND id_categoria = '$categoria'";
}

// Agrupamos por día
$query = "
  SELECT DATE(fecha_pedido) AS fecha, SUM(total) AS total_ventas
  FROM pedidos
  $where
  GROUP BY DATE(fecha_pedido)
  ORDER BY fecha ASC
";

$result = $conn->query($query);

$barras = [];
$totalVentas = 0;
$totalProductos = 0;

while ($row = $result->fetch_assoc()) {
  $fecha = $row["fecha"];
  $valor = ($tipo == "ventas") ? (float)$row["total_ventas"] : 0;

  if ($tipo == "conteo") {
    // Contar productos vendidos ese día
    $queryItems = "
      SELECT SUM(pedido_items.cantidad) AS cantidad
      FROM pedido_items
      JOIN pedidos ON pedidos.id_pedido = pedido_items.id_pedido
      WHERE DATE(pedidos.fecha_pedido) = '$fecha'
    ";
    $r2 = $conn->query($queryItems);
    $valor = (int)$r2->fetch_assoc()["cantidad"];
    $totalProductos += $valor;
  }

  if ($tipo == "ventas") $totalVentas += $valor;

  $barras[] = [
    "etiqueta" => $fecha,
    "valor" => $valor
  ];
}

echo json_encode([
  "barras" => $barras,
  "totalVentas" => $totalVentas,
  "totalProductos" => $totalProductos
]);
?>
