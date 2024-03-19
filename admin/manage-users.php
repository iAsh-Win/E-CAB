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
    <title>Manage Driver</title>
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
              <h3 class="page-title">Manage Users</h3>
              <!-- <form class="search-form d-none d-md-block" action="#">
                                <i class="icon-magnifier"></i>
                                <input type="search" class="form-control" placeholder="Search Here" title="Search here">
                            </form> -->
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="<?php echo BASE_URL ?>">Deshboard</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Manage Users</li>
                </ol>
              </nav>
            </div>
            <div class="row">

              <!-- content-wrapper ends -->
              <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">

                    <div class="table-responsive">
                      <table class="table table-dark table-hover card-body">
                        <thead>
                          <tr>
                            <th>Sr no</th>
                            <th>User Photo</th>
                            <th>User Name</th>
                            <th>User Email</th>
                            <th>User Mobile</th>
                            <th>Actions</th>
                          </tr>
                        </thead>

                        <tbody>
                          <?php
                          include DB;
                          $searchsql = "SELECT * FROM users";
                          $i = 1;
                          $result = mysqli_query($conn, $searchsql);

                          while ($row = mysqli_fetch_assoc($result)) {

                            // Check if mobile number is null, replace with hyphen ("-")
                            $mobileNo = ($row["mobileNo"] !== null) ? $row["mobileNo"] : '-';

                            echo '<tr>
                                    <td class="p-3">' . $i . '</td>
                                    <td class="py-1 p-3"><img src="/E-cab/' . trim($row['userImage']) . '" alt="image" /></td>
                                    <td class="p-3">' . $row["name"] . '</td>
                                    <td class="p-3">' . $row["emailID"] . '</td>
                                    <td class="p-3">' . $mobileNo . '</td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-rounded btn-sm m-1" onclick="deleteuser(\'' . $row["id"] . '\')">Delete</button>
                                    </td>
                                </tr>';

                            $i++;
                          }
                          ?>
                        </tbody>
                      </table>
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
                    success: function(data) {

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
            success: function() {
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
            success: function() {
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
            success: function() {
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