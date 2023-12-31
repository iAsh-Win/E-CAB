<?php
include("path.php");

// Your PHP code here
session_start();
// Your PHP code here
unset($_SESSION["driver_login"]);
unset($_SESSION["driveremail"]);
session_destroy();
// Redirect to the login page or any other desired page
header("location:" . BASE_URL . "login.php");
exit;
?>