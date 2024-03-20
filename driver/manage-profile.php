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
    $er = "";
    if (isset($_GET["error"]) && $_GET["error"] != "") {
      $er = urldecode($_GET["error"]);

    }
    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
      <?php
      include("partials/_links.php");

      ?>
         <link rel="shortcut icon" href="../static/pictures/favicon.png" />
      <title>Manage Profile</title>
    </head>
    <style>
      h6 {
        color: red;
      }

      /* Custom CSS for the dark background input */
      input[type="email"].dark-background,
      input[type="text"].dark-background {
        background-color: #212529;
        /* Set your desired dark background color here */
        color: #fff;
        /* Set the text color for better visibility on dark background */
        /* Add any additional styles you want for the disabled state */
      }
    </style>

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
                <h3 class="page-title">

                </h3>
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


                    <form class="form-sample" action=function.php method="post" enctype="multipart/form-data">
                      <p class="card-description"> Driver info </p>
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group row">
                            <label class="col-sm-3 col-form-label">First Name</label>
                            <div class="col-sm-9">
                              <input type="text" value="<?php echo $row["firstname"] ?>" name="firstname"
                                class="form-control text-white" required />
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Last Name</label>
                            <div class="col-sm-9">
                              <input type="text" value="<?php echo $row["lastname"]; ?>" name="lastname"
                                class="form-control text-white" required />
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
                              <input type="email" value="<?php echo $row["email"]; ?>" name="email"
                                class="form-control text-light dark-background" disabled />

                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Phone</label>
                            <div class="col-sm-9">
                              <input type="text" value="<?php echo $row["phone"]; ?>" name="phone"
                                class="form-control text-white" required />
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
                              <input type="text" value="<?php echo $row["carno"]; ?>" name="carno"
                                class="form-control text-white" required />
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Driving Licence No</label>
                            <div class="col-sm-9">
                              <input type="text" value="<?php echo $row["licence"]; ?>" name="licence"
                                class="form-control text-light dark-background" disabled />
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
                              <input type="text" value="<?php echo $row["city"]; ?>" name="city"
                                class="form-control text-white" />
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group row">
                            <label class="col-sm-3 col-form-label">State</label>
                            <div class="col-sm-9">
                              <input type="text" value="<?php echo $row["state"]; ?>" name="state"
                                class="form-control text-white" required />
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Pincode</label>
                            <div class="col-sm-9">
                              <input type="text" value="<?php echo $row["pin"]; ?>" name="pin"
                                class="form-control text-white" required />
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Profile Picture</label>
                            <div class="input-group-sm col-sm-9">
                              <div class="input-group">
                                <div class="custom-file">
                                  <input type="file" name="img" id="profilePicture" class="custom-file-input">

                                </div>

                              </div>
                            </div>

                            <span class=p-3>
                              <h6 class=".text-danger">
                                <?php if ($er) {
                                  echo '* ' . $er . '';
                                } ?>
                              </h6>

                            </span>
                          </div>
                        </div>

                      </div>
                      <input type="hidden" name="Manageprofile" value="driverprofile">
                      <button type="submit" class="btn btn-primary mb-2">Submit</button>
                    </form>
                    <p>Last Updated:&nbsp;<?php echo $row["date_update"]; ?></p>
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
      <script>
        $(document).ready(function () {
          $('.file-upload-browse').on('click', function () {
            var fileInput = $(this).parents('.input-group').find('.custom-file-input');
            fileInput.trigger('click');
          });

          $('.custom-file-input').on('change', function () {
            var fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').html(fileName);
          });
        });



      </script>
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