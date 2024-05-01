<?php
// Include required files for database connection
include ("path.php");
include inc . 'db.php';

// Check the request method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Read JSON data from the input stream
    $inputData = file_get_contents('php://input');
    // Decode JSON data into a PHP array
    $data = json_decode($inputData, true);

    // Initialize the response array
    $response = [
        'success' => false,
        'message' => ''
    ];

    // Check if data was successfully decoded and validate the form data
    if ($data !== null) {
        // Retrieve and trim the input data
        $name = trim($data['name'] ?? '');
        $bank = trim($data['bank'] ?? '');
        $accountNumber = trim($data['accountNumber'] ?? '');
        $ifscCode = trim($data['ifscCode'] ?? '');
        $branch = trim($data['branch'] ?? '');
        $mobileNumber = trim($data['mobileNumber'] ?? '');
        $driverId = trim($data['driverId'] ?? '');

        // Validate the data (check for non-empty fields)
        if ($name && $accountNumber && $ifscCode && $branch && $mobileNumber && $driverId) {
            // Prepare an SQL statement to insert data into the database
            $sql = "INSERT INTO bank_details (name, account_number,bank, ifsc_code, branch, mobile_number, driverid) VALUES (?,?, ?, ?, ?, ?, ?)";

            // Use prepared statements to prevent SQL injection
            if ($stmt = $conn->prepare($sql)) {
                // Bind parameters to the statement
                $stmt->bind_param("sssssss", $name, $accountNumber, $bank, $ifscCode, $branch, $mobileNumber, $driverId);

                // Execute the statement
                if ($stmt->execute()) {
                    // Success: Respond with success message
                    $response['success'] = true;
                    $response['message'] = 'Data saved successfully.';
                } else {
                    // Error: Respond with failure message
                    $response['message'] = 'Failed to save data: ' . $stmt->error;
                }

                // Close the statement
                $stmt->close();
            } else {
                // Error: Unable to prepare the SQL statement
                $response['message'] = 'Error preparing the SQL statement: ' . $conn->error;
            }
        } else {
            // Missing required fields
            $response['message'] = 'Missing required fields. Please ensure all fields are filled in.';
        }
    } else {
        // Invalid JSON input data
        $response['message'] = 'Invalid JSON input data.';
    }

    // Return the response as JSON
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    // If the request method is not POST, return an error message
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method. This API accepts POST requests only.'
    ]);
}
?>