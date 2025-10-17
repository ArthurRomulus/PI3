<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
    <link rel="stylesheet" href="Admin_nav_bar.css">
    <link rel="stylesheet" href="Admin_perfil.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include 'Admin_nav_bar.php'; ?>

    <div class="content">
        <h1>Coffee Shop</h1>

        <!-- Bienvenida -->
        <div class="welcome-text">
            <h2>Bienvenido de nuevo, Luis Perez Ponzo</h2>
        </div>

        <!-- Contenedor de perfil -->
        <div class="profile-container">
            <!-- Información del admin -->
            <div class="profile-info">
                <h3>Información del Admin</h3>
                <p><strong>Nombre:</strong> Luis Perez Ponzo</p>
                <p><strong>Correo:</strong> admin@coffeeshop.com</p>
                <p><strong>Teléfono:</strong> 555-123-4567</p>
                <p><strong>Rol:</strong> Administrador</p>
                <button class="btn-edit" id="openEditModal">Editar Información</button>
            </div>

            <!-- Foto de perfil -->
            <div class="profile-picture">
                <img src="img/admin.png" alt="Perfil Admin">
            </div>
        </div>

        <!-- Modal para editar información -->
        <div id="editModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Editar Información</h2>
                <input type="text" placeholder="Nombre" value="Luis Perez Ponzo">
                <input type="email" placeholder="Correo" value="admin@coffeeshop.com">
                <input type="file" accept="image/*">
                <input type="text" placeholder="Teléfono" value="555-123-4567">
                <input type="text" placeholder="Rol" value="Administrador">
                <button>Guardar Cambios</button>
            </div>
        </div>
    </div>

    <!-- Script para modal -->
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
