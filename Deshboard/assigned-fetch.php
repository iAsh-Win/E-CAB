<?php
session_start();

// Check if the user is logged in
if (isset ($_SESSION['Logged-in-user']) && isset ($_SESSION['isLoggedin']) && $_SESSION['isLoggedin'] == true) {
    require ("../mainDB.php");

    try {
        if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset ($_GET["bookid"]) && $_GET["bookid"] != '') {
            $bookid = mysqli_real_escape_string($conn, $_GET['bookid']);

            // Function to fetch driver details and location
            function fetchDriverDetails($conn, $reqid)
            {
                $ride_query = "SELECT * FROM `driverrequest` WHERE reqID=$reqid";
                $rides = mysqli_query($conn, $ride_query);

                if (!$rides) {
                    throw new Exception(mysqli_error($conn));
                }

                $req = mysqli_fetch_assoc($rides);

                if ($req && $req['status'] == 'Accepted') {
                    $did = $req['driverID'];

                    $driver_query = "SELECT * FROM `driver` WHERE driverid=$did";
                    $drivers = mysqli_query($conn, $driver_query);

                    if (!$drivers) {
                        throw new Exception(mysqli_error($conn));
                    }

                    $driverdetails = mysqli_fetch_assoc($drivers);

                    $driver_location_query = "SELECT * FROM driver_location WHERE driverid='$did'";
                    $result3 = mysqli_query($conn, $driver_location_query);

                    if (!$result3) {
                        throw new Exception(mysqli_error($conn));
                    }

                    $locationdetails = mysqli_fetch_assoc($result3);

                    return array('driverdetails' => $driverdetails, 'locationdetails' => $locationdetails);
                }

                return null;
            }

            // Fetch initial data
            $bookings_query = "SELECT reqID FROM bookings WHERE bookid='$bookid'";
            $result = mysqli_query($conn, $bookings_query);

            if (!$result) {
                throw new Exception(mysqli_error($conn));
            }

            $reqID_row = mysqli_fetch_assoc($result);

            if ($reqID_row && $reqID_row["reqID"] != NULL) {
                $reqid = $reqID_row["reqID"];
                $data = fetchDriverDetails($conn, $reqid);
            } else {
                $data = null;
            }

            // Encode data to JSON
            echo json_encode($data);

        }
    } catch (Exception $e) {
        // Handle exceptions
    } finally {
        mysqli_close($conn);
    }
} else {
    header("Location:../");
    exit();
}
?>