<?php
// Your PHP code here
session_start();
if (!isset($_SESSION["adminlogin"]) && !isset($_SESSION["adminusername"])) {
  ?>
  <!DOCTYPE html>
  <html lang="en">

  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Admin Login</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="css/style.css" <!-- End layout styles -->
    <link rel="shortcut icon" href="../static/pictures/favicon.png" />
  </head>

  <body>
    <div class="container-scroller">
      <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth">
          <div class="row flex-grow">
            <div class="col-lg-4 mx-auto">
              <div class="auth-form-light text-center p-5">

                <h4>Admin Login</h4>
                <h6 class="font-weight-light">Sign in to continue.</h6>

                <form class="pt-3" method="post" action="function.php">
                  <div class="form-group">
                    <input type="text" class="form-control form-control-lg" id="username" name="username"
                      placeholder="Username" Required>
                  </div>
                  <div class="form-group">
                    <input type="password" class="form-control form-control-lg" id="Password" name="password"
                      placeholder="Password" Required>
                  </div>
                  <input type="hidden" name="formname" value="admin_login">
                  <div class="mt-3">
                    <button type="submit" class="btn btn-primary mr-2">Submit</button>
                  </div>

                  <div class="my-2 d-flex justify-content-between align-items-center">

                    <a href="#" class="auth-link text-black">Forgot password?</a>

                  </div>

                  <div class="my-2 d-flex justify-content-between align-items-center">

                    <?php
                    // Your PHP code here
                    if (isset($_GET["error"]) && $_GET["error"] != "") {
                      echo '<b class="auth-link text-black">' . urldecode($_GET["error"]) . '</b>';
                    }
                    ?>
                  </div>


                </form>
              </div>
            </div>
          </div>
        </div>
        <!-- content-wrapper ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>

  </body>

  </html>

  <?php
  // Your PHP code here
} else {
  header("Location:/E-cab/admin");

}
?>