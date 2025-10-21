<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../conexion.php';

try {
    // Obtener categoría del query string si existe
    $categoria = isset($_GET['categoria']) ? strtolower(trim($_GET['categoria'])) : null;
    // Obtener término de búsqueda si existe
    $busqueda = isset($_GET['buscar']) ? strtolower(trim($_GET['buscar'])) : null;

    $where = [];
    $params = [];

    // Construir la condición WHERE basada en los filtros
    if ($categoria && $categoria !== 'categorías') {
        $where[] = "LOWER(categoria) LIKE :categoria";
        $params['categoria'] = '%' . $categoria . '%';
    }

    if ($busqueda) {
        $where[] = "(LOWER(nombre) LIKE :busqueda OR LOWER(descripcion) LIKE :busqueda)";
        $params['busqueda'] = '%' . $busqueda . '%';
    }

    // Construir la consulta SQL
    $sql = "SELECT id, nombre, descripcion, precio, imagen_url, categoria FROM productos";
    if (!empty($where)) {
        $sql .= " WHERE " . implode(" AND ", $where);
    }
    $sql .= " ORDER BY nombre ASC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Normalizar rutas de imagen
    foreach ($productos as &$p) {
        if (empty($p['imagen_url'])) {
            $p['imagen_url'] = null;
            continue;
        }

        if (preg_match('#^https?://#i', $p['imagen_url'])) {
            continue;
        }

        $orig = ltrim($p['imagen_url'], '/');
        $basename = basename($orig);
        $candidates = [
            '../img/' . $basename,
            'img/' . $basename,
            '../../img/' . $basename,
            '../Admin/img/' . $basename,
            '../../Admin/img/' . $basename
        ];

        $found = false;
        foreach ($candidates as $candidate) {
            $fsPath = realpath(__DIR__ . '/' . $candidate);
            if ($fsPath && file_exists($fsPath)) {
                $docRoot = realpath($_SERVER['DOCUMENT_ROOT']);
                $relativeWebPath = str_replace($docRoot, '', $fsPath);
                $relativeWebPath = str_replace('\\', '/', $relativeWebPath);
                $baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];
                $p['imagen_url'] = $baseUrl . $relativeWebPath;
                $found = true;
                break;
            }
        }

        if (!$found) {
            $p['imagen_url'] = null;
        }
    }

    echo json_encode([
        'success' => true,
        'data' => $productos
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Error al obtener productos: ' . $e->getMessage()
    ]);
}