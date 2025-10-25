<?php
session_start();

if (!isset($_SESSION['userid'])) {
    header("Location: ../../General/login.php"); 
    exit();
}

include '../../conexion.php';

$user_id = $_SESSION['userid'];
$user_data = null;

$sql = "SELECT u.userid, u.username, u.email, u.profilescreen, u.role, 
               a.numero_admin, a.nombre_completo, a.telefono, a.telefono_emergencia, a.direccion
        FROM usuarios u
        LEFT JOIN administradores a ON u.userid = a.userid
        WHERE u.userid = ?";

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

if ($user_data === null) {
    die("Error: No se pudo cargar la información del usuario.");
}

$role_name = 'Usuario';
if ($user_data['role'] == 4) $role_name = 'Administrador';
elseif ($user_data['role'] == 2) $role_name = 'Cajero';

$display_name = !empty($user_data['nombre_completo']) ? $user_data['nombre_completo'] : $user_data['username'];
$user_email = $user_data['email'];
$user_phone = $user_data['telefono'] ?? 'No especificado';
$user_emergency = $user_data['telefono_emergencia'] ?? 'No especificado';
$user_address = $user_data['direccion'] ?? 'No especificada';

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
        <h2>Bienvenido de nuevo, <?= htmlspecialchars($display_name); ?></h2>
    </div>

    <div class="profile-container">
        <div class="profile-info">
            <h3>Información del Usuario</h3>
            <p><strong>Nombre:</strong> <?= htmlspecialchars($display_name); ?></p>
            <p><strong>Correo:</strong> <?= htmlspecialchars($user_email); ?></p>
            <p><strong>Teléfono:</strong> <?= htmlspecialchars($user_phone); ?></p>
            <p><strong>Teléfono de emergencia:</strong> <?= htmlspecialchars($user_emergency); ?></p>
            <p><strong>Dirección:</strong> <?= htmlspecialchars($user_address); ?></p>
            <p><strong>Rol:</strong> <?= htmlspecialchars($role_name); ?></p>
            <button class="btn-edit" id="openEditModal">Editar Información</button>
        </div>
        <div class="profile-picture">
            <img src="<?= htmlspecialchars($user_data['profilescreen']); ?>" alt="Perfil Admin">
        </div>
    </div>

    <!-- Modal de edición -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Editar Información</h2>
            <form action="editar_perfil.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="userid" value="<?= $user_data['userid']; ?>">

                <label>Nombre completo:</label>
                <input type="text" name="nombre_completo" value="<?= htmlspecialchars($display_name); ?>">

                <label>Correo:</label>
                <input type="email" name="email" value="<?= htmlspecialchars($user_email); ?>">

                <label>Teléfono:</label>
                <input type="text" name="telefono" value="<?= htmlspecialchars($user_phone); ?>">

                <label>Teléfono de emergencia:</label>
                <input type="text" name="telefono_emergencia" value="<?= htmlspecialchars($user_emergency); ?>">

                <label>Dirección:</label>
                <input type="text" name="direccion" value="<?= htmlspecialchars($user_address); ?>">

                <label>Foto de perfil:</label>
                <input type="file" name="profile_pic" accept="image/*">

                <label>Rol:</label>
                <input type="text" value="<?= $role_name ?>" readonly>

                <button type="submit">Guardar Cambios</button>
            </form>
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
