<?php
include("path.php");
include DB;
function check($i)
{
    echo '<pre>';
    print_r($i); // or var_dump($i);
    echo '</pre>';
}

function sanitizeInput($input)
{
    global $conn;
    return mysqli_real_escape_string($conn, $input);
}
// Your PHP code here
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["formname"]) && $_POST["formname"] == "admin_login") {
    // Assuming this file contains the database connection

    // Validate and sanitize user inputs
    $username = isset($_POST["username"]) ? mysqli_real_escape_string($conn, $_POST["username"]) : "";
    $password = isset($_POST["password"]) ? mysqli_real_escape_string($conn, $_POST["password"]) : "";

    // Use prepared statement to prevent SQL injection
    $sql = "SELECT * FROM admin WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $numRows = mysqli_num_rows($result);

        if ($numRows == 1) {

            session_start();
            $_SESSION["adminlogin"] = true;
            $_SESSION["adminusername"] = $username;
            header("Location:/E-cab/admin");


        } elseif ($numRows > 1) {
            $er = "Multiple records found, something is not right";
            header("Location:/E-cab/admin/login.php/?error=" . urlencode($er));

        } else {
            $er = "No correct admin found, Please Review your credentials...";
            header("Location:/E-cab/admin/login.php?error=" . urlencode($er));

        }
        exit;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["key1"]) && isset($_POST["key2"]) && $_POST["key1"] == "deactive") {
    $mail = sanitizeInput($_POST["key2"]);
    $insertsql = "UPDATE `driver` SET `active`='1' where `email`='$mail'";
    $row = mysqli_query($conn, $insertsql);

}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["key1"]) && isset($_POST["key2"]) && $_POST["key1"] == "active") {
    $mail = sanitizeInput($_POST["key2"]);
    $insertsql = "UPDATE `driver` SET `active`='0' where `email`='$mail'";
    $row = mysqli_query($conn, $insertsql);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["key1"]) && isset($_POST["key2"]) && $_POST["key1"] == "delete") {
    $mail = sanitizeInput($_POST["key2"]);
    $insertsql = "DELETE FROM `driver` where `email`='$mail'";
    $row = mysqli_query($conn, $insertsql);
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["Manageprofiledriver"]) && isset($_POST["pin"]) && $_POST["Manageprofiledriver"] == "yes") {
    $firstname = sanitizeInput($_POST["firstname"]);
    $lastname = sanitizeInput($_POST["lastname"]);
    $gender = sanitizeInput($_POST["gender"]);
    $email = sanitizeInput($_POST["email"]);
    $phone = sanitizeInput($_POST["phone"]);
    $carno = sanitizeInput($_POST["carno"]);
    $driverid = sanitizeInput($_POST["driverid"]);
    $city = sanitizeInput($_POST["city"]);
    $state = sanitizeInput($_POST["state"]);
    $pin = sanitizeInput($_POST["pin"]);
    $licence = sanitizeInput($_POST["licence"]);
    $cabcate = sanitizeInput($_POST["cabcate"]);
    $date = date("Y-m-d");
    $uploadOk = 1;
    $error = "";
    $insertsql = "UPDATE `driver` SET `firstname`='$firstname',`lastname`='$lastname',`gender`='$gender',`phone`='$phone',`city`='$city',`state`='$state',`pin`='$pin',`cateid`='$cabcate',`carno`='$carno',`email`='$email',`licence`='$licence',`date_update`='$date' WHERE `driverid`='$driverid' ";
    $row = mysqli_query($conn, $insertsql);

    if ($row) {
        header("Location:" . BASE_URL . "driver-view.php?driver=$driverid");
        exit;
    }
    exit;

}
?>