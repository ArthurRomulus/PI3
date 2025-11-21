<div class="sidebar">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="../Admin_nav_bar.css">

  <div class="logo">
    <img src="../../Images/logo.png" alt="Logo" width="150">
  </div>

  <div class="nav-items-container">
    <?php $currentPage = basename($_SERVER['PHP_SELF']); ?>

    <a href="../Admin_Inicio" class="nav-item <?php if($currentPage == '../Admin_Inicio') echo 'active'; ?>">
      <i class="fas fa-home"></i><span data-translate="Inicio">Inicio</span>
    </a>
    <a href="../Admin_Productos" class="nav-item <?php if($currentPage == '../Admin_Productos') echo 'active'; ?>">
      <i class="fas fa-store"></i><span data-translate="Productos">Productos</span>
    </a>
    <a href="../Admin_Promociones" class="nav-item <?php if($currentPage == '../Admin_Promociones') echo 'active'; ?>">
      <i class="fas fa-percent"></i><span data-translate="Promociones">Promociones</span>
    </a>
    <a href="../Admin_Estadisticas" class="nav-item <?php if($currentPage == '../Admin_Estadisticas') echo 'active'; ?>">
      <i class="fas fa-chart-line"></i><span data-translate="Estadísticas">Estadísticas</span>
    </a>
    <a href="../Admin_Comentarios" class="nav-item <?php if($currentPage == '../Admin_Comentarios') echo 'active'; ?>">
      <i class="fas fa-comments"></i><span data-translate="Comentarios">Comentarios</span>
    </a>
    <a href="../Admin_Personal" class="nav-item <?php if($currentPage == '../Admin_Personal') echo 'active'; ?>">
      <i class="fas fa-users"></i><span data-translate="Personal">Personal</span>
    </a>
    <a href="../Admin_Perfil" class="nav-item <?php if($currentPage == '../Admin_Perfil') echo 'active'; ?>">
      <i class="fas fa-user"></i><span data-translate="Perfil">Perfil</span>
    </a>
  </div>
</div>
