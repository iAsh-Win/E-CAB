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
    <title>Driver Login</title>
    <!-- plugins:css -->
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
    <link rel="shortcut icon" href="../static/pictures/favicon.png" />
    <style>
      /* Add or modify styles as needed */
      #proBanner {
        background: rgb(2, 0, 36);
        background: linear-gradient(90deg, rgba(2, 0, 36, 1) 0%, rgba(1, 1, 45, 1) 35%, rgba(0, 212, 255, 1) 100%);
      }
    </style>

    <?php

    $er = "";
    if (isset($_GET["msg"]) && $_GET["msg"] != "") {
      $er = urldecode($_GET["msg"]);
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
                                        class="mdi mdi-checkbox-marked-circle-outline me-3 text-white"></i>
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
          <div class="content-wrapper full-page-wrapper d-flex align-items-center auth register-half-bg">
            <div class="card col-lg-4 mx-auto">
              <div class="card-body px-5 py-5">
                <h3 class="card-title text-left mb-3">Driver Login</h3>
                <form action="function.php" method="post">
                  <div class="form-group">
                    <label>Username (Email) *</label>
                    <input type="email" name="email" class="form-control p_input text-white" required>
                  </div>
                  <div class="form-group">
                    <label>Password *</label>
                    <input type="password" name="pass" class="form-control p_input text-white" required>
                  </div>
                  <div class="form-group d-flex align-items-center justify-content-between">
                    <div class="form-check">
                      <label class="form-check-label">
                        <input type="checkbox" name="forgot" class="form-check-input"> Remember me </label>
                    </div>
                    <a href="forgot-password" class="forgot-pass">Forgot password</a>
                  </div>
                  <div class="text-center">
                    <input type="hidden" name="driverlogin" value="yes">
                    <button type="submit" class="btn btn-primary btn-block enter-btn">Login</button>
                  </div>
                  <div class="d-flex">
                    <button class="btn btn-facebook me-2 col">
                      <i class="mdi mdi-facebook"></i> Facebook </button>
                    <button class="btn btn-google col">
                      <i class="mdi mdi-google-plus"></i> Google plus </button>
                  </div>
                  <p class="sign-up">Don't have an Account?<a href="signup"> Sign Up</a></p>
                </form>
              </div>
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
    <script>
      // JavaScript to hide the banner when the close button is clicked
      document.getElementById("bannerClose").addEventListener("click", function () {
        document.getElementById("proBanner").style.display = "none";

      });

      setTimeout(function () {
        document.getElementById("proBanner").style.display = "none";
      }, 3000);
    </script>
    <!-- endinject -->
  </body>

  </html>

  <?php
}
?>