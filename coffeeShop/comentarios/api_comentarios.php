<?php
// Evitar que errores/warnings de PHP se impriman en el JSON y lo rompan
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING); 
ini_set('display_errors', 0);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json; charset=utf-8');

// Ajusta esta ruta si es necesario (../../ o ../)
require_once __DIR__ . '/../../conexion.php'; 

$respuesta = ['success' => false, 'data' => [], 'error' => ''];

try {
    $metodo = $_SERVER['REQUEST_METHOD'];
    
    // Obtenemos el ID del usuario de la sesión (si existe)
    $id_usuario_logueado = isset($_SESSION['userid']) ? (int)$_SESSION['userid'] : null;

    if ($metodo === 'POST') {
        
        // 1. Obtenemos la acción de forma segura
        $action = $_POST['action'] ?? 'create';

        // 2. RECUPERACIÓN SEGURA DE VARIABLES (AQUÍ ESTÁ LA SOLUCIÓN)
        // Usamos 'isset' para preguntar si existe antes de leer.
        // Si no existe, le asignamos NULL automáticamente.
        
        $nombre = isset($_POST['nombre']) ? $_POST['nombre'] : 'Anónimo';
        $comentario = isset($_POST['comentario']) ? $_POST['comentario'] : '';
        
        // CORRECCIÓN DEL PARENT_ID:
        // Verificamos si existe Y si no está vacío. Si falla, asignamos NULL.
        $parent_id = (isset($_POST['parent_id']) && $_POST['parent_id'] !== '') 
                     ? (int)$_POST['parent_id'] 
                     : NULL;
        // ===============================================
        // === ACCIÓN 1: LIKE / UNLIKE ===
        // ===============================================
        if ($action === 'like' || $action === 'unlike') {
            
            if (empty($_POST['id_resena'])) {
                throw new Exception('Falta el ID de la reseña.');
            }
            $id_resena = (int)$_POST['id_resena'];
            
            // Lógica simple: sumar o restar
            // (Nota: Idealmente aquí verificarías si el usuario ya dio like en una tabla aparte 'resena_likes')
            if ($action === 'like') {
                $sql = "UPDATE resena SET likes = likes + 1 WHERE idr = ?";
            } else {
                $sql = "UPDATE resena SET likes = GREATEST(0, likes - 1) WHERE idr = ?";
            }

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id_resena);
            if (!$stmt->execute()) {
                throw new Exception('Error al actualizar like: ' . $stmt->error);
            }
            $stmt->close();
            
            $respuesta['success'] = true;
        }

        // ===============================================
        // === ACCIÓN 2: RESPONDER A UN COMENTARIO ===
        // ===============================================
        elseif ($action === 'reply') {

            // Recogemos datos de forma segura
            $nombre = $_POST['nombre'] ?? 'Anónimo';
            $comentario = $_POST['comentario'] ?? '';
            $parent_id = !empty($_POST['parent_id']) ? (int)$_POST['parent_id'] : null;

            if (empty($comentario)) { throw new Exception('El comentario no puede estar vacío.'); }
            if (!$parent_id) { throw new Exception('Falta el ID del comentario padre.'); }

            $sql = "INSERT INTO resena (nombre, comentario, parent_id, likes, userid, fecha) VALUES (?, ?, ?, 0, ?, NOW())";
            $stmt = $conn->prepare($sql);
            // "ssii": string, string, int, int
            $stmt->bind_param("ssii", $nombre, $comentario, $parent_id, $id_usuario_logueado);
            
            if (!$stmt->execute()) { throw new Exception('Error al guardar respuesta: ' . $stmt->error); }
            
            $nuevo_id = $conn->insert_id;
            
            // Buscamos la foto del usuario para devolverla al JS inmediatamente
            $avatar_src = 'assest/default-avatar.png'; // Valor por defecto si no tiene
            if ($id_usuario_logueado) {
                $stmt_av = $conn->prepare("SELECT profilescreen FROM usuarios WHERE userid = ?");
                $stmt_av->bind_param("i", $id_usuario_logueado);
                $stmt_av->execute();
                $res_av = $stmt_av->get_result();
                if ($row = $res_av->fetch_assoc()) {
                     // Si la ruta en BD es solo "foto.png", el JS le agrega "../images/"
                    $avatar_src = $row['profilescreen']; 
                }
                $stmt_av->close();
            }

            $respuesta['success'] = true;
            // Devolvemos el objeto completo para que JS lo pinte sin recargar
            $respuesta['data'] = [
                'idr' => $nuevo_id,
                'nombre' => $nombre,
                'comentario' => $comentario,
                'parent_id' => $parent_id,
                'calificacion' => null,
                'fecha' => date('Y-m-d H:i:s'),
                'imagen_url' => null,
                'etiquetas' => null,
                'likes' => 0,
                'userid' => $id_usuario_logueado,
                'profilescreen' => $avatar_src,
                'respuestas' => []
            ];
            $stmt->close();
        }

        // ===============================================
        // === ACCIÓN 3: CREAR COMENTARIO NUEVO (Padre) ===
        // ===============================================
        else {
            // Acción por defecto 'create'
            
            $nombre = $_POST['nombre'] ?? 'Anónimo';
            $comentario = $_POST['comentario'] ?? '';
            $calificacion = !empty($_POST['calificacion']) ? (int)$_POST['calificacion'] : 0;
            
            if (empty($comentario)) { throw new Exception('Escribe un comentario.'); }
            if ($calificacion < 1) { throw new Exception('Selecciona una calificación.'); }

            // Procesar Etiquetas
            $etiquetas = null;
            if (isset($_POST['etiquetas']) && is_array($_POST['etiquetas'])) {
                $etiquetas = implode(', ', $_POST['etiquetas']);
            }

            // Procesar Imagen
            $imagen_url = null; // Inicializamos en null para evitar errores
            
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
                $directorio = __DIR__ . '/../../uploads/comentarios/'; 
                if (!file_exists($directorio)) { mkdir($directorio, 0777, true); }
                
                $ext = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));
                if (!in_array($ext, ['jpg', 'png', 'jpeg', 'webp'])) {
                    throw new Exception('Formato de imagen no permitido.');
                }
                
                $nombre_archivo = uniqid() . '.' . $ext;
                $ruta_destino = $directorio . $nombre_archivo;
                
                if (move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_destino)) {
                    // Guardamos la ruta relativa para usarla en el src del img
                    $imagen_url = "uploads/comentarios/" . $nombre_archivo;
                }
            }

            $sql = "INSERT INTO resena (nombre, comentario, calificacion, imagen_url, etiquetas, likes, userid, fecha) VALUES (?, ?, ?, ?, ?, 0, ?, NOW())";
            $stmt = $conn->prepare($sql);
            // "ssissi": string, string, int, string(null), string(null), int
            $stmt->bind_param("ssissi", $nombre, $comentario, $calificacion, $imagen_url, $etiquetas, $id_usuario_logueado);
            
            if (!$stmt->execute()) { throw new Exception('Error al guardar comentario: ' . $stmt->error); }
            
            $respuesta['success'] = true;
            $respuesta['data'] = ['id' => $conn->insert_id];
            $stmt->close();
        }

    } elseif ($metodo === 'GET') {
        // ===============================================
        // === OBTENER LISTA Y ESTADÍSTICAS ===
        // ===============================================
        
        // Traemos también la foto de perfil (profilescreen) haciendo JOIN
        $sql_lista = "SELECT r.*, u.profilescreen 
                      FROM resena r
                      LEFT JOIN usuarios u ON r.userid = u.userid
                      ORDER BY r.fecha DESC";
        
        $resultado = $conn->query($sql_lista);
        if (!$resultado) { throw new Exception('Error BD: ' . $conn->error); }
        
        $todos = [];
        $padres = [];

        // 1. Convertir resultado a array indexado por ID
        while ($fila = $resultado->fetch_assoc()) {
            $fila['respuestas'] = []; // Preparamos array de hijos
            $todos[$fila['idr']] = $fila;
        }

        // 2. Armar el árbol (meter hijos dentro de padres)
        foreach ($todos as $id => &$com) {
            $pid = $com['parent_id'];
            if ($pid && isset($todos[$pid])) {
                // Es hijo, lo agregamos al array de respuestas del padre
                $todos[$pid]['respuestas'][] = &$com;
            } else {
                // Es padre (o huérfano), va a la lista principal
                if (!$pid) { // Solo si parent_id es null o 0
                    $padres[] = &$com;
                }
            }
        }
        
        // 3. Estadísticas (Promedio, conteos)
        // Solo contamos los padres (parent_id IS NULL o 0) para no inflar el promedio con respuestas
        $sql_stats = "SELECT 
                        COUNT(*) as total,
                        AVG(calificacion) as promedio,
                        SUM(calificacion=5) as c5,
                        SUM(calificacion=4) as c4,
                        SUM(calificacion=3) as c3,
                        SUM(calificacion=2) as c2,
                        SUM(calificacion=1) as c1
                      FROM resena 
                      WHERE (parent_id IS NULL OR parent_id = 0) AND calificacion > 0";
        
        $res_stats = $conn->query($sql_stats);
        $data_stats = $res_stats->fetch_assoc();

        $stats = [
            'total' => (int)$data_stats['total'],
            'promedio' => round((float)$data_stats['promedio'], 1),
            'conteo' => [
                '5' => (int)$data_stats['c5'],
                '4' => (int)$data_stats['c4'],
                '3' => (int)$data_stats['c3'],
                '2' => (int)$data_stats['c2'],
                '1' => (int)$data_stats['c1']
            ]
        ];

        $respuesta['success'] = true;
        $respuesta['data'] = [
            'lista_completa' => $padres, // Enviamos solo los padres (que ya contienen a sus hijos)
            'stats' => $stats
        ];
    }

} catch (Exception $e) {
    http_response_code(500); // Error del servidor
    $respuesta['success'] = false;
    $respuesta['error'] = $e->getMessage();
}

$conn->close();
echo json_encode($respuesta);
?>