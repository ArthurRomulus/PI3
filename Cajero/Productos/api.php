<?php
header('Content-Type: application/json');
require '../conexion.php'; 

try {
    // --- OBTENER PRODUCTOS ---
    // 1. Consulta actualizada: Usamos "STOCK > 0" en lugar de "status = 1"
    $sql_productos = "SELECT idp, namep, ruta_imagen, precio, categoria, tamano_defecto 
                      FROM productos 
                      WHERE STOCK > 0";
    
    $sentencia_productos = $pdo->prepare($sql_productos);
    $sentencia_productos->execute();
    // Usamos FETCH_ASSOC para que sea más fácil de manejar
    $productos = $sentencia_productos->fetchAll(PDO::FETCH_ASSOC);

    // 2. NUEVA LÓGICA: Obtener las categorías para CADA producto
    // Preparamos una consulta que usaremos repetidamente
    $sql_categorias = "SELECT c.nombrecategoria 
                       FROM categorias c
                       JOIN producto_categorias pc ON c.id_categoria = pc.id_categoria
                       WHERE pc.idp = :idp";
    $sentencia_categorias = $pdo->prepare($sql_categorias);

    // Iteramos sobre cada producto (por referencia &) para añadirle sus categorías
    foreach ($productos as &$producto) {
        // Ejecutamos la consulta preparada con el ID del producto actual
        $sentencia_categorias->execute(['idp' => $producto['idp']]);
        $categorias_raw = $sentencia_categorias->fetchAll(PDO::FETCH_ASSOC);
        
        // Creamos un nuevo campo 'categorias_nombres' en el producto
        // Usamos array_column para obtener solo los nombres (ej: ['Frappés', 'Bebidas frias'])
        $producto['categorias_nombres'] = array_column($categorias_raw, 'nombrecategoria');
    }
    unset($producto); // Rompemos la referencia del bucle

    // --- OBTENER MODIFICADORES --- (Sin cambios)
    $sql_sabores = "SELECT id_sabor, nombre_sabor, precio_extra, tipo_modificador FROM sabores";
    $sentencia_sabores = $pdo->prepare($sql_sabores);
    $sentencia_sabores->execute();
    $sabores_todos = $sentencia_sabores->fetchAll();
    
    $leches = [];
    $basesCafe = [];
    foreach ($sabores_todos as $sabor) {
        if (strpos($sabor['tipo_modificador'], 'LECHE') !== false) {
            $leches[] = $sabor;
        } 
        elseif ($sabor['tipo_modificador'] === 'BASE') {
            $basesCafe[] = $sabor;
        }
    }

    // --- OBTENER TAMAÑOS --- (Sin cambios)
    $sql_tamanos = "SELECT tamano_id, nombre_tamano, precio_aumento FROM tamanos";
    $sentencia_tamanos = $pdo->prepare($sql_tamanos);
    $sentencia_tamanos->execute();
    $tamanos = $sentencia_tamanos->fetchAll(PDO::FETCH_ASSOC); // Usar FETCH_ASSOC es buena práctica

    // --- CONSTRUIR LA RESPUESTA ---
    $respuesta = [
        // "productos" ahora contiene la lista de categorías en 'categorias_nombres'
        "productos" => $productos, 
        "modificadores" => [
            "leches" => $leches,
            "basesCafe" => $basesCafe
        ],
        "tamanos" => $tamanos
    ];
    
    echo json_encode($respuesta);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Error en la base de datos: " . $e->getMessage()]);
}
?>