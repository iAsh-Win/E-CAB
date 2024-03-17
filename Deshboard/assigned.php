<?php
session_start();

if (isset ($_SESSION['Logged-in-user']) && isset ($_SESSION['isLoggedin']) && $_SESSION['isLoggedin'] == true) {
    require ("../mainDB.php");
    try {

        if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset ($_GET["bookid"]) && $_GET["bookid"] != '') {

            // $bookings_query = "SELECT reqID FROM bookings WHERE bookid='" . mysqli_real_escape_string($conn, $_GET['bookid']) . "'";
            // if ($result2 = mysqli_query($conn, $bookings_query)) {
            //     if ($reqID_row = mysqli_fetch_assoc($result2)) {
            //         $reqid = null;
            //         while ($reqid == null) {
            //             echo '<script>
            //             document.getElementById("divz1").style.display = "flex";
            //             document.getElementById("divz2").style.display = "none";
            //         </script>';
            //             if ($reqID_row["reqID"] != NULL) {

            //                 $reqid = $reqID_row["reqID"];
            //             } else {



            //                 // Wait for a short duration before checking again to avoid overwhelming the server
            //                 usleep(2000000); // Sleep for 2 seconds
            //                 // Sleep for 500 milliseconds (0.5 seconds)
            //             }
            //         }
            //         if ($reqID_row["reqID"] != NULL) {

            //             // Once reqid is found, continue with the rest of the code
            //             $ride_query = "SELECT * FROM `driverrequest` WHERE reqID=$reqid";
            //             $rides = mysqli_query($conn, $ride_query);
            //             $req = mysqli_fetch_assoc($rides);
            //             if ($req['status'] == 'Accepted') {

            //                 $did = $req['driverID'];
            //                 $driver_query = "SELECT * FROM `driver` WHERE driverid=$did";
            //                 $drivers = mysqli_query($conn, $driver_query);
            //                 $driverdetails = mysqli_fetch_assoc($drivers);
            //                 $driver_location_query = "SELECT * FROM driver_location WHERE driverid='" . mysqli_real_escape_string($conn, $req['driverID']) . "'";
            //                 $result3 = mysqli_query($conn, $driver_location_query);
            //                 if (!$result3) {
            //                     throw new Exception(mysqli_error($conn));
            //                 }
            //                 $locationdetails = mysqli_fetch_assoc($result3);


            //             }
            //         }
            //     }
            // }



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
                    .booktext2,
                    .title {
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
                    margin: auto;
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

                .image img {
                    border-radius: 50%;
                    box-sizing: border-box;
                    display: block;
                    height: 100px;
                    width: 100px;
                    object-fit: cover;
                    margin: 10px auto;
                }

                #map {
                    height: 200px;
                    border-radius: 19px;
                }

                .loader-container {
                    display: none;
                    position: relative;
                    margin-top: 20px;
                }

                .loader {
                    border: 8px solid #d0d0ff;
                    border-top: 8px solid rgb(4, 0, 138);
                    border-radius: 50%;
                    width: 50px;
                    height: 50px;
                    animation: spin 1s linear infinite;
                    /* position: absolute; */
                    top: 50%;
                    left: 50%;
                    margin: 0px;
                }

                @keyframes spin {
                    0% {
                        transform: rotate(0deg);
                    }

                    100% {
                        transform: rotate(360deg);
                    }
                }
            </style>



            <script>
                // Function to fetch updated data
                // Function to fetch updated data
                function fetchData() {
                    $.ajax({
                        url: 'assigned-fetch.php?bookid=<?php echo $_GET["bookid"]; ?>',
                        type: 'GET',
                        success: function (response) {
                            console.log("hii " + new Date().toLocaleTimeString());


                            // Check if response contains data
                            if (response) {
                                var data = JSON.parse(response);
                                if (data && data.driverdetails) {

                                    var lat = data.locationdetails.latitude;
                                    var lon = data.locationdetails.longitude;

                                    // Update HTML content with driver details
                                    var driverdetails = data.driverdetails;
                                    document.getElementById("driver-image").src = '../driver/' + driverdetails.photo;
                                    document.getElementById("driver-name").innerText = driverdetails.firstname + " " + driverdetails.lastname;
                                    document.getElementById("driver-mobile").innerText = driverdetails.phone;
                                    document.getElementById("cab-number").innerText = driverdetails.carno;
                                   
                                    // Update UI with driver details and location
                                    document.getElementById("divz1").style.display = "none";
                                    document.getElementById("divz2").style.display = "flex";
                                    map(lat, lon);
                                } else {
                                    // Display message if no driver details available
                                    console.log('Driver details not available');
                                }
                            } else {
                                // Display message if response is empty
                                console.log('No data received');
                            }
                        },
                        error: function (xhr, status, error) {
                            // Handle errors
                            console.error(error);
                        },
                        complete: function () {
                            // Schedule next fetch after a delay (e.g., 5 seconds)
                            setTimeout(fetchData, 5000); // 5 seconds
                        }
                    });
                }

                // Call fetchData function to start fetching data
                fetchData();


                // Call fetchData function to start fetching data
                fetchData();
            </script>

            <body>
                <nav>
                    <div class="logo">
                        <img src="static/pictures/logo2.png" class="logo-img" alt="logo">
                    </div>
                    <div class="btns"><a href="#" class="main-btn Mytrips">My Trips</a></div>
                    <div class="user-div">

                        <div class="btns"><a href="trips.html" class="main-btn Mytrips">My Trips</a></div>

                        <img src="static/pictures/favicon.png" class="user-image" alt="">
                        <div class="dropbox">
                            <a href="#">Manage Profile</a>
                            <a href="#">Log out</a>
                        </div>

                    </div>
                </nav>
                <main>
                    <div class="display" style="grid-template-columns: 1fr; grid-template-rows: 1fr;">
                        <div class="tripsdiv" id="divz1" style="align-items: center;gap: 30px;">
                            <div class="text">
                                <h1 class="title" style="margin: 15px 0px;">Please wait, we are finding most nearest driver to you.
                                </h1>
                            </div>
                            <div id="loader" class="loader-container">
                                <div class="loader"></div>
                                <script>document.getElementById('loader').style.display = 'block';</script>
                            </div>
                            <div class="text">
                                <div class="btns" style="margin: 15px 0px;"><a href="trips" class="main-btn Mytrips">Go to Trips</a>
                                </div>
                            </div>

                        </div>


                        <div class="tripsdiv" id="divz2" style="display:none;">
                            <div class="text">
                                <h1 class="title" style="margin: 15px 0px;">You have been assigned nearest driver</h1>
                            </div>
                            <div class="image"><img id="driver-image" src="" alt=""></div>
                            <div class="info" style="margin: 20px auto;">
                                <div class="details">
                                    <p class="booktext">Driver Name:</p>
                                    <p class="booktext2" id="driver-name"></p>
                                </div>
                                <div class="details">
                                    <p class="booktext">Driver Mobile:</p>
                                    <p class="booktext2" id="driver-mobile"></p>
                                </div>
                                <div class="details">
                                    <p class="booktext">Cab Number:</p>
                                    <p class="booktext2" id="cab-number"></p>
                                </div>
                            </div>

                            <div class="info mapDiv" id="map">
                            </div>
                            <div class="info" style="margin: 10px auto;">
                                <div class="btns"><a href="trips" class="main-btn Mytrips">Go to Trips</a></div>
                            </div>
                        </div>

                    </div>
                </main>

                <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
                <script>
                    map(lat, lon);
                    // console.log(lat)
                    //     console.log(lon)
                    // document.addEventListener("DOMContentLoaded", function () {

                    //     var map = L.map('map').setView([22.5726, 88.3639], 15); // Set the initial center and zoom level

                    //     L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    //         attribution: '&copy; OpenStreetMap contributors'
                    //     }).addTo(map);
                    //     map.zoomControl.remove();
                    //     map.attributionControl.setPrefix('E=Cab'); // This sets an empty prefix
                    //     map.attributionControl.remove();

                    //     var singleLocation = L.latLng(lat, lon); // Example coordinates for Kolkata, India
                    //     var singleMarker = L.marker(singleLocation).addTo(map);

                    //     // Fit the map to the bounds of the single marker
                    //     map.setView(singleLocation, 15);
                    // });
                    function map(lat, lon) {

                        var map = L.map('map').setView([22.5726, 88.3639], 15); // Set the initial center and zoom level

                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '&copy; OpenStreetMap contributors'
                        }).addTo(map);
                        map.zoomControl.remove();
                        map.attributionControl.setPrefix('E=Cab'); // This sets an empty prefix
                        map.attributionControl.remove();

                        var singleLocation = L.latLng(lat, lon); // Example coordinates for Kolkata, India
                        var singleMarker = L.marker(singleLocation).addTo(map);

                        // Fit the map to the bounds of the single marker
                        map.setView(singleLocation, 15);
                    }
                </script>


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