<?php
header('Content-Type: application/json; charset=utf-8');
// require_once __DIR__ . '/../conexion.php'; // <-- Ruta antigua e incorrecta
require_once __DIR__ . '/../conexion.php'; // <-- CAMBIO 2: Ruta corregida a la misma carpeta

$respuesta = ['success' => false, 'data' => [], 'error' => ''];

try {
    $metodo = $_SERVER['REQUEST_METHOD'];

    if ($metodo === 'POST') {
        
        // Comprobamos la 'action' para saber qué hacer
        $action = $_POST['action'] ?? 'create';

        // ===============================================
        // === ACCIÓN 1: CREAR UN "ME GUSTA" ===
        // ===============================================
        if ($action === 'like') {
            
            if (empty($_POST['id_resena'])) {
                throw new Exception('ID de reseña no proporcionado.');
            }
            $id_resena = (int)$_POST['id_resena'];

            $sql_like = "UPDATE resena SET likes = likes + 1 WHERE idr = ?";
            $stmt_like = $conn->prepare($sql_like);
            $stmt_like->bind_param("i", $id_resena);
            
            if (!$stmt_like->execute()) {
                throw new Exception('Error al guardar el like: ' . $stmt_like->error);
            }
            
            $stmt_like->close();
            $respuesta['success'] = true;

        } 
        // ===============================================
        // === CAMBIO 1: ACCIÓN 1.5: QUITAR UN "ME GUSTA" (NUEVO) ===
        // ===============================================
        elseif ($action === 'unlike') {
            
            if (empty($_POST['id_resena'])) {
                throw new Exception('ID de reseña no proporcionado.');
            }
            $id_resena = (int)$_POST['id_resena'];

            // Usamos GREATEST(0, ...) para evitar que los likes bajen de 0
            $sql_unlike = "UPDATE resena SET likes = GREATEST(0, likes - 1) WHERE idr = ?";
            $stmt_unlike = $conn->prepare($sql_unlike);
            $stmt_unlike->bind_param("i", $id_resena);
            
            if (!$stmt_unlike->execute()) {
                throw new Exception('Error al quitar el like: ' . $stmt_unlike->error);
            }
            
            $stmt_unlike->close();
            $respuesta['success'] = true;
        }
        // ===============================================
        // === ACCIÓN 2: CREAR UNA "RESPUESTA" ===
        // ===============================================
        elseif ($action === 'reply') {

            if (empty($_POST['nombre']) || empty($_POST['comentario']) || empty($_POST['parent_id'])) {
                throw new Exception('El nombre, comentario y parent_id son obligatorios.');
            }
            $nombre = $_POST['nombre'];
            $comentario = $_POST['comentario'];
            $parent_id = (int)$_POST['parent_id'];

            // Las respuestas no tienen calificación, imagen ni etiquetas
            $sql = "INSERT INTO resena (nombre, comentario, parent_id, likes) VALUES (?, ?, ?, 0)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssi", $nombre, $comentario, $parent_id);
            
            if (!$stmt->execute()) { throw new Exception('Error al guardar la respuesta: ' . $stmt->error); }
            
            // Devolvemos el comentario recién creado para que JS lo pinte
            $nuevo_id = $conn->insert_id;
            $respuesta['success'] = true;
            $respuesta['data'] = [
                'idr' => $nuevo_id,
                'nombre' => $nombre,
                'comentario' => $comentario,
                'parent_id' => $parent_id,
                'calificacion' => null,
                'fecha' => date('Y-m-d H:i:s'), // Fecha actual
                'imagen_url' => null,
                'etiquetas' => null,
                'likes' => 0
            ];
            $stmt->close();
        }
        // ===============================================
        // === ACCIÓN 3: CREAR UN "COMENTARIO" (Principal) ===
        // ===============================================
        else {
            
            if (empty($_POST['nombre']) || empty($_POST['comentario']) || empty($_POST['calificacion'])) {
                throw new Exception('El nombre, comentario y calificación son obligatorios.');
            }
            $nombre = $_POST['nombre'];
            $comentario = $_POST['comentario'];
            $calificacion = isset($_POST['calificacion']) ? (int)$_POST['calificacion'] : null;
            $imagen_url_db = null;
            $etiquetas_array = isset($_POST['etiquetas']) && is_array($_POST['etiquetas']) ? $_POST['etiquetas'] : [];
            $etiquetas = implode(', ', $etiquetas_array);
            if (empty($etiquetas)) { $etiquetas = null; }

            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
                // La ruta de subida también debe ser corregida
                $directorio_subidas = __DIR__ . '/../uploads/comentarios/'; // Asume que 'uploads' es hermano de 'Usuario'
                if (!file_exists($directorio_subidas)) { mkdir($directorio_subidas, 0777, true); }
                $nombre_archivo = uniqid() . '-' . basename($_FILES['imagen']['name']);
                $ruta_archivo = $directorio_subidas . $nombre_archivo;
                $tipo_archivo = strtolower(pathinfo($ruta_archivo, PATHINFO_EXTENSION));
                if (!in_array($tipo_archivo, ['jpg', 'png', 'jpeg'])) { throw new Exception('Solo JPG, JPEG, PNG.'); }
                if (move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_archivo)) {
                    // La URL para la BD debe ser relativa al HTML
                    $imagen_url_db = '../uploads/comentarios/' . $nombre_archivo; 
                } else { throw new Exception('Error al mover archivo.'); }
            }
            
            $sql = "INSERT INTO resena (nombre, comentario, calificacion, imagen_url, etiquetas, likes) VALUES (?, ?, ?, ?, ?, 0)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssiss", $nombre, $comentario, $calificacion, $imagen_url_db, $etiquetas); 
            
            if (!$stmt->execute()) { throw new Exception('Error al guardar: ' . $stmt->error); }
            $respuesta['success'] = true;
            $respuesta['data'] = ['id' => $conn->insert_id];
            $stmt->close();
        }

    } elseif ($metodo === 'GET') {
        // --- OBTENER DATOS (LÓGICA COMPLETAMENTE NUEVA) ---

        // 1. Obtener TODOS los comentarios
        $sql_lista = "SELECT idr, nombre, comentario, calificacion, fecha, imagen_url, etiquetas, likes, parent_id 
                      FROM resena 
                      ORDER BY fecha DESC";
        $resultado_lista = $conn->query($sql_lista);
        if (!$resultado_lista) { throw new Exception('Error lista: ' . $conn->error); }
        
        // 2. Construir un árbol anidado (comentarios padres con sus respuestas)
        $comentarios_planos = [];
        $comentarios_anidados = [];

        // Primero, creamos un mapa de todos los comentarios por su ID
        while ($fila = $resultado_lista->fetch_assoc()) {
            $fila['respuestas'] = []; // Añadimos un array para las respuestas
            $comentarios_planos[$fila['idr']] = $fila;
        }
        $resultado_lista->free();

        // Segundo, asignamos cada comentario a su padre
        foreach ($comentarios_planos as $idr => &$comentario) {
            // Si tiene un parent_id Y ese padre existe en nuestro mapa...
            if ($comentario['parent_id'] && isset($comentarios_planos[$comentario['parent_id']])) {
                // Lo añadimos al array 'respuestas' de su padre
                $comentarios_planos[$comentario['parent_id']]['respuestas'][] = &$comentario;
            } else {
                // Si no tiene padre, es un comentario principal
                $comentarios_anidados[] = &$comentario;
            }
        }
        unset($comentario); // Rompemos la referencia

        // 3. Obtener estadísticas (sin cambios)
        $sql_stats = "SELECT COUNT(idr) as total_resenas, AVG(calificacion) as promedio_calificacion,
                        SUM(CASE WHEN calificacion = 5 THEN 1 ELSE 0 END) as conteo_5,
                        SUM(CASE WHEN calificacion = 4 THEN 1 ELSE 0 END) as conteo_4,
                        SUM(CASE WHEN calificacion = 3 THEN 1 ELSE 0 END) as conteo_3,
                        SUM(CASE WHEN calificacion = 2 THEN 1 ELSE 0 END) as conteo_2,
                        SUM(CASE WHEN calificacion = 1 THEN 1 ELSE 0 END) as conteo_1
                    FROM resena WHERE calificacion BETWEEN 1 AND 5 AND parent_id IS NULL"; // <-- Solo contamos los padres
        $resultado_stats = $conn->query($sql_stats);
        if (!$resultado_stats) { throw new Exception('Error stats: ' . $conn->error); }
        $stats = $resultado_stats->fetch_assoc();
        $resultado_stats->free();
        $conteo_por_estrella = ['5'=>(int)$stats['conteo_5'],'4'=>(int)$stats['conteo_4'],'3'=>(int)$stats['conteo_3'],'2'=>(int)$stats['conteo_2'],'1'=>(int)$stats['conteo_1']];

        // 4. Combinar todo
        $respuesta['success'] = true;
        $respuesta['data'] = [
            'lista_completa' => $comentarios_anidados, // <-- Enviamos la lista ANIDADA
            'stats' => ['total' => (int)$stats['total_resenas'], 'promedio' => round((float)$stats['promedio_calificacion'], 1), 'conteo' => $conteo_por_estrella]
        ];

    } else {
        throw new Exception('Método no permitido');
    }

} catch (Exception $e) {
    http_response_code(500);
    $respuesta['error'] = $e->getMessage();
}

$conn->close();
echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);
?>