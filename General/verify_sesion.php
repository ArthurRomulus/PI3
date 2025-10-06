<?php
session_start();
if (    isset($_SESSION['role']) == 4 ){
    echo "<a href='../Admin/Admin_inicio.php'> Admin</a>";
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Panel Privado</title>
</head>
<body>

    <h1>Bienvenido, <?php echo htmlspecialchars($_SESSION['username']); ?> ☕</h1>
    <p>Tu email es: <?php echo htmlspecialchars($_SESSION['email']); ?></p>
    <p>Tu rol es: <?php echo htmlspecialchars($_SESSION['role']); ?></p>

    <form action="logout.php" method="POST">
        <button type="submit">Cerrar sesión</button>
    </form>
</body>
</html>
