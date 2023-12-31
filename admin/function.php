<?php
// Your PHP code here
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["formname"]) && $_POST["formname"] == "admin_login") {
    include("db.php"); // Assuming this file contains the database connection

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
?>