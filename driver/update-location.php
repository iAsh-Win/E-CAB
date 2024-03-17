<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the required data is present
    if (
        isset($_POST['driverId']) &&
        isset($_POST['latitude']) &&
        isset($_POST['longitude'])
    ) {
        // Your database connection code (replace with your actual database credentials)
        include("path.php");
        include inc . "db.php";

        // Sanitize input data
        $driverId = mysqli_real_escape_string($conn, $_POST['driverId']);
        $latitude = mysqli_real_escape_string($conn, $_POST['latitude']);
        $longitude = mysqli_real_escape_string($conn, $_POST['longitude']);

        // Check if the record exists
        $check_query = "SELECT COUNT(*) FROM driver_location WHERE driverid = ?";
        $check_stmt = mysqli_prepare($conn, $check_query);
        mysqli_stmt_bind_param($check_stmt, "s", $driverId);
        mysqli_stmt_execute($check_stmt);
        mysqli_stmt_bind_result($check_stmt, $count);
        mysqli_stmt_fetch($check_stmt);
        mysqli_stmt_close($check_stmt);

        if ($count > 0) {
            // Update the existing record
            $query = "UPDATE driver_location SET latitude = ?, longitude = ?, timestamp = NOW() WHERE driverid = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "sss", $latitude, $longitude, $driverId);
            $result = mysqli_stmt_execute($stmt);
        } else {
            // Insert a new record
            $query = "INSERT INTO driver_location (driverid, latitude, longitude, timestamp) 
                      VALUES (?, ?, ?, NOW())";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "sss", $driverId, $latitude, $longitude);
            $result = mysqli_stmt_execute($stmt);
        }

        if ($result) {
            echo "Location updated successfully";
        } else {
            echo "Error updating location: " . mysqli_error($conn);
        }

        // Close the prepared statement
        mysqli_stmt_close($stmt);
    } else {
        echo "Incomplete data received";
    }

    // Close the database connection
    mysqli_close($conn);
}

?>
