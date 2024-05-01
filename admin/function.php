<?php
include ("path.php");
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
            header("Location:/E-cab/admin/login/?error=" . urlencode($er));

        } else {
            $er = "No correct admin found, Please Review your credentials...";
            header("Location:/E-cab/admin/login?error=" . urlencode($er));

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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["key1"]) && isset($_POST["key2"]) && $_POST["key1"] == "paidthedriver") {
    $mail = sanitizeInput($_POST["key2"]);
    $insertsql = "UPDATE `bank_details` SET `paid`='yes' where `id`='$mail'";
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
        header("Location:" . BASE_URL . "driver-view?driver=$driverid");
        exit;
    }
    exit;

}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["cateupdate"]) && $_POST["cateupdate"] == "yes") {
    $cateid = sanitizeInput($_POST["cateid"]);
    $basefare = sanitizeInput($_POST["basefare"]);
    $date = date("Y-m-d");


    $updatesql = "UPDATE `cabcate` SET `basefare`='$basefare', `date-update`='$date' WHERE `cateid`='$cateid'";
    $result = mysqli_query($conn, $updatesql);

    if ($result) {
        header("Location:" . BASE_URL . "manage-cabs");
        exit;
    }
    exit;
}



if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["cateadd"]) && $_POST["cateadd"] == "yes") {
    $catename = sanitizeInput($_POST["catename"]);
    $basefare = sanitizeInput($_POST["basefare"]);




    $insertsql = "INSERT INTO `cabcate`(`catename`, `basefare`) VALUES ('$catename','$basefare')";
    $row = mysqli_query($conn, $insertsql);

    if ($row) {
        header("Location:" . BASE_URL . "manage-cabs");
        exit;
    }
    exit;
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["catedelete"]) && isset($_POST["categoryisdelete"]) && $_POST["catedelete"] == "yes") {
    $cateid = sanitizeInput($_POST["cateid"]);

    $insertsql = "DELETE FROM `cabcate` WHERE `cateid`=$cateid";
    $row = mysqli_query($conn, $insertsql);


}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["uid"]) && isset($_POST["userdelete"]) && $_POST["userdelete"] == "yes") {
    $cateid = sanitizeInput($_POST["uid"]);

    $insertsql = "DELETE FROM `users` WHERE `id`=$cateid";
    $row = mysqli_query($conn, $insertsql);


}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["sadd"]) && $_POST["sadd"] == "yes") {
    // Assuming sanitizeInput() properly sanitizes the input
    $sname = sanitizeInput($_POST["sname"]); // Corrected variable name
    $price = sanitizeInput($_POST["price"]); // Corrected variable name
    $duration = sanitizeInput($_POST["duration"]); // Corrected variable name

    // Insert the subscription plan into the database
    $insertsql = "INSERT INTO `subscription_plan`(`sname`, `sprice`, `duration`) VALUES ('$sname','$price','$duration')"; // Corrected variable names
    $result = mysqli_query($conn, $insertsql);

    // Check if the insertion was successful
    if ($result) {

        exit;
    } else {
        // Handle errors if the insertion failed
        echo "Error: " . mysqli_error($conn);
        // You might also want to log the error for debugging purposes
    }
    exit;
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["subupdate"]) && $_POST["subupdate"] == "yes") {
    $cateid = sanitizeInput($_POST["cateid"]);
    $basefare = sanitizeInput($_POST["basefare"]);
    $duration = sanitizeInput($_POST["duration"]);
    date_default_timezone_set('Asia/Kolkata');

    // Get the current date and time in IST
    $createdDate = date("Y-m-d H:i:s"); // Get current date and time

    // Ensure that column names are wrapped in backticks if they contain hyphens or spaces
    $updatesql = "UPDATE `subscription_plan` SET `sprice`='$basefare', `duration`='$duration', `created-date`='$createdDate' WHERE `id`='$cateid'";
    $result = mysqli_query($conn, $updatesql);

    if ($result) {
        header("Location: " . BASE_URL . "manage-subscription");
        exit;
    } else {
        echo "Error updating record: " . mysqli_error($conn);
        // Handle the error appropriately
    }
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["subdelete"]) && isset($_POST["subcategoryisdelete"]) && $_POST["subdelete"] == "yes") {
    $cateid = sanitizeInput($_POST["id"]);

    $insertsql = "DELETE FROM `subscription_plan` WHERE `id`=$cateid";
    $row = mysqli_query($conn, $insertsql);


}

?>