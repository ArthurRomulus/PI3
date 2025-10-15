<?php
// 1. INICIAMOS LA SESIÓN Y VERIFICAMOS SI EL USUARIO ESTÁ LOGUEADO
session_start();

// Si no existe la variable de sesión 'userid', lo redirigimos al login
if (!isset($_SESSION['userid'])) {
    // Asegúrate de que la ruta a tu login.php sea correcta
    header("Location: ../Login/login.php"); 
    exit();
}

// 2. INCLUIMOS LA CONEXIÓN A LA BASE DE DATOS
include '../../database.php';

// 3. OBTENEMOS LA INFORMACIÓN COMPLETA DEL USUARIO DESDE LA BD
$user_id = $_SESSION['userid'];
$user_data = null;

$sql = "SELECT username, email, profilescreen, role FROM usuarios WHERE userid = ?";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user_data = $result->fetch_assoc();
    }
    $stmt->close();
}

// Si por alguna razón no se encuentran los datos, mostramos un error o valores por defecto.
if ($user_data === null) {
    die("Error: No se pudo cargar la información del usuario.");
}

// 4. PREPARAMOS LOS DATOS PARA MOSTRARLOS
// Convertimos el número del rol a un texto legible
$role_name = 'Usuario'; // Valor por defecto
if ($user_data['role'] == 4) {
    $role_name = 'Administrador';
} elseif ($user_data['role'] == 2) {
    $role_name = 'Cajero';
}

// Usamos el nombre de pantalla (profilescreen) si existe, si no, el username
$display_name = !empty($user_data['email']) ? $user_data['email'] : $user_data['username'];
$user_email = $user_data['email'];
$user_phone = $user_data['telefono'] ?? 'No especificado'; // Muestra 'No especificado' si es NULL

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario</title>
    <link rel="stylesheet" href="../Admin_nav_bar.css">
    <link rel="stylesheet" href="Admin_perfil.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include '../Admin_nav_bar.php'; ?>

    <div class="content">
        <h1>Coffee Shop</h1>

        <div class="welcome-text">
            <h2>Bienvenido de nuevo, <?php echo htmlspecialchars($display_name); ?></h2>
        </div>

        <div class="profile-container">
            
            <div class="profile-info">
                <h3>Información del Usuario</h3>
                <p><strong>Nombre:</strong> <?php echo htmlspecialchars($display_name); ?></p>
                <p><strong>Correo:</strong> <?php echo htmlspecialchars($user_email); ?></p>
                <p><strong>Rol:</strong> <?php echo htmlspecialchars($role_name); ?></p>
                <button class="btn-edit" id="openEditModal">Editar Información</button>
            </div>
            <div class="profile-picture">
                <img src="../../Images/DefaultProfile.png" alt="Perfil Admin">
            </div>
        </div>

        <div id="editModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Editar Información</h2>
                <input type="text" placeholder="Nombre" value="<?php echo htmlspecialchars($display_name); ?>">
                <input type="email" placeholder="Correo" value="<?php echo htmlspecialchars($user_email); ?>">
                <input type="file" accept="image/*">
                <input type="text" placeholder="Teléfono" value="<?php echo htmlspecialchars($user_phone); ?>">
                <input type="text" placeholder="Rol" value="<?php echo htmlspecialchars($role_name); ?>" readonly> <button>Guardar Cambios</button>
            </div>
        </div>
    </div>

    <script>
        const editModal = document.getElementById('editModal');
        const openEditBtn = document.getElementById('openEditModal');
        const closeEditBtn = document.querySelector('#editModal .close');

        openEditBtn.onclick = () => editModal.style.display = 'flex';
        closeEditBtn.onclick = () => editModal.style.display = 'none';
        window.onclick = (e) => { if(e.target == editModal) editModal.style.display = 'none'; }
    </script>
</body>
</html>