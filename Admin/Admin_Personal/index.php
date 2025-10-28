<?php
include "../../conexion.php"; // conexión a la base de datos

// === INSERCIÓN DE NUEVO USUARIO ===
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["new_user"])) {
    $username = $_POST["username"];
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT);
    $email = $_POST["email"];
    $role = $_POST["role"];
    $telefono = $_POST["telefono"];
    $telefonoEmergencia = $_POST["telefono_emergencia"];
    $direccion = $_POST["direccion"];

    // Imagen de perfil opcional
    $profilePath = null;
    if (!empty($_FILES["image"]["name"])) {
        $uploadDir = "../../uploads/";
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
        $profilePath = $uploadDir . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $profilePath);
    }

    // Insertar en tabla usuarios
    $stmt = $conn->prepare("INSERT INTO usuarios (username, password, email, role, profilescreen) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $username, $password, $email, $role, $profilePath);
    $stmt->execute();
    $userid = $conn->insert_id;

    // Si es cajero (id_rol = 2), insertarlo en empleados_cajeros
    if ($role == 2) {
        $numeroEmpleado = "CJ-" . str_pad($userid, 4, "0", STR_PAD_LEFT);
        $stmt2 = $conn->prepare("INSERT INTO empleados_cajeros (userid, numero_empleado, nombre_completo, telefono, telefono_emergencia, direccion) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt2->bind_param("isssss", $userid, $numeroEmpleado, $username, $telefono, $telefonoEmergencia, $direccion);
        $stmt2->execute();
    }

      if ($role == 4) {
        $numeroAdmin = "CJ-" . str_pad($userid, 4, "0", STR_PAD_LEFT);
        $stmt2 = $conn->prepare("INSERT INTO administradores (userid, numero_admin, nombre_completo, telefono, telefono_emergencia, direccion) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt2->bind_param("isssss", $userid, $numeroAdmin, $username, $telefono, $telefonoEmergencia, $direccion);
        $stmt2->execute();
    }

    echo "<script>alert('Usuario registrado correctamente');</script>";
        header("location: /PI3/Admin/Admin_Personal/");

    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Coffee Shop - Usuarios</title>

  <link rel="stylesheet" href="../general.css">
  <link rel="stylesheet" href="../Admin_nav_bar.css">
  <link rel="stylesheet" href="personal.css">
  <link rel="stylesheet" href="usuariocrud.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

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

    <button class="add_user_button" onclick="openModal('addUserModal')">
  <i class="fa-solid fa-user-plus"></i> Añadir usuario
</button>

    <!-- 🔽 FILTRO POR ROL -->
    <div class="filter-container">
      <label for="roleFilter"><i class="fa-solid fa-filter"></i> Filtrar por rol:</label>
      <select id="roleFilter" onchange="filterByRole()">
        <option value="all">Todos</option>
        <?php
          $rolesFilter = $conn->query("SELECT id_rol, rolename FROM roles WHERE status = 1");
          while ($r = $rolesFilter->fetch_assoc()) {
            echo '<option value="' . htmlspecialchars($r['id_rol']) . '">' . htmlspecialchars($r['rolename']) . '</option>';
          }
        ?>
      </select>
    </div>

    <div class="layout" id="graphic">


    <div class="layout" id="graphic">



      <?php 
        $result = $conn->query("SELECT * FROM usuarios");
        while ($row = $result->fetch_assoc()) {
          echo '<div class="empleado" id="empleado_' . htmlspecialchars($row['userid']) . '">';
            echo '<img src="' . htmlspecialchars($row['profilescreen'] ?: '../../Images/default_profile.png') . '" alt="Perfil">';
            echo '<div class="info">';
              echo '<strong class="nombre">' . htmlspecialchars($row['username']) . '</strong>';
              echo '<span class="rol">Rol: ' . htmlspecialchars($row['role']) . '</span>'; 
              echo '<span class="email">Email: ' . htmlspecialchars($row['email']) . '</span>'; 
              echo '<button class="modify_button" 
                        onclick="openUpdateModal(this)"
                        data-id="' . htmlspecialchars($row['userid']) . '"
                        data-username="' . htmlspecialchars($row['username']) . '"
                        data-email="' . htmlspecialchars($row['email']) . '"
                        data-role="' . htmlspecialchars($row['role']) . '"
                        data-image="' . htmlspecialchars($row['profilescreen']) . '">
                        Modificar usuario
                    </button>'; 

                    
echo  '<button class="remove_user_button" onclick="deleteUser(' . htmlspecialchars($row['userid']) . ')">Eliminar usuario</button>';
            echo '</div>'; 
          echo '</div>';
        }
      ?>


    </div>
  </div>

  <!-- MODAL NUEVO USUARIO -->
  <div class="GeneralModal" id="addUserModal" style="display:none;">
    <div class="modal-content">
      <button class="close-btn" onclick="closeModal('addUserModal')">×</button>
      <h2>Registrar nuevo usuario</h2>
      <form action="" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="new_user" value="1">

        <label>Nombre de usuario</label>
        <input type="text" name="username" required>

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Contraseña</label>
        <input type="password" name="password" required>

        <label>Teléfono</label>
        <input type="text" name="telefono" required>

        <label>Teléfono de emergencia</label>
        <input type="text" name="telefono_emergencia" required>

        <label>Dirección</label>
        <input type="text" name="direccion" required>

        <label>Rol del usuario</label>
        <select name="role" required>
          <?php
            $resultRoles = $conn->query("SELECT id_rol, rolename FROM roles WHERE status = 1");
            if ($resultRoles && $resultRoles->num_rows > 0) {
              while ($r = $resultRoles->fetch_assoc()) {
                echo '<option value="' . htmlspecialchars($r['id_rol']) . '">' . htmlspecialchars($r['rolename']) . '</option>';
              }
            } else {
              echo '<option disabled>No hay roles disponibles</option>';
            }
          ?>
        </select>

        <label>Imagen de perfil</label>
        <input type="file" name="image" accept="image/png, image/jpeg, image/webp">

        <input type="submit" value="Guardar" class="btn-save">
      </form>
    </div>
  </div>

  <!-- MODAL MODIFICAR -->
  <div class="GeneralModal" id="updateModal" style="display:none;">
    <div class="modal-content">
      <button class="close-btn" onclick="closeModal('updateModal')">×</button>

      <h2>Modificar usuario</h2>
      <form action="UpdateUser.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" id="id" name="id">

        <label>Username</label>
        <input type="text" id="username" name="username">

        <label>email</label>
        <input type="text" id="email" name="email">


        <label>Contraseña</label>
        <input type="password" id="password" name="password">

        <label>Imagen de perfil</label>
        <input type="file" id="userprofile" name="image" accept="image/png, image/jpeg, image/webp">

        <label>Rol del usuario</label>
        <select id="role" name="role">
          <?php
            $roles = $conn->query("SELECT id_rol, rolename FROM roles WHERE status = 1");
            while ($rol = $roles->fetch_assoc()) {
              echo '<option value="'. htmlspecialchars($rol['id_rol']) .'">' . htmlspecialchars($rol['rolename']) . '</option>';
            }
          ?>
        </select>

        <input type="submit" value="Actualizar" class="btn-save">
      </form>
    </div>
  </div>

  <script>
    function openModal(id) {
      document.getElementById(id).style.display = "block";
    }
    function closeModal(id) {
      document.getElementById(id).style.display = "none";
    }
    function openUpdateModal(button) {
      document.getElementById('updateModal').style.display = 'block';
      document.getElementById('id').value = button.dataset.id;
      document.getElementById('username').value = button.dataset.username;
      document.getElementById('role').value = button.dataset.role;
    }
    window.onclick = function(e) {
      if (e.target.classList.contains('updateModal')){
        e.target.style.display = 'none';
      }
    }

    function filterByRole() {
  const selectedRole = document.getElementById("roleFilter").value;
  const users = document.querySelectorAll(".empleado");

  users.forEach(user => {
    const roleText = user.querySelector(".rol").textContent;
    const roleValue = roleText.replace("Rol: ", "").trim();

    if (selectedRole === "all" || selectedRole === roleValue) {
      user.style.display = "flex";
    } else {
      user.style.display = "none";
    }
  });
}

function deleteUser(userid) {
  if (!confirm("¿Seguro que deseas eliminar este usuario?")) return;

  fetch("DeleteUser.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded"
    },
    body: "userid=" + encodeURIComponent(userid)
  })
  .then(response => response.text())
  .then(data => {
    if (data.trim() === "success") {
      alert("Usuario eliminado correctamente");
      const userDiv = document.getElementById("empleado_" + userid);
      if (userDiv) userDiv.remove();
    } else if (data.trim() === "not_found") {
      alert("El usuario no existe.");
    } else {
      alert("Error al eliminar el usuario.");
    }
  })
  .catch(error => {
    console.error("Error:", error);
    alert("Error al comunicarse con el servidor.");
  });
}


  </script>
</body>
</html>

