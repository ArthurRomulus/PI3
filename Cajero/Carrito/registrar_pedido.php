<?php
session_start(); // ¡¡MUY IMPORTANTE!! Debe ser la primera línea
header('Content-Type: application/json; charset=utf-8');

// 1. Conexión a la BD
require_once __DIR__ . '/../conexion.php'; 
// 2. Librería de Stripe
require_once __DIR__ . '/../lib/init.php'; 

// --- Configuración de Stripe ---
// ¡¡RECUERDA PONER TU CLAVE SECRETA AQUÍ!!
\Stripe\Stripe::setApiKey('sk_test_51SSiVI6xsnAsFl7HlMG6YEB8IaTSZie2e0XZa8slFN0PyT2LLBNTYfjMtIKXxZhC3LoTxO7b5qiUTATeVpW7Ym0p00ahJSYYyK');
// ------------------------------------

// 3. Leer el JSON que envía el carrito.js
$json = file_get_contents('php://input');
$data = json_decode($json, true);

$cart = $data['carrito'] ?? null;
$totalGeneral = $data['total'] ?? null;
$metodoPago = $data['metodo'] ?? 'Efectivo';
$tokenStripe = $data['token'] ?? null; 

// --- Validaciones ---
if (empty($cart)) {
    echo json_encode(['success' => false, 'error' => 'El carrito está vacío.']);
    exit;
}
if ($totalGeneral === null) {
    echo json_encode(['success' => false, 'error' => 'No se recibió el total.']);
    exit;
}

// 4. Iniciar Transacción de Base de Datos
try {
    $pdo->beginTransaction();

    // 5. Lógica de Pago
    $id_cargo_stripe = null;
    
    if ($metodoPago === 'Tarjeta') {
        if (empty($tokenStripe)) {
            throw new Exception("No se recibió un token de pago para la tarjeta.");
        }

        $montoEnCentavos = (int)($totalGeneral * 100);

        $cargo = \Stripe\Charge::create([
            'amount' => $montoEnCentavos,
            'currency' => 'mxn', 
            'description' => 'Pago de prueba TPV Blackwood',
            'source' => $tokenStripe,
        ]);
        
        if ($cargo->status == 'succeeded') {
            $id_cargo_stripe = $cargo->id; 
        } else {
            throw new Exception("El pago con tarjeta falló: " . $cargo->failure_message);
        }
    }

    // 6. Insertar en la tabla `pedidos`
    // --- ¡¡CORRECCIÓN CLAVE!! ---
    
    // Verificamos si el 'userid' está en la sesión (creado por tu Login)
    if (!isset($_SESSION['userid'])) {
        throw new Exception("Error de sesión. No se pudo identificar al cajero. Por favor, inicia sesión de nuevo.");
    }
    
    // Leemos el ID del cajero que ha iniciado sesión
    $id_cajero_actual = $_SESSION['userid']; 
    
    $sql_pedido = "INSERT INTO pedidos (userid, total, estado, metodo_pago, tipo_pedido, id_pago_stripe) 
                   VALUES (?, ?, 'Proceso', ?, 'En Local', ?)";
    
    $stmt_pedido = $pdo->prepare($sql_pedido);
    // Usamos el ID de la sesión en lugar de un número fijo
    $stmt_pedido->execute([$id_cajero_actual, $totalGeneral, $metodoPago, $id_cargo_stripe]); 
    // --------------------------------
    
    $id_pedido = $pdo->lastInsertId();

    // 7. Preparar la consulta para `pedido_items`
    $sql_item = "INSERT INTO pedido_items (id_pedido, id_producto, cantidad, precio_unitario, modificadores_desc) 
                 VALUES (?, ?, ?, ?, ?)";
    $stmt_item = $pdo->prepare($sql_item);

    // 8. Recorrer el carrito e insertar cada item
    foreach ($cart as $item) {
        $descripcion = $item['size'] . ', ' . $item['desc'];
        
        $stmt_item->execute([
            $id_pedido,
            $item['idp'], 
            $item['qty'],
            $item['price'],
            $descripcion
        ]);
    }

    // 9. Si todo salió bien, confirmamos la transacción
    $pdo->commit();

    // 10. Devolver respuesta de éxito
    echo json_encode([
        'success' => true, 
        'nuevoPedidoId' => $id_pedido
    ]);

} catch (\Stripe\Exception\CardException $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'error' => 'Pago rechazado: ' . $e->getError()->message]);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode([
        'success' => false, 
        'error' => 'Error: ' . $e->getMessage()
    ]);
}
?>