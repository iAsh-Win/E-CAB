<?php
session_start();

if (isset($_SESSION['Logged-in-user']) && isset($_SESSION['isLoggedin']) && $_SESSION['isLoggedin'] == true) {
    require ("../mainDB.php");
    try {

        if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET["did"]) && $_GET["did"] != '' && isset($_GET["bookid"]) && $_GET["bookid"] != '') {
            $email = mysqli_real_escape_string($conn, $_SESSION['Logged-in-user']);

            // Execute the query
            $query = "SELECT * FROM users WHERE emailID='$email'";
            $result = mysqli_query($conn, $query);
            $row = mysqli_fetch_assoc($result);


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
                <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"
                    integrity="sha512-0s3pK34VJqJ/dmEPPSr9y7q+7k1TL5MRlBC3ddcMVBQRCQ9Qt4KVrvvXzKkh0S8cQKxmjz8Fs5CwqL9cvOvjsA=="
                    crossorigin="" />


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

                    margin: auto 0;
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
                    height: 600px;
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

            </script>

            <body>
                <nav>
                    <div class="logo"><a href="./">
                            <img src="static/pictures/logo2.png" class="logo-img" alt="logo"></a>
                    </div>

                    <div class="user-div">

                        <div class="btns"><a href="trips" class="main-btn Mytrips">My Trips</a></div>

                        <img src="<?php echo '../' . trim($row['userImage']); ?>" class="user-image" alt="userimage" id="pf">
                        <div class="dropbox">
                            <a href="manage-profile">Manage Profile</a>
                            <a href="feedback">Give Feedback</a>
                            <a href="log-out">Log out</a>
                        </div>

                    </div>
                </nav>


                <main>
                    <div class="display" style="grid-template-columns: 1fr; grid-template-rows: 1fr;">
                        <div class="tripsdiv">
                            <div class="info mapDiv" id="map"></div>
                        </div>
                    </div>
                </main>
                <?php
                if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET["did"]) && $_GET["did"] != '' && isset($_GET["bookid"]) && $_GET["bookid"] != '') {
                    $did = $_GET['did'];

                    $latitude = 0;
                    $longitude = 0;


                } else {
                    // Display a message if either 'a' or 'b' is not set
                    echo "Sorry, You Can't Access this Page !";
                }
                ?>

                <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
                <script>
                    let map;
                    let marker;
                    const did = <?php echo json_encode($did); ?>;

                    // Initialize the map with the initial coordinates and zoom level
                    function initializeMap(lat, lon) {
                        map = L.map('map').setView([lat, lon], 15);

                        // Add OpenStreetMap tiles
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '&copy; OpenStreetMap contributors'
                        }).addTo(map);

                        // Add a marker at the initial coordinates
                        marker = L.marker([lat, lon]).addTo(map);
                    }

                    // Function to update driver location on the map and in the database
                    function updateDriverLocation() {
                        // Retrieve PHP variables from the server for 'did' and 'bookid'
                        const did = <?php echo json_encode($_GET['did']); ?>;
                        const bookid = <?php echo json_encode($_GET['bookid']); ?>;

                        // Construct the URL for fetching the latest driver location
                        const url = `trackDriver?did=${did}&bookid=${bookid}`;

                        // Fetch the latest driver location from the server
                        fetch(url)
                            .then(response => response.json())
                            .then(data => {

                                if (data.latitude && data.longitude) {
                                    const newLat = data.latitude;
                                    const newLon = data.longitude;

                                    // Update the marker's position on the map
                                    marker.setLatLng([newLat, newLon]);

                                    // Optionally, center the map on the new location (uncomment if desired)
                                    map.setView([newLat, newLon], 65);
                                } else {
                                    console.error('Invalid data format: expected latitude and longitude as numbers.');
                                }
                            })
                            .catch(error => {
                                // Handle any errors that occur during the fetch request
                                console.error('Error fetching driver location:', error);
                            });

                    }

                    // Call the initializeMap function with initial coordinates
                    const lat = <?php echo json_encode($latitude); ?>;
                    const lon = <?php echo json_encode($longitude); ?>;
                    initializeMap(lat, lon);

                    // Start updating the driver's location dynamically every few seconds
                    setInterval(updateDriverLocation, 5000); // Update every 5 seconds

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