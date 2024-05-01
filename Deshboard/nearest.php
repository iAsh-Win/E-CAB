<?php
// Include database connection file
require('../mainDB.php');

// Set the content type of the response to JSON
header('Content-Type: application/json');

// Decode JSON data from the POST request
$data = json_decode(file_get_contents("php://input"), true);

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if all required fields are present in the JSON data
    if (isset($data['userLatitude'], $data['userLongitude'], $data['selectedCab'])) {
        // User's latitude and longitude
        $userLatitude = $data['userLatitude'];
        $userLongitude = $data['userLongitude'];

        // Convert selectedCab to its corresponding cateid
        switch ($data['selectedCab']) {
            case 'compact':
                $selectedCab = 1;
                break;
            case 'family':
                $selectedCab = 2;
                break;
            default:
                $selectedCab = 3; // Default case for other types (e.g., luxury)
                break;
        }

        // Function to calculate distance between two points using the Haversine formula
        function calculateDistance($userLat, $userLon, $driverLat, $driverLon) {
            $earthRadius = 6371; // Earth's radius in kilometers

            // Convert latitude and longitude from degrees to radians
            $userLat = deg2rad($userLat);
            $userLon = deg2rad($userLon);
            $driverLat = deg2rad($driverLat);
            $driverLon = deg2rad($driverLon);

            // Calculate differences between coordinates
            $latDiff = $driverLat - $userLat;
            $lonDiff = $driverLon - $userLon;

            // Calculate the Haversine distance
            $distance = 2 * $earthRadius * asin(
                sqrt(
                    pow(sin($latDiff / 2), 2) +
                    cos($userLat) * cos($driverLat) * pow(sin($lonDiff / 2), 2)
                )
            );

            return $distance; // Return the distance in kilometers
        }

        // Fetch drivers based on selected cab type
        $ridesQuery = "SELECT * FROM driver WHERE cateid=$selectedCab";
        $ridesResult = mysqli_query($conn, $ridesQuery);

        // Check if the query returned any drivers
        if (mysqli_num_rows($ridesResult) > 0) {
            // Initialize an array to store nearest drivers
            $nearestDrivers = array();

            // Iterate over each driver in the result set
            while ($driver = mysqli_fetch_assoc($ridesResult)) {
                $driverId = $driver['driverid'];

                // Fetch driver's location
                $locationQuery = "SELECT latitude, longitude FROM driver_location WHERE driverid=$driverId";
                $locationResult = mysqli_query($conn, $locationQuery);

                // Check if location data is available
                if ($locationResult && mysqli_num_rows($locationResult) > 0) {
                    while ($row = mysqli_fetch_assoc($locationResult)) {
                        $driverLat = $row['latitude'];
                        $driverLon = $row['longitude'];

                        // Calculate distance between user and driver
                        $distance = calculateDistance($userLatitude, $userLongitude, $driverLat, $driverLon);

                        // Store driver information in an array
                        $nearestDrivers[] = array(
                            'driverId' => $driverId,
                            'distance' => $distance
                        );
                    }
                } else {
                    // Handle case where driver's location data is not available
                    echo json_encode(['error' => 'No location data found for driver']);
                    exit;
                }
            }

            // Sort the array of drivers based on distances
            usort($nearestDrivers, function ($a, $b) {
                return $a['distance'] - $b['distance'];
            });

            // Start a session to store the nearest drivers' information
            session_start();
            $_SESSION['nearestDrivers'] = $nearestDrivers;

            // Output the nearest drivers' information as JSON
            echo json_encode($_SESSION['nearestDrivers']);
        } else {
            // If no drivers are found for the selected cab type
            echo json_encode(['error' => 'No driver found for the selected cab type']);
        }
    } else {
        // Handle case where data is incomplete
        echo json_encode(['error' => 'Incomplete data received']);
    }
} else {
    // Handle case where request method is not POST
    echo json_encode(['error' => 'Invalid request method']);
}
?>
