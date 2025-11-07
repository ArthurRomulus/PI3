<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';
require __DIR__ . '/PHPMailer/src/Exception.php';


            $mail->SMTPDebug = 2; // No mostrar mensajes de debug
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = false;
            $mail->Username   = 'vitals.news.pi@gmail.com';
            $mail->Password   = 'srqthrreggudhqzt';
            $mail->SMTPSecure = defined('PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS') ? PHPMailer::ENCRYPTION_STARTTLS : 'tls';
            $mail->Port       = 587;

            $mail->setFrom('vitals.news.pi@gmail.com', 'VitalNews');
            $mail->addAddress("mparra8@ucol.mx");

            $mail->isHTML(true);
            $mail->Subject = 'Recuperación de contraseña - VitalNews';
            $mail->Body    = "<p>Hola,</p><p>Haz clic en el siguiente enlace para cambiar tu contraseña:</p>
                              <p>Este enlace expirará en 1 hora.</p>";

            $mail->send();



?>
