<?php
require('../mainDB.php');
header('Content-Type: application/json');

// Decoding JSON data from the POST request
$data = json_decode(file_get_contents("php://input"));

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if all required fields are present in the JSON data
    if (isset($data->userLatitude, $data->userLongitude, $data->selectedCab)) {
        // User's location
        $userLatitude = $data->userLatitude;
        $userLongitude = $data->userLongitude;

        // Convert selectedCab to its corresponding cateid
        switch ($data->selectedCab) {
            case 'compact':
                $selectedCab = 1;
                break;
            case 'family':
                $selectedCab = 2;
                break;
            default:
                $selectedCab = 3;
                break;
        }

        // Function to calculate the distance between two points using the Haversine formula
        function calculateDistance($userLat, $userLon, $driverLat, $driverLon)
        {
            $earthRadius = 6371; // Earth's radius in kilometers

            // Convert latitude and longitude from degrees to radians
            $userLat = deg2rad($userLat);
            $userLon = deg2rad($userLon);
            $driverLat = deg2rad($driverLat);
            $driverLon = deg2rad($driverLon);

            // Calculate the differences between the coordinates
            $latDiff = $driverLat - $userLat;
            $lonDiff = $driverLon - $userLon;

            // Calculate the Haversine distance
            $distance = 2 * $earthRadius * asin(
                sqrt(
                    pow(sin($latDiff / 2), 2) +
                    cos($userLat) * cos($driverLat) * pow(sin($lonDiff / 2), 2)
                )
            );

            return $distance; // Distance in kilometers
        }

        // Fetch drivers based on selected cab type
        $ridesQuery = "SELECT * FROM driver WHERE cateid=$selectedCab";
        $ridesResult = mysqli_query($conn, $ridesQuery);

        if ($ridesResult) {
            $nearestDrivers = array();

            while ($driver = mysqli_fetch_assoc($ridesResult)) {
                $driverId = $driver['driverid'];

                // Fetch driver's location
                $locationQuery = "SELECT latitude, longitude FROM driver_location WHERE driverid=$driverId";
                $locationResult = mysqli_query($conn, $locationQuery);

                if ($locationResult) {
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
                    // Handle query error or no location found
                    echo json_encode(['error' => 'Error in fetching driver location']);
                    exit;
                }
            }

            // Sort the array based on distances
            usort($nearestDrivers, function($a, $b) {
                return $a['distance'] - $b['distance'];
            });

            // Start session
            session_start();

            // Store nearest drivers information in session
            $_SESSION['nearestDrivers'] = $nearestDrivers;

            // Output nearest drivers information as JSON
            echo json_encode($_SESSION['nearestDrivers']);
        } else {
            // Handle query error
            echo json_encode(['error' => 'Error in fetching drivers']);
        }
    } else {
        echo json_encode(['error' => 'Incomplete data received']);
    }
}
?>
