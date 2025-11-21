<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../conexion.php'; 

// Verificar sesión
if (!isset($_SESSION['userid'])) {
    echo json_encode(['error' => 'No autorizado']);
    exit;
}

$id_usuario = $_SESSION['userid'];

try {
    // 1. BUSCAR LA FECHA DEL ÚLTIMO CORTE DE CAJA DE ESTE USUARIO
    $sql_ultimo_corte = "SELECT fecha_cierre FROM cortes_caja 
                         WHERE id_usuario_cierre = ? AND estado = 'cerrado' 
                         ORDER BY fecha_cierre DESC LIMIT 1";
    $stmt = $pdo->prepare($sql_ultimo_corte);
    $stmt->execute([$id_usuario]);
    $ultimo_corte = $stmt->fetch(PDO::FETCH_ASSOC);

    // Si hubo un corte previo, sumamos ventas desde esa fecha. Si no, desde el inicio de los tiempos.
    $fecha_inicio = $ultimo_corte ? $ultimo_corte['fecha_cierre'] : '2000-01-01 00:00:00'; 

    // 2. CALCULAR VENTAS Y PEDIDOS DEL TURNO ACTUAL
    // Usamos >= para incluir el pedido hecho justo al momento del corte.
    $sql_totales = "SELECT 
                        COUNT(*) as total_pedidos, 
                        COALESCE(SUM(total), 0) as total_ventas 
                    FROM pedidos 
                    WHERE userid = ? AND fecha_pedido >= ?"; // <-- CORRECCIÓN: CAMBIO A `>=`
    $stmt_totales = $pdo->prepare($sql_totales);
    $stmt_totales->execute([$id_usuario, $fecha_inicio]);
    $totales = $stmt_totales->fetch(PDO::FETCH_ASSOC);

    // 3. OBTENER LOS ÚLTIMOS 10 PEDIDOS (Lista inferior)
    $sql_lista = "SELECT id_pedido, estado, metodo_pago, DATE_FORMAT(fecha_pedido, '%h:%i %p') as hora 
                  FROM pedidos 
                  WHERE userid = ? AND fecha_pedido >= ? " ; // <-- CORRECCIÓN: CAMBIO A `>=`
    // Añadimos la ordenación y límite aquí directamente para que PHP no falle al concatenar
    $sql_lista .= "ORDER BY fecha_pedido DESC LIMIT 10"; 

    $stmt_lista = $pdo->prepare($sql_lista);
    $stmt_lista->execute([$id_usuario, $fecha_inicio]);
    $lista_pedidos = $stmt_lista->fetchAll(PDO::FETCH_ASSOC);

    // 4. OBTENER ALERTAS DE INVENTARIO (Stock < 10)
    $sql_stock = "SELECT namep, STOCK FROM productos WHERE STOCK < 10 ORDER BY STOCK ASC";
    $stmt_stock = $pdo->query($sql_stock);
    $alertas = $stmt_stock->fetchAll(PDO::FETCH_ASSOC);

    // Devolver todo en un JSON
    echo json_encode([
        'success' => true,
        'ventas_hoy' => $totales['total_ventas'],
        'conteo_pedidos' => $totales['total_pedidos'],
        'pedidos' => $lista_pedidos,
        'alertas' => $alertas
    ]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => 'Error de servidor: ' . $e->getMessage()]);
}
?>