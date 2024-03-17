<?php
// Include necessary files and start session if needed
include ("path.php");
session_start();

// Check if user is logged in
if (
    isset ($_SESSION["driver_login"]) && $_SESSION["driver_login"] == true &&
    isset ($_SESSION["driveremail"]) && $_SESSION["driveremail"] != ""
) {
    // Include database connection
    include inc . 'db.php';

    $driver = $_SESSION["driveremail"];
    $searchsql = "SELECT * FROM driver WHERE email='$driver'";

    // Execute the SQL query (you may want to add error handling here)
    $result = mysqli_query($conn, $searchsql);

    // Fetch the data
    $row = mysqli_fetch_assoc($result);
    $driverId = $row["driverid"];


    // Query to check for new bookings for the driver
    $checkNewBookingsQuery = "SELECT COUNT(*) AS new_bookings_count 
                              FROM driverrequest 
                              WHERE driverID = $driverId 
                              AND status = 'pending'";

    // Execute query
    $checkNewBookingsResult = mysqli_query($conn, $checkNewBookingsQuery);

    // Check if query was successful
    if ($checkNewBookingsResult) {
        // Fetch the result
        $row = mysqli_fetch_assoc($checkNewBookingsResult);

        // Check if there are new bookings
        $newBookingsCount = $row['new_bookings_count'];
        if ($newBookingsCount > 0) {
            // If new bookings exist, echo 'true'
            echo 'true';
        } else {
            // If no new bookings, echo 'false'
            echo 'false';
        }
    } else {
        // Error occurred while executing the query
        echo 'false'; // Return 'false' indicating no new bookings (you can handle errors differently if needed)
    }
} else {
    // User is not logged in, return 'false'
    echo 'false';
}
?>