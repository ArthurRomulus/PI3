<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <link rel="stylesheet" href="general.css">
    <link rel="stylesheet" href="personal.css">
    <link rel="stylesheet" href="usuariocrud.css">
    <script src="functions.js"></script>


    <?php 
        include "Admin_nav_bar.php";
    ?> 
    <div class="content">
    <div class="top-bar">
      <div class="admin-profile">
        <span class="admin-name">Admin - Luis Perez Ponzo</span>
        <img src="img/admin.png" alt="Perfil Admin">
      </div>
    </div>
            <h1>Coffee shop</h1> 
            <?php include 'date.php' ?>
      <div class = "layout" id = "graphic" name="graphic">
          <div class="empleado" id ="personalTemplate" name ="personalTemplate">
            <img src="img/admin.png" alt="Perfil Admin">
            <div class ="info">
              <strong id="nombre">Empleado Plantilla</strong>
              <span id = "turno">Turno:</span>
              <span id ="Jornada">Jornada:</span>
                  <script src="functions.js"></script>

              <button class = "modify_button" onclick="openModal('updateModal')">Modificar usuario</button>
              <button class = "remove_user_button" onclick="this.parentElement.parentElement.remove()">Eliminar usuario</button>
            </div> 
          </div>
      </div>
    </div>

<div class="updateModal" id="updateModal">
  <div class="modal-content">
    <button class="close-btn" onclick="closeModal('updateModal')">×</button>
    
    <label for="username">Username</label>
    <input type="text" id="username">

    <label for="userpassword">Password</label>
    <input type="password" id="userpassword">

    <label for="userprofile">Profile Image</label>
    <input type="file" accept="image/png, image/jpeg, image/webp" id="userprofile">

    <input type="submit" id="updateuser" value="Actualizar">
  </div>
</div>

</body>
</html>
