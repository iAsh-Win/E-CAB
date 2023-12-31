<?php
// Your PHP code here
session_start();
// Your PHP code here
unset($_SESSION["adminlogin"]);
unset($_SESSION["adminusername"]);
session_destroy();
// Redirect to the login page or any other desired page
header("Location: /E-cab/admin/login.php");

?>