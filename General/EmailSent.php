<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Si usas Composer
// require 'phpmailer/src/PHPMailer.php'; // Si lo agregaste manualmente
// require 'phpmailer/src/Exception.php';
// require 'phpmailer/src/SMTP.php';

$mail = new PHPMailer(true);

$email = $_POST['email'];

include "../conexion.php";

function ct($conn, $e){
  $usersdata = $conn -> prepare('SELECT * FROM usuarios WHERE email = ?');

  $usersdata->bind_param("s", $e);
  $usersdata->execute();
  $r = $usersdata->get_result();

  if ($r2 = $r->fetch_assoc()){
      do {
        $newToken = bin2hex(random_bytes(16));
        $v1 = $conn->prepare('SELECT * FROM usuarios WHERE Password_Token = ?');
        $v1->bind_param("s", $e);
        $v1->execute();  
        $et = $v1->get_result()->fetch_assoc();
      } while ($et);

      $cd = date("Y-m-d H:i:s");
      $cdv2 = date("Y-m-d H:i:s", strtotime($cd ." +1 hour"));

      $u = $conn -> prepare("UPDATE usuarios SET Password_Token = ?, Password_Token_Exp = ? WHERE email = ?");
      $u->bind_param("sss", $newToken, $cdv2, $e);
      $u->execute();

      return $newToken;
  } else {
    return null;
  }
}

if ($email) {
  $usersdata = $conn->prepare('SELECT * FROM usuarios WHERE email = ?');

  $usersdata->bind_param("s", $email);
  $usersdata->execute();
  $result = $usersdata->get_result();

  if ($rowusers = $result->fetch_assoc()){
    $nT = ct($conn,$email);
  

    try {
        // Configuración del servidor SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
          // Servidor SMTP (puede ser Outlook, etc.)
        $mail->SMTPAuth   = true;
        $mail->Username   = 'coffeeshopPIE3@gmail.com';    // Tu correo
        $mail->Password   = 'mzja rkll viva qhit'; // Tu clave o clave de aplicación
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
        $mail->Port       = 587;

        // Remitente y destinatario
        $mail->setFrom('coffeeshopPIE3@gmail.com', 'Coffeeshop');
        $mail->addAddress($email, 'Receptor'); 

        // Contenido
        $enl = "http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/RecoverPassword.php?token=$nT";
        $mail->isHTML(true);
        $mail->Subject = 'Prueba desde localhost';
        $mail->Body    = "<h1>Correo de recuperacion de contraseña</h1> Este es un correo de recuperacion de contraseña. Si no fuiste tu aprieta aqui. <br> Este es tu token, no se lo muestres a nadie.
        <br> <p><a href='$enl'>$enl</a></p> <br> <button>Cancelar recuperacion</button>";
        $mail->AltBody = 'Este es el texto plano del mensaje.';

        $mail->send();
        header("location: AskEmail.php?s=success");


    } catch (Exception $e) {
        echo "❌ Error al enviar el correo: {$mail->ErrorInfo}";
    }
  }
}
?>
