<?php
session_start();

if (isset ($_SESSION['Logged-in-user']) && isset ($_SESSION['isLoggedin']) && $_SESSION['isLoggedin'] == true) {
    require ("../mainDB.php");
    try {
        // Escape the user input to prevent SQL injection
        $email = mysqli_real_escape_string($conn, $_SESSION['Logged-in-user']);
        // $id = mysqli_real_escape_string($conn, $_SESSION['id']);

        // Execute the query
        $query = "SELECT * FROM users WHERE emailID='$email'";
        $result = mysqli_query($conn, $query);

        // Check for errors in the query execution
        if (!$result) {
            throw new Exception(mysqli_error($conn));
        }

        // Fetch data as an associative array
        $row = mysqli_fetch_assoc($result);
        $rides = "SELECT * FROM ride WHERE uid='" . $row['id'] . "' ORDER BY book_time ASC";
        $result2 = mysqli_query($conn, $rides);
        // Handle the data (you can modify this part based on your needs)

        // echo '<pre>';
        // print_r($row);
        // echo '</pre>';



        ?>
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

                .dropbox {}


                .taskDiv {
                    grid-row: 1;
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
                transform: translateX(-65px);
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

                max-height: calc(100vh - 40vh);
                overflow-y: auto;
                width: max-content;
            }

            .tripsdiv {
                display: flex;
                flex-direction: column;
                margin: 0px auto;
            }

            /* .trips {
                                                                        width: 40vw;
                                                                    } */
        </style>

        <body>
            <nav>
                <div class="logo"><a href="./">
                        <img src="static/pictures/logo2.png" class="logo-img" alt="logo"></a>
                </div>
                <!-- <div class="btns"><a href="#" class="main-btn Mytrips">My Trips</a></div> -->
                <div class="user-div">

                    <!-- <div class="btns"><a href="trips.html" class="main-btn Mytrips">My Trips</a></div> -->

                    <img src="<?php echo '../' . trim($row['userImage']); ?>" class="user-image" alt="">
                    <div class="dropbox">
                        <a href="#">Manage Profile</a>
                        <a href="log-out">Log out</a>
                    </div>

                </div>
            </nav>
            <main>
                <div class="display" style="grid-template-columns: 1fr; grid-template-rows: 1fr;">
                    <div class="taskDiv">
                        <div class="tripsdiv">
                            <h1 class="title" style="margin: 30px 0px;">Recent Trips</h1>
                            <div class="mydiv">
                                <?php
                                $count = 1;
                                while ($ride = mysqli_fetch_assoc($result2)) {
                                    $bookings = mysqli_query($conn, "SELECT * FROM bookings WHERE rid='" . $ride['rid'] . "'");

                                    if ($bookings) {
                                        $bookingdata = mysqli_fetch_assoc($bookings);

                                        if ($bookingdata) {
                                            $status = $bookingdata['status'];
                                            $color = "rgb(1, 0, 32)"; // Default color
                        
                                            if ($status == 'Confirmed' || $status == 'Completed') {
                                                $color = "green";
                                            } elseif ($status == 'Pending') {
                                                $color = "orange";
                                            } elseif ($status == 'Cancelled') {
                                                $color = "red";
                                            }

                                            echo '<div class="bookCtn trips">
                                                        <span>' . $count . '</span>
                                                        <span>' . $ride['booking_time'] . '</span>
                                                        <span>' . $ride['source'] . '</span>
                                                        <span>' . $ride['destination'] . '</span>
                                                        <span style="color: ' . $color . '">' . $status . '</span>
                                                        <span><a href="trips-details?Booking=' . $bookingdata['bookid'] . '&Ride=' . $ride['rid'] . '&uid=' . $row['id'] . '" class="main-btn">View Details</a></span>
                                                    </div>';

                                            $count++;
                                        }
                                    }
                                }
                                ?>

                            </div>

                            <!-- <div class="bookCtn trips">
                                <span>1</span>
                                <span>23-09-2024</span>
                                <span>Shahibaug</span>
                                <span>Nava Vadaj</span>
                                <span style="color: green;">Confirmed</span>
                                <span><a href="#" class="main-btn">View Details</a></span>
                            </div>
                            <div class="bookCtn trips">
                                <span>2</span>
                                <span>23-09-2024</span>
                                <span>Shahibaug</span>
                                <span>Nava Vadaj</span>
                                <span style="color: green;">Confirmed</span>
                                <span><a href="#" class="main-btn">View Details</a></span>
                            </div>
                            <div class="bookCtn trips">
                                <span>3</span>
                                <span>23-09-2024</span>
                                <span>Shahibaug</span>
                                <span>Nava Vadaj</span>
                                <span style="color: green;">Confirmed</span>
                                <span><a href="#" class="main-btn">View Details</a></span>
                            </div> -->



                        </div>
                    </div>
                </div>
            </main>

        </body>

        </html>
        <?php
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