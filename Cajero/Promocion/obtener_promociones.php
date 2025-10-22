<?php
header('Content-Type: application/json; charset=utf-8');
// Cargar la conexión
require_once __DIR__ . '/../conexion.php';

try {
    // Ajusta el nombre de la tabla si en tu DB tiene otro nombre (promocion o promociones)
    $sql = "SELECT idPromo, nombrePromo, condiciones, imagen_url, tipo_descuento, valor_descuento, fechaInicio, fechaFin FROM promocion ORDER BY idPromo ASC";
    $stmt = $pdo->query($sql);
    $promos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Normalizar rutas de imagen si es necesario
    foreach ($promos as &$p) {
        // Si la imagen viene vacía, dejar null
        if (empty($p['imagen_url'])) {
            $p['imagen_url'] = null;
            continue;
        }

        // Si la ruta es una URL absoluta, dejarla tal cual
        if (preg_match('#^https?://#i', $p['imagen_url'])) {
            continue;
        }

        // Probar varias rutas candidatas en disco y usar la primera que exista.
        // Las rutas en la BD pueden venir como: 'Images/CafeAmer.png', '../../Images/CafeAmer.png', '../Images/...', etc.
        $orig = ltrim($p['imagen_url'], '/');
        $basename = basename($orig);
        $candidates = [
            $orig,
            '../' . $orig,
            '../../' . $orig,
            'Images/' . $orig,
            '../Images/' . $orig,
            '../../Images/' . $orig,
            // Admin-specific locations where the admin script writes images
            '../Admin/img/' . $basename,
            '../../Admin/img/' . $basename,
            'Admin/img/' . $basename,
            // Common admin folder variants
            '../Admin/' . $orig,
            '../../Admin/' . $orig,
            'Admin/' . $orig,
            // try img/ directly
            'img/' . $basename,
            '../img/' . $basename,
            '../../img/' . $basename,
        ];

        $found = false;
        foreach ($candidates as $candidate) {
            // Construir la ruta de archivo en el servidor desde este script
            $fsPath = realpath(__DIR__ . '/' . $candidate);
            if ($fsPath && file_exists($fsPath)) {
                // Convertir a ruta relativa web desde la carpeta Promocion (mantener la forma que funciona en el navegador)
                // Usaremos la misma cadena candidata para que el front-end pueda resolverla relativamente
                $p['imagen_url'] = $candidate;
                $found = true;
                break;
            }
        }

        if (!$found) {
            // Como fallback, intentar añadir ../../ delante (comportamiento anterior)
            $fallback = '../../' . $orig;
            $fsFallback = realpath(__DIR__ . '/' . $fallback);
            if ($fsFallback && file_exists($fsFallback)) {
                $p['imagen_url'] = $fallback;
            } else {
                // Si no existe en ningún sitio, devolver null para que el front no muestre imagen
                $p['imagen_url'] = null;
            }
        }
    }

    echo json_encode(['success' => true, 'data' => $promos], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Error al obtener promociones: ' . $e->getMessage()]);
}

?>
