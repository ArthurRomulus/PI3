<?php
// Este archivo es 'incluido' por api.php
// Ya tenemos acceso a la variable $pdo

try {
    // --- OBTENER PRODUCTOS ---
    $sql_productos = "SELECT idp, namep, ruta_imagen, precio, categoria, tamano_defecto 
                      FROM productos 
                      WHERE STOCK > 0";
    
    $sentencia_productos = $pdo->prepare($sql_productos);
    $sentencia_productos->execute();
    $productos = $sentencia_productos->fetchAll(PDO::FETCH_ASSOC);

    // --- OBTENER CATEGORÍAS (VERSIÓN CORREGIDA) ---
    // Usamos la columna 'categoria' que ya existe en la tabla 'productos'
    foreach ($productos as &$producto) {
        if (!empty($producto['categoria'])) {
            // Convertimos la categoría (ej: "Cafés") en el array 
            // que el JavaScript espera (ej: ["Cafés"])
            $producto['categorias_nombres'] = [$producto['categoria']];
        } else {
            // Si no tiene categoría, dejamos un array vacío
            $producto['categorias_nombres'] = [];
        }
    }
    unset($producto); // Rompemos la referencia del bucle

    // --- OBTENER MODIFICADORES ---
    // ✅ RESTAURADO: Usando la tabla 'sabores' como indicaste
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

    // --- OBTENER TAMAÑOS ---
    $sql_tamanos = "SELECT tamano_id, nombre_tamano, precio_aumento FROM tamanos";
    $sentencia_tamanos = $pdo->prepare($sql_tamanos);
    $sentencia_tamanos->execute();
    $tamanos = $sentencia_tamanos->fetchAll(PDO::FETCH_ASSOC);

    // --- CONSTRUIR LA RESPUESTA ---
    $respuesta = [
        "productos" => $productos, 
        "modificadores" => [
            "leches" => $leches,
            "basesCafe" => $basesCafe
        ],
        "tamanos" => $tamanos
    ];
    
    echo json_encode($respuesta);

} catch (PDOException $e) {
    // Lanzamos la excepción para que el 'catch' principal en api.php la reciba.
    throw $e;
}
?>