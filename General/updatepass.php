<?php

$t = $_POST["Token"];


if ($_POST["password"] === $_POST["passwordConfirm"]) {

    include "../conexion.php";
    $ct = date("Y-m-d H:i:s");

    // Verificar si el token existe y no ha expirado
    $s = $conn->prepare("SELECT * FROM usuarios WHERE Password_Token = ? AND Password_Token_Exp > ?");
    $s->bind_param("ss", $t, $ct);
    $s->execute();
    $a = $s->get_result()->fetch_assoc();

    if ($a) {

        // Evitar que la nueva contraseña sea igual a la actual
        if (password_verify($_POST["password"], $a["password"])) {
            header("Location: RecoverPassword.php?token=$t&s=error2"); // misma contraseña
            exit;
        }

        if (strlen($_POST["password"]) < 6) {
                        header("Location: RecoverPassword.php?token=$t&s=error4"); // token expirado
            exit;
        }

        // Generar el nuevo hash
        $p = password_hash($_POST["password"], PASSWORD_DEFAULT);

        if ($ct < $a["Password_Token_Exp"]) {
            // Actualizar contraseña y limpiar token
            $up = $conn->prepare("UPDATE usuarios SET Password_Token = NULL, Password_Token_Exp = NULL, password = ? WHERE Password_Token = ?");
            $up->bind_param("ss", $p, $t);
            $up->execute();

            header("Location: RecoverPassword.php?s=success");
            exit;

        } else {
            header("Location: RecoverPassword.php?token=$t&s=error3"); // token expirado
            exit;
        }

    } else {
        header("Location: RecoverPassword.php?token=$t&s=error_token"); // token no válido
        exit;
    }

} else {
    header("Location: RecoverPassword.php?token=$t&s=error1"); // contraseñas no coinciden
    exit;
}

?>
