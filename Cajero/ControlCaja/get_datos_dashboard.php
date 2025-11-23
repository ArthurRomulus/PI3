<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../conexion.php'; 

if (!isset($_SESSION['userid'])) {
    echo json_encode(['error' => 'No autorizado']);
    exit;
}

$id_usuario = $_SESSION['userid'];

try {
    // 1. Fecha último corte
    $sql_ultimo_corte = "SELECT fecha_cierre FROM cortes_caja 
                         WHERE id_usuario_cierre = ? AND estado = 'cerrado' 
                         ORDER BY fecha_cierre DESC LIMIT 1";
    $stmt = $pdo->prepare($sql_ultimo_corte);
    $stmt->execute([$id_usuario]);
    $ultimo_corte = $stmt->fetch(PDO::FETCH_ASSOC);

    $fecha_inicio = $ultimo_corte ? $ultimo_corte['fecha_cierre'] : '2000-01-01 00:00:00'; 

    // 2. Totales
    $sql_totales = "SELECT COUNT(*) as total_pedidos, COALESCE(SUM(total), 0) as total_ventas 
                    FROM pedidos WHERE userid = ? AND fecha_pedido >= ?";
    $stmt_totales = $pdo->prepare($sql_totales);
    $stmt_totales->execute([$id_usuario, $fecha_inicio]);
    $totales = $stmt_totales->fetch(PDO::FETCH_ASSOC);

    // 3. Lista Pedidos
    $sql_lista = "SELECT id_pedido, estado, metodo_pago, 
                  DATE_FORMAT(fecha_pedido, '%h:%i %p') as hora,
                  TIMESTAMPDIFF(MINUTE, fecha_pedido, NOW()) as minutos
                  FROM pedidos WHERE userid = ? AND fecha_pedido >= ? 
                  ORDER BY fecha_pedido DESC LIMIT 10";
    $stmt_lista = $pdo->prepare($sql_lista);
    $stmt_lista->execute([$id_usuario, $fecha_inicio]);
    $lista_pedidos = $stmt_lista->fetchAll(PDO::FETCH_ASSOC);

    // 4. Alertas Stock
    $sql_stock = "SELECT namep, STOCK FROM productos WHERE STOCK < 10 ORDER BY STOCK ASC";
    $stmt_stock = $pdo->query($sql_stock);
    $alertas = $stmt_stock->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'ventas_hoy' => $totales['total_ventas'],
        'conteo_pedidos' => $totales['total_pedidos'],
        'pedidos' => $lista_pedidos,
        'alertas' => $alertas
    ]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>