<?php
// Your PHP code here
session_start();
if (isset($_SESSION["adminlogin"]) && $_SESSION["adminlogin"] == true && isset($_SESSION["adminusername"])) {

  ?>

  <!DOCTYPE html>
  <html lang="en">

  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Stellar Admin</title>
    <?php
    // Your PHP code here
    include("partials/_links.php");
    ?>
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
        ?>
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="page-header">
              <h3 class="page-title">Manage Drivers</h3>
              <!-- <form class="search-form d-none d-md-block" action="#">
                                <i class="icon-magnifier"></i>
                                <input type="search" class="form-control" placeholder="Search Here" title="Search here">
                            </form> -->
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="<?php echo BASE_URL ?>">Deshboard</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Manage Drivers</li>
                </ol>
              </nav>
            </div>
            <div class="row">

              <!-- content-wrapper ends -->
              <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">

                    <table class="table table-dark table-hover card-body">
                      <thead>
                        <tr>
                          <th>Sr no</th>
                          <th>Driver Photo</th>
                          <th>Driver Name</th>
                          <th>Driver Email</th>
                          <th>Status</th>
                          <th>Change Status</th>
                          <th>Action</th>

                        </tr>
                      </thead>

                      <tbody>
                        <?php
                        include DB;
                        $searchsql = "SELECT * FROM driver";
                        $i = 1;
                        $result = mysqli_query($conn, $searchsql);

                        while ($row = mysqli_fetch_assoc($result)) {

                          echo '<tr>
                                    <td>' . $i . '</td>
                                    <td class="py-1"><img src="' . DriverPhotos . $row["photo"] . '" alt="image" /></td>
                                    <td>' . $row["firstname"] . " " . $row["lastname"] . '</td>
                                    <td><a class="nav-link" href="driver-view.php?driver=' . $row["driverid"] . '">' . $row["email"] . '</a></td>
                                    <td>';


                          echo ($row["active"] == 0) ? '<div class="form-check form-check-danger"><label class="badge badge-success">Active</label></div>' : '<div class="form-check form-check-danger"><label class="badge badge-danger">deActive</label> </div>';

                          echo '</td>
                          <td> <div class="form-check form-check-danger">';
                          echo ($row["active"] == 0) ? '<button type="button" class="btn btn-danger btn-rounded  btn-sm"  onclick="deactivebtn1(\'' . $row["email"] . '\')">deActive</button>' : '<button type="button" class="btn btn-success btn-rounded  btn-sm"  onclick="activebtn1(\'' . $row["email"] . '\')">Active</button>';
                          echo '</div>
                                    </td>';

                          echo ' <td>
                               <div class="form-check form-check-danger">
                                   <button type="button" class="btn btn-danger btn-rounded btn-sm m-1" onclick="deletebtn(\'' . $row["email"] . '\')">Delete</button></div></td></tr>';


                          $i++;
                        }
                        ?>
                      </tbody>
                    </table>
                    <div class="d-flex mt-4 flex-wrap">
                      <p class="text-muted">Showing 1 to 10 of 57 entries</p>
                      <nav class="ml-auto">
                        <ul class="pagination separated pagination-info">
                          <li class="page-item"><a href="#" class="page-link"><i class="icon-arrow-left"></i></a></li>
                          <li class="page-item active"><a href="#" class="page-link">1</a></li>
                          <li class="page-item"><a href="#" class="page-link">2</a></li>
                          <li class="page-item"><a href="#" class="page-link">3</a></li>
                          <li class="page-item"><a href="#" class="page-link">4</a></li>
                          <li class="page-item"><a href="#" class="page-link"><i class="icon-arrow-right"></i></a></li>
                        </ul>
                      </nav>
                    </div>
                  </div>
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
      <script>
        function deletebtn(eml) {
          postData = {
            key1: "delete",
            key2: eml
          };
          $.ajax({
            type: "POST", // Use POST method
            url: "function.php", // Replace with your server-side endpoint
            data: postData, // Data to be sent in the request body
            success: function () {
              location.reload();
            },
          });
        }

        function deactivebtn1(eml) {
          postData = {
            key1: "deactive",
            key2: eml
          };
          $.ajax({
            type: "POST", // Use POST method
            url: "function.php", // Replace with your server-side endpoint
            data: postData, // Data to be sent in the request body
            success: function () {
              location.reload();
            },
          });
        }
        function activebtn1(eml) {
          postData = {
            key1: "active",
            key2: eml
          };
          $.ajax({
            type: "POST", // Use POST method
            url: "function.php", // Replace with your server-side endpoint
            data: postData, // Data to be sent in the request body
            success: function () {
              location.reload();
            },
          });
        }
      </script>
  </body>

  </html>


  <?php
  // Your PHP code here
} else {
  header("Location:/E-cab/admin/login.php");

}
?>