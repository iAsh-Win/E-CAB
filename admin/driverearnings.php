<?php

session_start();
if (isset($_SESSION["adminlogin"]) && $_SESSION["adminlogin"] == true && isset($_SESSION["adminusername"])) {
  include 'partials/db.php';
  if (isset($_GET["driver"])) {

    $driverid = $_GET["driver"];


    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
      <title>Driver Earnings</title>
      <?php
      include ("partials/_links.php");

      ?>
      <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
      <link rel="shortcut icon" href="../static/pictures/favicon.png" />

    </head>

    <body>

      <?php

      $totalearning = 0;
      $totalbookings = 0;

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
          ?>
          <!-- partial -->
          <div class="main-panel">
            <div class="content-wrapper">
              <div class="page-header">
                <h3 class="page-title">Driver Earnings</h3>
                <!-- <form class="search-form d-none d-md-block" action="#">
                                <i class="icon-magnifier"></i>
                                <input type="search" class="form-control" placeholder="Search Here" title="Search here">
                            </form> -->
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo BASE_URL ?>">Deshboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Driver Earnings</li>
                  </ol>
                </nav>
              </div>
              <div class="row">

                <!-- content-wrapper ends -->
                <div class="card-container">

                  <div class="card mb-3">
                    <div class="card-body">
                    <div class="col-8 col-sm-12 col-xl-8 my-auto">
                            <div class="d-flex d-sm-block d-md-flex align-items-center">
                              <h2 class="mb-1">₹
                                <?php
                                echo $totalearning;
                                ?>
                              </h2>
                              <!-- <p class="text-success ms-2 mb-0 font-weight-medium">+3.5%</p> -->
                            </div>
                            <h6 class="text-muted font-weight-normal">This Driver have completed over
                              <?php
                              echo $totalbookings;
                              ?> Bookings
                            </h6>
                          </div>

                      
                    </div>
                  </div>

                </div>

                <script>
                  function deleteuser(id) {
                    $.ajax({
                      type: 'POST',
                      url: 'function.php',
                      data: {
                        uid: id,
                        userdelete: 'yes',
                        userisdelete: true
                      },
                      dataType: 'json',
                      success: function (data) {

                      }
                    });
                    location.reload();
                  }
                </script>

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