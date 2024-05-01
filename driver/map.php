<?php
include ("path.php");

// Your PHP code here
session_start();

if (
    isset($_SESSION["driver_login"]) && $_SESSION["driver_login"] == true &&
    isset($_SESSION["driveremail"]) && $_SESSION["driveremail"] != ""
) {
    include inc . 'db.php';
    $driver = $_SESSION["driveremail"];
    $searchsql = "SELECT * FROM driver WHERE email='$driver'";

    // Execute the SQL query (you may want to add error handling here)
    $result = mysqli_query($conn, $searchsql);

    // Fetch the data
    if ($row = mysqli_fetch_assoc($result)) {
        $did = $row['driverid'];
        $name = $row["firstname"] . " " . $row["lastname"];



        ?>

        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>E-CAB | Your Trips</title>
            <link rel="stylesheet" href="static/css/deshboard.css">
            <link rel="icon" type="i../mage/png" href="static/pictures/favicon.png">
            <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
            <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />

            <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
            <script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>
            <style>
                body {
                    background: rgb(245, 245, 255);
                }

                #map {
                    height: 87vh;
                    /* Set the desired height */
                    width: 100%;
                    /* Full width */
                    margin: 15px auto;

                }

                .tooltip {
                    color: rgb(0, 0, 34);
                    font-family: "Poppins";
                    font-size: 14px;
                    font-style: normal;
                    font-weight: 500;
                    line-height: normal;
                }
            </style>
        </head>

        <body>
            <script>
                var mylat, mylon;
                let intervalId;
                let lastSavedLocation = null;

                function intvl() {
                    setInterval(updateLocation, 20000);
                }
                let i = 1;


                // Call the intvl function to start the interval
                intvl();



                function stopLocationUpdates() {
                    clearInterval(intervalId);
                }

                function updateLocation() {
                    console.log(i);
                    i++
                    if ("geolocation" in navigator) {
                        navigator.geolocation.getCurrentPosition(
                            // Success callback
                            function (position) {
                                const driverId = <?php echo $row['driverid']; ?>;
                                const latitude = position.coords.latitude;
                                const longitude = position.coords.longitude;
                                mylat = position.coords.latitude;
                                mylon = position.coords.longitude;

                                // Check if the location has changed
                                if (
                                    lastSavedLocation &&
                                    latitude === lastSavedLocation.latitude &&
                                    longitude === lastSavedLocation.longitude
                                ) {
                                    console.log('Location has not changed.');
                                    return;
                                }

                                const formData = new FormData();
                                formData.append('driverId', driverId);
                                formData.append('latitude', latitude);
                                formData.append('longitude', longitude);

                                // Make an AJAX request to update the location
                                fetch('update-location.php', {
                                    method: 'POST',
                                    body: formData
                                })
                                    .then(response => {
                                        if (!response.ok) {
                                            throw new Error('Network response was not ok');
                                        }
                                        return response.text();
                                    })
                                    .then(data => {
                                        console.log('Location updated successfully:', data);
                                        // Update the lastSavedLocation with the new location
                                        lastSavedLocation = {
                                            latitude,
                                            longitude
                                        };
                                    })
                                    .catch(error => {
                                        console.error('Error updating location:', error);
                                    });
                            },
                            function (error) {
                                console.error("Error getting location:", error.message);
                            }
                        );
                    } else {
                        console.error("Geolocation is not supported by this browser.");
                    }
                }

            </script>

            <nav style="display: flex;">
                <div class="logo"><a href="./">
                        <img src="static/pictures/logo2.png" class="logo-img" alt="logo"></a>
                </div>
                <div class="btns"><a onclick="cm()" class="main-btn Mytrips">Complete the Ride</a></div>
                <div class="user-div">


                </div>
            </nav>
            <div class="container" style="max-width:1400px; margin:auto;">
                <div class="row">
                    <div class=" p-0 col-12 grid-margin">
                        <div class="card" style="outline:none; border:none;  background: rgb(245, 245, 255);">

                            <?php
                            if (isset($_GET['a']) && $_GET['a'] !== "" && isset($_GET['b']) && $_GET['b'] !== "") {
                                $query = "SELECT * FROM driver_location WHERE driverid=$did";

                                // Execute the query
                                $result = mysqli_query($conn, $query);
                                $row1 = mysqli_fetch_assoc($result);
                                $latitude = $row1['latitude'];
                                $longitude = $row1['longitude'];

                                // Use prepared statements to avoid SQL injection
                                $a = $_GET['a'];
                                $b = $_GET['b'];

                                // Prepare and execute the SQL query
                                $stmt = $conn->prepare("SELECT * FROM ride_cord WHERE bookid = ?");
                                $stmt->bind_param('s', $a);
                                $stmt->execute();
                                $result = $stmt->get_result();

                                // Fetch the data
                                if ($result->num_rows > 0) {
                                    $data = $result->fetch_assoc();

                                    // Get the required data from the result
                                    $s_long = $data['s_long'];
                                    $s_lat = $data['s_lat'];
                                    $d_long = $data['d_long'];
                                    $d_lat = $data['d_lat'];
                                }

                                ?>
                                <div id="map" style="border-radius: 35px;"></div>


                                <?php
                            } else {
                                // Display a message if either 'a' or 'b' is not set
                                echo "Sorry, You Can't Access this Page !";
                            }
                            ?>





                        </div>
                    </div>
                </div>
            </div>

            <script>
                function cm() {
                    // Retrieve the book ID from the PHP echo, ensuring it is declared as a variable
                    const bookid = <?php echo isset($_GET['a']) ? json_encode($_GET['a']) : 'null'; ?>;
                    const req = <?php echo isset($_GET['b']) ? json_encode($_GET['b']) : 'null'; ?>;

                    // Check if bookid is null or undefined
                    if (bookid === 'null' || bookid === null || bookid === undefined) {
                        // Redirect to 'index' page
                        window.location.href = 'index';
                    } else {

                        postData = {
                            operation: 'CompleteRide',
                            bookid: bookid,
                            driverid: <?php echo $row['driverid']; ?>,
                            reqID: req,
                        };

                        $.ajax({
                            type: "POST",
                            url: "function.php", // Replace with your server-side endpoint
                            data: postData,
                            dataType: 'json', // Specify the expected data type
                            success: function (result1) {

                                window.location.href = 'index';

                            },
                            error: function (xhr, status, error) {
                                console.error("AJAX Request Error:", status, error);
                                window.location.href = 'index';
                            }
                        });
                    }

                }


            </script>

            <!-- <script>
                let driverMarker, startMarker;
                let routeControl;
                // Global set to track spoken instructions
                const spokenInstructions = new Set();


                function logRouteInfoAndCheckNearestInstruction(event, driverLat, driverLon) {
                    // Create a LatLng object for the driver's current location
                    const driverLatLng = L.latLng(driverLat, driverLon);
                    const nearestRange = 100; // Define the threshold distance (100 meters) for proximity check

                    // Process route instructions and calculate distance to driver
                    const instructionsWithDistance = event.route.instructions.map(instruction => {
                        const pointIndex = instruction.index;
                        const coord = event.route.coordinates[pointIndex];
                        const instructionLatLng = L.latLng(coord.lat, coord.lng);
                        const distanceToDriver = driverLatLng.distanceTo(instructionLatLng);

                        return {
                            instructionText: instruction.text,
                            distanceToDriver: distanceToDriver
                        };
                    });

                    // Sort the instructions by distance to the driver
                    instructionsWithDistance.sort((a, b) => a.distanceToDriver - b.distanceToDriver);

                    // Iterate through the sorted list of instructions
                    for (const instructionWithDistance of instructionsWithDistance) {
                        const { instructionText, distanceToDriver } = instructionWithDistance;

                        // If the instruction is within the desired range and hasn't been spoken
                        if (distanceToDriver <= nearestRange && !spokenInstructions.has(instructionText)) {
                            // Speak the instruction
                            speakInstruction(instructionText);

                            // Add the instruction to the spokenInstructions set
                            spokenInstructions.add(instructionText);

                            // Check if the instruction is "You have arrived at your first destination"
                            if (instructionText.includes('You have arrived at your 1st destination')) {
                                console.log("marker removedd")
                                // Remove the start marker from the map
                                if (startMarker && map && typeof map.removeLayer === 'function') {
                                    map.removeLayer(startMarker);
                                    // Optionally, set startMarker to null
                                    startMarker = null;
                                }
                            }
                        }
                    }
                }

                function initializeMap(sourceLat, sourceLon, destLat, destLon, driverLat, driverLon) {
                    // Create the map and set its view to the source location
                    var map = L.map('map').setView([sourceLat, sourceLon], 13);

                    // Add OpenStreetMap tiles
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; OpenStreetMap contributors'
                    }).addTo(map);

                    // Define custom icons
                    var startIcon = L.icon({
                        iconUrl: 'static/pictures/start-mark.png',
                        iconSize: [32, 32],
                        iconAnchor: [16, 32],
                        popupAnchor: [0, -32]
                    });

                    var endIcon = L.icon({
                        iconUrl: 'static/pictures/end-mark.png',
                        iconSize: [22, 32],
                        iconAnchor: [11, 32],
                        popupAnchor: [0, -32]
                    });

                    var driverIcon = L.icon({
                        iconUrl: 'static/pictures/dv.svg',
                        iconSize: [32, 32],
                        iconAnchor: [16, 32],
                        popupAnchor: [0, -32]
                    });

                    // Create custom markers
                    startMarker = L.marker([sourceLat, sourceLon], { icon: startIcon }).addTo(map);
                    var endMarker = L.marker([destLat, destLon], { icon: endIcon }).addTo(map);
                    driverMarker = L.marker([driverLat, driverLon], { icon: driverIcon }).addTo(map);

                    // Function to perform geocoding and set tooltip content
                    function setTooltipContent(marker, lat, lon) {
                        var url = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}`;
                        fetch(url)
                            .then(response => response.json())
                            .then(data => {
                                if (data && data.display_name) {
                                    const fullAddress = data.display_name;
                                    const truncatedAddress = fullAddress.length > 20 ? fullAddress.substring(0, 20) + "..." : fullAddress;

                                    // Bind truncated tooltip content to the marker
                                    marker.bindTooltip(truncatedAddress, {
                                        permanent: true,
                                        direction: 'top',
                                        offset: [0, -30],
                                        className: 'tooltip'
                                    }).openTooltip();

                                    // Add click event listener to the marker
                                    marker.on('click', function () {
                                        marker.setTooltipContent(fullAddress);
                                        marker.closeTooltip();
                                        marker.openTooltip();
                                    });
                                }
                            })
                            .catch(error => {
                                console.error("Geocoding error:", error);
                            });
                    }

                    // Set tooltip content for start, end, and driver markers after a short delay
                    setTimeout(() => {
                        setTooltipContent(startMarker, sourceLat, sourceLon);
                        setTooltipContent(endMarker, destLat, destLon);
                        setTooltipContent(driverMarker, driverLat, driverLon);
                    }, 500);

                 

                    // Add routing control
                    routeControl = L.Routing.control({
                        waypoints: [
                            L.latLng(driverLat, driverLon),
                            L.latLng(sourceLat, sourceLon),
                            L.latLng(destLat, destLon)
                        ],
                        routeWhileDragging: true,
                        lineOptions: {
                            styles: [{ color: 'green', weight: 4 }]
                        },
                        createMarker: function () {
                            return null; // Avoid creating additional markers
                        }
                    }).addTo(map);

                    // // Add event listener to log route information and check nearest instruction
                    // routeControl.on('routeselected', logRouteInfoAndCheckNearestInstruction);

                    routeControl.on('routeselected', function (event) {
                        logRouteInfoAndCheckNearestInstruction(event, driverLat, driverLon);
                    });
                }




                function speakInstruction(text) {
                    // Step 1: Create a SpeechSynthesisUtterance instance with the provided text
                    const speech = new SpeechSynthesisUtterance(text);

                    // Step 2: Set the language to English for proper pronunciation
                    speech.lang = 'en-US';
                    console.log(text)
                    // Step 3: Speak the text
                    window.speechSynthesis.speak(speech);
                }












                function updateDriverLocation(did, sourceLat, sourceLon, destLat, destLon) {
                    // Fetch the latest driver's location from the PHP script
                    fetch(`dloc.php?driverid=${did}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.error) {
                                console.error(data.error);
                                return;
                            }

                            const { latitude, longitude } = data;

                            // Check if driverMarker and routeControl are defined before trying to update them
                            if (typeof driverMarker !== 'undefined' && typeof routeControl !== 'undefined') {
                                // Update the driver's marker position on the map
                                driverMarker.setLatLng([latitude, longitude]);

                                // Recalculate the route from the driver's current position to the destination
                                routeControl.setWaypoints([
                                    L.latLng(latitude, longitude),
                                    L.latLng(sourceLat, sourceLon),
                                    L.latLng(destLat, destLon)
                                ]);

                                // Log the latest location for debugging purposes
                                console.log(`Updated driver's location: Latitude: ${latitude}, Longitude: ${longitude}`);

                                // Add an event listener for route selection to speak instructions
                                routeControl.on('routeselected', function (event) {
                                    logRouteInfoAndCheckNearestInstruction(event, latitude, longitude);
                                });
                            } else {
                                console.error("driverMarker or routeControl is not defined");
                            }
                        })
                        .catch(error => {
                            console.error("Error fetching latest driver's location:", error);
                        });
                }

                // Start updating the driver's location in real-time
                setInterval(() => updateDriverLocation(
             
                ), 5000); // Update every 5 seconds


            </script> -->


            <script>



                let driverMarker, startMarker;
                let routeControl;
                const spokenInstructions = new Set();
                let sourceReached = false;

                // Function to handle route instructions and update the route as needed
                function logRouteInfoAndCheckNearestInstruction(event, driverLat, driverLon, destLat, destLon) {
                    const driverLatLng = L.latLng(driverLat, driverLon);
                    const nearestRange = 200; // Define the threshold distance (100 meters) for proximity check

                    const instructionsWithDistance = event.route.instructions.map(instruction => {
                        const pointIndex = instruction.index;
                        const coord = event.route.coordinates[pointIndex];
                        const instructionLatLng = L.latLng(coord.lat, coord.lng);
                        const distanceToDriver = driverLatLng.distanceTo(instructionLatLng);

                        return {
                            instructionText: instruction.text,
                            distanceToDriver: distanceToDriver
                        };
                    });

                    // Sort instructions by distance to driver
                    instructionsWithDistance.sort((a, b) => a.distanceToDriver - b.distanceToDriver);

                    // Iterate through the sorted list of instructions
                    for (const instructionWithDistance of instructionsWithDistance) {
                        const { instructionText, distanceToDriver } = instructionWithDistance;

                        if (distanceToDriver <= nearestRange && !spokenInstructions.has(instructionText)) {
                            speakInstruction(instructionText);
                            spokenInstructions.add(instructionText);

                            // Check if the instruction is "You have arrived at your 1st destination"
                            if (instructionText.includes('You have arrived at your 1st destination') && !sourceReached) {


                                // Remove the start marker from the map
                                if (startMarker && map && typeof map.removeLayer === 'function') {
                                    map.removeLayer(startMarker);
                                    startMarker = null; // Optionally, set startMarker to null
                                }

                                // Set the sourceReached flag to true to indicate the source has been reached
                                sourceReached = true;

                                // Update the route to guide the driver directly to the final destination
                                routeControl.setWaypoints([
                                    L.latLng(driverLat, driverLon),
                                    L.latLng(destLat, destLon)
                                ]);

                                break; // Exit the loop since we've handled the instruction
                            }
                        }
                    }
                }

                // Function to initialize the map and route control
                function initializeMap(sourceLat, sourceLon, destLat, destLon, driverLat, driverLon) {
                    const map = L.map('map').setView([sourceLat, sourceLon], 800); // Set the initial zoom level to 50

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; OpenStreetMap contributors'
                    }).addTo(map);

                    const startIcon = L.icon({
                        iconUrl: 'static/pictures/start-mark.png',
                        iconSize: [32, 32],
                        iconAnchor: [16, 32],
                        popupAnchor: [0, -32]
                    });

                    const endIcon = L.icon({
                        iconUrl: 'static/pictures/end-mark.png',
                        iconSize: [22, 32],
                        iconAnchor: [11, 32],
                        popupAnchor: [0, -32]
                    });

                    const driverIcon = L.icon({
                        iconUrl: 'static/pictures/dv.svg',
                        iconSize: [32, 32],
                        iconAnchor: [16, 32],
                        popupAnchor: [0, -32]
                    });

                    // Create markers for source, destination, and driver
                    startMarker = L.marker([sourceLat, sourceLon], { icon: startIcon }).addTo(map);
                    const endMarker = L.marker([destLat, destLon], { icon: endIcon }).addTo(map);
                    driverMarker = L.marker([driverLat, driverLon], { icon: driverIcon }).addTo(map);

                    // Function to set tooltip content for a marker
                    function setTooltipContent(marker, lat, lon) {
                        const url = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}`;
                        fetch(url)
                            .then(response => response.json())
                            .then(data => {
                                if (data && data.display_name) {
                                    const fullAddress = data.display_name;
                                    const truncatedAddress = fullAddress.length > 20 ? fullAddress.substring(0, 20) + "..." : fullAddress;

                                    marker.bindTooltip(truncatedAddress, {
                                        permanent: true,
                                        direction: 'top',
                                        offset: [0, -30],
                                        className: 'tooltip'
                                    }).openTooltip();

                                    // Add click event listener to the marker
                                    marker.on('click', function () {
                                        marker.setTooltipContent(fullAddress);
                                        marker.closeTooltip();
                                        marker.openTooltip();
                                    });
                                }
                            })
                            .catch(error => {
                                console.error("Geocoding error:", error);
                            });
                    }

                    // Set tooltip content for markers after a short delay
                    setTimeout(() => {
                        setTooltipContent(startMarker, sourceLat, sourceLon);
                        setTooltipContent(endMarker, destLat, destLon);
                        setTooltipContent(driverMarker, driverLat, driverLon);
                    }, 500);

                    // Initialize route control
                    routeControl = L.Routing.control({
                        waypoints: [
                            L.latLng(driverLat, driverLon),
                            L.latLng(sourceLat, sourceLon),
                            L.latLng(destLat, destLon)
                        ],
                        routeWhileDragging: true,
                        lineOptions: {
                            styles: [{ color: 'green', weight: 4 }]
                        },
                        createMarker: function () {
                            return null; // Avoid creating additional markers
                        }
                    }).addTo(map);

                    // Add event listener to handle route instructions
                    routeControl.on('routeselected', function (event) {
                        logRouteInfoAndCheckNearestInstruction(event, driverLat, driverLon, destLat, destLon);
                    });
                }

                // Function to speak the given instruction text
                function speakInstruction(text) {
                    const speech = new SpeechSynthesisUtterance(text);
                    speech.lang = 'en-US';
                    window.speechSynthesis.speak(speech);
                }

                // Initialize the map with source, destination, and driver locations
                initializeMap(<?php echo $s_lat; ?>, <?php echo $s_long; ?>, <?php echo $d_lat; ?>, <?php echo $d_long; ?>, <?php echo $latitude; ?>, <?php echo $longitude; ?>);

                // Function to update the driver's location in real-time
                // function updateDriverLocation(did, sourceLat, sourceLon, destLat, destLon) {
                //     fetch(`dloc.php?driverid=${did}`)
                //         .then(response => response.json())
                //         .then(data => {
                //             if (data.error) {
                //                 console.error(data.error);
                //                 return;
                //             }

                //             const { latitude, longitude } = data;

                //             // Check if driverMarker and routeControl are defined
                //             if (typeof driverMarker !== 'undefined' && typeof routeControl !== 'undefined') {
                //                 driverMarker.setLatLng([latitude, longitude]);

                //                 if (!sourceReached) {
                //                     // Recalculate the route from the driver's current position to source and destination
                //                     routeControl.setWaypoints([
                //                         L.latLng(latitude, longitude),
                //                         L.latLng(sourceLat, sourceLon),
                //                         L.latLng(destLat, destLon)
                //                     ]);
                //                 } else {
                //                     // Update the route directly to the final destination
                //                     routeControl.setWaypoints([
                //                         L.latLng(latitude, longitude),
                //                         L.latLng(destLat, destLon)
                //                     ]);
                //                 }


                //                 // Add event listener to handle route instructions
                //                 routeControl.on('routeselected', function (event) {
                //                     logRouteInfoAndCheckNearestInstruction(event, latitude, longitude, destLat, destLon);
                //                 });
                //             } else {
                //                 console.error("driverMarker or routeControl is not defined");
                //             }
                //         })
                //         .catch(error => {
                //             console.error("Error fetching latest driver's location:", error);
                //         });
                // }

                // Start updating the driver's location in real-time every 5 seconds
                // setInterval(() => updateDriverLocation(
                    <?php echo $did; ?>,
                    <?php echo $s_lat; ?>,
                    <?php echo $s_long; ?>,
                    <?php echo $d_lat; ?>,
                    <?php echo $d_long; ?>
                // ), 5000); // Update every 5 seconds

                // Delay the start of the interval function by 30 seconds
                setTimeout(() => {
                    // Start setInterval to call myfun every 5 seconds
                    setInterval(() => {
                        myfun(
                            <?php echo $did; ?>,
                            <?php echo $s_lat; ?>,
                            <?php echo $s_long; ?>,
                            <?php echo $d_lat; ?>,
                            <?php echo $d_long; ?>
                        );
                    }, 5000);
                }, 30000); // 30 seconds delay in milliseconds

                function myfun(did, sourceLat, sourceLon, destLat, destLon) {
                    const latitude = mylat;
                    const longitude = mylon;

                    // Check if driverMarker and routeControl are defined
                    if (typeof driverMarker !== 'undefined' && typeof routeControl !== 'undefined') {
                        driverMarker.setLatLng([latitude, longitude]);

                        if (!sourceReached) {
                            // Recalculate the route from the driver's current position to source and destination
                            routeControl.setWaypoints([
                                L.latLng(latitude, longitude),
                                L.latLng(sourceLat, sourceLon),
                                L.latLng(destLat, destLon)
                            ]);
                        } else {
                            // Update the route directly to the final destination
                            routeControl.setWaypoints([
                                L.latLng(latitude, longitude),
                                L.latLng(destLat, destLon)
                            ]);
                        }
                        console.log(latitude);
                        console.log(longitude);

                        // Add event listener to handle route instructions
                        routeControl.on('routeselected', function (event) {
                            logRouteInfoAndCheckNearestInstruction(event, latitude, longitude, destLat, destLon);
                        });
                    }
                }
            </script>


        </body>

        </html>
        <?php
        include ("partials/_scripts.php"); ?>

        </body>

        </html>

        <?php
    } else {
        header("location:" . BASE_URL . "login.php");
    }
} else {
    header("location:" . BASE_URL . "login.php");
}
exit;
?>