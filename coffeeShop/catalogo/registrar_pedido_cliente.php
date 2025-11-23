<?php
// archivo: /coffeShop/catalogo/registrar_pedido_cliente.php
session_start();
header('Content-Type: application/json; charset=utf-8');

// Ajusta estas rutas según tu estructura real
require_once '../../Cajero/conexion.php'; 
require_once '../../Cajero/lib/init.php'; 

// Configuración de Stripe
\Stripe\Stripe::setApiKey('sk_test_51SSiVI6xsnAsFl7HlMG6YEB8IaTSZie2e0XZa8slFN0PyT2LLBNTYfjMtIKXxZhC3LoTxO7b5qiUTATeVpW7Ym0p00ahJSYYyK');

// Leer JSON del frontend (solo esperamos el token)
$json = file_get_contents('php://input');
$data = json_decode($json, true);
$tokenStripe = $data['token'] ?? null;

// Leer carrito de la sesión
$cart = $_SESSION['cart'] ?? [];

if (empty($cart)) {
    echo json_encode(['success' => false, 'error' => 'El carrito está vacío o expiró.']);
    exit;
}

// Calcular total desde el backend (más seguro)
$totalGeneral = 0;
foreach ($cart as $item) {
    $totalGeneral += (float)$item['precio'] * (int)$item['qty'];
}

try {
    $pdo->beginTransaction();

    // 1. Procesar Pago con Stripe
    if (!$tokenStripe) throw new Exception("Falta el token de pago.");

    $cargo = \Stripe\Charge::create([
        'amount' => (int)($totalGeneral * 100), // Centavos
        'currency' => 'mxn',
        'description' => 'Compra Online Coffee-Shop',
        'source' => $tokenStripe,
    ]);

    if ($cargo->status !== 'succeeded') {
        throw new Exception("Pago fallido: " . $cargo->failure_message);
    }

    // 2. Insertar Pedido
    // Como es cliente online, el userid puede ser NULL o un ID genérico si no hay login.
    // Si tienes login de cliente, usa $_SESSION['cliente_id']. Si no, usa NULL.
    $id_usuario = isset($_SESSION['userid']) ? $_SESSION['userid'] : null; 
    // OJO: Si tu tabla 'pedidos' requiere userid NOT NULL, asigna un ID de "usuario web" o ajusta la tabla.

    $sql_pedido = "INSERT INTO pedidos (userid, total, estado, metodo_pago, tipo_pedido, id_pago_stripe) 
                   VALUES (?, ?, 'Pendiente', 'Tarjeta', 'Online', ?)";
    $stmt = $pdo->prepare($sql_pedido);
    $stmt->execute([$id_usuario, $totalGeneral, $cargo->id]);
    $id_pedido = $pdo->lastInsertId();

    // 3. Insertar Items
    $sql_item = "INSERT INTO pedido_items (id_pedido, id_producto, cantidad, precio_unitario, modificadores_desc) 
                 VALUES (?, ?, ?, ?, ?)";
    $stmt_item = $pdo->prepare($sql_item);

    // 4. Descontar Stock
    $sql_stock = "UPDATE productos SET STOCK = STOCK - ? WHERE idp = ?";
    $stmt_stock = $pdo->prepare($sql_stock);

    foreach ($cart as $id => $item) {
        $descripcion = ($item['nombre'] ?? '') . ' ' . ($item['tamano'] ?? '');
        
        // Insertar detalle
        $stmt_item->execute([
            $id_pedido,
            $item['id'],
            $item['qty'],
            $item['precio'],
            $descripcion
        ]);

        // Descontar stock
        $stmt_stock->execute([$item['qty'], $item['id']]);
    }

    $pdo->commit();
    
    // Limpiar carrito de sesión (opcional, ya lo hacemos en JS pero es bueno asegurar)
    $_SESSION['cart'] = [];

    echo json_encode(['success' => true, 'nuevoPedidoId' => $id_pedido]);

} catch (\Stripe\Exception\CardException $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'error' => $e->getError()->message]);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>