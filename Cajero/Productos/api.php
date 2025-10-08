<?php
header('Content-Type: application/json');
require '../conexion.php'; 

try {
    // --- OBTENER PRODUCTOS --- (Sin cambios)
$sql_productos = "SELECT idp, namep, ruta_imagen, precio, categoria, tamano_defecto FROM productos WHERE status = 1";    $sentencia_productos = $pdo->prepare($sql_productos);
    $sentencia_productos->execute();
    $productos = $sentencia_productos->fetchAll();

    // --- OBTENER MODIFICADORES --- (Sin cambios)
    $sql_sabores = "SELECT id_sabor, nombre_sabor, precio_extra, tipo_modificador FROM sabores";
    $sentencia_sabores = $pdo->prepare($sql_sabores);
    $sentencia_sabores->execute();
    $sabores_todos = $sentencia_sabores->fetchAll();
    
    // --- LÓGICA MEJORADA PARA SEPARAR MODIFICADORES ---
    $leches = [];
    $basesCafe = []; // Nueva categoría específica
    foreach ($sabores_todos as $sabor) {
        // Si el tipo es LECHE_VACA o LECHE_VEGETAL, va a la lista de leches
        if (strpos($sabor['tipo_modificador'], 'LECHE') !== false) {
            $leches[] = $sabor;
        } 
        // Si el tipo es BASE (para café), va a la lista de bases de café
        elseif ($sabor['tipo_modificador'] === 'BASE') {
            $basesCafe[] = $sabor;
        }
        // Otros tipos como 'TÉ' simplemente se ignoran por ahora
    }

    // --- OBTENER TAMAÑOS --- (Sin cambios)
    $sql_tamanos = "SELECT tamano_id, nombre_tamano, precio_aumento FROM tamanos";
    $sentencia_tamanos = $pdo->prepare($sql_tamanos);
    $sentencia_tamanos->execute();
    $tamanos = $sentencia_tamanos->fetchAll();

    // --- CONSTRUIR LA RESPUESTA CON LA NUEVA CATEGORÍA ---
    $respuesta = [
        "productos" => $productos,
        "modificadores" => [
            "leches" => $leches,
            "basesCafe" => $basesCafe // Usamos la nueva lista
        ],
        "tamanos" => $tamanos
    ];
    
    echo json_encode($respuesta);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Error en la base de datos: " . $e->getMessage()]);
}
?>