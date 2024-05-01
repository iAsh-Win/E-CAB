<?php
include ("path.php");
include inc . 'db.php';

// Get the driver ID from the request (assuming it's passed as a GET parameter)
$did = intval($_GET['driverid']);

// Query the database for the latest driver location
$query = "SELECT latitude, longitude FROM driver_location WHERE driverid=$did";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $latitude = $row['latitude'];
    $longitude = $row['longitude'];

    // Return the latitude and longitude as a JSON object
    echo json_encode(array('latitude' => $latitude, 'longitude' => $longitude));
} else {
    // Return an error message if the driver ID is not found
    echo json_encode(array('error' => 'Driver not found'));
}

// Close the database connection
mysqli_close($conn);




?>
