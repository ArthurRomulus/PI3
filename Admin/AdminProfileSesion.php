<?php
    session_start();
?>

  <link rel="stylesheet" href="../AdminProfileSesion.css">


<div class="top-bar">
  <div class="admin-profile">
    <img src='<?php echo htmlspecialchars($_SESSION['profilescreen']);?>' alt='Perfil Admin'>
    <span class="admin-name"><?php echo htmlspecialchars($_SESSION['username']); ?></span>

    <div class="theme-switch-wrapper">
        <label class="theme-switch" for="theme-toggle">
            <input type="checkbox" id="theme-toggle" />
            <div class="slider round"></div>
        </label>
    </div>

    <form method="POST" action="../../General/logout.php">
      <button type="submit" class="logoutButton">Cerrar sesión</button>
    </form>
  </div>
</div>
