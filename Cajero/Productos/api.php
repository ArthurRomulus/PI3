<?php
header('Content-Type: application/json; charset=utf-8');
require '../conexion.php'; 

/*
Esta API SÍ utiliza tus nuevas tablas:
- listboxes (para 'Tipo Leche')
- listbox_opciones (para 'Entera', 'Almendra', etc.)
- producto_listbox (para vincular un producto con un listbox)
*/

try {
    $response = [
        'success' => false,
        'productos' => [],
        'tamanos' => [],
    ];

    // --- 1. OBTENER TODOS LOS TAMAÑOS (Sin cambios) ---
    $sql_tamanos = "SELECT tamano_id, nombre_tamano, precio_aumento FROM tamanos";
    $response['tamanos'] = $pdo->query($sql_tamanos)->fetchAll(PDO::FETCH_ASSOC);

    // --- 2. OBTENER PRODUCTOS (Sin cambios) ---
    $sql_productos = "SELECT idp, namep, ruta_imagen, precio, categoria, tamano_defecto 
                      FROM productos 
                      WHERE STOCK > 0";
    $productos = $pdo->query($sql_productos)->fetchAll(PDO::FETCH_ASSOC);

    // --- 3. PREPARAR CONSULTAS PARA MODIFICADORES (Lógica nueva) ---
    
    // Consulta para obtener las categorías de un producto
    $sql_categorias = "SELECT c.nombrecategoria 
                       FROM categorias c
                       JOIN producto_categorias pc ON c.id_categoria = pc.id_categoria
                       WHERE pc.idp = :idp";
    $stmt_categorias = $pdo->prepare($sql_categorias);

    // Consulta para obtener los GRUPOS (listboxes) de un producto
    $sql_grupos = "SELECT g.id, g.nombre
                   FROM listboxes g
                   JOIN producto_listbox pl ON g.id = pl.listbox_id
                   WHERE pl.producto_id = :idp
                   ORDER BY g.id ASC"; // Asegura un orden consistente
    $stmt_grupos = $pdo->prepare($sql_grupos);

    // Consulta para obtener las OPCIONES de un grupo (listbox)
    $sql_opciones = "SELECT id, valor, precio
                     FROM listbox_opciones
                     WHERE listbox_id = :id_grupo";
    $stmt_opciones = $pdo->prepare($sql_opciones);


    // --- 4. CONSTRUIR CADA PRODUCTO ---
    foreach ($productos as &$producto) { // Bucle por referencia
        
        // Añadir categorías (como tenías antes)
        $stmt_categorias->execute(['idp' => $producto['idp']]);
        $categorias_raw = $stmt_categorias->fetchAll(PDO::FETCH_ASSOC);
        $producto['categorias_nombres'] = array_column($categorias_raw, 'nombrecategoria');

        // Añadir modificadores (Lógica nueva)
        $producto['modificadores'] = []; // Inicializa el array de modificadores
        
        // Busca los grupos (listboxes) asignados a este producto
        $stmt_grupos->execute(['idp' => $producto['idp']]);
        $grupos = $stmt_grupos->fetchAll(PDO::FETCH_ASSOC);

        foreach ($grupos as $grupo) {
            // Para cada grupo (ej. "Tipo Leche"), busca sus opciones
            $stmt_opciones->execute(['id_grupo' => $grupo['id']]);
            $opciones = $stmt_opciones->fetchAll(PDO::FETCH_ASSOC);
            
            // Añade las opciones al grupo
            $grupo['opciones'] = $opciones;
            
            // Añade el grupo completo (con sus opciones) al producto
            $producto['modificadores'][] = $grupo;
        }
    }

    // Asignar los productos procesados a la respuesta
    $response['productos'] = $productos;
    $response['success'] = true;

    echo json_encode($response, JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'error' => 'Error en la API: ' . $e->getMessage(),
        'trace' => $e->getTraceAsString() // (Opcional: para depuración)
    ]);
}
?>