<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel de Control</title>
  <link rel="stylesheet" href="../Admin_nav_bar.css">
  <link rel="stylesheet" href="../general.css">
  
  <style>
    /* Estilos para el .content (Panel de Control)
       Apila todo verticalmente y quita la altura fija.
    */

    /* Estilos para el texto "Bienvenido"
       (Sin 'position: absolute')
    */
    .text {
      font-size: 40px;
      line-height: 1.5;
      color: white; 
      margin-top: 30px; /* Le da espacio DEBAJO de la barra de perfil */
    }

    /* Estilos para el logo (SIN 'position: absolute')
       Ahora es un elemento normal
    */
    .img-logo {
        width: 80%; /* Ancho relativo al contenedor .content */
        max-width: 500px; /* Un tamaño máximo */
        height: auto;     /* Mantiene la proporción */
        opacity: 0.2;     
        margin-top: 40px; /* Espacio DEBAJO del texto "Bienvenido" */
    }

    /* Esto es del "arrow" que tenías, lo dejamos */
    .arrow {
      width: 0;
      height: 0;
      border-top: 40px solid transparent;
      border-bottom: 40px solid transparent;
      border-right: 60px solid white;
      margin-right: 20px;
    }

  </style>
</head>
<body>
  <?php include "../Admin_nav_bar.php"; ?>

  <div class="content">
    <?php include "../AdminProfileSesion.php"; ?>
    <div class="arrow"></div>
    <div class="text">
      <p>¡Se le da la bienvenida al panel de control!</p>
      <p>¿Retomamos desde donde lo dejamos?</p>
    </div>

    <img src="../../Images/logo.png" class="img-logo">
  </div>
</body>
</html>
