
  
<div class="sidebar">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="Admin_nav_bar.css">
    <div class="logo">
      <img src="img/logo.png" alt="Logo" width="150">
    </div>

    <?php   
      $currentPage = basename($_SERVER['PHP_SELF']); // obtiene la página actual
    ?>

    <a href="Admin_inicio.php" class="nav-item <?php if($currentPage == 'Admin_inicio.php') echo 'active'; ?>">
      <i class="fas fa-home"></i>
      <span>Inicio</span>
    </a>

    <a href="Admin_productos.php" class="nav-item <?php if($currentPage == 'Admin_productos.php') echo 'active'; ?>">
      <i class="fas fa-store"></i>
      <span>Productos</span>
    </a>

    <a href="Admin_promociones.php" class="nav-item <?php if($currentPage == 'Admin_promociones.php') echo 'active'; ?>">
      <i class="fas fa-percent"></i>
      <span>Promociones</span>
    </a>

    <a href="Admin_estadisticas.php" class="nav-item <?php if($currentPage == 'Admin_estadisticas.php') echo 'active'; ?>">
      <i class="fas fa-chart-line"></i>
      <span>Estadísticas</span>
    </a>

    <a href="Admin_personal.php" class="nav-item <?php if($currentPage == 'Admin_personal.php') echo 'active'; ?>">
      <i class="fas fa-users"></i>
      <span>Personal</span>
    </a>

    <a href="Admin_perfil.php" class="nav-item <?php if($currentPage == 'Admin_perfil.php') echo 'active'; ?>">
      <i class="fas fa-user"></i>
      <span>Perfil</span>
    </a>

  

  </div>

  
