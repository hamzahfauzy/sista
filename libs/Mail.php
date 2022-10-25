<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Mail {
    
    function send($to, $subject, $message)
    {
        //Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = false; // SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'mail.htd-official.com';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = 'iks-asahan@htd-official.com';                     //SMTP username
            $mail->Password   = 'wJg])Xmnr,DE';                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom('iks-asahan@htd-official.com', 'IKS Asahan');
            $mail->addAddress($to);               //Name is optional

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body    = $message;

            $mail->send();
            return [
                'status' => 'success',
                'message' => 'Message has been sent'
            ];
        } catch (Exception $e) {
            return [
                'status' => 'fail',
                'message' => "Message could not be sent. Mailer Error: {$mail->ErrorInfo}"
            ];
            // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
        
        return [
            'status' => 'fail',
            'message' => 'Not working'
        ];
    }
}