<?php


// Create a MySQLi connection
$conn = new mysqli("localhost", "root", "", "e-cab");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}


?>
