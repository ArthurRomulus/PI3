
<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Perfil de Admin</title>
<link rel="stylesheet" href="../Admin_nav_bar.css">

<link rel="stylesheet" href="Admin_perfil.css">
<link rel="stylesheet" href="../general.css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<script src="../../theme-toggle.js" defer></script>
</head>
<body>
<?php include '../Admin_nav_bar.php';?>

<div class="content">

    <?php
    
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

    <div class="profile-theme-lang-container">
        <div class="lang-switch">
            <img src="../../Images/es_flag.png" id="btn-es" class="lang-flag active" alt="Español" title="Español">
            <img src="../../Images/uk_flag.png" id="btn-en" class="lang-flag" alt="English" title="English">
        </div>

        <div class="theme-switch-wrapper">
            <label class="theme-switch" for="theme-toggle">
                <input type="checkbox" id="theme-toggle" />
                <div class="slider round"></div>
            </label>
        </div>
    </div>
    <h1>Blackwood Coffee</h1>

    <div class="welcome-text">
        <h2 data-translate="Bienvenido de nuevo, <?= htmlspecialchars($display_name); ?>">Bienvenido de nuevo, <?= htmlspecialchars($display_name); ?></h2>
    </div>

    <div class="profile-container">
        <div class="profile-info">
            <h2 data-translate="Información del Usuario">Información del Usuario</h2>
            <p><strong><span data-translate="Nombre">Nombre</span>:</strong> <?= htmlspecialchars($display_name); ?></p>
            <p><strong><span data-translate="Correo electrónico">Correo electrónico</span>:</strong> <?= htmlspecialchars($user_email); ?></p>
            <p><strong><span data-translate="Teléfono">Teléfono</span>:</strong> <?= htmlspecialchars($user_phone); ?></p>
            <p><strong><span data-translate="Teléfono de emergencia">Teléfono de emergencia</span>:</strong> <?= htmlspecialchars($user_emergency); ?></p>
            <p><strong><span data-translate="Dirección">Dirección</span>:</strong> <?= htmlspecialchars($user_address); ?></p>
            <p><strong><span data-translate="Rol">Rol</span>:</strong> <?= htmlspecialchars($role_name); ?></p>

            <button class="btn-edit" id="openEditModal">
                <span data-translate="Editar Información">Editar Información</span>
            </button>
        </div>
        <div class="profile-picture">
            <img src="<?= htmlspecialchars($user_data['profilescreen']); ?>" alt="Perfil Admin">
        </div>
    </div>

    <!-- Modal de edición -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2 data-translate="Editar Información">Editar Información</h2>
            <form action="editar_perfil.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="userid" value="<?= $user_data['userid']; ?>">

                <label data-translate="Nombre completo:">Nombre completo:</label>
                <input type="text" name="nombre_completo" value="<?= htmlspecialchars($display_name); ?>">

                <label data-translate="Correo:">Correo:</label>
                <input type="email" name="email" value="<?= htmlspecialchars($user_email); ?>">

                <label data-translate="Teléfono">Teléfono:</label>
                <input type="text" name="telefono" value="<?= htmlspecialchars($user_phone); ?>">

                <label data-translate="Teléfono de emergencia:">Teléfono de emergencia:</label>
                <input type="text" name="telefono_emergencia" value="<?= htmlspecialchars($user_emergency); ?>">

                <label data-translate="Dirección">Dirección:</label>
                <input type="text" name="direccion" value="<?= htmlspecialchars($user_address); ?>">

                <label data-translate="Foto de perfil:">Foto de perfil:</label>
                <input type="file" name="profile_pic" accept="image/*">

                <label data-translate="Rol:">Rol:</label>
                <input type="text" value="<?= $role_name ?>" readonly>

                <button type="submit" data-translate="Guardar Cambios">Guardar Cambios</button>
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
<script src="../../translate.js"></script>
</body>
</html>
