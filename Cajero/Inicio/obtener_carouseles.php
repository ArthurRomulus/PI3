<?php
// Establecer el encabezado de contenido como JSON
header('Content-Type: application/json; charset=utf-8');

// 1. Incluir la conexión (RUTA CORREGIDA)
require_once __DIR__ . '/../conexion.php'; 

// Definir las categorías para cada carrusel
$bebidas_cats = ['Bebidas frias', 'Bebidas calientes', 'Cafés', 'Tés', 'Frappés'];
$comidas_cats = ['Comida', 'Postres', 'Panes'];

// Preparar el array de respuesta
$response = [
    'success' => false,
    'promociones' => [],
    'bebidas' => [],
    'comidas' => [],
    'error' => null
];

try {
    // 2. Obtener Promociones (CONSULTA CORREGIDA A LA TABLA 'promocion')
    // Asignamos columnas de 'promocion' a los nombres que el JS espera (nombre, descripcion, precio)
    $stmt_promo = $pdo->query("SELECT 
                                    idPromo, 
                                    nombrePromo AS nombre, 
                                    condiciones AS descripcion, 
                                    valor_descuento AS precio, 
                                    imagen_url 
                               FROM promocion 
                               WHERE activo = 1");
    $response['promociones'] = $stmt_promo->fetchAll(PDO::FETCH_ASSOC);

    // 3. Obtener Bebidas (COLUMNAS CORREGIDAS: idp, namep, ruta_imagen)
    $in_bebidas = str_repeat('?,', count($bebidas_cats) - 1) . '?';
    $sql_bebidas = "SELECT p.idp, p.namep AS nombre, p.descripcion, p.precio, p.ruta_imagen AS imagen_url 
                    FROM productos p
                    JOIN producto_categorias pc ON p.idp = pc.idp 
                    JOIN categorias c ON pc.id_categoria = c.id_categoria
                    WHERE c.nombrecategoria IN ($in_bebidas) AND p.STOCK > 0
                    GROUP BY p.idp"; 
    
    $stmt_bebidas = $pdo->prepare($sql_bebidas);
    $stmt_bebidas->execute($bebidas_cats);
    $response['bebidas'] = $stmt_bebidas->fetchAll(PDO::FETCH_ASSOC);

    // 4. Obtener Comidas (COLUMNAS CORREGIDAS: idp, namep, ruta_imagen)
    $in_comidas = str_repeat('?,', count($comidas_cats) - 1) . '?';
    $sql_comidas = "SELECT p.idp, p.namep AS nombre, p.descripcion, p.precio, p.ruta_imagen AS imagen_url 
                    FROM productos p
                    JOIN producto_categorias pc ON p.idp = pc.idp
                    JOIN categorias c ON pc.id_categoria = c.id_categoria
                    WHERE c.nombrecategoria IN ($in_comidas) AND p.STOCK > 0
                    GROUP BY p.idp";
    
    $stmt_comidas = $pdo->prepare($sql_comidas);
    $stmt_comidas->execute($comidas_cats);
    $response['comidas'] = $stmt_comidas->fetchAll(PDO::FETCH_ASSOC);
    
    $response['success'] = true;

} catch (PDOException $e) {
    // Manejo de errores
    $response['error'] = 'Error de base de datos: ' . $e->getMessage();
    $response['success'] = false;
}

// Enviar la respuesta JSON
echo json_encode($response);
?>