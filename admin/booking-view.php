<?php
// Your PHP code here
session_start();
if (isset ($_SESSION["adminlogin"]) && $_SESSION["adminlogin"] == true && isset ($_SESSION["adminusername"])) {
  if (isset ($_GET["bookid"])) {

    $driverid = $_GET["bookid"];


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
      include ("partials/_links.php");
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
        include ("partials/_navbar.php");
        ?>
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
          <!-- partial:../../partials/_sidebar.html -->
          <?php
          // Your PHP code here
          include ("partials/_sidebar.php");
          include DB;

          $searchsql = "SELECT * FROM bookings where bookid=$driverid";

          $result = mysqli_query($conn, $searchsql);
          $row = mysqli_fetch_assoc($result);

          ?>
          <!-- partial -->
          <div class="main-panel">
            <div class="content-wrapper">
              <div class="page-header">
                <h3 class="page-title">

                </h3>
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="manage-bookings">Manage Bookings</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                      <?php
                      echo $row["bookid"];
                      ?>
                    </li>
                  </ol>
                </nav>
              </div>
              <div class="card-container">
                <?php
                include DB;
                $rideid = $row['rid'];
                if ($row['status'] != "Cancelled" && $row['reqID'] != NULL) {
                  $reqID = $row['reqID'];
                  $req = "SELECT * FROM `driverrequest` WHERE reqID=$reqID";
                  $driverrequest = mysqli_query($conn, $req);
                  $requested = mysqli_fetch_assoc($driverrequest);
                  if ($requested) {
                    $driverid = $requested['driverID'];
                    $searchsql4 = "SELECT * FROM driver WHERE driverid=$driverid";
                    $drive = mysqli_query($conn, $searchsql4);
                    $driver = mysqli_fetch_assoc($drive);
                  } else {
                    $driver = ['firstname' => ''];
                  }
                } else {
                  $driver = ['firstname' => '-'];
                  $requested = ['status' => '-'];
                }

                $searchsql = "SELECT * FROM ride WHERE rid=$rideid";
                $result = mysqli_query($conn, $searchsql);

                while ($row = mysqli_fetch_assoc($result)) {
                  $uid = $row['uid'];
                  $cid = $row['selectedCab'];
                  $searchsql2 = "SELECT * FROM users WHERE id=$uid";
                  $user = mysqli_query($conn, $searchsql2);
                  $users = mysqli_fetch_assoc($user);

                  $searchsql3 = "SELECT * FROM cabcate WHERE cateid=$cid";
                  $cab = mysqli_query($conn, $searchsql3);
                  $cabs = mysqli_fetch_assoc($cab);
                  ?>

                  <div class="card mb-3">
                    <div class="card-body">
                      <h5 class="card-title">Book ID:
                        <?php echo $row["rid"]; ?>
                      </h5>
                      <p class="card-text"><strong>Username:</strong>
                        <?php echo $users["name"]; ?>
                      </p>
                      <p class="card-text"><strong>Booking Time:</strong>
                        <?php echo $row["booking_time"]; ?>
                      </p>
                      <p class="card-text"><strong>Source:</strong>
                        <?php echo $row["source"]; ?>
                      </p>
                      <p class="card-text"><strong>Destination:</strong>
                        <?php echo $row["destination"]; ?> 
                      </p>
                      <p class="card-text"><strong>Distance:</strong>
                        <?php echo $row["distance"]; ?> KM
                      </p>
                      <p class="card-text"><strong>Fare:</strong>
                        ₹<?php echo $row["fare"]; ?>
                      </p>
                      <p class="card-text"><strong>Selected Cab:</strong>
                        <?php echo $cabs["catename"]; ?>
                      </p>
                      <p class="card-text"><strong>Driver Status:</strong>
                        <?php echo $requested["status"]; ?>
                      </p>
                      <p class="card-text"><strong>Assigned Driver:</strong>
                        <?php echo $driver["firstname"]; ?>
                      </p>
                      
                    </div>
                  </div>

                  <?php
                }
                ?>
              </div>
            </div>



            <!-- content-wrapper ends -->
            <!-- partial:../../partials/_footer.html -->
            <?php
            // Your PHP code here
            include ("partials/_footer.php");
            ?>
            <!-- partial -->
          </div>
          <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
      </div>
      <?php
      // Your PHP code here
      include ("partials/_scripts.php");
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