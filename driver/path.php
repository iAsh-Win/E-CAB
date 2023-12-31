<?php
// Your PHP code here
// Define the base URL
define('BASE_URL', '/E-cab/driver/');


// Define paths for different purposes
define('INCLUDE_PATH', $_SERVER['DOCUMENT_ROOT'] . BASE_URL . '/partials/');
define('UPLOADS_PATH', $_SERVER['DOCUMENT_ROOT'] . BASE_URL . '/imagedata/');
define('inc','partials/');
define('uplode','imagedata/');


// Example usage in file inclusion
// include INCLUDE_PATH . 'header.php';

// // Example usage in redirect
// header("Location: " . BASE_URL . "driver");

// // Example usage in HTML link
// echo '<link rel="stylesheet" href="' . STYLESHEET_PATH . '">';

// // Example usage for file upload
// $targetFileName = UPLOADS_PATH . basename($_FILES["img"]["name"]);

// // Example usage in database connection
// $databasePath = DATABASE_PATH;

?>