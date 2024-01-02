<?php
// Your PHP code here
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $data = json_decode(file_get_contents("php://input"));


    $latitude = $data->latitude;
    $longitude = $data->longitude;
    $driverid = $data->driverid;


    // Uncomment the following lines after including your database connection
    include("path.php");
    include inc . "db.php";
    $sql = "INSERT INTO driver_location (Driverid, latitude, longitude) VALUES ('$driverid','$latitude', '$longitude')";
    $result = mysqli_query($conn, $sql);

    if ($result === TRUE) {
        echo "Location updated successfully";
    }
}

?>