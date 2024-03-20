<?php
session_start();
$driver = $_SESSION["driveremail"];

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Upgrage the Existing Plan</title>
    <?php include ("partials/_links.php"); ?>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="shortcut icon" href="../static/pictures/favicon.png" />
</head>
<style>
    .card {

        border-radius: 30px;
    }
</style>

<body>




    <nav class="navbar" style="left:0; padding:10px 40px;">
        <a class="navbar-brand" href="#">
            <img src="./../static/pictures/logo.png" style="height: 40px;" alt="logo">

        </a>
    </nav>

    <div class="container-scroller">



        <!-- partial -->

        <div class="main container-fluid " style="width: 100%; padding: 30px 0px; background : black;">
            <div style="margin:5px 20px;">
                <div class="row" style="margin:10px auto">
                    <div class="col-12 grid-margin stretch-card">
                        <div class="card corona-gradient-card">
                            <div class="card-body py-0 px-0 px-sm-3">
                                <div class="row align-items-center">
                                    <div class="col-4 col-sm-3 col-xl-2">
                                        <img src="assets/images/dashboard/Group126@2x.png"
                                            class="gradient-corona-img img-fluid" alt="">
                                    </div>
                                    <div class="col-5 col-sm-7 col-xl-8 p-0">
                                        <h4 class="mb-1 mb-sm-0">Take Super Subscription to Drive the future</h4>
                                        <p class="mb-0 font-weight-normal d-none d-sm-block">Unlock Your Own Driver
                                            Deshboard!</p>
                                    </div>
                                    <div class="col-3 col-sm-2 col-xl-2 ps-0 text-center">
                                        <span>
                                            <a href="#" class="btn btn-outline-light btn-rounded get-started-btn">Learn
                                                More</a>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



                <div class="row" style="margin:0px auto">

                    <?php
                    include ("path.php");
                    include inc . 'db.php';
                    // Assuming you have already established a database connection and fetched data from the 'subscription_plan' table
                    
                    // Fetch subscription plans from the database
                    $sql = "SELECT * FROM `subscription_plan`";
                    $result = mysqli_query($conn, $sql);

                    // Check if there are any subscription plans
                    if (mysqli_num_rows($result) > 0) {
                        // Loop through each subscription plan
                        // Loop through each subscription plan
                        while ($row = mysqli_fetch_assoc($result)) {
                            // Calculate duration in months (assuming 30 days per month)
                            $duration_months = ceil($row['duration'] / 30);

                            // Output HTML dynamically for each subscription plan
                            ?>
                            <div class="col-md-6 col-xl-4 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex flex-row justify-content-center">
                                            <h1 class="card-title">
                                                <?php echo $row['sname']; ?>
                                            </h1>
                                        </div>
                                        <div class="dropdown-divider"></div>

                                        <div class="preview-list justify-content-center">
                                            <ul class="list-group list-group-flush text-center">
                                                <li class="list-group-item" style="color:white; background:inherit;">Plan
                                                    Duration:
                                                    <?php echo $duration_months; ?> Months
                                                </li>
                                                <li class="list-group-item" style="color:white; background:inherit;">Online
                                                    Payment Support</li>
                                                <li class="list-group-item" style="color:white; background:inherit;">Efficient
                                                    Dashboard</li>
                                                <li class="list-group-item" style="color:white; background:inherit;">Nearest
                                                    Bookings</li>
                                                <li class="list-group-item" style="color:white; background:inherit;">24/7
                                                    Customer Support</li>
                                            </ul>
                                            <div class="dropdown-divider"></div>
                                            <div class="d-flex flex-row justify-content-center m-3">
                                                <h1 class="card-title">₹
                                                    <?php echo $row['sprice']; ?>
                                                </h1>
                                            </div>
                                            <div class="d-flex flex-row justify-content-center">
                                                <button type="button" class="btn btn-info btn-rounded btn-fw"
                                                    onclick="buySubscription(<?php echo $row['id']; ?>)">Buy</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }

                    } else {
                        // If no subscription plans found
                        echo "No subscription plans found.";
                    }

                    // Close the database connection
                    mysqli_close($conn);
                    ?>

                </div>
            </div>
        </div>

        <script>
            function buySubscription(subscriptionId) {
                // Fetch API to send subscription ID to server for payment processing
                fetch('payment/payFunction.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        subscriptionId: subscriptionId
                    }),
                })
                    .then(response => response.json()) // Parse response as JSON
                    .then(data => {

                        // Check if there's an error in the response
                        if (data.error) {
                            // Handle the error, such as displaying an error message
                            console.error('Error:', data.message);
                        
                        } else {
                            // Process the successful response, such as redirecting to payment page
                            console.log('Subscription price:', data.plan_price);
                            pay(data.plan_price)
                        }
                    })
                    .catch((error) => {
                        // Handle network errors or other exceptions
                        console.error('Error:', error);
                    });
            }


            function pay(amt) {
                fetch('payment/payment-script.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        name: '<?php echo $driver; ?>', // Echo the PHP variable $driver
                        amount: amt,
                        email: '<?php echo $driver; ?>',
                        // Add other properties if needed
                    })
                })

                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json(); // Assuming the response is in JSON format
                    })
                    .then(data => {
                        // Handle the response data as needed
                        console.log(data);

                        // If there are no errors, you can redirect or perform other actions
                        if (!data.error) {
                            // Redirect example
                            // window.location.href = data.payment_url;
                            // Replace the current URL with the payment URL
                            window.location.replace(data.payment_url);

                        }
                    })
                    .catch(error => {
                        // Handle errors
                        console.error('Error making payment:', error);
                    });
            }
        </script>
        <!-- page-body-wrapper ends -->
    </div>

    <?php include ("partials/_scripts.php"); ?>
</body>

</html>