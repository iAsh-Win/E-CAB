<?php
session_start();

if (isset($_SESSION['Logged-in-user']) && isset($_SESSION['isLoggedin']) && $_SESSION['isLoggedin']) {
    // Include the database connection file
    require("../mainDB.php");

    try {
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET["did"]) && !empty($_GET["did"]) && isset($_GET["bookid"]) && !empty($_GET["bookid"])) {
            // Retrieve the driver ID from the GET parameters
            $did = $_GET['did'];

            // Prepare a statement to fetch the driver's location
            $stmt = $conn->prepare("SELECT latitude, longitude FROM driver_location WHERE driverid = ?");
            $stmt->bind_param('i', $did);
            
            // Execute the statement
            $stmt->execute();
            
            // Get the result
            $result = $stmt->get_result();

            // Check if a row was returned
            if ($result && $row = $result->fetch_assoc()) {
                $latitude = $row['latitude'];
                $longitude = $row['longitude'];

                // Return the data as JSON (uncomment if needed for an AJAX request)
                header('Content-Type: application/json');
                echo json_encode(['latitude' => $latitude, 'longitude' => $longitude]);
            } else {
                throw new Exception("No data found for the provided driver ID.");
            }

            // Close the statement
            $stmt->close();
        } else {
            throw new Exception("Invalid request method or parameters.");
        }
    } catch (Exception $e) {
        // Handle any exceptions
        echo "Error: " . $e->getMessage();
    } finally {
        // Close the database connection
        if (isset($conn) && $conn instanceof mysqli) {
            $conn->close();
        }
    }
} else {
    // Redirect unauthenticated users to the homepage
    header("Location:../");
    exit();
}
?>
