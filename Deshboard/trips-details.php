<?php
session_start();

if (isset ($_SESSION['Logged-in-user']) && isset ($_SESSION['isLoggedin']) && $_SESSION['isLoggedin'] == true) {
    require ("../mainDB.php");
    try {
        $email = mysqli_real_escape_string($conn, $_SESSION['Logged-in-user']);
        $query = "SELECT * FROM users WHERE emailID='$email'";
        $result = mysqli_query($conn, $query);

        // Check for errors in the query execution
        if (!$result) {
            throw new Exception(mysqli_error($conn));
        }

        // Fetch data as an associative array
        $row = mysqli_fetch_assoc($result);
        if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset ($_GET["Booking"]) && $_GET["Booking"] != '' && isset ($_GET['Ride']) && $_GET['Ride'] != '' && isset ($_GET['uid']) && $_GET['uid'] != '') {
            $rides = "SELECT * FROM ride WHERE rid='" . mysqli_real_escape_string($conn, $_GET['Ride']) . "'";
            $result2 = mysqli_query($conn, $rides);
            if (!$result2) {
                throw new Exception(mysqli_error($conn));
            }
            $ridedetails = mysqli_fetch_assoc($result2);

            $booking = "SELECT * FROM bookings WHERE bookid='" . mysqli_real_escape_string($conn, $_GET['Booking']) . "'";
            $result3 = mysqli_query($conn, $booking);
            if (!$result3) {
                throw new Exception(mysqli_error($conn));
            }
            $Bookdetails = mysqli_fetch_assoc($result3);



            ?>

            <!DOCTYPE html>
            <html lang="en">

            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>E-CAB | Your Trips</title>
                <link rel="stylesheet" href="static/css/deshboard.css">
                <link rel="icon" type="image/png" href="static/pictures/favicon.png">

                <link rel="preconnect" href="https://fonts.googleapis.com" />
                <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
                <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
                    rel="stylesheet" />
                <!-- done  p-->
                <link rel="preconnect" href="https://fonts.googleapis.com" />
                <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
                <link
                    href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
                    rel="stylesheet" />
                <!-- josef font -->
                <link rel="preconnect" href="https://fonts.googleapis.com">
                <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
                <link
                    href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100..700;1,100..700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
                    rel="stylesheet">
                <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
                <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

                <!-- Include jQuery and Typeahead.js -->
                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/typeahead.js/0.11.1/typeahead.bundle.min.js"></script>

                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
                <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

            </head>
            <style>
                .trips span {
                    margin: 0 auto;
                    color: rgb(1, 0, 32);
                    font-family: "Montserrat";
                    /* font-family: "Poppins"; */
                    font-size: 14px;
                    font-style: normal;
                    font-weight: 500;
                    line-height: normal;
                }

                .trips {
                    padding: 0px 25px;
                    height: 63px;
                    display: grid;
                    grid-template-columns: 0.5fr 1fr 1fr 1fr 1fr 1fr;
                    align-items: center;
                    margin: 12px 0px;
                    background: #e5e4ed;
                    box-shadow: none;
                    width: 74vw;

                }

                .taskDiv {
                    align-items: unset;
                }

                @media screen and (max-width: 750px) {
                    .trips {
                        padding: 0 4px;
                        width: 91vw;
                    }

                    .trips span {
                        font-size: 10px;
                    }

                    .trips span .main-btn {
                        font-size: 10px;
                    }

                    .booktext,
                    .booktext2 {
                        font-size: 14px;
                    }
                }


                .dropbox {
                    position: absolute;
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    padding: 15px 5px;
                    gap: 10px;
                    width: 200px;
                    background: rgb(249, 249, 255);
                    top: 76px;

                    box-shadow: 0px 4px 10px 4px rgba(0, 0, 0, 0.14);
                    border-radius: 15px;

                    /* -webkit-box-shadow: 3px 5px 50px -11px #000000;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      box-shadow: 3px 5px 50px -11px #313131; */
                    visibility: hidden;
                    transform: translateX(-20px);
                    opacity: 0;
                    transition: all 0.5s ease-in-out;
                    z-index: 10000000000000000;

                }

                body:has(.user-image:hover) .dropbox,
                .dropbox:hover {

                    visibility: visible;
                    opacity: 1;
                }

                .dropbox a {
                    z-index: 10000000000000000;
                    text-decoration: none;
                    display: inline-flex;
                    height: 40px;
                    padding: 0px 12px;
                    justify-content: center;
                    align-items: center;
                    border-radius: 1000px;

                    margin: 0px 5px;


                    color: rgb(2 0 92);
                    font-family: "Montserrat";
                    /* font-family: "Poppins"; */
                    font-size: 14px;
                    font-style: normal;
                    font-weight: 500;
                    line-height: normal;
                    transition: all 0.2s ease-in;
                }

                .dropbox a:hover {
                    background-color: rgb(226, 225, 247);
                }

                .mydiv {
                    background-color: red;
                    max-height: calc(100vh - 40vh);
                    overflow-y: auto;
                    width: max-content;
                }

                .tripsdiv {
                    display: flex;
                    flex-direction: column;
                    margin: 0px auto;
                }

                .booktext {
                    color: rgb(1, 0, 32);
                    font-family: "Montserrat";
                    /* font-family: "Poppins"; */
                    font-size: 13px;
                    font-style: normal;
                    font-weight: 600;
                    line-height: normal;
                }

                .booktext2 {
                    color: rgb(1, 0, 32);
                    font-family: "Montserrat";
                    /* font-family: "Poppins"; */
                    font-size: 13px;
                    font-style: normal;
                    font-weight: 500;
                    line-height: normal;
                }

                .details {
                    display: flex;
                    flex-direction: row;

                    padding: 5px;
                    margin: 5px 0px;
                    gap: 5px;
                }
            </style>

            <body>
                <nav>
                    <div class="logo">
                        <img src="static/pictures/logo2.png" class="logo-img" alt="logo">
                    </div>
                    <div class="btns"><a href="#" class="main-btn Mytrips">My Trips</a></div>
                    <div class="user-div">

                        <div class="btns"><a href="trips.html" class="main-btn Mytrips">My Trips</a></div>

                        <img src="<?php echo '../' . trim($row['userImage']); ?>" class="user-image" alt="">
                        <div class="dropbox">
                            <a href="#">Manage Profile</a>
                            <a href="log-out">Log out</a>
                        </div>

                    </div>
                </nav>
                <main>
                    <div class="display" style="grid-template-columns: 1fr; grid-template-rows: 1fr;">
                        <div class="tripsdiv">
                            <div class="text">
                                <h1 class="title" style="margin: 15px 0px;">Trips Details</h1>
                            </div>
                            <div class="bookCtn">
                                <div class="details">
                                    <p class="booktext">Source:</p>
                                    <p class="booktext2">
                                        <?php echo $ridedetails['source']; ?>
                                    </p>
                                </div>
                                <div class="details">
                                    <p class="booktext">Destination:</p>
                                    <p class="booktext2">
                                        <?php echo $ridedetails['destination']; ?>
                                    </p>
                                </div>
                                <div class="details">
                                    <p class="booktext">Pickup Datetime:</p>
                                    <p class="booktext2">
                                        <?php echo $ridedetails['booking_time']; ?>
                                    </p>
                                </div>
                                <div class="details">
                                    <p class="booktext">Booked on:</p>
                                    <p class="booktext2">
                                        <?php echo $ridedetails['book_time']; ?>
                                    </p>
                                </div>

                                <div class="details">
                                    <p class="booktext">Total Fare:</p>
                                    <p class="booktext2">₹
                                        <?php echo $ridedetails['fare']; ?>
                                    </p>
                                </div>

                                <div class="details">
                                    <p class="booktext">Payment:</p>
                                    <p class="booktext2">
                                        <?php echo $Bookdetails['payment_type']; ?>
                                    </p>
                                </div>
                                <div class="details">
                                    <p class="booktext">Payment Status:</p>
                                    <p class="booktext2">
                                        <?php
                                        if ($Bookdetails['payment_type'] == 'online') {
                                            $paydetails = "SELECT * FROM payments WHERE bookID='" . mysqli_real_escape_string($conn, $_GET['Booking']) . "'";
                                            $result4 = mysqli_query($conn, $paydetails);
                                            if (!$result4) {
                                                throw new Exception(mysqli_error($conn));
                                            }
                                            $Paymentdetails = mysqli_fetch_assoc($result4);
                                            echo $Paymentdetails['status'];
                                        } else {
                                            echo "-";
                                        }
                                        ?>
                                    </p>
                                </div>
                                <div class="details">
                                    <p class="booktext">Booking Status:</p>
                                    <?php
                                    $status = $Bookdetails['status'];
                                    $color = "rgb(1, 0, 32)"; // Default color
                        
                                    if ($status == 'Confirmed' || $status == 'Completed') {
                                        $color = "green";
                                    } elseif ($status == 'Pending') {
                                        $color = "orange";
                                    } elseif ($status == 'Cancelled') {
                                        $color = "red";
                                    }
                                    ?>

                                    <p class="booktext2" style="color: <?php echo $color; ?>">
                                        <?php echo $status; ?>
                                    </p>

                                </div>
                                <div class="details">
                                    <p class="booktext">Selected Cab:</p>
                                    <p class="booktext2">
                                        <?php
                                        $driver = false;
                                        $cabid = $ridedetails['selectedCab'];
                                        $cars = 'SELECT catename from cabcate where cateid=' . $cabid . '';
                                        if ($row = mysqli_query($conn, $cars)) {
                                            if ($cabname = mysqli_fetch_assoc($row)) {
                                                echo $cabname['catename'];
                                            } else {
                                                echo '-';
                                            }
                                        } else {
                                            echo '-';
                                        }
                                        ?>
                                    </p>
                                </div>
                                <div class="details">
                                    <p class="booktext">Driver Status:</p>
                                    <p class="booktext2">
                                        <?php
                                        if ($Bookdetails['reqID'] != NULL) {
                                            $bookid = $Bookdetails['bookid'];
                                            $ride = "SELECT * FROM `driverrequest` WHERE bookid=$bookid";
                                            $rides = mysqli_query($conn, $ride);
                                            while ($req = mysqli_fetch_assoc($rides)) {
                                                if ($req['status'] == 'Accepted') {
                                                    $did = $req['driverID'];
                                                    $drive = "SELECT * FROM `driver` WHERE driverid=$did";
                                                    $riders = mysqli_query($conn, $drive);
                                                    $driver = mysqli_fetch_assoc($riders);
                                                    echo $req['status'];
                                                    break;
                                                } else if ($req['status'] == 'Pickup') {
                                                    $did = $req['driverID'];
                                                    $drive = "SELECT * FROM `driver` WHERE driverid=$did";
                                                    $riders = mysqli_query($conn, $drive);
                                                    $driver = mysqli_fetch_assoc($riders);
                                                    echo $req['status'];
                                                    break;
                                                } else if ($req['status'] == 'Complete') {
                                                    $did = $req['driverID'];
                                                    $drive = "SELECT * FROM `driver` WHERE driverid=$did";
                                                    $riders = mysqli_query($conn, $drive);
                                                    $driver = mysqli_fetch_assoc($riders);
                                                    echo $req['status'];

                                                } else if ($req['status'] == 'pending') {
                                                    echo "Pending";
                                                } else {

                                                }
                                            }

                                        } else {
                                            echo "Pending";
                                        }
                                        ?>
                                    </p>
                                </div>
                                <div class="details">
                                    <p class="booktext">Driver Name:</p>
                                    <p class="booktext2">
                                        <?php
                                        if ($Bookdetails['reqID'] != NULL) {
                                            if ($driver) {
                                                echo $driver["firstname"] . " " . $driver["lastname"];
                                            }
                                        } else {
                                            echo "-";
                                        }
                                        ?>
                                    </p>
                                </div>
                                <div class="details">
                                    <p class="booktext">Driver Mobile:</p>
                                    <p class="booktext2">
                                        <?php
                                        if ($Bookdetails['reqID'] != NULL) {
                                            if ($driver) {
                                                echo $driver["phone"];
                                            }
                                        } else {
                                            echo "-";
                                        }
                                        ?>
                                    </p>
                                </div>
                                <div class="details">
                                    <p class="booktext">Driver Cab No:</p>
                                    <p class="booktext2">
                                        <?php
                                        if ($Bookdetails['reqID'] != NULL) {
                                            if ($driver) {
                                                echo $driver["carno"];
                                            }
                                        } else {
                                            echo "-";
                                        }
                                        ?>
                                    </p>
                                </div>

                                <?php
                                if ($Bookdetails['status'] != 'Cancelled' && $Bookdetails['status'] != 'Completed') { ?>
                                    <div class="btns"><a href="function?cancelBooking=<?php echo $Bookdetails['bookid']; ?>"
                                            class="main-btn Mytrips" style="color:rgb(183, 1, 1); font-size: 13px;">Cancel
                                            the Trip</a></div>
                                    <?php
                                } ?>

                            </div>
                        </div>


                    </div>
                </main>

            </body>

            </html>
            <?php
        }
    } catch (Exception $e) {

    } finally {

        mysqli_close($conn);
    }
    // Your PHP code here
} else {
    header("Location:../");
    exit();
}
?>