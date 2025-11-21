<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../conexion.php';

if (!isset($_SESSION['userid'])) {
    echo json_encode(['success' => false, 'error' => 'No hay sesión activa']);
    exit;
}

// Leer datos del formulario (JSON)
$json = file_get_contents('php://input');
$data = json_decode($json, true);

$fondo_inicial = $data['fondoInicial'] ?? 0;
$ventas_efectivo_user = $data['ventasEfectivo'] ?? 0;
$ventas_tarjeta_user = $data['ventasTarjeta'] ?? 0;
$gastos = $data['gastosRetiros'] ?? 0;
$conteo_real = $data['conteoReal'] ?? 0;
$id_usuario = $_SESSION['userid'];

try {
    $pdo->beginTransaction();

    // 1. Calcular el saldo ESPERADO según el sistema (Base de datos)
    // Buscamos ventas desde el último corte
    $sql_ultimo_corte = "SELECT fecha_cierre FROM cortes_caja 
                         WHERE id_usuario_cierre = ? AND estado = 'cerrado' 
                         ORDER BY fecha_cierre DESC LIMIT 1";
    $stmt = $pdo->prepare($sql_ultimo_corte);
    $stmt->execute([$id_usuario]);
    $ultimo_corte = $stmt->fetch(PDO::FETCH_ASSOC);
    // Si no hay corte, usamos el inicio del día
    $fecha_inicio = $ultimo_corte ? $ultimo_corte['fecha_cierre'] : date('Y-m-d 00:00:00'); 

    // Sumamos lo que el sistema cree que se vendió en efectivo
    $sql_sistema = "SELECT COALESCE(SUM(total), 0) as total_sistema 
                    FROM pedidos 
                    WHERE userid = ? AND fecha_pedido > ? AND metodo_pago = 'Efectivo'";
    $stmt_sis = $pdo->prepare($sql_sistema);
    $stmt_sis->execute([$id_usuario, $fecha_inicio]);
    $res_sistema = $stmt_sis->fetch(PDO::FETCH_ASSOC);
    $ventas_reales_sistema = $res_sistema['total_sistema'];

    // Formula: (Fondo Inicial + Ventas Efectivo Sistema) - Gastos
    $saldo_esperado = ($fondo_inicial + $ventas_reales_sistema) - $gastos;
    
    // Diferencia: Lo que el usuario tiene en la mano MENOS lo que debería tener
    $diferencia = $conteo_real - $saldo_esperado;

    // 2. Guardar el Corte en la tabla `cortes_caja`
    $sql_insert = "INSERT INTO cortes_caja 
                   (fecha_apertura, fecha_cierre, saldo_inicial, saldo_esperado, saldo_real_contado, diferencia, id_usuario_cierre, estado) 
                   VALUES 
                   (?, NOW(), ?, ?, ?, ?, ?, 'cerrado')";
    
    $stmt_insert = $pdo->prepare($sql_insert);
    $stmt_insert->execute([
        $fecha_inicio,      // fecha_apertura
        $fondo_inicial,
        $saldo_esperado,
        $conteo_real,
        $diferencia,
        $id_usuario
    ]);

    $pdo->commit();

    echo json_encode([
        'success' => true,
        'diferencia' => $diferencia,
        'mensaje' => 'Corte realizado con éxito'
    ]);

} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>