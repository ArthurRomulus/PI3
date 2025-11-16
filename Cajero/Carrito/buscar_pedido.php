<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../conexion.php'; // <-- ¡RUTA CORREGIDA!

// 1. Leer el JSON que envía el carrito.js
$json = file_get_contents('php://input');
$data = json_decode($json, true);

$pedido_id = $data['pedido_id'] ?? null;

if (empty($pedido_id)) {
    echo json_encode(['success' => false, 'error' => 'No se proporcionó un ID de pedido.']);
    exit;
}

try {
    // 2. Buscar el pedido principal
    $sql_pedido = "SELECT estado, metodo_pago, total FROM pedidos WHERE id_pedido = ?";
    $stmt_pedido = $pdo->prepare($sql_pedido);
    $stmt_pedido->execute([$pedido_id]);
    $pedido = $stmt_pedido->fetch(PDO::FETCH_ASSOC);

    if (!$pedido) {
        echo json_encode(['success' => false, 'error' => 'Pedido no encontrado.']);
        exit;
    }

    // 3. Buscar los items del pedido y unirlos con la info del producto
    $sql_items = "SELECT 
                    i.id_producto AS idp,
                    i.cantidad AS qty,
                    i.precio_unitario AS price,
                    i.modificadores_desc AS desc_full,
                    pr.namep AS name,
                    pr.ruta_imagen AS imgSrc
                  FROM pedido_items i
                  JOIN productos pr ON i.id_producto = pr.idp
                  WHERE i.id_pedido = ?";
    
    $stmt_items = $pdo->prepare($sql_items);
    $stmt_items->execute([$pedido_id]);
    $items = $stmt_items->fetchAll(PDO::FETCH_ASSOC);

    // 4. Re-construir el formato del carrito que JS espera
    $items_formateados = [];
    foreach ($items as $item) {
        $desc_parts = explode(',', $item['desc_full'], 2);
        $size = trim($desc_parts[0]);
        $desc = isset($desc_parts[1]) ? trim($desc_parts[1]) : '';

        $items_formateados[] = [
            'idp' => $item['idp'],
            'name' => $item['name'],
            'price' => (float)$item['price'],
            'qty' => (int)$item['qty'],
            'size' => $size,
            'desc' => $desc,
            'imgSrc' => $item['imgSrc'],
            'cartKey' => $item['name'] . '|' . $size . '|' . $desc 
        ];
    }

    // 5. Devolver la respuesta completa
    echo json_encode([
        'success' => true,
        'pedido' => [
            'id_pedido' => $pedido_id,
            'estado' => $pedido['estado'],
            'metodo_pago' => $pedido['metodo_pago'],
            'total' => (float)$pedido['total'],
            'items' => $items_formateados
        ]
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false, 
        'error' => 'Error de base de datos: ' . $e->getMessage()
    ]);
}
?>