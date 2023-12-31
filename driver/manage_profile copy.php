<?php
include("path.php");

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
    $name = $row["firstname"] . " " . $row["lastname"];
    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
      <?php
      include("partials/_links.php");

      ?>
      <title>Manage Profile</title>
    </head>

    <body>
      <div class="container-scroller">

        <!-- partial:partials/_sidebar.html -->
        <?php
        include("partials/_sidebar.php");

        ?>
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
          <!-- partial:partials/_navbar.html -->
          <?php
          // Your PHP code here
          include("partials/_navbar.php");
          ?>



          <!-- partial -->
          <div class="main-panel">
            <div class="content-wrapper">
              <div class="page-header">
                <h3 class="page-title">Manage Profile</h3>
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>">Deshboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Manage Profile</li>
                  </ol>
                </nav>
              </div>
              <div class="col-12 grid-margin">
                <div class="card">
                  <div class="image-container align-self-center">
                    <img class="om" src=<?php echo $row["photo"]; ?> alt="Profile Picture">
                  </div>
                  <div class="card-body">

                    <h4 class="card-title">Horizontal Two column</h4>
                    <form class="form-sample" action="" method="post">
                      <!-- enctype="multipart/form-data" -->
                      <p class="card-description"> Driver info </p>
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group row">
                            <label class="col-sm-3 col-form-label">First Name</label>
                            <div class="col-sm-9">
                              <input type="text" name="firstname" class="form-control text-white" required />
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Last Name</label>
                            <div class="col-sm-9">
                              <input type="text" name="lastname" class="form-control text-white" required />
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
                              <input type="text" name="phone" class="form-control text-white" required />
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
                              <input type="text" name="carno" class="form-control text-white" required />
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Driving Licence No</label>
                            <div class="col-sm-9">
                              <input type="text" name="licence" class="form-control text-white" required />
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
                              <input type="text" name="city" class="form-control text-white" />
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
                              <input type="text" name="pin" class="form-control text-white" required />
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Profile Picture</label>
                            <div class="input-group-sm col-sm-9">
                              <input type="file" name="img" id="profilePicture" class="file-upload-default">
                              <input type="text" class="form-control file-upload-info mb-2" disabled
                                placeholder="Upload Image">

                              <span class="input-group-append">
                                <button class="file-upload-browse btn btn-primary mb-4" type="button">Upload</button>
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
                              <input type="password" name="password" class="form-control text-white" required />
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Verify your Password</label>
                            <div class="col-sm-9">
                              <input type="password" class="form-control text-white" required />
                              <input type="hidden" name="Manageprofile" value="driverprofile">
                            </div>
                          </div>
                        </div>
                      </div>
                      <button type="submit" class="btn btn-primary mb-2">Submit</button>
                    </form>
                  </div>
                </div>
              </div>
            </div>
            <!-- content-wrapper ends -->



            <!-- partial:partials/_footer.html -->
            <?php
            include("partials/_footer.php");

            ?>
            <!-- partial -->
          </div>
          <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
      </div>

      <?php
      include("partials/_scripts.php"); ?>

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