<?php
// Your PHP code here
session_start();
if (isset ($_SESSION["adminlogin"]) && $_SESSION["adminlogin"] == true && isset ($_SESSION["adminusername"])) {
  if (isset ($_GET["feedID"])) {

    $driverid = $_GET["feedID"];


    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
      <!--  meta tags -->
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <title>View Feedback</title>
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

          $searchsql = "SELECT * FROM feedback where feedID=$driverid";

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
                    <li class="breadcrumb-item"><a href="manage-feedback">Manage Feedback</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                      <?php
                      echo $row["feedID"];
                      ?>
                    </li>
                  </ol>
                </nav>
              </div>
              <div class="card-container">


                <div class="card mb-3">
                  <div class="card-body">
                    <h5 class="card-title">Feed ID:
                      <?php echo $row["feedID"]; ?>
                    </h5>
                   <hr>
                    <p><?php echo $row["feedback"]; ?></p>


                  </div>
                </div>


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