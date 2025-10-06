<?php
session_start();
session_unset();
session_destroy();

header("Location: ../General/registro_usuarios.php");
exit();
?>
