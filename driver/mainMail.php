<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer files
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';
require 'phpmailer/src/Exception.php';

// Create an instance of PHPMailer
$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';  // Your SMTP server
    $mail->SMTPAuth = true;
    $mail->Username = 'ashwinsolanki9898@gmail.com'; // Your SMTP username
    $mail->Password = 'qfdtdhxomshlhwdc'; // Your SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
    $mail->Port = 587; // TCP port to connect to

    // Recipients
    $mail->setFrom('ashwinsolanki9898@gmail.com', 'E-CAB');
    $mail->addAddress('ashwinsolanki9898@gmail.com', 'Recipient1 Name');

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'congrates';
    $mail->Body = 'This is the HTML message body <b>in bold!</b>';
    $mail->AltBody = 'hiiiiiiThis is the plain text version for non-HTML mail clients';

    // Send the email
    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>