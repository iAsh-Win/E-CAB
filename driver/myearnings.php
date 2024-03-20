<?php
include ("path.php");

// Your PHP code here
session_start();

if (
  isset ($_SESSION["driver_login"]) && $_SESSION["driver_login"] == true &&
  isset ($_SESSION["driveremail"]) && $_SESSION["driveremail"] != ""
) {
  include inc . 'db.php';
  $driver = $_SESSION["driveremail"];
  $searchsql = "SELECT * FROM driver WHERE email='$driver'";

  // Execute the SQL query (you may want to add error handling here)
  $result = mysqli_query($conn, $searchsql);

  // Fetch the data
  if ($row = mysqli_fetch_assoc($result)) {
    $name = $row["firstname"] . " " . $row["lastname"];
    $rides = "SELECT * FROM ride WHERE selectedCab='" . $row['cateid'] . "' ORDER BY book_time ASC";
    if ($result2 = mysqli_query($conn, $rides)) {

      // echo '<pre>';
      // print_r($rows);
      // echo '</pre>';

      ?>

      <!DOCTYPE html>
      <html lang="en">

      <head>
        <title>My  Earnings</title>
        <?php
        include ("partials/_links.php");

        ?>
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <link rel="shortcut icon" href="../static/pictures/favicon.png" />

      </head>

      <body>

        <!-- <body> -->
        <!-- --------------------------------------------------------------------------- -->
        <script>
          let intervalId;
          let lastSavedLocation = null;

          function intvl() {
            setInterval(updateLocation, 20000);
          }
          let i = 1;


          // Call the intvl function to start the interval
          intvl();



          function stopLocationUpdates() {
            clearInterval(intervalId);
          }
          function updateLocation() {
            console.log(i);
            i++
            if ("geolocation" in navigator) {
              navigator.geolocation.getCurrentPosition(
                // Success callback
                function (position) {
                  const driverId = <?php echo $row['driverid']; ?>;
                  const latitude = position.coords.latitude;
                  const longitude = position.coords.longitude;

                  // Check if the location has changed
                  if (
                    lastSavedLocation &&
                    latitude === lastSavedLocation.latitude &&
                    longitude === lastSavedLocation.longitude
                  ) {
                    console.log('Location has not changed.');
                    return;
                  }

                  const formData = new FormData();
                  formData.append('driverId', driverId);
                  formData.append('latitude', latitude);
                  formData.append('longitude', longitude);

                  // Make an AJAX request to update the location
                  fetch('update-location.php', {
                    method: 'POST',
                    body: formData
                  })
                    .then(response => {
                      if (!response.ok) {
                        throw new Error('Network response was not ok');
                      }
                      return response.text();
                    })
                    .then(data => {
                      console.log('Location updated successfully:', data);
                      // Update the lastSavedLocation with the new location
                      lastSavedLocation = { latitude, longitude };
                    })
                    .catch(error => {
                      console.error('Error updating location:', error);
                    });
                },
                function (error) {
                  console.error("Error getting location:", error.message);
                }
              );
            } else {
              console.error("Geolocation is not supported by this browser.");
            }
          }
        </script>

        <!-- --------------------------------------------------------------------------- -->
        <div class="container-scroller">

          <!-- partial:partials/_sidebar.html -->
          <?php
          include ("partials/_sidebar.php");

          ?>
          <!-- partial -->
          <div class="container-fluid page-body-wrapper">
            <!-- partial:partials/_navbar.html -->
            <?php
            // Your PHP code here
            include ("partials/_navbar.php");
            ?>
            <?php
            $totalearning = 0;
            $totalbookings = 0;
            $driverid = $row['driverid'];
            $checkAccepted = "SELECT * FROM driverrequest WHERE driverID = $driverid AND status = 'Complete'";
            if ($d1 = $conn->query($checkAccepted)) {
              while ($check = mysqli_fetch_assoc($d1)) {
                $bookingId = $check['bookid'];
                $bookingQuery = "SELECT * FROM bookings WHERE bookid = $bookingId";
                if ($booking = $conn->query($bookingQuery)) {
                  $totalbookings++; // Increment total bookings count
                  $bookdata = mysqli_fetch_assoc($booking);
                  $rid = $bookdata['rid'];
                  $ridesQuery = "SELECT * FROM ride WHERE rid = '$rid'";
                  if ($result2 = $conn->query($ridesQuery)) {
                    while ($ride = mysqli_fetch_assoc($result2)) {
                      $totalearning += $ride['fare']; // Accumulate the total fare
                    }
                  }
                }
              }
            }
            ?>






            <!-- partial -->
            <div class="main-panel">
              <div class="content-wrapper">
                <div class="page-header">
                  <h3 class="page-title">My  Earnings</h3>
                  <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item"><a href="index">Deshboard</a></li>
                      <li class="breadcrumb-item active" aria-current="page">My Earnings</li>
                    </ol>
                  </nav>
                </div>

                <div class="row justify-content-center">
                  <div class="col-sm-4 grid-margin">
                    <div class="card">
                      <div class="card-body p-5">
                        <h5>Total Earnings</h5>
                        <div class="row center">
                          <div class="col-8 col-sm-12 col-xl-8 my-auto">
                            <div class="d-flex d-sm-block d-md-flex align-items-center">
                              <h2 class="mb-1">₹
                                <?php
                                echo $totalearning;
                                ?>
                              </h2>
                              <!-- <p class="text-success ms-2 mb-0 font-weight-medium">+3.5%</p> -->
                            </div>
                            <h6 class="text-muted font-weight-normal">You have completed over
                              <?php
                              echo $totalbookings;
                              ?> Bookings
                            </h6>
                          </div>
                          <div class="col-4 col-sm-12 col-xl-4 text-center text-xl-right">
                            <i class="icon-lg mdi mdi-codepen text-primary ms-auto"></i>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>


              </div>
              <!-- content-wrapper ends -->



              <!-- partial:partials/_footer.html -->
              <?php
              include ("partials/_footer.php");

              ?>
              <!-- partial -->
            </div>
            <!-- main-panel ends -->
          </div>
          <!-- page-body-wrapper ends -->
        </div>

        <?php
        include ("partials/_scripts.php"); ?>

      </body>

      </html>
      <?php
    }
  } else {
    header("location:" . BASE_URL . "login");
  }
} else {
  header("location:" . BASE_URL . "login");
}
exit;
?>