<?php

include ("path.php");
include inc . 'db.php';

function check($i)
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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset ($_POST["form_name"]) && $_POST["form_name"] == "driverreg") {
    // Function to sanitize user input


    // Sanitize and validate each input
    $firstname = sanitizeInput($_POST["firstname"]);
    $lastname = sanitizeInput($_POST["lastname"]);
    $gender = sanitizeInput($_POST["gender"]);
    $email = sanitizeInput($_POST["email"]);
    $phone = sanitizeInput($_POST["phone"]);
    $carno = sanitizeInput($_POST["carno"]);
    $licence = sanitizeInput($_POST["licence"]);
    $city = sanitizeInput($_POST["city"]);
    $state = sanitizeInput($_POST["state"]);
    $pin = sanitizeInput($_POST["pin"]);
    $password = sanitizeInput($_POST["password"]);
    $cabcate = sanitizeInput($_POST["cabcate"]);

    $uploadOk = 1;
    $error = "";

    $searchsql = "SELECT * FROM driver WHERE email='$email'";

    // Execute the SQL query (you may want to add error handling here)
    $result = mysqli_query($conn, $searchsql);
    $row1 = mysqli_num_rows($result);
    // Fetch the data
    if ($row1 == 0) {


        $allowedExtensions = ["jpg", "jpeg", "png", "gif"];
        $targetDirectory = uplode;  // Set the target directory where you want to store the uploaded images
        $targetFileName = $targetDirectory . basename($_FILES["img"]["name"]);
        $imageFileType = strtolower(pathinfo($targetFileName, PATHINFO_EXTENSION));


        // Check if the file was uploaded successfully
        if ($_FILES["img"]["error"] == UPLOAD_ERR_NO_FILE) {
            $error = "Please uplode an Image.";
            $uploadOk = 0;  // Set uploadOk to 0 to indicate an error
        } elseif (!in_array($imageFileType, $allowedExtensions)) {
            $error = "Sorry, only JPG, JPEG, PNG, and GIF files are allowed.";
            $uploadOk = 0;
        } else {

            if (move_uploaded_file($_FILES["img"]["tmp_name"], $targetFileName)) {
                $insertsql = "INSERT INTO `driver`(`firstname`, `lastname`, `gender`, `phone`, `email`, `password`, `city`, `state`, `pin`, `cateid`, `carno`, `licence`,`photo`) 
            VALUES ('$firstname','$lastname','$gender','$phone','$email','$password','$city','$state','$pin','$cabcate','$carno','$licence','$targetFileName')";

                $row = mysqli_query($conn, $insertsql);

                if ($row) {
                    header("Location:" . BASE_URL . "login.php?msg=Success, Your Account is created, just login to proceeds..!!!");
                    exit;

                }
            } else {
                $uploadOk = 0;
                $error = "Sorry, there was an error uploading your file.";
            }
        }


    } else {
        $uploadOk = 0;
        $error = "Driver Already Exist.!!!";
    }

    if ($uploadOk != 1 && $error != "") {
        $er = urlencode($error);
        header("Location:" . BASE_URL . "signup?error=$er");
        exit;

    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset ($_POST["driverlogin"]) && $_POST["driverlogin"] == "yes") {
    $email = sanitizeInput($_POST["email"]);
    $password = sanitizeInput($_POST["pass"]);
    $searchsql = "SELECT * FROM driver WHERE email='$email' AND password='$password'";
    $result = mysqli_query($conn, $searchsql);

    if ($result) {
        $numRows = mysqli_num_rows($result);

        if ($numRows == 1) {

            session_start();
            $row = mysqli_fetch_assoc($result);

            $_SESSION["driver_login"] = true;
            $_SESSION["driveremail"] = $email;


            header("Location: " . BASE_URL);
        } elseif ($numRows > 1) {
            $er = "Multiple records found, something is not right";
            header("Location:" . BASE_URL . "login.php/?msg=" . urlencode($er));

        } else {
            $er = "No Driver found ! Please check your Username or Password...";
            header("Location:" . BASE_URL . "login.php?msg=" . urlencode($er));

        }

    } else {
        header("location:" . BASE_URL . "login.php");
    }

    exit;
}



if ($_SERVER["REQUEST_METHOD"] == "POST" && isset ($_POST["Manageprofile"]) && $_POST["Manageprofile"] == "driverprofile") {



    $firstname = sanitizeInput($_POST["firstname"]);
    $lastname = sanitizeInput($_POST["lastname"]);
    $gender = sanitizeInput($_POST["gender"]);

    $phone = sanitizeInput($_POST["phone"]);
    $carno = sanitizeInput($_POST["carno"]);

    $city = sanitizeInput($_POST["city"]);
    $state = sanitizeInput($_POST["state"]);
    $pin = sanitizeInput($_POST["pin"]);
    $cabcate = sanitizeInput($_POST["cabcate"]);
    $date = date("Y-m-d");
    $uploadOk = 1;
    $error = "";
    session_start();

    $email = $_SESSION["driveremail"];

    $allowedExtensions = ["jpg", "jpeg", "png", "gif"];
    $targetDirectory = uplode;  // Set the target directory where you want to store the uploaded images
    $targetFileName = $targetDirectory . basename($_FILES["img"]["name"]);
    $imageFileType = strtolower(pathinfo($targetFileName, PATHINFO_EXTENSION));


    if ($_FILES["img"]["error"] == UPLOAD_ERR_NO_FILE) {

        $insertsql = "UPDATE `driver` SET `firstname`='$firstname',`lastname`='$lastname',`gender`='$gender',`phone`='$phone',`city`='$city',`state`='$state',`pin`='$pin',`cateid`='$cabcate',`carno`='$carno',`date_update`='$date' WHERE `email`='$email' ";
        $row = mysqli_query($conn, $insertsql);

        if ($row) {
            header("Location:" . BASE_URL . "manage-profile?$Phone1");
            exit;


        }
    } elseif (!in_array($imageFileType, $allowedExtensions)) {
        $error = "Sorry, only JPG, JPEG, PNG, and GIF files are allowed.";
        $uploadOk = 0;

    } else {


        if (move_uploaded_file($_FILES["img"]["tmp_name"], $targetFileName)) {
            $insertsql = "UPDATE `driver` SET `firstname`='$firstname',`lastname`='$lastname',`gender`='$gender',`phone`='$phone',`city`='$city',`state`='$state',`pin`='$pin',`cateid`='$cabcate',`carno`='$carno',`date_update`='$date',`photo`='$targetFileName' WHERE `email`='$email' ";

            $row = mysqli_query($conn, $insertsql);

            if ($row) {
                header("Location:" . BASE_URL . "manage-profile");
                exit;

            }
        } else {
            $uploadOk = 0;
            $error = "Sorry, there was an error uploading your file.";
        }
    }

    if ($uploadOk != 1 && $error != "") {
        $er = urlencode($error);
        header("Location:" . BASE_URL . "manage-profile?error=$er");
        exit;
    }


}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset ($_POST["key1"]) && isset ($_POST["key2"]) && $_POST["key2"] == "div1") {

    $email = sanitizeInput($_POST["key1"]);
    $searchsql = "SELECT * FROM driver WHERE email='$email'";

    // Execute the SQL query (you may want to add error handling here)
    $result = mysqli_query($conn, $searchsql);

    // Fetch the data
    $rowCount = mysqli_num_rows($result);

    if ($rowCount > 0) {


    } else {
        echo "Email does not exist";
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset ($_POST["param1"]) && isset ($_POST["param2"]) && $_POST["param3"] == "change") {
    $email = sanitizeInput($_POST["param1"]);
    $pass = sanitizeInput($_POST["param2"]);


    $insertsql = "UPDATE `driver` SET `password`='$pass' WHERE `email`='$email'";

    $row = mysqli_query($conn, $insertsql);

    if ($row) {
        echo "changed";
    } else {
        echo "not changed: ";
    }


}
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset ($_POST["operation"]) && $_POST["operation"] == "AcceptBook" && isset ($_POST["bookid"]) && isset ($_POST["driverid"]) && isset ($_POST["reqID"])) {

    $bookid = mysqli_real_escape_string($conn, $_POST["bookid"]);
    $driverid = mysqli_real_escape_string($conn, $_POST["driverid"]);
    $reqID = mysqli_real_escape_string($conn, $_POST["reqID"]);

    $rideUpdateQuery = "UPDATE driverrequest SET status='Accepted' WHERE reqID='$reqID'";
    $bookingUpdateQuery = "UPDATE bookings SET reqID='$reqID', status='Confirmed' WHERE bookid='$bookid'";

    $rides = mysqli_query($conn, $rideUpdateQuery);
    $bookings = mysqli_query($conn, $bookingUpdateQuery);

    // Check if both queries were successful
    if ($bookings && $rides) {
        echo json_encode(["status" => "done", "reqID" => $reqID]);
    } else {
        echo json_encode(["status" => "error", "message" => mysqli_error($conn)]);
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset ($_POST["operation"]) && isset ($_POST["bookid"]) && $_POST["operation"] == "PickBook") {

    $reqID = mysqli_real_escape_string($conn, $_POST["reqID"]);
    $ride = "UPDATE driverrequest SET status='Pickup' WHERE reqID='$reqID'";
    $rides = mysqli_query($conn, $ride);

    // Check if both queries were successful
    if ($rides) {
        echo json_encode(["status" => "done"]);
    } else {
        echo json_encode(["status" => "error", "message" => mysqli_error($conn)]);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset ($_POST["operation"]) && isset ($_POST["bookid"]) && $_POST["operation"] == "CompleteRide") {
    $bookid = mysqli_real_escape_string($conn, $_POST["bookid"]);
    $reqID = mysqli_real_escape_string($conn, $_POST["reqID"]);
    $ride = "UPDATE driverrequest SET status='Complete' WHERE reqID='$reqID'";
    $rides = mysqli_query($conn, $ride);

    $deletereq = "DELETE FROM driverrequest WHERE reqID!='$reqID' AND bookid='$bookid'";
    $delete = mysqli_query($conn, $deletereq);

    $bookings = "UPDATE bookings SET status='Completed' WHERE bookid='$bookid'";
    $bk = mysqli_query($conn, $bookings);

    // Check if both queries were successful
    if ($rides) {
        echo json_encode(["status" => "done"]);
    } else {
        echo json_encode(["status" => "error", "message" => mysqli_error($conn)]);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset ($_POST["operation"]) && isset ($_POST["bookid"]) && $_POST["operation"] == "DeclineRide") {

    $reqID = mysqli_real_escape_string($conn, $_POST["reqID"]);
    $ride = "UPDATE driverrequest SET status='Decline' WHERE reqID='$reqID'";
    $rides = mysqli_query($conn, $ride);

    // Check if both queries were successful
    if ($rides) {
        echo json_encode(["status" => "done"]);
    } else {
        echo json_encode(["status" => "error", "message" => mysqli_error($conn)]);
    }
}


?>