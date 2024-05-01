<?php
require ("../mainDB.php");
session_start();
// Set the content type to JSON
header('Content-Type: application/json');

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Create a response array to send back as JSON
    $response = [];

    // Get the file upload and other form data from the request
    if (isset($_FILES['profile-image']) && isset($_POST['name']) && isset($_POST['mobile'])) {
        // Get the uploaded file
        $file = $_FILES['profile-image'];

        // Set the allowed file types and maximum file size (e.g., 2MB)
        $allowedTypes = ['image/jpeg', 'image/png'];
        $maxFileSize = 2 * 1024 * 1024; // 2MB

        // Validate the file type and size
        if (in_array($file['type'], $allowedTypes) && $file['size'] <= $maxFileSize) {
            // Define the upload directory and file path
            // Set the upload directory
            $uploadDir = '../user-images/';

            // Get the current timestamp
            $timestamp = time();

            // Create a unique file name by appending the timestamp to the original file name
            $fileName = $timestamp . basename($file['name']);

            // Define the upload path
            $uploadPath = $uploadDir . $fileName;

            // Move the uploaded file to the desired location
            if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                // File uploaded successfully, process the other form data

                // Get the name and mobile values
                $name = htmlspecialchars($_POST['name']);
                $mobile = htmlspecialchars($_POST['mobile']);
                if (strpos($uploadPath, '../') === 0) {
                    $relativePath = substr($uploadPath, 3); // Remove the first 3 characters '../'
                } else {
                    $relativePath = $uploadPath; // Use the original path if '../' is not found
                }
                // Get the user's email from the session
                $eid = $_SESSION['Logged-in-user'];

                // Prepare an SQL query using a prepared statement
                $stmt = $conn->prepare("UPDATE `users` SET `name` = ?, `mobileNo` = ?, `userImage` = ? WHERE `emailID` = ?");

                // Bind the parameters to the query
                $stmt->bind_param("ssss", $name, $mobile, $relativePath, $eid);

                // Execute the query and check for errors
                if ($stmt->execute()) {
                    // Send a success response with the new image URL
                    $response['success'] = true;
                    $response['newImageUrl'] = $uploadPath;
                } else {
                    // Query execution failed
                    $response['success'] = false;
                    $response['error'] = 'Failed to update user information.';
                }

                // Close the prepared statement
                $stmt->close();
            } else {
                // Failed to move the file
                $response['success'] = false;
                $response['error'] = 'Failed to upload the file.';
            }
        } else {
            // Invalid file type or size
            $response['success'] = false;
            $response['error'] = 'Invalid file type or file size too large.';
        }
    } else {
        // Missing file or form data
        $response['success'] = false;
        $response['error'] = 'Missing file or form data.';
    }

    // Return the JSON response
    echo json_encode($response);
} else {
    // Invalid request method
    http_response_code(405); // Method Not Allowed
    echo json_encode(['success' => false, 'error' => 'Invalid request method.']);
}
?>