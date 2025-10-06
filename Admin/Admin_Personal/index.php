<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Coffee Shop - Usuarios</title>

  <link rel="stylesheet" href="../general.css">
  <link rel="stylesheet" href="personal.css">
  <link rel="stylesheet" href="usuariocrud.css">
</head>
<body>
  <script src="../functions.js" defer></script>

  <?php include "../Admin_nav_bar.php"; ?> 

  <div class="content">
    <?php include "../AdminProfileSesion.php"; ?>

    <h1>Coffee shop</h1> 
    <div class="topbar">
      <?php include '../date.php'; ?>
    </div>

    <div class="layout" id="graphic">
      <?php 
        include "../../database.php";

        $result = $db->query("SELECT * FROM usuarios");

        while ($row = $result->fetch_assoc()) {
          echo '<div class="empleado" id="empleado_' . htmlspecialchars($row['userid']) . '">';
            echo '<img src="' . htmlspecialchars($row['profilescreen']) . '" alt="Perfil Admin">';
            echo '<div class="info">';
              echo '<strong class="nombre">' . htmlspecialchars($row['username']) . '</strong>';
              echo '<span class="rol">Rol: ' . htmlspecialchars($row['role']) . '</span>'; 
              echo '<span class="email">Email: ' . htmlspecialchars($row['email']) . '</span>'; 
              echo '<button 
                      class="modify_button"
                      onclick="openUpdateModal(this)"
                      data-id="' . htmlspecialchars($row['userid']) . '"
                      data-username="' . htmlspecialchars($row['username']) . '"
                      data-email="' . htmlspecialchars($row['email']) . '"
                      data-role="' . htmlspecialchars($row['role']) . '"
                      data-image="' . htmlspecialchars($row['profilescreen']) . '"
                    >Modificar usuario</button>';
              echo '<button class="remove_user_button" onclick="this.closest(\'.empleado\').remove()">Eliminar usuario</button>';
            echo '</div>'; 
          echo '</div>';
        }
      ?>
    </div>
  </div>

  <!-- MODAL -->
<!-- MODAL -->
<div class="updateModal" id="updateModal" style="display:none;">
  <div class="modal-content">
    <button class="close-btn" onclick="closeModal('updateModal')">×</button>

    <!-- ID oculto del usuario -->
    <input type="hidden" id="userid" name="id">

    <label for="username">Username</label>
    <input type="text" id="username" name="username">

    <label for="userpassword">Password</label>
    <input type="password" id="userpassword" name="password">

    <label for="userprofile">Profile Image</label>
    <input type="file" accept="image/png, image/jpeg, image/webp" id="userprofile" name="image">

    <label for="role">Rol del usuario</label>
    <select id="role" name="role">
      <?php
        include "../../database.php"; // asegúrate de tener la conexión activa aquí
        $result = $db->query("SELECT id_rol, rolename FROM roles WHERE status = 1");

        if ($result && $result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            echo '<option value="'. htmlspecialchars($row['id_rol']) .'">' 
                . htmlspecialchars($row['rolename']) . '</option>';
          }
        } else {
          echo '<option disabled>No hay roles disponibles</option>';
        }
      ?>
    </select>

    <input type="submit" id="updateuser" value="Actualizar">
  </div>
</div>


</body>
</html>
