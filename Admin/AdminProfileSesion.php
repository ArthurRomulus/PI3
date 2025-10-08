<?php
    session_start();
?>

  <link rel="stylesheet" href="../AdminProfileSesion.css">


<div class="top-bar">
  <div class="admin-profile">
    <img src='<?php echo htmlspecialchars($_SESSION['profilescreen']);?>' alt='Perfil Admin'>
    <span class="admin-name">Admin - <?php htmlspecialchars($_SESSION['username']); ?></span>

    <form method="POST" action="../../General/logout.php">
      <button type="submit" class="logoutButton">Cerrar sesión</button>
    </form>
  </div>
</div>
