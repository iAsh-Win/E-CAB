<?php

// Your PHP code here
session_start();
if (isset ($_SESSION["adminlogin"]) && $_SESSION["adminlogin"] == true && isset ($_SESSION["adminusername"])) {
  $totalbookings = 0;
  $totaldrivers = 0;
  $totalearnings = 0;
  $totalusers = 0;

  $totalLeads = 0;
  $cashTransactions = 0;
  $onlineTransactions = 1000;

  include 'partials/db.php'; // Make sure the path to your database connection file is correct

  // Count total bookings
  $searchsql = "SELECT COUNT(*) AS total FROM bookings";
  $result = mysqli_query($conn, $searchsql);
  $row = mysqli_fetch_assoc($result);
  $totalbookings = $row['total'];

  // Calculate total earnings
  $searchsql = "SELECT SUM(r.fare) AS totalearnings FROM bookings b JOIN ride r ON b.rid = r.rid";
  $result = mysqli_query($conn, $searchsql);
  $row = mysqli_fetch_assoc($result);
  $totalearnings = $row['totalearnings'];

  // Count total drivers
  $searchsql = "SELECT COUNT(*) AS total FROM driver";
  $result = mysqli_query($conn, $searchsql);
  $row = mysqli_fetch_assoc($result);
  $totaldrivers = $row['total'];

  // Count total users
  $searchsql = "SELECT COUNT(*) AS total FROM users";
  $result = mysqli_query($conn, $searchsql);
  $row = mysqli_fetch_assoc($result);
  $totalusers = $row['total'];

  // Close database connection
  mysqli_close($conn);


  ?>
  <!DOCTYPE html>
  <html lang="en">

  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <title>ADMIN DESHBOARD</title>
    <?php
    // Your PHP code here
    include ("partials/_links.php");
    ?>
  </head>

  <body>
    <div class="container-scroller">
      <!-- partial:partials/_navbar.html -->
      <?php
      // Your PHP code here
      include ("partials/_navbar.php");
      ?>
      <!-- partial -->
      <div class="container-fluid page-body-wrapper">
        <!-- partial:partials/_sidebar.html -->
        <?php
        // Your PHP code here
        include ("partials/_sidebar.php");
        ?>
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="row">
              <div class="col-md-12 grid-margin">
                <div class="card">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-md-12">
                        <div class="d-sm-flex align-items-baseline report-summary-header">
                          <h5 class="font-weight-semibold">STATICS</h5>
                        </div>
                      </div>
                    </div>
                    <div class="row report-inner-cards-wrapper">
                      <div class=" col-md -6 col-xl report-inner-card">
                        <div class="inner-card-text">
                          <span class="report-title">TOTAL BOOKINGS</span>
                          <h4>
                            <?php
                            echo $totalbookings;
                            ?>
                          </h4>

                        </div>
                        <div class="inner-card-icon bg-success">
                          <i class="icon-rocket"></i>
                        </div>
                      </div>
                      <div class="col-md-6 col-xl report-inner-card">
                        <div class="inner-card-text">
                          <span class="report-title">CUSTOMERS</span>
                          <h4>
                            <?php
                            echo $totalusers;
                            ?>
                          </h4>

                        </div>
                        <div class="inner-card-icon bg-danger">
                          <i class="icon-user"></i>
                        </div>
                      </div>
                      <div class="col-md-6 col-xl report-inner-card">
                        <div class="inner-card-text">
                          <span class="report-title">DRIVERS</span>
                          <h4>
                            <?php
                            echo $totaldrivers;
                            ?>
                          </h4>

                        </div>
                        <div class="inner-card-icon bg-warning">
                          <i class="icon-globe-alt"></i>
                        </div>
                      </div>
                      <div class="col-md-6 col-xl report-inner-card">
                        <div class="inner-card-text">
                          <span class="report-title">TOTAL EARNING</span>
                          <h4>₹
                            <?php
                            echo $totalearnings;
                            ?>
                          </h4>

                        </div>
                        <div class="inner-card-icon bg-primary">
                          <i class="icon-diamond"></i>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row quick-action-toolbar">
              <div class="col-md-12 grid-margin">
                <div class="card">
                  <div class="card-header d-block d-md-flex">
                    <h5 class="mb-0">Quick Actions</h5>
                    <p class="ml-auto mb-0">How are your active users trending overtime?<i class="icon-bulb"></i></p>
                  </div>
                  <div class="d-md-flex row m-0 quick-action-btns" role="group" aria-label="Quick action buttons">
                    <div class="col-sm-6 col-md-3 p-3 text-center btn-wrapper">
                      <button type="button" onclick="location.href='manage-users'" class="btn px-0"> <i
                          class="icon-user mr-2"></i> Manage Users</button>
                    </div>
                    <div class="col-sm-6 col-md-3 p-3 text-center btn-wrapper">
                      <button type="button" onclick="location.href='manage-driver'" class="btn px-0"><i
                          class="icon-docs mr-2"></i> Manage Drivers</button>
                    </div>
                    <div class="col-sm-6 col-md-3 p-3 text-center btn-wrapper">
                      <button type="button" onclick="location.href='manage-bookings'" class="btn px-0"><i
                          class="icon-folder mr-2"></i> Manage Bookings</button>
                    </div>
                    <div class="col-sm-6 col-md-3 p-3 text-center btn-wrapper">
                      <button type="button" onclick="location.href='manage-cabs'" class="btn px-0"><i
                          class="icon-book-open mr-2"></i>Manage Cabs</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>


          </div>
          <!-- content-wrapper ends -->

          <!-- partial:partials/_footer.html -->
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
  
    include inc . '_scripts.php';

    ?>

  </html>

  <?php
  // Your PHP code here
} else {
  header("Location:/E-cab/admin/login");

}
?>