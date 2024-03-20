<?php
include ("../partials/db.php");
session_start();
$driver = $_SESSION["driveremail"];
$searchsql = "SELECT * FROM driver WHERE email='$driver'";


// Execute the SQL query (you may want to add error handling here)
$result = mysqli_query($conn, $searchsql);
$driverz = mysqli_fetch_assoc($result);
$driverid = $driverz["driverid"];

$json_data = file_get_contents("php://input");
$data = json_decode($json_data, true);
// Check if the form is submitted and subscription ID is provided
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset ($data["subscriptionId"]) && !empty ($data["subscriptionId"])) {
    // Get subscription ID from the form data and sanitize it
    $plan_id = mysqli_real_escape_string($conn, $data["subscriptionId"]);

    // Fetch subscription plan details from the database based on the provided subscription ID
    $sql = "SELECT * FROM `subscription_plan` WHERE id = $plan_id";
    $result = mysqli_query($conn, $sql);

    // Check if the query was successful
    if ($result) {
        // Check if there are any subscription plans with the provided ID
        if (mysqli_num_rows($result) > 0) {
            // Fetch the subscription plan details
            $row = mysqli_fetch_assoc($result);

            // Extract plan price and convert it to a numeric format
            $plan_price = floatval($row['sprice']); // Convert to float
// or
            $plan_price = intval($row['sprice']);   // Convert to integer
            $_SESSION['Plan_id'] = $row['id'];
            // Prepare response data
            $response = ['plan_price' => $plan_price, 'error' => false];

            // Send JSON response
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        } else {
            // Subscription ID is not valid
            $response = ['error' => true, 'message' => 'Invalid subscription ID'];
        }
    } else {
        // Error in executing the query
        $response = ['error' => true, 'message' => 'Database error'];
    }
}
if (isset ($_GET['success'])) {
    if ($_GET['success'] === 'true') {
        $code = $_GET['code'];
        $transactionId = $_GET['transactionId'];
        $amount = $_GET['amount'] / 100; // Assuming amount is in cents, convert to dollars
        $type = $_GET['type'];
        $plan_id = $_SESSION['Plan_id'];

        $insertSql = "INSERT INTO `subscription_payment`(`driverid`, `TransactionID`, `status`, `type`, `amount`) VALUES 
        ('$driverid','$transactionId','$code','$type','$amount')";
        $ins = mysqli_query($conn, $insertSql);

        if ($ins) {
            $sql = "SELECT * FROM `subscription_plan` WHERE id = $plan_id";
            $result = mysqli_query($conn, $sql);
            $plan = mysqli_fetch_assoc($result);
            $endDate = date('Y-m-d H:i:s', strtotime("+{$plan['duration']} days"));

            $insert2 = "INSERT INTO `subscription`(`subid`, `driverid`, `end_date`) VALUES
            ('$plan_id','$driverid','$endDate')";
            mysqli_query($conn, $insert2);

            $updateDriver = "UPDATE driver SET active ='0' WHERE driverid = $driverid";
            mysqli_query($conn, $updateDriver);
            header("location: ../");
            exit;
        }
    } else {
        $code = $_GET['code'];
        $transactionId = $_GET['transactionId'];
        $amount = $_GET['amount'] / 100; // Assuming amount is in cents, convert to dollars
        $type = $_GET['type'];
        $plan_id = $_SESSION['Plan_id'];

        $insertSql = "INSERT INTO `subscription_payment`(`driverid`, `TransactionID`, `status`, `type`, `amount`) VALUES 
        ('$driverid','$transactionId','$code','$type','$amount')";
        $ins = mysqli_query($conn, $insertSql);


        header("location: ../pricing");
        exit;

    }



} else {
    // Subscription ID not provided
    $response = ['error' => true, 'message' => 'Subscription ID not provided'];
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Send JSON error response for any failure case

?>