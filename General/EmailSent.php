<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


$mail = new PHPMailer(true);

$email = $_POST['email'];

require 'vendor/autoload.php';


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
        $mail->Subject = 'Reset password.';
$mail->Body = '
<div style="width:100%; background:#f4f4f4; padding:30px 0; font-family:Arial, sans-serif;">
  <div style="max-width:550px; margin:auto; background:white; padding:25px; border-radius:12px; box-shadow:0 4px 15px rgba(0,0,0,0.15);">
    
    <div style="text-align:center;">
      <img src="cid:logo_cafe" alt="Logo" style="width:150px; margin-bottom:20px;">
    </div>

    <h2 style="text-align:center; color:#333;">Recuperación de contraseña</h2>

    <p style="font-size:15px; color:#444; line-height:1.5;">
      Hola,<br><br>
      Hemos recibido una solicitud para restablecer la contraseña de tu cuenta en <strong>Coffeeshop</strong>.
      Si no solicitaste este cambio, simplemente ignora este mensaje.
    </p>

    <p style="font-size:15px; color:#444;">Haz clic en el siguiente botón para continuar:</p>

    <div style="text-align:center; margin:25px 0;">
      <a href="' . $enl . '" 
      style="text-decoration:none; padding:14px 25px; background:#6f4e37; color:white; font-size:16px; border-radius:8px; display:inline-block;">
        Restablecer contraseña
      </a>
    </div>

    <p style="font-size:13px; color:#777; text-align:center;">
      Este enlace expirará en 1 hora.<br>
      Por seguridad, no compartas este correo con nadie.
    </p>

  </div>
</div>
';

$mail->AddEmbeddedImage('../Images/logo.png', 'logo_cafe');


        $mail->AltBody = 'Este es el texto plano del mensaje.';

        $mail->send();
        header("location: AskEmail.php?s=success");


    } catch (Exception $e) {
          header("location: AskEmail.php?s=failed");

    }
  } else {
                  header("location: AskEmail.php?s=failedNoexist");

  }
  }



?>
