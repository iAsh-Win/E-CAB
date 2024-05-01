<?php
session_start();

if (isset ($_SESSION['Logged-in-user']) && isset ($_SESSION['isLoggedin']) && $_SESSION['isLoggedin'] == true) {
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
            <title>E-CAB | Shere Your Insigts</title>
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

                max-height: calc(100vh - 40vh);
                overflow-y: auto;
                width: max-content;
            }

            .tripsdiv {
                display: flex;
                flex-direction: column;
                margin: 0px auto;
            }

            .mytext {
                color: rgb(2 0 92);
                font-family: "Montserrat";
                /* font-family: "Poppins"; */
                font-size: 14px;
                font-style: normal;
                font-weight: 500;
                line-height: normal;
            }
        </style>

        <body>
            <nav>
                <div class="logo"><a href="./">
                        <img src="static/pictures/logo2.png" class="logo-img" alt="logo"></a>
                </div>

                <div class="user-div">

                    <div class="btns"><a href="trips" class="main-btn Mytrips">My Trips</a></div>

                    <img src="static/pictures/favicon.png" class="user-image" alt="">
                    <div class="dropbox">
                        <a href="#">Manage Profile</a>
                        <a href="#">Log out</a>
                    </div>

                </div>
            </nav>
            <main>
                <div class="display" style="grid-template-columns: 1fr; grid-template-rows: 1fr;">
                    <div class="tripsdiv" id="feeddiv">

                        <div class="text">
                            <h1 class="title" style="margin: 30px 0px;">Shere Your Feeback</h1>
                        </div>

                        <div class="mydiv">

                            <div class="feeds" style="padding: 0px 5px;">
                                <textarea id="feed" style="border:1px solid; border-radius: 20px; background-color: inherit; padding:10px;    width: 386px;
                       height: 185px; resize: none;" class="inputbox mytext" cols="30" rows="10"></textarea>
                            </div>
                            <div class="btns" style="text-align: center;margin: 30px auto;">
                                <a class="main-btn Mytrips" onclick="submitFeedback()">Submit</a>
                            </div>

                        </div>


                    </div>
                    <div class="tripsdiv" id="msgdiv" style="display:none;">
                        <div class="mydiv mytext" style="margin:auto; font-size:17px">Your Valuable Insights are Submitted
                            Successfully.
                            <div class="btns" style="text-align: center;margin: 10px auto;"><a href="index"
                                    class="main-btn Mytrips">Deshboard</a></div>
                        </div>

                    </div>
                </div>
            </main>
            <script>
                function submitFeedback() {
                    // Retrieve feedback from textarea
                    let feed = document.getElementById("feed").value;

                    // Check if feedback is not empty
                    if (feed.length > 0) {
                        // Make a POST request to "function.php" endpoint
                        fetch("function.php", {
                            method: 'POST', // HTTP method
                            headers: {
                                'Content-Type': 'application/json', // Specify content type
                                // Add any additional headers as needed
                            },
                            body: JSON.stringify({ 'feedback': feed }) // Convert feedback to JSON string
                        })
                            .then(response => response.text()) // Parse JSON response
                            .then(data => {

                                document.getElementById("feed").value = "";
                                document.getElementById("feeddiv").style.display = "none";
                                document.getElementById("msgdiv").style.display = "flex";
                            })
                            .catch(error => {
                                console.error('Error:', error); // Log any errors that occur during the request
                                // Handle error here
                            });
                    }
                }
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