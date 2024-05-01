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




function generateOTP()
{
    // Generate a random number between 1000 and 9999
    $randomOTP = rand(1000, 9999);

    // Convert the number to a string
    $otpString = strval($randomOTP);

    // Ensure the OTP does not start with zero
    while ($otpString[0] === '0') {
        $randomOTP = rand(1000, 9999);
        $otpString = strval($randomOTP);
    }

    return $otpString;
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["key1"]) && isset($_POST["key2"]) && $_POST["key2"] == "div1") {


    $otp = generateOTP();




    try {

        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';  // Your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = ''; // Your SMTP username
        $mail->Password = ''; // Your SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
        $mail->Port = 587; // TCP port to connect to

        // Recipients
        $mail->setFrom('', 'E-CAB');
        $mail->addAddress($_POST["key1"], 'Recipient1 Name');

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'FORGOT PASSWORD OTP AUTHNTICATION';
        $mail->Body = 'Your Forgot Password OTP is: <b>' . $otp . ' .</b>';
        $mail->AltBody = 'E-CAB DRIVER AUTHENTICATION.';

        // Send the email
        $mail->send();

        echo $otp;

    } catch (Exception $e) {
        echo "Error: {$mail->ErrorInfo}";
    }

}
?>