<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer files
require_once './driver/phpmailer/src/PHPMailer.php';
require_once './driver/phpmailer/src/SMTP.php';
require_once './driver/phpmailer/src/Exception.php';

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
function sendOTP($email)
{
    $otp = generateOTP();


    try {

        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';  // Your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = ''; // Your SMTP username
        $mail->Password = ''; // Your SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
        $mail->Port = 587; // TCP port to connect to

        // Recipients
        $mail->setFrom('', 'E-CAB');
        $mail->addAddress($email, 'Recipient1 Name');

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'OTP AUTHNTICATION';
        $mail->Body = 'Your OTP is: <b>' . $otp . ' .</b>';
        $mail->AltBody = 'E-CAB USER AUTHENTICATION.';

        // Send the email
        $mail->send();

        return $otp;

    } catch (Exception $e) {

    }
}

require("mainDB.php");

function checkData($i)
{
    echo "<pre>";
    print_r($i);
    echo "</pre>";


    // echo "<pre>";
    // print_r($_FILES["img"]);
    // echo "</pre>";

}


function sanitizeInput($input)
{
    global $conn;
    return mysqli_real_escape_string($conn, $input);

}



$requestData = json_decode(file_get_contents('php://input'), true);


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($requestData["operation"]) && $requestData["operation"] == "checkEmail" && $requestData["email"] != "") {

    // Process the POST data&& $_SERVER['HTTP_REFERER'] == "http://localhost/E-cab/sign-up.html"




    $emailToCheck = sanitizeInput($requestData['email']);
    $isValidEmail = filter_var($emailToCheck, FILTER_VALIDATE_EMAIL);


    // $referringUrl = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'Unknown';

    // checking in database 

    $searchsql = "SELECT * FROM users WHERE emailID='$emailToCheck'";

    // Execute the SQL query (you may want to add error handling here)
    $result = mysqli_query($conn, $searchsql);
    $row1 = mysqli_num_rows($result);
    $exist = false;
    $sentOTP = "";
    if ($row1 <= 0) {
        $exist = false;
        $sentOTP = sendOTP($emailToCheck);
    } else {
        $exist = true;

    }

    // Prepare the response data
    $responseData = [
        'operation' => 'checkedEmail',
        'email' => $emailToCheck,
        // 'referringUrl' => $referringUrl,
        'isValid' => $isValidEmail,
        'isExist' => $exist,
        'OTP' => $sentOTP,

    ];

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($responseData);

} else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($requestData["operation"]) && $requestData["operation"] == "resendOTP" && $requestData["email"] != "") {
    $emailToResend = sanitizeInput($requestData['email']);
    $resend = sendOTP($emailToResend);
    $responseData = [
        'operation' => 'OTP-resent',
        'email' => $emailToResend,

        'OTP-resent' => $resend,

    ];

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($responseData);
} else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($requestData["operation"]) && $requestData["operation"] == "login" && $requestData["email"] != "" && $requestData["password"] != "") {
    $email = sanitizeInput($requestData['email']);
    $password = sanitizeInput($requestData['password']);
    $exist = "";

    $searchsql = "SELECT * FROM users WHERE emailID='$email' AND password='$password'";
    $result = mysqli_query($conn, $searchsql);

    if ($result) {
        $numRows = mysqli_num_rows($result);

        if ($numRows == 1) {

            // session_start();
            // $row = mysqli_fetch_assoc($result);

            // $_SESSION["driver_login"] = true;
            // $_SESSION["driveremail"] = $email;
            $exist = "1";
            session_start();
            $_SESSION['Logged-in-user'] = $email;
            $_SESSION['isLoggedin'] = true;

        } else {
            $exist = "0";


        }

    } else {
        echo json_encode(['error' => 'cannot responding...']);
    }

    $responseData = [
        'operation' => 'login',
        'email' => $email,
        'users' => $exist,
        'pass' => $password,


    ];

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($responseData);
    if ($exist == "1") {
        exit();
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($requestData["operation"]) && $requestData["operation"] == "registerUser" && isset($requestData['myObj']) && $requestData['myObj'] != "") {
    $mobile = sanitizeInput($requestData['myObj']['Mobile']);
    $password = sanitizeInput($requestData['myObj']['Password']);
    $email = sanitizeInput($requestData['myObj']['email']);
    $name = sanitizeInput($requestData['myObj']['Name']);



    $insertQuery = "INSERT INTO `users`(`name`, `emailID`, `mobileNo`, `password`, `userImage`) VALUES ('$name', '$email', '$mobile', '$password', 'user-images/default.png')";
    $row = mysqli_query($conn, $insertQuery);




    if ($row) {
        mysqli_close($conn);
        echo json_encode(['success' => true]);
  
        session_start();
        $_SESSION['Logged-in-user'] = $email;
        $_SESSION['isLoggedin'] = true;

        exit();
    } else {
        echo json_encode(['success' => false, 'error' => 'Registration failed']);
        exit();
    }
} else {
    // http_response_code(405); // Method Not Allowed
    // echo json_encode(['error' => 'Method Not Allowed']);
    echo json_encode($requestData);
}


?>

<?php
require_once 'vendor/autoload.php';

use Google\Client;
use Google\Service\Oauth2;

// Init configuration
$clientID = '';
$clientSecret = '';
$redirectUri = 'http://localhost/E-cab/function';

// Create Client Request to access Google API
$client = new Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope("email");
$client->addScope("profile");

// Authenticate code from Google OAuth Flow
if (isset($_GET['code']) && isset($_GET['authuser']) && isset($_GET['scope'])) {
    try {
        $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

        // Check if token is in the correct format
        if (!isset($token['access_token'])) {

            throw new InvalidArgumentException('Invalid token format. Access token is missing.');
        }

        $client->setAccessToken($token);

        // Get profile info
        $google_oauth = new Oauth2($client);
        $google_account_info = $google_oauth->userinfo->get();

        $email = $google_account_info->getEmail();
        $name = $google_account_info->getName();
        $id = $google_account_info->getId();
        $picture = $google_account_info->getPicture();
        $time = time();

        // echo "<pre>";
        // print_r($google_account_info);
        // echo "</pre>";
        session_start();

        $searchsql = "SELECT * FROM users WHERE emailID='$email'";

        // Execute the SQL query (you may want to add error handling here)
        $result = mysqli_query($conn, $searchsql);
        $row1 = mysqli_num_rows($result);

        if ($row1 <= 0) {
            $filename = 'user-images/' . $time . '.' . 'jpg';
            $pictureContent = @file_get_contents($picture);

            echo $filename;
            if ($pictureContent !== false) {
                $result = @file_put_contents($filename, $pictureContent);
                if ($result !== false) {
                    $insertQuery = "INSERT INTO `users`(`name`, `emailID`,`userImage`) VALUES ('$name','$email',' $filename')";
                    $row = mysqli_query($conn, $insertQuery);
                    if ($row) {
                        mysqli_close($conn);
                        header("Location: Deshboard");
                        $_SESSION['Logged-in-user'] = $email;
                        $_SESSION['isLoggedin'] = true;
                        exit();
                    } else {
                        exit();

                    }
                }
            } else {
                echo "fatch proble.";
            }
        } else {
            header("Location: Deshboard");
            $_SESSION['Logged-in-user'] = $email;
            $_SESSION['isLoggedin'] = true;
            exit();

        }



        // Now you can use this profile info to create an account on your website and make the user logged in.
    } catch (Exception $e) {

        echo 'Error occurred during authentication. Please try again later.';
    }
}
?>