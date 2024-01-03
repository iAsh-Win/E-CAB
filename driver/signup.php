<?php
session_start();
include("path.php");


if (isset($_SESSION["driver_login"]) && $_SESSION["driver_login"] == true && isset($_SESSION["driveremail"]) && $_SESSION["driveremail"] != "") {
    header("location:" . BASE_URL);
} else {
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Driver Registration</title>
        <!-- plugins:css -->
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    </head>
    <link rel="stylesheet" href="assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- End layout styles -->
    <link rel="shortcut icon" href="assets/images/favicon.png" />
    <style>
        /* Add or modify styles as needed */
        #proBanner {
            background: rgb(2, 0, 36);
            background: linear-gradient(90deg, rgba(2, 0, 36, 1) 0%, rgba(1, 1, 45, 1) 35%, rgba(0, 212, 255, 1) 100%);
        }
    </style>
    <?php

    $er = "";
    if (isset($_GET["error"]) && $_GET["error"] != "") {
        $er = urldecode($_GET["error"]);
    }

    ?>
    </head>

    <body>
        <div class="container-scroller">
            <?php
            if ($er) {
                echo '<div class="row p-0 m-0 proBanner" id="proBanner">
                    <div class="col-md-12 p-1 m-0">
                        <div class="card-body card-body-padding d-flex align-items-center justify-content-between">
                            <div class="ps-lg-1">
                                <div class="d-flex align-items-center justify-content-between">
                                <i
                                        class="mdi mdi-alert-circle me-3 text-white"></i>
                                    <p class="mb-0 font-weight-medium me-3 buy-now-text">' . $er . '!</p>
                                    
                                </div>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                            
                                <button id="bannerClose" class="btn border-0 p-0">
                                    <i class="mdi mdi-close text-white me-0"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                ';
            }
            ?>
            <div class="container-fluid page-body-wrapper full-page-wrapper">
                <div class="row w-100 m-0">
                    <div class="content-wrapper full-page-wrapper  auth login-bg">
                        <h3 class="text-center mb-4">Regiter yourself to work with us!</h3>
                        <div class="card col-lg-11 mx-auto my-auto p-3">
                            <form class="form-sample" action=function.php method="post" enctype="multipart/form-data">
                                <p class="card-description"> Driver info </p>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">First Name</label>
                                            <div class="col-sm-9">
                                                <input type="text" id="firstname" name="firstname"
                                                    class="form-control text-white" required />
                                                <span id="firstnameError" style="color: red; display: none;">Only characters
                                                    allowed</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Last Name</label>
                                            <div class="col-sm-9">
                                                <input type="text" id="lastname" name="lastname"
                                                    class="form-control text-white" required />
                                                <span id="lastnameError" style="color: red; display: none;">Only characters
                                                    allowed</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Gender</label>
                                            <div class="col-sm-9">
                                                <select class="form-control text-white" name="gender">
                                                    <option>Male</option>
                                                    <option>Female</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Email</label>
                                            <div class="col-sm-9">
                                                <input type="email" name="email" class="form-control text-white" required />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Phone</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="phone" class="form-control text-white"
                                                    pattern="[0-9]{1,10}"
                                                    title="Please enter only numbers (up to 10 digits)" required />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">

                                            <label class="col-sm-3 col-form-label">Select your Car Category</label>
                                            <div class="col-sm-9">
                                                <select class="form-control text-white" name="cabcate">
                                                    <?php
                                                    include("path.php");
                                                    include inc . 'db.php';
                                                    $sql = 'select * from cabcate';
                                                    $result = mysqli_query($conn, $sql);
                                                    while ($row = mysqli_fetch_array($result)) {
                                                        echo '<option value="' . $row['cateid'] . '">' . $row['catename'] . '</option>';
                                                    }
                                                    ?>

                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Car-No</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="carno" class="form-control text-white" required
                                                    pattern="^[A-Z]{2}\d{2}[A-Z]{2}\d{4}$"
                                                    title="Format: Add Such Like format GJ01FR1234." />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Driving Licence No</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="licence" class="form-control text-white" required
                                                    pattern="^[A-Z]{2}\d{13}$"
                                                    title="Format: Add Such Like format GJ1234567891234." />
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <p class="card-description"> Address </p>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">City</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="city" class="form-control text-white" required />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">State</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="state" class="form-control text-white" required />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Pincode</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="pin" class="form-control text-white" required
                                                    pattern="[0-9]{6}" title="Please enter a 6-digit pincode" />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Profile Picture</label>
                                            <div class="input-group-sm col-sm-9">
                                                <input type="file" name="img" id="profilePicture"
                                                    class="file-upload-default">
                                                <input type="text" class="form-control file-upload-info mb-2" disabled
                                                    placeholder="Upload Image">

                                                <span class="input-group-append">
                                                    <button class="file-upload-browse btn btn-primary mb-4"
                                                        type="button">Upload</button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <p class="card-description">Passwords </p>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Set your password</label>
                                            <div class="col-sm-9">
                                                <input type="password" name="password" class="form-control text-white"
                                                    pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*\W)(?!.*\s).{8,}"
                                                    title="Password must contain at least one uppercase letter, one lowercase letter, one special character, and be at least 8 characters long"
                                                    required />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Verify your Password</label>
                                            <div class="col-sm-9">
                                                <input type="password" id="verifyPassword" class="form-control text-white"
                                                    pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*\W)(?!.*\s).{8,}"
                                                    title="Password must match the previous entry" required />
                                                <input type="hidden" name="form_name" value="driverreg">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary mb-2">Submit</button>
                            </form>

                        </div>
                    </div>
                    <!-- content-wrapper ends -->
                </div>
                <!-- row ends -->
            </div>
            <!-- page-body-wrapper ends -->
        </div>
        <!-- container-scroller -->
        <!-- plugins:js -->
        <script src="assets/vendors/js/vendor.bundle.base.js"></script>
        <!-- endinject -->
        <!-- Plugin js for this page -->
        <!-- End plugin js for this page -->
        <!-- inject:js -->
        <script src="assets/js/off-canvas.js"></script>
        <script src="assets/js/hoverable-collapse.js"></script>
        <script src="assets/js/misc.js"></script>
        <script src="assets/js/settings.js"></script>
        <script src="assets/js/todolist.js"></script>
        <!-- endinject -->

        <script>
            $(document).ready(function () {
                $('.file-upload-browse').on('click', function () {
                    var fileInput = $(this).parents('.input-group-sm').find('.file-upload-default');
                    fileInput.trigger('click');
                });
                $('.file-upload-default').on('change', function () {
                    $(this).next('.form-control').val($(this).val().split('\\').pop());
                });
            });
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const firstNameInput = document.getElementById('firstname');

                firstNameInput.addEventListener('input', function () {
                    const firstName = firstNameInput.value;
                    const regex = /^[a-zA-Z]+$/; // Regular expression to allow only characters

                    if (!regex.test(firstName)) {
                        document.getElementById('firstnameError').style.display = 'inline'; // Show error message
                        firstNameInput.setCustomValidity('Only characters allowed'); // Set custom validation message
                    } else {
                        document.getElementById('firstnameError').style.display = 'none'; // Hide error message
                        firstNameInput.setCustomValidity(''); // Reset custom validation message
                    }
                });
            });
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const lastNameInput = document.getElementById('lastname');

                lastNameInput.addEventListener('input', function () {
                    const lastName = lastNameInput.value;
                    const regex = /^[a-zA-Z]+$/; // Regular expression to allow only characters

                    if (!regex.test(lastName)) {
                        document.getElementById('lastnameError').style.display = 'inline'; // Show error message
                        lastNameInput.setCustomValidity('Only characters allowed'); // Set custom validation message
                    } else {
                        document.getElementById('lastnameError').style.display = 'none'; // Hide error message
                        lastNameInput.setCustomValidity(''); // Reset custom validation message
                    }
                });
            });
        </script>

        <script>
            window.onload = function () {
                var verifyPasswordInput = document.getElementById('verifyPassword');

                verifyPasswordInput.addEventListener('input', function () {
                    var setPassword = document.querySelector('input[name="password"]');
                    if (setPassword.value !== verifyPasswordInput.value) {
                        verifyPasswordInput.setCustomValidity("Passwords do not match");
                    } else {
                        verifyPasswordInput.setCustomValidity('');
                    }
                });
            };
        </script>


    </body>

    <script>
        // JavaScript to hide the banner when the close button is clicked
        document.getElementById("bannerClose").addEventListener("click", function () {
            document.getElementById("proBanner").style.display = "none";

        });

        setTimeout(function () {
            document.getElementById("proBanner").style.display = "none";
        }, 3000);
    </script>

    </html>
    <?php
}
?>