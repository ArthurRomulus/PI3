<?php
header('Content-Type: application/json; charset=utf-8');

// 1. RUTA CORREGIDA
require_once __DIR__ . '/../conexion.php'; 

try {
    $busqueda = isset($_GET['buscar']) ? strtolower(trim($_GET['buscar'])) : null;

    $where = [];
    $params = [];

    // 2. COLUMNAS CORREGIDAS (namep)
    if ($busqueda) {
        $where[] = "(LOWER(namep) LIKE :busqueda OR LOWER(descripcion) LIKE :busqueda)";
        $params['busqueda'] = '%' . $busqueda . '%';
    } else {
        echo json_encode(['success' => true, 'data' => []]);
        exit;
    }

    // 3. COLUMNAS CORREGIDAS (idp, namep, ruta_imagen) y alias para el JS
    $sql = "SELECT idp, namep AS nombre, descripcion, precio, ruta_imagen AS imagen_url, categoria 
            FROM productos";

    if (!empty($where)) {
        $sql .= " WHERE " . implode(" AND ", $where);
    }
    
    // 4. COLUMNA CORREGIDA (namep)
    $sql .= " ORDER BY namep ASC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'data' => $productos // El JS de búsqueda espera los productos dentro de 'data'
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Error al obtener productos: ' . $e->getMessage()
    ]);
}
?>