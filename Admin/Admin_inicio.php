<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel de Control</title>
  <link rel="stylesheet" href="general.css">
  <style>

    .content {
        font-family: Arial, sans-serif;
        background: #bfbfbf;
        margin: 20px;
        height: 88vh;
        border-radius: 30px;
        padding: 20px;
        flex: 1; /* se adapta dentro del layout */
        min-width: 280px;
        display: flex;
              margin-left: 220px; /* como ya tenías */

        align-items: center;
    }

    .arrow {
      width: 0;
      height: 0;
      border-top: 40px solid transparent;
      border-bottom: 40px solid transparent;
      border-right: 60px solid white;
      margin-right: 20px;
    }

    .text {
      font-size: 40px;
      line-height: 1.5;
      color: white;
    }

    .admin2-profile {
      display: flex;
      gap: 20px;
      background: #D7D7D7;
      padding: 20px 25px;
      border-radius: 40px;
      flex-wrap: wrap;
      flex-direction: row;
    }

    .admin2-textadmin {
      font-size: 30px;
      font-weight: 600;
      color: #1C1B1F;
    }

    .admin2-name {
      font-size: 22px;
      margin-top: 50px;
      font-weight: 600;
      color: #1C1B1F;

    }

    .admin2-profile img {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      object-fit: cover;
      border: 3px solid #8c8f96;
    }

.top2-bar {
  position: absolute;
  top: 20px;
  left: 50%;              /* la llevamos al centro */
  transform: translateX(-50%); /* ajusta al centro exacto */
  display: flex;
  align-items: center;
  z-index: 10;
}

.img-logo {
    width: 100vw;
    height: auto;
    max-width: 100vh;
    opacity: 0.5;
}

  </style>
</head>
<body>
  <?php include "Admin_nav_bar.php"; ?>


  <div class="content">


    <div class="top2-bar">
      <div class="admin2-profile">
        <span class="admin2-textadmin">Admin</span>
        <span class="admin2-name">Adminusername</span>
        <img src="img/admin.png" alt="Perfil Admin">
      </div>
    </div>

    <div class="arrow"></div>
    <div class="text">
      <p>¡Se le da la bienvenida al panel de control!</p>
      <p>¿Retomamos desde donde lo dejamos?</p>
    </div>

    <img src="img/logo.png" class="img-logo">
  </div>
</body>
</html>
