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


            .profile-container {
                display: flex;
                /* Arrange elements horizontally */
                align-items: center;
                /* Vertically align image and label */
                margin: 15px auto;
                /* Add some spacing below */
                cursor: default;
                /* Default cursor for container */
            }

            .clickable {
                cursor: pointer;
                /* Indicate clickable behavior for the image */
                border-radius: 50%;
                /* Make image circular */
                width: 100px;
                /* Set image width */
                height: 100px;
                /* Set image height */
                object-fit: cover;
                /* Crop image to fit within dimensions */
                border: 2px solid #ddd;
                /* Add a border for better visibility */
            }

            #profile-image-label {
                margin-left: 10px;
                /* Add spacing between image and label */
                font-size: 14px;
                /* Set label font size */
                color: #333;
                /* Set label text color */
            }
        </style>

        <body>
            <nav>
                <a href="./">
                    <div class="logo">
                        <img src="static/pictures/logo2.png" class="logo-img" alt="logo">
                    </div>
                </a>

                <div class="user-div">
                    <div class="btns"><a href="trips" class="main-btn Mytrips">My Trips</a></div>
                    <img src="<?php echo '../' . trim($row['userImage']); ?>" class="user-image" alt="userimage" id="pf">


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
                            <h1 class="title" style="margin: 30px 0px;">Update Your Profile</h1>
                        </div>

                        <div class="bookCtn">

                            <div class="profile-container">
                                <img src="<?php echo '../' . trim($row['userImage']); ?>" alt="User Profile Image"
                                    id="profile-image" class="clickable">
                                <input type="file" id="profile-image-upload" accept="image/*" style="display: none;">

                                <p id="error-message"></p>
                            </div>
                            <div class="inputbox mytext">
                                <?php ?>
                                <div class="svg1">Name</div>
                                <input type="text" name="username" id="name" value=" <?php echo $row["name"] ?>">
                            </div>

                            <div class="inputbox mytext">
                                <div class="svg1">Email</div>
                                <input type="text" name="email" value=" <?php echo $row["emailID"] ?>" disabled="true">
                            </div>
                            <?php
                            $mobile = $row["mobileNo"];
                            $mobile = isset($mobile) ? $mobile : $row["mobileNo"] ?? "";
                            echo ' <div class="inputbox mytext">
                                <div class="svg1">Mobile</div>
                                <input type="tel" name="mobile" id="mobile" maxlength="10" inputmode="numeric"  oninput="this.value = this.value.replace(/[^0-9]/g, \'\');" value="' . $mobile . '" >
                            </div>';
                            ?>


                            <p class="mytext" style="font-size: 11px;margin: 5px auto;color: red;" id="er"></p>
                            <div class="btns" style="
                        margin: 10px auto;
                    "><a onclick="change();" class="main-btn Mytrips" style="cursor: pointer;
                            background: rgb(0 25 115);
                            color: white; ">Change</a></div>

                        </div>
                    </div>
                </div>
            </main>
            <script>

                // Get references to the image, file input, and error paragraph elements
                const profileImage = document.getElementById("profile-image");
                const profileImageUpload = document.getElementById("profile-image-upload");
                const errorParagraph = document.getElementById("error-message");

                // Add event listener for initial image load (to handle missing image)
                window.addEventListener("load", function () {
                    if (!profileImage.src) {
                        errorParagraph.textContent = "Error: No image found.";
                        errorParagraph.style.color = "red";
                    } else {
                        // Clear any existing error message and color if there's an image
                        errorParagraph.textContent = "";
                        errorParagraph.style.color = "";
                    }
                });

                // Add event listener to the profile image for click events
                profileImage.addEventListener("click", function () {
                    profileImageUpload.click(); // Trigger file selection dialog when clicked
                });

                let uploadedFile = null;
                // (Optional) Handle file selection and image preview (if desired)
                profileImageUpload.addEventListener("change", function (event) {
                    uploadedFile = event.target.files[0];

                    // Validate file type
                    const allowedExtensions = /(.jpg|.jpeg|.png)$/i; // Case-insensitive regex
                    if (!allowedExtensions.test(uploadedFile.name)) {
                        errorParagraph.textContent = "Please select a JPG, JPEG, or PNG image.";
                        errorParagraph.style.color = "red";
                        return;
                    }

                    // Clear any existing error message on valid file selection
                    errorParagraph.textContent = "";
                    errorParagraph.style.color = "";

                    // (Optional) Display a preview of the selected image
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        profileImage.src = e.target.result;
                    };
                    reader.readAsDataURL(uploadedFile);

                    // (Here, you might implement logic to upload the file to your server)



                });


                function change() {
                    let pf = document.getElementById("pf");
                    let uploadedFile = document.querySelector('input[type="file"]').files[0]; // Get the file from the file input
                    let name = document.getElementById("name").value;
                    let mobile = document.getElementById("mobile").value;
                    let errorParagraph = document.getElementById("er");
                    errorParagraph.innerText = "";

                    // Validate inputs
                    if (name.length === 0) {
                        errorParagraph.innerText = "Name shouldn't be blank";
                        errorParagraph.style.color = "red";
                        return; // Stop execution
                    }

                    if (mobile.length === 0 || mobile.length !== 10) {
                        errorParagraph.innerText = "Mobile number must have 10 Digits";
                        errorParagraph.style.color = "red";
                        return; // Stop execution
                    }

                    if (!uploadedFile) {
                        errorParagraph.innerText = "Please upload an image";
                        errorParagraph.style.color = "red";
                        return; // Stop execution
                    }

                    const formData = new FormData();
                    formData.append("profile-image", uploadedFile);
                    formData.append("name", name);
                    formData.append("mobile", mobile);

                    // Send the FormData object to the PHP script using fetch
                    fetch("manage-save.php", {
                        method: "POST",
                        body: formData
                    })
                        .then(response => response.json())
                        .then(data => {

                            if (data.success) {
                                // Update the profile image src attribute to the new image URL from the server
                                pf.src = data.newImageUrl;
                                errorParagraph.textContent = "Profile Updated Sucessfully.";
                                errorParagraph.style.color = "green";
                            } else {
                                // Display an error message
                                errorParagraph.textContent = data.error;
                                errorParagraph.style.color = "red";
                            }
                        })
                        .catch(error => {
                            console.error("An error occurred:", error);
                        });
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