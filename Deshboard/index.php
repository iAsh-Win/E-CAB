<?php
session_start();

if (isset($_SESSION['Logged-in-user']) && isset($_SESSION['isLoggedin']) && $_SESSION['isLoggedin'] == true) {
    require ("../mainDB.php");
    try {
        // Escape the user input to prevent SQL injection
        $email = mysqli_real_escape_string($conn, $_SESSION['Logged-in-user']);

        // Execute the query
        $query = "SELECT * FROM users WHERE emailID='$email'";
        $result = mysqli_query($conn, $query);
        $query2 = "SELECT * FROM cabcate";
        $result2 = mysqli_query($conn, $query2);
        // Check for errors in the query execution
        if (!$result && !$result2) {
            throw new Exception(mysqli_error($conn));
        }

        // Fetch data as an associative array
        $row = mysqli_fetch_assoc($result);

        // Handle the data (you can modify this part based on your needs)




        ?>
        <!DOCTYPE html>
        <html lang="en">


        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>E-CAB | Plan Your Trip</title>
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
            .hidden {
                display: none;
            }

            .loader-wrapper {
                flex-direction: column;
                gap: 35px;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(255, 255, 255, 0.8);
                display: flex;
                justify-content: center;
                align-items: center;
                z-index: 99999999999999;
            }

            .loader {
                border: 8px solid #d0d0ff;
                border-top: 8px solid rgb(4, 0, 138);
                border-radius: 50%;
                width: 50px;
                height: 50px;
                animation: spin 1s linear infinite;
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

        <body>
            <nav>
                <div class="logo">
                    <img src="static/pictures/logo2.png" class="logo-img" alt="logo">
                </div>

                <div class="user-div">
                    <div class="btns"><a href="trips" class="main-btn Mytrips">My Trips</a></div>
                    <img src="<?php echo '../' . trim($row['userImage']); ?>" class="user-image" alt="userimage">


                    <div class="dropbox">
                        <a href="manage-profile">Manage Profile</a>
                        <a href="manage-profile">Give Feedback</a>
                        <a href="log-out">Log out</a>
                    </div>
                </div>
            </nav>
            <div class="loader-wrapper">
                <div class="loader"></div>
                <p class="car-detail" style="font-family: Montserrat; font-size: 15px; font-weight:500;">Please Wait, We are
                    Processing...</p>
            </div>


            <main>
                <div class="display">
                    <div class="taskDiv">


                        <div class="bookCtn" id="div1">
                            <h1 class="title">Take a Ride</h1>

                            <form action="" id="BookingForm">
                                <div class="inputbox" id="inputBox">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="17" class="svg1" height="16"
                                        viewBox="0 0 17 16" fill="none">
                                        <circle cx="8.20439" cy="7.97294" r="6.83277" stroke="#5B5B5B" stroke-width="1.95222" />
                                    </svg>
                                    <input type="text" name="pickup" id="inputField" placeholder="Enter pickup location">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" class="svg2" id="locationsvg"
                                        viewBox="0 0 24 24" fill="none">
                                        <path
                                            d="M20.8527 4.78473L15.0419 20.4806C14.6749 21.4723 13.6178 21.9818 12.6817 21.6177C12.4499 21.5259 12.2396 21.3871 12.0641 21.2101C11.8885 21.033 11.7515 20.8215 11.6617 20.5889L9.81975 15.9329C9.47361 15.0391 8.78744 14.3189 7.91146 13.9299L3.47796 11.9962C2.55554 11.5931 2.13581 10.4755 2.53895 9.49934C2.6343 9.26365 2.77545 9.04921 2.95422 8.86842C3.133 8.68764 3.34585 8.5441 3.58046 8.44612L18.5306 2.34543C19.4745 1.95987 20.5218 2.44402 20.8683 3.42794C21.0216 3.86328 21.0157 4.34548 20.8537 4.78473H20.8527Z"
                                            fill="#C5C5C5" />
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" class="svg2" id="closesvg1"
                                        onclick="clearInputField(inputField)" viewBox="0 0 16 16" fill="none">
                                        <circle cx="7.80888" cy="7.80888" r="7.30888" stroke="#5B5B5B" />
                                        <path d="M10 10L5 5" stroke="#5B5B5B" />
                                        <path d="M10 5L5 10" stroke="#5B5B5B" />
                                    </svg>
                                </div>
                                <div class="bookCtn DropboxList " id="d1">
                                    <ul id="uls" class="custom-menu">
                                        <li><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32"
                                                fill="none">
                                                <circle cx="16" cy="16" r="15.75" fill="white" stroke="black"
                                                    stroke-width="0.5" />
                                                <path
                                                    d="M15.8362 5.41797C13.6863 5.42082 11.6252 6.27614 10.105 7.79637C8.58473 9.3166 7.72941 11.3777 7.72656 13.5276C7.72656 19.3496 15.282 26.6415 15.603 26.949C15.6655 27.0097 15.7491 27.0436 15.8362 27.0436C15.9233 27.0436 16.0069 27.0097 16.0693 26.949C16.3903 26.6415 23.9458 19.3496 23.9458 13.5276C23.9429 11.3777 23.0876 9.3166 21.5674 7.79637C20.0472 6.27614 17.9861 5.42082 15.8362 5.41797ZM15.8362 17.2445C15.101 17.2445 14.3824 17.0265 13.7712 16.6181C13.1599 16.2097 12.6835 15.6292 12.4022 14.95C12.1209 14.2708 12.0473 13.5235 12.1907 12.8025C12.3341 12.0814 12.6881 11.4192 13.2079 10.8993C13.7277 10.3795 14.39 10.0255 15.111 9.8821C15.8321 9.73868 16.5794 9.81229 17.2586 10.0936C17.9378 10.3749 18.5183 10.8513 18.9267 11.4626C19.3351 12.0738 19.5531 12.7925 19.5531 13.5276C19.5525 14.5132 19.1607 15.4583 18.4638 16.1552C17.7669 16.8521 16.8218 17.2439 15.8362 17.2445Z"
                                                    fill="#1D004C" />
                                                <circle cx="16" cy="29" r="1" fill="#1D004C" />
                                            </svg>
                                            <div class="address">
                                                <p class="mainAd">Select Place to go</p>
                                                <p class="detailAd"></p>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <div class="inputbox" id="inputBox1">
                                    <svg width="16" height="16" viewBox="0 0 16 16" class="svg1" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <rect x="0.97611" y="0.97611" width="13.6655" height="13.6655" stroke="#5B5B5B"
                                            stroke-width="1.95222" />
                                    </svg>
                                    <input type="text" name="dropoff" id="inputField1" placeholder="Enter destination">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" class="svg2" id="closesvg2"
                                        onclick="clearInputField(inputField1)" viewBox="0 0 16 16" fill="none">
                                        <circle cx="7.80888" cy="7.80888" r="7.30888" stroke="#5B5B5B" />
                                        <path d="M10 10L5 5" stroke="#5B5B5B" />
                                        <path d="M10 5L5 10" stroke="#5B5B5B" />
                                    </svg>
                                </div>
                                <div class="bookCtn DropboxList" id="d2">
                                    <ul id="uls1" class="custom-menu1">
                                        <li><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32"
                                                fill="none">
                                                <circle cx="16" cy="16" r="15.75" fill="white" stroke="black"
                                                    stroke-width="0.5" />
                                                <path
                                                    d="M15.8362 5.41797C13.6863 5.42082 11.6252 6.27614 10.105 7.79637C8.58473 9.3166 7.72941 11.3777 7.72656 13.5276C7.72656 19.3496 15.282 26.6415 15.603 26.949C15.6655 27.0097 15.7491 27.0436 15.8362 27.0436C15.9233 27.0436 16.0069 27.0097 16.0693 26.949C16.3903 26.6415 23.9458 19.3496 23.9458 13.5276C23.9429 11.3777 23.0876 9.3166 21.5674 7.79637C20.0472 6.27614 17.9861 5.42082 15.8362 5.41797ZM15.8362 17.2445C15.101 17.2445 14.3824 17.0265 13.7712 16.6181C13.1599 16.2097 12.6835 15.6292 12.4022 14.95C12.1209 14.2708 12.0473 13.5235 12.1907 12.8025C12.3341 12.0814 12.6881 11.4192 13.2079 10.8993C13.7277 10.3795 14.39 10.0255 15.111 9.8821C15.8321 9.73868 16.5794 9.81229 17.2586 10.0936C17.9378 10.3749 18.5183 10.8513 18.9267 11.4626C19.3351 12.0738 19.5531 12.7925 19.5531 13.5276C19.5525 14.5132 19.1607 15.4583 18.4638 16.1552C17.7669 16.8521 16.8218 17.2439 15.8362 17.2445Z"
                                                    fill="#1D004C" />
                                                <circle cx="16" cy="29" r="1" fill="#1D004C" />
                                            </svg>
                                            <div class="address">
                                                <p class="mainAd">Select Place to go</p>
                                                <p class="detailAd"></p>
                                            </div>
                                        </li>



                                    </ul>
                                </div>

                                <div class="inputbox" id="inputBox2">
                                    <svg width="16" height="16" class="svg1" viewBox="0 0 25 25" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M17.5727 16.4693L13.1554 13.9416V8.21655C13.1554 7.71102 12.7359 7.3066 12.2116 7.3066H12.133C11.6087 7.3066 11.1892 7.71102 11.1892 8.21655V14.1818C11.1892 14.6241 11.4252 15.0411 11.8315 15.2686L16.6158 18.0364C17.0615 18.2891 17.6382 18.1628 17.9004 17.7331C18.1756 17.2907 18.0314 16.722 17.5727 16.4693ZM24.5329 3.5278L20.4957 0.292435C19.9452 -0.1499 19.1194 -0.0867099 18.6475 0.45673C18.1887 0.987532 18.2674 1.78374 18.8179 2.23871L22.842 5.47407C23.3925 5.91641 24.2183 5.85322 24.6902 5.30978C25.162 4.77897 25.0834 3.98277 24.5329 3.5278ZM2.14492 5.47407L6.16898 2.23871C6.73261 1.78374 6.81126 0.987532 6.33938 0.45673C5.88061 -0.0867099 5.05483 -0.1499 4.50431 0.292435L0.467136 3.5278C-0.0833876 3.98277 -0.162034 4.77897 0.309843 5.30978C0.768613 5.85322 1.5944 5.91641 2.14492 5.47407ZM12.5 2.25135C5.98548 2.25135 0.703074 7.34452 0.703074 13.6257C0.703074 19.9068 5.98548 25 12.5 25C19.0145 25 24.2969 19.9068 24.2969 13.6257C24.2969 7.34452 19.0145 2.25135 12.5 2.25135ZM12.5 22.4724C7.44043 22.4724 3.32461 18.504 3.32461 13.6257C3.32461 8.74735 7.44043 4.77897 12.5 4.77897C17.5596 4.77897 21.6754 8.74735 21.6754 13.6257C21.6754 18.504 17.5596 22.4724 12.5 22.4724Z"
                                            fill="black" />
                                    </svg>
                                    <input type="text" id="datetimePicker" placeholder="Enter time to go">

                                </div>

                                <button type="submit" onclick="searchCab(event)" id="submit" class="submit">Search</button>
                            </form>
                        </div>

                        <div class="bookCtn" id="div2" style="margin-top: 10px;">
                            <h1 class="title" style="margin: 10px 0px;"><svg xmlns="http://www.w3.org/2000/svg" width="25"
                                    onclick="back()" style="cursor: pointer;" height="25" viewBox="0 0 60 60" fill="none">
                                    <path
                                        d="M29.9996 60C46.5413 60 59.9989 46.5419 59.9989 29.9999C59.9989 13.4581 46.5413 0 29.9996 0C13.4578 0 0 13.4581 0 29.9999C0 46.5419 13.4578 60 29.9996 60ZM29.9996 4.10676C44.2769 4.10676 55.8921 15.7223 55.8924 29.9999C55.8924 44.2774 44.2771 55.893 29.9996 55.8932C15.7223 55.893 4.10703 44.2774 4.10703 29.9996C4.10703 15.7226 15.7223 4.10676 29.9996 4.10676Z"
                                        fill="#091F5B" />
                                    <path
                                        d="M25.9712 42.8489C26.7731 43.6505 28.0733 43.6502 28.875 42.8489C29.6772 42.0467 29.6772 40.7468 28.8747 39.9446L20.9843 32.0544L43.8334 32.0522C44.9675 32.0519 45.8865 31.1328 45.8865 29.9983C45.8863 28.8643 44.9672 27.9454 43.8332 27.9454L20.9832 27.9476L28.8755 20.0558C29.6774 19.2539 29.6774 17.9534 28.8755 17.1518C28.4744 16.751 27.949 16.5503 27.4234 16.5503C26.898 16.5503 26.3726 16.751 25.9715 17.1515L14.5742 28.5486C14.1889 28.9335 13.9727 29.4556 13.9727 30.0005C13.9729 30.5453 14.1892 31.0671 14.5744 31.4529L25.9712 42.8489Z"
                                        fill="#091F5B" />
                                </svg> Select Cab</h1>
                            <?php
                            if (mysqli_num_rows($result2) > 0) {
                                while ($CabRow = mysqli_fetch_assoc($result2)) {
                                    if ($CabRow['catename'] == 'Compact') {
                                        echo '<a class="bookCtn book2" id="compact" onclick="cabSelect(\'compact\')">
                                    <img src="static/pictures/compact-car.svg" alt="">
                                    <div class="car-details">
                                        <p class="car-name">' . $CabRow['catename'] . '</p>
                                        <p class="car-detail">' . $CabRow['CabDesc'] . '</p>
                                    </div>
                                    <div class="car-name price" id="cprice"></div>
                                </a>';
                                    } else if ($CabRow['catename'] == 'Family') {
                                        echo ' <a class="bookCtn book2" id="family" onclick="cabSelect(\'family\')">
                                    <img src="static/pictures/family-car.svg" alt="">
                                    <div class="car-details">
                                        <p class="car-name">' . $CabRow['catename'] . '</p>
                                        <p class="car-detail">' . $CabRow['CabDesc'] . '</p>
                                    </div>
                                    <div class="car-name price" id="fprice"></div>
                                </a>';
                                    } else {
                                        echo ' <a class="bookCtn book2" id="premium" onclick="cabSelect(\'premium\')">
                                    <img src="static/pictures/premium-car.svg" alt="">
                                    <div class="car-details">
                                        <p class="car-name">' . $CabRow['catename'] . '</p>
                                        <p class="car-detail">' . $CabRow['CabDesc'] . '</p>
                                    </div>
                                    <div class="car-name price" id="pprice"></div>
                                </a>';
                                    }
                                }
                            }
                            ?>
                            <!-- <a class="bookCtn book2" id="compact" onclick="cabSelect('compact')">
                                <img src="static/pictures/compact-car.svg" alt="">
                                <div class="car-details">
                                    <p class="car-name">Compact</p>
                                    <p class="car-detail">Affordable, compact rides</p>
                                </div>
                                <div class="car-name price" id="cprice">₹ 251.78</div>
                            </a>

                            <a class="bookCtn book2" id="family" onclick="cabSelect('family')">
                                <img src="static/pictures/family-car.svg" alt="">
                                <div class="car-details">
                                    <p class="car-name">Family</p>
                                    <p class="car-detail">Comfortable , top-quality </p>
                                </div>
                                <div class="car-name price" id="fprice">₹ 251.78</div>
                            </a>

                            <a class="bookCtn book2" id="premium" onclick="cabSelect('premium')">
                                <img src="static/pictures/premium-car.svg" alt="">
                                <div class="car-details">
                                    <p class="car-name">Premium</p>
                                    <p class="car-detail">premium car, compact rides</p>
                                </div>
                                <div class="car-name price" id="pprice">₹ 251.78</div>
                            </a> -->

                            <div class="bookCtn payblock">
                                <div class="payments" style="display: flex; flex-direction: row; gap: 5px;">
                                    <p class="car-name payment myfontsize" id="paymentAs">Select Payment As </p>
                                    <div class="car-name myfontsize textalign"><input type="radio" class="redio"
                                            onchange="paymentchanged('online')" name="paymentOption" id="on" value="Online">
                                        <label for="on">Online </label>

                                        <input type="radio" class="redio" name="paymentOption" onchange="paymentchanged('cash')"
                                            id="cash" value="Cash">
                                        <label for="cash">Cash </label>
                                    </div>



                                </div>

                                <button type="submit" class="submit" id="final-btn" onclick="finalbtn()">Confirm Your
                                    Ride</button>

                            </div>

                        </div>

                    </div>

                    <div class="mapDiv" id="map">
                        <!-- <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3628.485534568074!2d72.3695022!3d24.572434400000002!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x395ccba0b28c7387%3A0x2546b751230686a0!2sMANCHHARAM%20BADHIYA%20FARM%20HOUSE!5e0!3m2!1sen!2sin!4v1708937499301!5m2!1sen!2sin"
                    style="border: none;" class="map" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"></iframe> -->
                    </div>

                </div>
            </main>
            <script>
                document.querySelector(".loader-wrapper").style.display = "none";
                // Get the current time
                var cabSelected = false;
                var paymentSelected = false;
                var locationsObj = {
                    user: {
                        name: '<?php echo $row['name']; ?>',
                        email: '<?php echo $row['emailID']; ?>',
                        id: '<?php echo $row['id']; ?>'
                    },
                    pickup: {},
                    dropOff: {},
                    datetime: {},
                    totaldistance: {},
                    selectedcab: {},
                    totalfare: {},
                    paymenttype: {},
                };


                var cabprices = {
                    compact: {},
                    family: {},
                    premium: {},
                }
                var totaldistance;
                var totalfare;

                const inputBox = document.getElementById('inputBox');
                const inputField = document.getElementById('inputField');

                inputBox.addEventListener('click', () => {
                    inputField.focus();
                    document.getElementById("d1").style.visibility = "visible";
                    document.getElementById("d1").style.opacity = "1";

                });

                inputField.addEventListener('keyup', (event) => {
                    if (['ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown'].includes(event.key)) {

                        // Prevent the default behavior of arrow keys
                        event.preventDefault();
                    }
                    if (inputField.value !== "") {
                        document.getElementById("locationsvg").style.display = "none";
                        document.getElementById("closesvg1").style.display = "block";
                    } else {
                        document.getElementById("closesvg1").style.display = "none";
                        document.getElementById("locationsvg").style.display = "block";
                    }
                });



                document.addEventListener('click', (event) => {
                    if (!inputBox.contains(event.target)) {
                        inputField.blur();
                        // inputBox.style.outline = ''; // Reset border when unfocused
                        document.getElementById("d1").style.visibility = "hidden";
                        document.getElementById("d1").style.opacity = "";
                        document.getElementById("closesvg1").style.display = "none";
                        document.getElementById("locationsvg").style.display = "block";
                        if (Object.keys(locationsObj["pickup"]).length === 0) {
                            document.getElementById('inputField').value = "";
                        }
                        else {
                            document.getElementById('inputField').value = locationsObj["pickup"].mainad;
                        }

                    }
                });

                const inputBox1 = document.getElementById('inputBox1');
                const inputField1 = document.getElementById('inputField1');

                inputBox1.addEventListener('click', () => {
                    inputField1.focus();
                    document.getElementById("d2").style.visibility = "visible";
                    document.getElementById("d2").style.opacity = "1";

                });

                document.addEventListener('click', (event) => {
                    if (!inputBox1.contains(event.target)) {
                        inputField1.blur();
                        // inputBox.style.outline = ''; // Reset border when unfocused
                        document.getElementById("d2").style.visibility = "hidden";
                        document.getElementById("d2").style.opacity = "0";
                        document.getElementById("closesvg2").style.display = "none";
                        if (Object.keys(locationsObj["dropOff"]).length === 0) {
                            document.getElementById('inputField1').value = "";
                        }
                        else {
                            document.getElementById('inputField1').value = locationsObj["dropOff"].mainad;
                        }
                    }
                });
                inputField1.addEventListener('keyup', () => {
                    if (inputField1.value !== "") {

                        document.getElementById("closesvg2").style.display = "block";
                    } else {
                        document.getElementById("closesvg2").style.display = "none";

                    }
                });

                function clearInputField(id) {
                    id.value = "";
                }

                document.getElementById("locationsvg").addEventListener('click', () => {
                    document.querySelector(".loader-wrapper").style.display = "flex";
                    // Check if the browser supports Geolocation
                    // if ("geolocation" in navigator) {

                    //     navigator.geolocation.getCurrentPosition(

                    //         // Success callback
                    //         function (position) {
                    //             document.getElementById("d1").style.visibility = "hidden";
                    //             document.getElementById("d1").style.opacity = "";
                    //             const latitude = position.coords.latitude;
                    //             const longitude = position.coords.longitude;
                    //             const apiUrl5 = `https://nominatim.openstreetmap.org/reverse?lat=${latitude}&lon=${longitude}&format=json`;

                    //             fetch(apiUrl5)
                    //                 .then(response => response.json())
                    //                 .then(data => {
                    //                     const placeName = data.name;
                    //                     liData("No-type", "No-id", placeName, longitude, latitude);
                    //                     document.querySelector(".loader-wrapper").style.display = "none";

                    //                 })
                    //                 .catch(error => {
                    //                     console.error('Error fetching data:', error);
                    //                 });

                    //             // Log the latitude and longitude to the console
                    //             console.log("Latitude: " + latitude);
                    //             console.log("Longitude: " + longitude);

                    //         },
                    //         // Error callback
                    //         function (error) {
                    //             console.error("Error getting location:", error.message);
                    //         }
                    //     );

                    // } else {
                    //     console.error("Geolocation is not supported by this browser.");
                    // }

                    if (longitude !== '' && latitude !== '') {

                        // Use the reverse geocoding API
                        const apiUrl5 = `https://nominatim.openstreetmap.org/reverse?lat=${latitude}&lon=${longitude}&format=json`;

                        fetch(apiUrl5)
                            .then(response => response.json())
                            .then(data => {
                                console.log(data);
                                let placeName = data.name;
                                if (placeName == "") {
                                    placeName = data.display_name;
                                }
                                liData("No-type", "No-id", placeName, longitude, latitude);
                                document.querySelector(".loader-wrapper").style.display = "none";

                            })
                            .catch(error => {
                                console.error('Error fetching data:', error);
                                removeLoaderAndUpdateText('Unable to fetch location data. Please try again.');
                            });
                    } else {
                        document.querySelector(".loader-wrapper").style.display = "none";
                    }


                });

                function truncateString(inputString, maxCharacters) {
                    if (inputString.length <= maxCharacters) {
                        return inputString;  // Return the input string if it's already within the limit
                    } else {
                        let truncatedString = inputString.substr(0, maxCharacters);

                        // Check if the last character is part of a word
                        if (/\s/.test(inputString[maxCharacters - 1])) {
                            truncatedString = truncatedString.slice(0, -1);  // Remove the trailing space
                        }

                        return truncatedString;
                    }
                }


                function checkTextboxes() {
                    const hasValue1 = inputField1.value.trim() !== '';
                    const hasValue2 = inputField.value.trim() !== '';
                    const hasValue3 = datetimePicker.value.trim() !== '';
                    submit.disabled = !(hasValue1 && hasValue2 && hasValue3);
                }

                document.getElementById("final-btn").disabled = true;

                function hasAllValues(obj) {
                    return Object.values(obj).every(value => {
                        if (typeof value === 'object' && value !== null) {
                            // Check if the object has at least one key
                            return Object.keys(value).length !== 0;
                        } else {
                            // Check if the value is not null, undefined, or an empty string
                            return value !== null && value !== undefined && value !== '';
                        }
                    });
                }


                function checkFinalbtn() {
                    var paymentOptions = document.querySelectorAll('input[name="paymentOption"]');
                    var isPaymentSelected = Array.from(paymentOptions).some(option => option.checked);
                    var finalBtn = document.getElementById("final-btn");
                    console.log(locationsObj)

                    if (cabSelected && paymentSelected && isPaymentSelected && hasAllValues(locationsObj)) {
                        // Enable the button
                        finalBtn.disabled = false;
                    } else {
                        // Disable the button
                        finalBtn.disabled = true;
                    }
                }



                function back() {
                    document.getElementById("div1").style.display = "flex";
                    document.getElementById("div2").style.display = "none";
                }
                // jquery -------------------------------------------------------------------
                $(document).ready(function () {
                    // Initialize Typeahead on the inputField
                    var bloodhound = new Bloodhound({
                        datumTokenizer: Bloodhound.tokenizers.whitespace,
                        queryTokenizer: Bloodhound.tokenizers.whitespace,
                        remote: {
                            url: 'https://nominatim.openstreetmap.org/search?q=%QUERY&format=json&countrycodes=IN',
                            wildcard: '%QUERY',
                            transform: function (response) {
                                return $.map(response, function (place) {
                                    return place;  // Return the entire OpenStreetMap object for each place
                                });
                            }
                        }
                    });

                    $('#inputField').typeahead({
                        hint: true,
                        highlight: true,
                        minLength: 1
                    },
                        {
                            name: 'places',
                            source: bloodhound,
                            templates: {
                                suggestion: function (data) {

                                    // Use data as a JSON string
                                    let mainad = truncateString(data.name, 40);



                                    let detailAd = data.display_name;

                                    let modifiedDetailAd = detailAd.replace(mainad, '');
                                    modifiedDetailAd = modifiedDetailAd.replace(',', '');
                                    detailAd = truncateString(modifiedDetailAd, 48);

                                    // Remove <strong> tag from mainAd


                                    return '<li class="custom-menu-item" data-info=\'' + JSON.stringify(data) + '\'><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none">' +
                                        '<circle cx="16" cy="16" r="15.75" fill="white" stroke="black" stroke-width="0.5" />' +
                                        '<path d="M15.8362 5.41797C13.6863 5.42082 11.6252 6.27614 10.105 7.79637C8.58473 9.3166 7.72941 11.3777 7.72656 13.5276C7.72656 19.3496 15.282 26.6415 15.603 26.949C15.6655 27.0097 15.7491 27.0436 15.8362 27.0436C15.9233 27.0436 16.0069 27.0097 16.0693 26.949C16.3903 26.6415 23.9458 19.3496 23.9458 13.5276C23.9429 11.3777 23.0876 9.3166 21.5674 7.79637C20.0472 6.27614 17.9861 5.42082 15.8362 5.41797ZM15.8362 17.2445C15.101 17.2445 14.3824 17.0265 13.7712 16.6181C13.1599 16.2097 12.6835 15.6292 12.4022 14.95C12.1209 14.2708 12.0473 13.5235 12.1907 12.8025C12.3341 12.0814 12.6881 11.4192 13.2079 10.8993C13.7277 10.3795 14.39 10.0255 15.111 9.8821C15.8321 9.73868 16.5794 9.81229 17.2586 10.0936C17.9378 10.3749 18.5183 10.8513 18.9267 11.4626C19.3351 12.0738 19.5531 12.7925 19.5531 13.5276C19.5525 14.5132 19.1607 15.4583 18.4638 16.1552C17.7669 16.8521 16.8218 17.2439 15.8362 17.2445Z" fill="#1D004C" />' +
                                        '<circle cx="16" cy="29" r="1" fill="#1D004C" /></svg>' +
                                        `<div class="address"><p class="mainAd">${mainad}</p><p class="detailAd">${detailAd}</p></div></li>`;
                                },
                                notFound: function () {
                                    return '<li class="custom-menu-item"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none">' +
                                        '<circle cx="16" cy="16" r="15.75" fill="white" stroke="black" stroke-width="0.5" />' +
                                        '<path d="M15.8362 5.41797C13.6863 5.42082 11.6252 6.27614 10.105 7.79637C8.58473 9.3166 7.72941 11.3777 7.72656 13.5276C7.72656 19.3496 15.282 26.6415 15.603 26.949C15.6655 27.0097 15.7491 27.0436 15.8362 27.0436C15.9233 27.0436 16.0069 27.0097 16.0693 26.949C16.3903 26.6415 23.9458 19.3496 23.9458 13.5276C23.9429 11.3777 23.0876 9.3166 21.5674 7.79637C20.0472 6.27614 17.9861 5.42082 15.8362 5.41797ZM15.8362 17.2445C15.101 17.2445 14.3824 17.0265 13.7712 16.6181C13.1599 16.2097 12.6835 15.6292 12.4022 14.95C12.1209 14.2708 12.0473 13.5235 12.1907 12.8025C12.3341 12.0814 12.6881 11.4192 13.2079 10.8993C13.7277 10.3795 14.39 10.0255 15.111 9.8821C15.8321 9.73868 16.5794 9.81229 17.2586 10.0936C17.9378 10.3749 18.5183 10.8513 18.9267 11.4626C19.3351 12.0738 19.5531 12.7925 19.5531 13.5276C19.5525 14.5132 19.1607 15.4583 18.4638 16.1552C17.7669 16.8521 16.8218 17.2439 15.8362 17.2445Z" fill="#1D004C" />' +
                                        '<circle cx="16" cy="29" r="1" fill="#1D004C" /></svg>' +
                                        '<div class="address"><p class="mainAd">No place found</p><p class="detailAd"></p></div></li>';
                                }

                            }
                        }).on('typeahead:select', function (ev, suggestion) {
                            // Handle the selection of an item from the menu
                            console.log('Selected:', suggestion);
                        }).on('typeahead:render', function () {
                            // Remove any previous suggestions
                            $('.custom-menu').empty();
                        }).on('typeahead:rendered', function () {

                            // Append suggestions to the ul after rendering
                            $('.custom-menu').append($('.tt-menu .custom-menu-item').clone());
                        });

                    // Event handling for clicking on suggestions
                    $(document).on('click', '.custom-menu-item', function () {
                        // Retrieve the data from the data attribute
                        var dataInfo = $(this).data('info');
                        console.log(dataInfo);
                        liData(dataInfo.osm_type, dataInfo.osm_id, dataInfo.name, dataInfo.lon, dataInfo.lat)
                    });
                });



                // second input box --------------------------------------------------
                $(document).ready(function () {
                    // Initialize Typeahead on the inputField
                    var bloodhound = new Bloodhound({
                        datumTokenizer: Bloodhound.tokenizers.whitespace,
                        queryTokenizer: Bloodhound.tokenizers.whitespace,
                        remote: {
                            url: 'https://nominatim.openstreetmap.org/search?q=%QUERY&format=json&countrycodes=IN',
                            wildcard: '%QUERY',
                            transform: function (response) {
                                return $.map(response, function (place) {
                                    return place;  // Return the entire OpenStreetMap object for each place
                                });
                            }
                        }
                    });

                    $('#inputField1').typeahead({
                        hint: true,
                        highlight: true,
                        minLength: 1
                    },
                        {
                            name: 'places',
                            source: bloodhound,
                            templates: {
                                suggestion: function (data) {

                                    // Use data as a JSON string
                                    let mainad = truncateString(data.name, 40);



                                    let detailAd = data.display_name;

                                    let modifiedDetailAd = detailAd.replace(mainad, '');
                                    modifiedDetailAd = modifiedDetailAd.replace(',', '');
                                    detailAd = truncateString(modifiedDetailAd, 48);

                                    // Remove <strong> tag from mainAd


                                    return '<li class="custom-menu-item1" data-info=\'' + JSON.stringify(data) + '\'><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none">' +
                                        '<circle cx="16" cy="16" r="15.75" fill="white" stroke="black" stroke-width="0.5" />' +
                                        '<path d="M15.8362 5.41797C13.6863 5.42082 11.6252 6.27614 10.105 7.79637C8.58473 9.3166 7.72941 11.3777 7.72656 13.5276C7.72656 19.3496 15.282 26.6415 15.603 26.949C15.6655 27.0097 15.7491 27.0436 15.8362 27.0436C15.9233 27.0436 16.0069 27.0097 16.0693 26.949C16.3903 26.6415 23.9458 19.3496 23.9458 13.5276C23.9429 11.3777 23.0876 9.3166 21.5674 7.79637C20.0472 6.27614 17.9861 5.42082 15.8362 5.41797ZM15.8362 17.2445C15.101 17.2445 14.3824 17.0265 13.7712 16.6181C13.1599 16.2097 12.6835 15.6292 12.4022 14.95C12.1209 14.2708 12.0473 13.5235 12.1907 12.8025C12.3341 12.0814 12.6881 11.4192 13.2079 10.8993C13.7277 10.3795 14.39 10.0255 15.111 9.8821C15.8321 9.73868 16.5794 9.81229 17.2586 10.0936C17.9378 10.3749 18.5183 10.8513 18.9267 11.4626C19.3351 12.0738 19.5531 12.7925 19.5531 13.5276C19.5525 14.5132 19.1607 15.4583 18.4638 16.1552C17.7669 16.8521 16.8218 17.2439 15.8362 17.2445Z" fill="#1D004C" />' +
                                        '<circle cx="16" cy="29" r="1" fill="#1D004C" /></svg>' +
                                        `<div class="address"><p class="mainAd">${mainad}</p><p class="detailAd">${detailAd}</p></div></li>`;
                                },
                                notFound: function () {
                                    return '<li class="custom-menu-item1"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none">' +
                                        '<circle cx="16" cy="16" r="15.75" fill="white" stroke="black" stroke-width="0.5" />' +
                                        '<path d="M15.8362 5.41797C13.6863 5.42082 11.6252 6.27614 10.105 7.79637C8.58473 9.3166 7.72941 11.3777 7.72656 13.5276C7.72656 19.3496 15.282 26.6415 15.603 26.949C15.6655 27.0097 15.7491 27.0436 15.8362 27.0436C15.9233 27.0436 16.0069 27.0097 16.0693 26.949C16.3903 26.6415 23.9458 19.3496 23.9458 13.5276C23.9429 11.3777 23.0876 9.3166 21.5674 7.79637C20.0472 6.27614 17.9861 5.42082 15.8362 5.41797ZM15.8362 17.2445C15.101 17.2445 14.3824 17.0265 13.7712 16.6181C13.1599 16.2097 12.6835 15.6292 12.4022 14.95C12.1209 14.2708 12.0473 13.5235 12.1907 12.8025C12.3341 12.0814 12.6881 11.4192 13.2079 10.8993C13.7277 10.3795 14.39 10.0255 15.111 9.8821C15.8321 9.73868 16.5794 9.81229 17.2586 10.0936C17.9378 10.3749 18.5183 10.8513 18.9267 11.4626C19.3351 12.0738 19.5531 12.7925 19.5531 13.5276C19.5525 14.5132 19.1607 15.4583 18.4638 16.1552C17.7669 16.8521 16.8218 17.2439 15.8362 17.2445Z" fill="#1D004C" />' +
                                        '<circle cx="16" cy="29" r="1" fill="#1D004C" /></svg>' +
                                        '<div class="address"><p class="mainAd">No place found</p><p class="detailAd"></p></div></li>';
                                }

                            }
                        }).on('typeahead:select', function (ev, suggestion) {
                            // Handle the selection of an item from the menu
                            console.log('Selected:', suggestion);
                        }).on('typeahead:render', function () {
                            // Remove any previous suggestions
                            $('.custom-menu1').empty();
                        }).on('typeahead:rendered', function () {

                            // Append suggestions to the ul after rendering
                            $('.custom-menu1').append($('.tt-menu .custom-menu-item1').clone());
                        });

                    // Event handling for clicking on suggestions
                    $(document).on('click', '.custom-menu-item1', function () {
                        // Retrieve the data from the data attribute
                        var dataInfo = $(this).data('info');
                        console.log(dataInfo);
                        liData1(dataInfo.osm_type, dataInfo.osm_id, dataInfo.name, dataInfo.lon, dataInfo.lat)
                    });
                });

                // jquery -------------------------------------------------------------------
                function liData(node, id, mainad, long, lat) {
                    console.log("osm type:", node);
                    console.log("ID:", id);
                    console.log("long:", long);
                    console.log("lat:", lat);
                    if (!locationsObj["pickup"]) {
                        locationsObj["pickup"] = {};
                    }
                    locationsObj["pickup"].osm_type = node;
                    locationsObj["pickup"].osm_id = id;
                    locationsObj["pickup"].long = long;
                    locationsObj["pickup"].lat = lat;
                    locationsObj["pickup"].mainad = mainad;

                    console.log(locationsObj);
                    document.getElementById('inputField').value = mainad;
                    checkTextboxes();
                }


                console.log(locationsObj);




                function liData1(node, id, mainad, long, lat) {

                    console.log("osm type:", node);
                    console.log("ID:", id);
                    console.log("long:", long);
                    console.log("lat:", lat);
                    if (!locationsObj["dropOff"]) {
                        locationsObj["dropOff"] = {};
                    }
                    locationsObj["dropOff"].osm_type = node;
                    locationsObj["dropOff"].osm_id = id;
                    locationsObj["dropOff"].mainad = mainad;
                    locationsObj["dropOff"].long = long;
                    locationsObj["dropOff"].lat = lat;

                    console.log(locationsObj);
                    document.getElementById('inputField1').value = mainad;
                    checkTextboxes();
                }

                var currentTime = new Date().toLocaleTimeString('en-US', { hour12: false });

                // Initialize the date-time picker with flatpickr
                flatpickr('#datetimePicker', {
                    enableTime: true,
                    dateFormat: "D j M Y h:i K",
                    time_24hr: false,
                    minDate: "today", // Set the minimum date to today

                    onChange: function (selectedDates, dateStr, instance) {
                        var selectedDate = selectedDates.length > 0 ? selectedDates[0] : null;

                        // Get today's date
                        var today = new Date();

                        // Check if the selected date is today
                        if (selectedDate && selectedDate.getDate() === today.getDate() && selectedDate.getMonth() === today.getMonth() && selectedDate.getFullYear() === today.getFullYear()) {
                            // If the selected date is today, update minTime to currentTime
                            instance.set('minTime', currentTime);
                        } else {
                            // Otherwise, allow any time
                            instance.set('minTime', '00:00');
                        }
                        if (!locationsObj["datetime"]) {
                            locationsObj["datetime"] = {};
                        }
                        locationsObj["datetime"] = dateStr;
                        console.log('Selected Date and Time:', dateStr);
                        console.log(locationsObj);
                        checkTextboxes();
                    }
                });

                function searchCab(event) {
                    event.preventDefault();
                    if (Object.keys(locationsObj["pickup"]).length !== 0 && Object.keys(locationsObj["dropOff"]).length !== 0 && Object.keys(locationsObj["datetime"]).length !== 0) {
                        // Enable the button
                        document.getElementById("submit").disabled = false;
                        document.querySelector(".loader-wrapper").style.display = "flex";
                        mapdata();

                    } else {
                        // Disable the button
                        document.getElementById("submit").disabled = true;
                    }
                }
                document.getElementById("submit").disabled = true;





                var map = L.map('map').setView([0, 0], 2); // Set the initial center and zoom level

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);
                map.zoomControl.remove();
                map.attributionControl.setPrefix('E=Cab'); // This sets an empty prefix
                map.attributionControl.remove();

                function mapdata() {


                    function haversine(lat1, lon1, lat2, lon2) {
                        const R = 6371; // Radius of the Earth in kilometers
                        const dLat = (lat2 - lat1) * Math.PI / 180;  // convert to radians
                        const dLon = (lon2 - lon1) * Math.PI / 180;  // convert to radians
                        const a =
                            Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                            Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                            Math.sin(dLon / 2) * Math.sin(dLon / 2);
                        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
                        const distance = R * c; // Distance in kilometers
                        return distance;
                    }


                    console.log("map data start");
                    map.eachLayer(layer => {
                        if (layer instanceof L.Marker || layer instanceof L.Polyline) {
                            layer.remove();
                        }
                    });
                    // Replace with the latitude and longitude of your two locations
                    var startLocation = L.latLng(locationsObj["pickup"].lat, locationsObj["pickup"].long);
                    var endLocation = L.latLng(locationsObj["dropOff"].lat, locationsObj["dropOff"].long);
                    // var startLocation = L.latLng(20.3735482, 72.9084376);
                    // var endLocation = L.latLng(24.7517805, 72.2041022);
                    // console.log('Pickup Coordinates:', locationsObj["pickup"].lat, locationsObj["pickup"].long);
                    // console.log('DropOff Coordinates:', locationsObj["dropOff"].lat, locationsObj["dropOff"].long);

                    // Add markers for the start and end locations
                    // Add markers for the start and end locations
                    var startIcon = L.icon({
                        iconUrl: 'static/pictures/start-mark.png',
                        iconSize: [32, 32],
                        iconAnchor: [16, 32],
                        popupAnchor: [0, -32]
                    });

                    var endIcon = L.icon({
                        iconUrl: 'static/pictures/end-mark.png',
                        iconSize: [22, 32],
                        iconAnchor: [16, 32],
                        popupAnchor: [0, -32]
                    });

                    // Add markers with custom icons
                    var startMarker = L.marker(startLocation, { icon: startIcon }).addTo(map);
                    var endMarker = L.marker(endLocation, { icon: endIcon }).addTo(map);

                    // Create tooltips with location names and set offset
                    var startTooltip = L.tooltip({ permanent: true, direction: 'top', className: 'tooltip', offset: [0, -30] })
                        .setLatLng(startLocation)
                        .setContent(locationsObj["pickup"].mainad);

                    var endTooltip = L.tooltip({ permanent: true, direction: 'top', className: 'tooltip', offset: [0, -30] })
                        .setLatLng(endLocation)
                        .setContent(locationsObj["dropOff"].mainad);

                    // Bind tooltips to markers
                    startMarker.bindTooltip(startTooltip).openTooltip();
                    endMarker.bindTooltip(endTooltip).openTooltip();
                    // Calculate distance using Haversine formula
                    var distanceInKm = (haversine(startLocation.lat, startLocation.lng, endLocation.lat, endLocation.lng)).toFixed(2);
                    console.log('haversine Distance:', distanceInKm, 'km');

                    // Request route information from OpenRouteService API
                    var apiKey = '5b3ce3597851110001cf62481858258d82b749f091578b63d9c28315';
                    var routingURL = 'https://api.openrouteservice.org/v2/directions/driving-car?api_key=' +
                        apiKey + '&start=' + startLocation.lng + ',' + startLocation.lat +
                        '&end=' + endLocation.lng + ',' + endLocation.lat;

                    fetch(routingURL)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log(data);
                            // Extract coordinates from the route
                            var coordinates = data.features[0].geometry.coordinates.map(coord => [coord[1], coord[0]]);

                            // Display route on the map
                            var route = L.polyline(coordinates, { color: 'rgb(2 0 92' }).addTo(map);

                            // Fit the map to the bounds of the route
                            map.fitBounds(route.getBounds());

                            // Display route information in the console
                            var distanceInKm = (data.features[0].properties.segments[0].distance / 1000).toFixed(2);
                            var durationInMinutes = (data.features[0].properties.segments[0].duration / 60).toFixed(2);
                            totaldistance = distanceInKm;
                            console.log('Distance:', distanceInKm, 'km');
                            console.log('Duration:', durationInMinutes, 'minutes');
                            console.log('Instructions:', data.features[0].properties.segments[0].instructions);
                            console.log("map data end");
                            setPrices();
                            map.fitBounds(route.getBounds());



                        })
                        .catch(error => console.error('Error fetching route data:', error));
                }

                function setPrices() {
                    locationsObj["totaldistance"] = totaldistance;
                    // let fare1 = 100;
                    // let fare2 = 200;
                    // let fare3 = 500;
                    var comPrice;
                    var famPrice;
                    var prePrice;
                    fetch('function.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ data: "fetch_price" })
                    })
                        .then(res => res.json())
                        .then(data => {
                            let comPrice, famPrice, prePrice;

                            data.forEach(element => {

                                if (element[1] == 'Compact') {
                                    comPrice = element[3];
                                } else if (element[1] == 'Family') {
                                    famPrice = element[3];
                                } else {
                                    prePrice = element[3];
                                }
                            });

                            let compact = comPrice * totaldistance;
                            let family = famPrice * totaldistance;
                            let premium = prePrice * totaldistance;

                            // Round up using Math.ceil()
                            cabprices["compact"] = Math.ceil(compact);
                            cabprices["family"] = Math.ceil(family);
                            cabprices["premium"] = Math.ceil(premium);

                            // Display prices rounded up
                            document.getElementById("cprice").innerText = "₹ " + cabprices["compact"];
                            document.getElementById("fprice").innerText = "₹ " + cabprices["family"];
                            document.getElementById("pprice").innerText = "₹ " + cabprices["premium"];
                            document.querySelector(".loader-wrapper").style.display = "none";
                            document.getElementById("div1").style.display = "none";
                            document.getElementById("div2").style.display = "flex";
                        })
                        .catch(error => console.log(error));



                }




                var selectedElement = null;

                function cabSelect(text) {
                    // Remove styling from the previously selected element
                    if (selectedElement !== null) {
                        selectedElement.style.boxShadow = "";
                        selectedElement.style.background = "";
                    }

                    // Apply styling to the currently selected element
                    var currentElement = document.getElementById(text);
                    currentElement.style.boxShadow = "0px 0px 0px 1px rgb(1 3 31 / 48%)";
                    currentElement.style.background = "rgb(247, 247, 255)";

                    // Update the selectedElement variable
                    selectedElement = currentElement;
                    locationsObj["selectedcab"] = text;

                    if (text == "compact") {

                        cabSelected = true;
                        console.log(cabprices["compact"]);
                        locationsObj["totalfare"] = cabprices["compact"];

                    }
                    else if (text == "family") {
                        cabSelected = true;
                        console.log(cabprices["family"]);
                        locationsObj["totalfare"] = cabprices["family"];
                    }
                    else if (text == "premium") {
                        cabSelected = true;
                        console.log(cabprices["premium"]);
                        locationsObj["totalfare"] = cabprices["premium"];
                    }
                    else { cabSelected = false; }
                    checkFinalbtn()
                    console.log("cab select;> " + cabSelected);
                }



                function paymentchanged(type) {

                    if (type == 'online') {
                        paymentSelected = true;
                        locationsObj["paymenttype"] = type;
                        console.log(type);
                        console.log(locationsObj);
                        console.log(cabSelected);
                        console.log(paymentSelected);
                    }
                    else if (type == 'cash') {
                        paymentSelected = true;
                        locationsObj["paymenttype"] = type;
                        console.log(type);
                        console.log(locationsObj);
                        console.log(cabSelected);
                        console.log(paymentSelected);
                    }
                    checkFinalbtn()
                    console.log("paymentSelected> " + paymentSelected);

                }

                function finalbtn() {
                    document.querySelector(".loader-wrapper").style.display = "flex";
                    console.log(locationsObj);
                    nearestDriver();


                }


                function pay() {
                    fetch('payment/payment-script.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            name: locationsObj.user.name,
                            email: locationsObj.user.email,
                            amount: locationsObj.totalfare,
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
                                document.querySelector(".loader-wrapper").style.display = "none";

                            }
                        })
                        .catch(error => {
                            // Handle errors
                            console.error('Error making payment:', error);
                        });
                }



                function nearestDriver() {
                    // Check if geolocation is available
                    if ("geolocation" in navigator) {
                        // Get the current position of the user
                        navigator.geolocation.getCurrentPosition(
                            // Success callback
                            function (position) {
                                const latitude = position.coords.latitude;
                                const longitude = position.coords.longitude;

                                // Send the user's coordinates and selected cab type to the server
                                fetch('nearest.php', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json'
                                    },
                                    body: JSON.stringify({
                                        userLatitude: latitude,
                                        userLongitude: longitude,
                                        selectedCab: locationsObj.selectedcab, // Ensure `locationsObj` is defined and contains `selectedcab`
                                    })
                                })
                                    // Process the server response
                                    .then(res => res.json())
                                    .then(data => {
                                        console.log(data);
                                        // Check for error in the response
                                        if (data.error) {
                                            const loaderWrapper = document.querySelector('.loader-wrapper');
                                            loaderWrapper.querySelector('.loader').classList.add('hidden');
                                            const paragraph = loaderWrapper.querySelector('.car-detail');
                                            paragraph.innerText = data.error;

                                            removeLoaderAndUpdateText(data.error);

                                            // Create a button container div
                                            const btnContainer = document.createElement('div');
                                            btnContainer.classList.add('text');

                                            // Create a back button element
                                            const button = document.createElement('a');
                                            button.onclick = function () {
                                                document.querySelector(".loader-wrapper").style.display = "none";
                                                restoreLoaderAndText();
                                                const loaderWrapper = document.querySelector('.loader-wrapper');
                                                const textElements = loaderWrapper.querySelectorAll('.text');
                                                textElements.forEach(element => {
                                                    element.classList.add('hidden');
                                                });
                                            }
                                            button.classList.add('main-btn', 'Mytrips'); // Add classes to the button
                                            button.innerText = 'Back'; // Set the button text

                                            // Append the button to the button container
                                            btnContainer.appendChild(button);

                                            // Append the button container to the loader wrapper
                                            loaderWrapper.appendChild(btnContainer);
                                        } else {
                                            // Call the function to process the booking (uncomment the next line if `bookProcess` is implemented)
                                            bookProcess(data);
                                        }
                                    })
                                    // Catch and log any fetch errors
                                    .catch(error => {
                                        console.error("Error fetching data:", error);
                                    });
                            },
                            // Error callback for geolocation
                            function (error) {
                                console.error("Error getting location:", error.message);
                            }
                        );
                    } else {
                        console.error("Geolocation is not supported by this browser.");
                    }
                }


                function bookProcess() {
                    if (locationsObj.paymenttype == 'cash') {

                        fetch('function.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(locationsObj)
                        })
                            .then(response => response.json())
                            .then(data => {
                                console.log(data);
                                if (data.assigned_drivers && data.nearest) {
                                    const urlWithDriverId = `assigned?bookid=${data.bookid}`;
                                    document.querySelector(".loader-wrapper").style.display = "none";

                                    window.location.href = urlWithDriverId;
                                }

                            })
                    }
                    else {
                        fetch('function.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(locationsObj)
                        })
                            .then(response => response.json())
                            .then(data => {
                                if (data == 'done') {
                                    pay();
                                }
                                // console.log(data);
                            })
                            .catch(error => {
                                // Handle errors
                                console.error('Error:', error);
                            });


                    }
                }
            </script>
            <script>
                let locationPermissionGranted = false; // Global variable to store location permission status

                // Function to hide the loader and update the paragraph text
                function removeLoaderAndUpdateText(txt) {
                    const loaderWrapper = document.querySelector('.loader-wrapper');
                    loaderWrapper.querySelector('.loader').classList.add('hidden');
                    const paragraph = loaderWrapper.querySelector('.car-detail');
                    paragraph.innerText = txt;
                }

                // Function to show the loader and revert the paragraph text
                function restoreLoaderAndText() {
                    const loaderWrapper = document.querySelector('.loader-wrapper');
                    loaderWrapper.querySelector('.loader').classList.remove('hidden');

                    const paragraph = loaderWrapper.querySelector('.car-detail');
                    paragraph.innerText = 'Please wait, we are processing...';
                }

                // Initially set the loader to be visible
                removeLoaderAndUpdateText('Kindly enable location and internet to proceed!');
                document.querySelector('.loader-wrapper').style.display = 'flex';

                // Function to check internet connectivity (using navigator.onLine)
                function checkInternetConnectivity() {
                    return navigator.onLine;
                }

                // Function to request location permission (improved for clarity)
                function requestLocationPermission() {
                    return new Promise((resolve, reject) => {
                        if (navigator.geolocation) {
                            navigator.geolocation.getCurrentPosition(
                                () => {
                                    locationPermissionGranted = true;
                                    resolve(true);
                                },
                                () => {
                                    locationPermissionGranted = false;
                                    resolve(false);
                                },
                                {
                                    enableHighAccuracy: false, // Improved: Set to false for faster acquisition
                                    timeout: 10000 // Improved: Set a reasonable timeout
                                }
                            );
                        } else {
                            resolve(false); // No geolocation support
                        }
                    });
                }

                let latitude, longitude;

                // Function to validate permissions and get coordinates (improved)
                async function validatePermissions() {
                    const isConnected = checkInternetConnectivity();
                    let hasLocationPermission;

                    // Check sessionStorage for latitude and longitude
                    latitude = sessionStorage.getItem('latitude');
                    longitude = sessionStorage.getItem('longitude');

                    if (latitude && longitude) {
                        document.querySelector('.loader-wrapper').style.display = 'none';
                        restoreLoaderAndText();

                    } else {

                        if (locationPermissionGranted) {
                            hasLocationPermission = true;
                        } else {
                            hasLocationPermission = await requestLocationPermission();
                        }

                        if (isConnected && hasLocationPermission) {
                            navigator.geolocation.getCurrentPosition(
                                (position) => {
                                    latitude = position.coords.latitude;
                                    longitude = position.coords.longitude;
                                    console.log('Latitude:', latitude);
                                    console.log('Longitude:', longitude);
                                    sessionStorage.setItem('latitude', latitude);
                                    sessionStorage.setItem('longitude', longitude);
                                    // Use the coordinates here (e.g., display on map, send to server)
                                    // ...

                                    document.querySelector('.loader-wrapper').style.display = 'none';
                                    restoreLoaderAndText();
                                },
                                (error) => {
                                    console.error('Error getting location:', error.message);
                                    console.error('Error code:', error.code);
                                    removeLoaderAndUpdateText('Unable to obtain location. Please try again.');
                                }
                            );
                        } else {
                            removeLoaderAndUpdateText('Kindly enable location and internet to proceed!');
                        }
                    }
                }

                // Run the validation function on page load
                window.onload = validatePermissions;

            </script>

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