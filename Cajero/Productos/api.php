<?php
header('Content-Type: application/json; charset=utf-8');

// RUTA CORREGIDA: Sale de 'Productos' para buscar 'conexion.php' en 'Cajero'
require '../conexion.php'; 

try {
    $response = [
        'success' => false,
        'productos' => [],
        'tamanos' => [],
    ];

    // 1. TAMAÑOS
    $sql_tamanos = "SELECT tamano_id, nombre_tamano, precio_aumento FROM tamanos";
    $response['tamanos'] = $pdo->query($sql_tamanos)->fetchAll(PDO::FETCH_ASSOC);

    // 2. PRODUCTOS (Con STOCK)
    $sql_productos = "SELECT idp, namep, ruta_imagen, precio, categoria, tamano_defecto, STOCK 
                      FROM productos 
                      WHERE STOCK > 0";
    $productos = $pdo->query($sql_productos)->fetchAll(PDO::FETCH_ASSOC);

    // 3. PREPARAR MODIFICADORES
    $sql_categorias = "SELECT c.nombrecategoria FROM categorias c JOIN producto_categorias pc ON c.id_categoria = pc.id_categoria WHERE pc.idp = :idp";
    $stmt_categorias = $pdo->prepare($sql_categorias);

    $sql_grupos = "SELECT g.id, g.nombre FROM listboxes g JOIN producto_listbox pl ON g.id = pl.listbox_id WHERE pl.producto_id = :idp ORDER BY g.id ASC";
    $stmt_grupos = $pdo->prepare($sql_grupos);

    $sql_opciones = "SELECT id, valor, precio FROM listbox_opciones WHERE listbox_id = :id_grupo";
    $stmt_opciones = $pdo->prepare($sql_opciones);

    // 4. ARMAR ESTRUCTURA
    foreach ($productos as &$producto) {
        // Categorías
        $stmt_categorias->execute(['idp' => $producto['idp']]);
        $categorias_raw = $stmt_categorias->fetchAll(PDO::FETCH_ASSOC);
        $producto['categorias_nombres'] = array_column($categorias_raw, 'nombrecategoria');

        // Modificadores
        $producto['modificadores'] = [];
        $stmt_grupos->execute(['idp' => $producto['idp']]);
        $grupos = $stmt_grupos->fetchAll(PDO::FETCH_ASSOC);

        foreach ($grupos as $grupo) {
            $stmt_opciones->execute(['id_grupo' => $grupo['id']]);
            $grupo['opciones'] = $stmt_opciones->fetchAll(PDO::FETCH_ASSOC);
            $producto['modificadores'][] = $grupo;
        }
    }

    $response['productos'] = $productos;
    $response['success'] = true;

    echo json_encode($response, JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>