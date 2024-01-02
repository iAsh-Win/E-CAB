<?php
// Your PHP code here
session_start();
if (isset($_SESSION["adminlogin"]) && $_SESSION["adminlogin"] == true && isset($_SESSION["adminusername"])) {
  if (isset($_GET["driver"])) {

    $driverid = $_GET["driver"];


    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
      <!--  meta tags -->
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <title>View Driver</title>
      <?php
      // Your PHP code here
      include("partials/_links.php");
      ?>
      <style>
        .image-container {
          width: 25vh;
          /* Set the desired width */
          height: 25vh;
          /* Set the desired height */
          overflow: hidden;
          /* Hide any overflow if the image is larger */
          border-radius: 50%;
          /* Create a round boundary */
          margin-top: 18px;
        }

        .image-container img {
          width: 100%;
          /* Make the image fill the container */
          height: 100%;
          /* Make the image fill the container */
          object-fit: fill;
          /* Ensure the image covers the container while maintaining aspect ratio */
          display: block;
          /* Remove extra space beneath the image */
          border-radius: 50%;
        }
      </style>
    </head>

    <body>
      <div class="container-scroller">
        <!-- partial:../../partials/_navbar.html -->
        <?php
        // Your PHP code here
        include("partials/_navbar.php");
        ?>
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
          <!-- partial:../../partials/_sidebar.html -->
          <?php
          // Your PHP code here
          include("partials/_sidebar.php");
          include DB;

          $searchsql = "SELECT * FROM driver where driverid=$driverid";

          $result = mysqli_query($conn, $searchsql);
          $row = mysqli_fetch_assoc($result);

          ?>
          <!-- partial -->
          <div class="main-panel">
            <div class="content-wrapper">
              <div class="page-header">
                <h3 class="page-title">
                  <?php
                  echo $row["firstname"] . " " . $row["lastname"];
                  echo ($row["active"] == 0) ? '<div class="form-check form-check-danger"><label class="badge badge-success">Active</label></div>' : '<div class="form-check form-check-danger"><label class="badge badge-danger">deActive</label> </div>';
                  ?>
                </h3>
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="manage-driver">Manage Driver</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                      <?php
                      echo $row["firstname"] . " " . $row["lastname"];
                      ?>
                    </li>
                  </ol>
                </nav>
              </div>
            </div>


            <div class="col-12 grid-margin">
              <div class="card">
                <div class="image-container align-self-center">
                  <img class="om" src=<?php echo DriverPhotos . $row["photo"]; ?> alt="Profile Picture">

                </div>


                <div class="card-body">


                  <form class="form-sample" action=function.php method="post">
                    <p class="card-description"> Driver info </p>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">First Name</label>
                          <div class="col-sm-9">
                            <input type="text" value="<?php echo $row["firstname"] ?>" name="firstname"
                              class="form-control " />

                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Last Name</label>
                          <div class="col-sm-9">
                            <input type="text" value="<?php echo $row["lastname"]; ?>" name="lastname"
                              class="form-control " />
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Gender</label>
                          <div class="col-sm-9">
                            <select class="form-control " name="gender">
                              <?php
                              if ($row["gender"] == "Male") {
                                echo "<option selected >Male</option>
                                    <option>Female</option>";
                              } else {
                                echo "<option>Male</option>
                                    <option selected>Female</option>";
                              }
                              ?>

                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Email</label>
                          <div class="col-sm-9">
                            <input type="email" value="<?php echo $row["email"]; ?>" name="email" class="form-control" />

                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Phone</label>
                          <div class="col-sm-9">
                            <input type="text" value="<?php echo $row["phone"]; ?>" name="phone" class="form-control " />
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group row">

                          <label class="col-sm-3 col-form-label">Select your Car Category</label>
                          <div class="col-sm-9">
                            <select class="form-control " name="cabcate">
                              <?php

                              include DB;
                              $sql = 'select * from cabcate';
                              $result = mysqli_query($conn, $sql);
                              while ($row1 = mysqli_fetch_array($result)) {
                                if ($row["cateid"] == $row1["cateid"]) {
                                  echo '<option value="' . $row1['cateid'] . '" selected >' . $row1['catename'] . '</option>';
                                } else {
                                  echo '<option value="' . $row1['cateid'] . '">' . $row1['catename'] . '</option>';
                                }
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
                            <input type="text" value="<?php echo $row["carno"]; ?>" name="carno" class="form-control " />
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Driving Licence No</label>
                          <div class="col-sm-9">
                            <input type="text" value="<?php echo $row["licence"]; ?>" name="licence" class="form-control" />
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
                            <input type="text" value="<?php echo $row["city"]; ?>" name="city" class="form-control " />
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">State</label>
                          <div class="col-sm-9">
                            <input type="text" value="<?php echo $row["state"]; ?>" name="state" class="form-control " />
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Pincode</label>
                          <div class="col-sm-9">
                            <input type="text" value="<?php echo $row["pin"]; ?>" name="pin" class="form-control " />
                          </div>
                        </div>
                      </div>


                    </div>
                    <input type="hidden" name="Manageprofiledriver" value="yes">
                    <input type="hidden" name="driverid" value="<?php echo $row["driverid"]; ?>">
                    <button type="submit" class="btn btn-primary mb-2">Submit</button>
                  </form>
                  <p>Last Updated:&nbsp;
                    <?php echo $row["date_update"]; ?>
                  </p>
                </div>
              </div>
            </div>
            <!-- content-wrapper ends -->
            <!-- partial:../../partials/_footer.html -->
            <?php
            // Your PHP code here
            include("partials/_footer.php");
            ?>
            <!-- partial -->
          </div>
          <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
      </div>
      <?php
      // Your PHP code here
      include("partials/_scripts.php");
      ?>
    </body>

    </html>


    <?php
    // Your PHP code here
  }
} else {
  header("Location:/E-cab/admin/login.php");

}
?>