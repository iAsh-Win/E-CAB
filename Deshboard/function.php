<?php
require('../mainDB.php');
session_start();
function checkData($i)
{
    echo "<pre>";
    print_r($i);
    echo "</pre>";
}
function hasAllValues($obj)
{
    foreach ($obj as $value) {
        if (is_array($value) && !empty($value)) {
            // Check if the array has at least one element
            return true;
        } elseif (is_object($value) && !empty(get_object_vars($value))) {
            // Check if the object has at least one property
            return true;
        } elseif ($value !== null && $value !== '') {
            // Check if the value is not null, undefined, or an empty string
            return true;
        }
    }

    return false;
}
function sanitizeInput($input)
{
    global $conn;
    return mysqli_real_escape_string($conn, $input);

}

$json_data = file_get_contents("php://input");
$data = json_decode($json_data, true);



if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($data['paymenttype']) && hasAllValues($data)) {

    // echo json_encode('data');
    if ($data['paymenttype'] == 'cash') {
        $uid = sanitizeInput($data['user']['id']);
        $source = sanitizeInput($data['pickup']['mainad']);
        $destination = sanitizeInput($data['dropOff']['mainad']);
        $distance = sanitizeInput($data['totaldistance']);
        $fare = sanitizeInput($data['totalfare']);
        $booking_time = sanitizeInput($data['datetime']);
        $selectedCab = sanitizeInput($data['selectedcab']);


        try {
            if ($selectedCab == 'compact') {
                $cabs = "SELECT * FROM `cabcate` WHERE catename = 'compact' LIMIT 1";

                if ($result = mysqli_query($conn, $cabs)) {
                    $row = mysqli_fetch_assoc($result);

                    if ($row) {
                        $selectedCabID = $row['cateid'];
                        // Perform actions with $selectedCabID
                    } else {
                        // Handle case when no rows are found
                    }

                    mysqli_free_result($result);
                } else {
                    // Handle query execution error
                }
            }

            if ($selectedCab == 'family') {
                $cabs = "SELECT * FROM `cabcate` WHERE catename = 'family' LIMIT 1";

                if ($result = mysqli_query($conn, $cabs)) {
                    $row = mysqli_fetch_assoc($result);

                    if ($row) {
                        $selectedCabID = $row['cateid'];
                        // Perform actions with $selectedCabID
                    } else {
                        // Handle case when no rows are found
                    }

                    mysqli_free_result($result);
                } else {
                    // Handle query execution error
                }
            }

            if ($selectedCab == 'premium') {
                $cabs = "SELECT * FROM `cabcate` WHERE catename = 'premium' LIMIT 1";

                if ($result = mysqli_query($conn, $cabs)) {
                    $row = mysqli_fetch_assoc($result);

                    if ($row) {
                        $selectedCabID = $row['cateid'];
                        // Perform actions with $selectedCabID
                    } else {
                        // Handle case when no rows are found
                    }

                    mysqli_free_result($result);
                } else {
                    // Handle query execution error
                }
            }

            $sql = "INSERT INTO `ride`(`uid`, `booking_time`, `source`, `destination`, `distance`, `fare`, `selectedCab`) 
                    VALUES ('$uid', '$booking_time', '$source', '$destination', '$distance', '$fare',$selectedCabID)";

            // Execute the query
            if ($conn->query($sql) === TRUE) {
                $lastInsertedId = $conn->insert_id;
                $sql2 = "INSERT INTO `bookings`(`rid`, `payment_type`, `status`) VALUES ('$lastInsertedId','cash','Confirmed')";
                if ($conn->query($sql2) === TRUE) {
                    echo json_encode('trips');
                }
            }

            // Explicitly close the database connection
            $conn->close();

        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }

    } else {

        $_SESSION['BookingObj'] = $data;
        echo json_encode('done');
    }

}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($data['data']) && $data['data'] == 'fetch_price') {
    $pricesql = "SELECT * FROM `cabcate`";
    $priceresult = $conn->query($pricesql);
    $prices = mysqli_fetch_all($priceresult);
    echo json_encode($prices);
}


if (isset($_SESSION['code'])) {
    if ($_SESSION['code'] == 'PAYMENT_SUCCESS') {

        $status = $_SESSION['code'];
        // Extracting values from BookingObj
        if (
            isset($_SESSION['BookingObj']['user']['id']) &&
            isset($_SESSION['BookingObj']['pickup']['mainad']) &&
            isset($_SESSION['BookingObj']['dropOff']['mainad']) &&
            isset($_SESSION['BookingObj']['totaldistance']) &&
            isset($_SESSION['BookingObj']['totalfare']) &&
            isset($_SESSION['BookingObj']['datetime'])
        ) {
            $bookingObj = $_SESSION['BookingObj'];
            $userId = sanitizeInput($bookingObj['user']['id']);
            $pickupMainad = sanitizeInput($bookingObj['pickup']['mainad']);
            $dropOffMainad = sanitizeInput($bookingObj['dropOff']['mainad']);
            $dateTime = sanitizeInput($bookingObj['datetime']);
            $totalDistance = sanitizeInput($bookingObj['totaldistance']);
            $selectedCab = sanitizeInput($bookingObj['selectedcab']);
            $totalFare = sanitizeInput($bookingObj['totalfare']);

            try {
                if ($selectedCab == 'compact') {
                    $cabs = "SELECT * FROM `cabcate` WHERE catename = 'compact' LIMIT 1";

                    if ($result = mysqli_query($conn, $cabs)) {
                        $row = mysqli_fetch_assoc($result);

                        if ($row) {
                            $selectedCabID = $row['cateid'];
                            // Perform actions with $selectedCabID
                        } else {
                            // Handle case when no rows are found
                        }

                        mysqli_free_result($result);
                    } else {
                        // Handle query execution error
                    }
                }

                if ($selectedCab == 'family') {
                    $cabs = "SELECT * FROM `cabcate` WHERE catename = 'family' LIMIT 1";

                    if ($result = mysqli_query($conn, $cabs)) {
                        $row = mysqli_fetch_assoc($result);

                        if ($row) {
                            $selectedCabID = $row['cateid'];
                            // Perform actions with $selectedCabID
                        } else {
                            // Handle case when no rows are found
                        }

                        mysqli_free_result($result);
                    } else {
                        // Handle query execution error
                    }
                }

                if ($selectedCab == 'premium') {
                    $cabs = "SELECT * FROM `cabcate` WHERE catename = 'premium' LIMIT 1";

                    if ($result = mysqli_query($conn, $cabs)) {
                        $row = mysqli_fetch_assoc($result);

                        if ($row) {
                            $selectedCabID = $row['cateid'];
                            // Perform actions with $selectedCabID
                        } else {
                            // Handle case when no rows are found
                        }

                        mysqli_free_result($result);
                    } else {
                        // Handle query execution error
                    }
                }


                $sql = "INSERT INTO `ride`(`uid`, `booking_time`, `source`, `destination`, `distance`, `fare`, `selectedCab`) 
                    VALUES ('$userId', '$dateTime', '$pickupMainad', '$dropOffMainad', '$totalDistance', '$totalFare',$selectedCabID)";

                // Execute the query
                if ($conn->query($sql) === TRUE) {

                    $lastInsertedId = $conn->insert_id;
                    $sql2 = "INSERT INTO `bookings`(`rid`, `payment_type`, `status`) VALUES ('$lastInsertedId','online','Confirmed')";
                    if ($conn->query($sql2) === TRUE) {
                        $lastInsertedId2 = $conn->insert_id;
                        $tid = $_SESSION['transection_id'];
                        $amt = $_SESSION['amount'] / 100;
                        $typ = $_SESSION['type'];
                        $sql3 = "INSERT INTO `payments`(`bookID`, `transactionID`, `status`, `type`, `amount`) VALUES ('$lastInsertedId2','$tid',' $status','$typ','$amt')";
                        if ($conn->query($sql3) === TRUE) {
                            echo "Record inserted successfully";
                        }
                    }

                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }

                // Explicitly close the database connection
                $conn->close();

            } catch (Exception $e) {
                echo "Error: " . $e->getMessage();
            }
        }
        unset($_SESSION['amount']);
        unset($_SESSION['type']);
        unset($_SESSION['transection_id']);
    } else {
        unset($_SESSION['declined_description']);
    }

    unset($_SESSION['code']);
    unset($_SESSION['BookingObj']);
    header('location: trips');


}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET["cancelBooking"]) && $_GET["cancelBooking"] != '') {

    $cancelBookingId = mysqli_real_escape_string($conn, $_GET['cancelBooking']);
    $sql2 = "UPDATE `bookings` SET `status` = 'Canceled' WHERE bookid = '$cancelBookingId'";
    $conn->query($sql2);

    header('location: trips');



}
?>