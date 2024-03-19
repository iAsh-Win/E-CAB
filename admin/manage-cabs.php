<?php
// Your PHP code here
session_start();
if (isset ($_SESSION["adminlogin"]) && $_SESSION["adminlogin"] == true && isset ($_SESSION["adminusername"])) {

  ?>

  <!DOCTYPE html>
  <html lang="en">

  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Manage Cabs</title>
    <?php
    // Your PHP code here
    include ("partials/_links.php");
    ?>
    <style>
      .error {
        color: red;
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
        ?>
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="page-header">
              <h3 class="page-title">Manage Cabs</h3>
              <!-- <form class="search-form d-none d-md-block" action="#">
                                <i class="icon-magnifier"></i>
                                <input type="search" class="form-control" placeholder="Search Here" title="Search here">
                            </form> -->
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="<?php echo BASE_URL ?>">Deshboard</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Manage Cabs</li>
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
                            <th>Cab Cate</th>
                            <th>BaseFare</th>
                            <th>Date Update</th>
                            <th>Actions</th>
                          </tr>
                        </thead>

                        <tbody>
                          <?php
                          include DB;
                          $searchsql = "SELECT * FROM cabcate";
                          $i = 1;
                          $result = mysqli_query($conn, $searchsql);


                          while ($row = mysqli_fetch_assoc($result)) {
                            echo '<tr>
                                    <td>' . $i . '</td>
                                    
                                    <td>' . $row["catename"] . '</a></td>
                                    <td>₹ ' . $row["basefare"] . '</td>
                                    <td>' . $row["date-update"] . '</td>
                                    <td>
                                   <button type="button" class="btn btn-danger btn-rounded btn-sm m-1" onclick="deletecab(\'' . $row["cateid"] . '\')">Delete</button></td>
                                    </tr>';
                            $i++;
                          }
                          ?>

                        </tbody>

                      </table>
                    </div>
                    <!-- <div class="d-flex mt-4 flex-wrap">
                      <input type="hidden" name="cateupdate" value="yes">
                      <button type="submit" class="btn btn-info btn-rounded  btn-sm" onclick="">Add Cab Category</button>
                    </div> -->



                    <div class=" mt-5col-12 grid-margin stretch-card">
                      <div class="card">
                        <div class="card-body">
                          <h4 class="card-title">Update Base Fare</h4>

                          <form class="form-inline " action=function.php method="post">
                            <label class="sr-only" for="inlineFormInputName2">Name</label>
                            <select class="form-control text-dark  mb-2 mr-sm-2 " name="cateid">
                              <?php
                              $sql = 'select * from cabcate';
                              $result = mysqli_query($conn, $sql);
                              while ($row1 = mysqli_fetch_array($result)) {
                                echo '<option value="' . $row1['cateid'] . '">' . $row1['catename'] . '</option>';

                              }
                              ?>
                            </select>
                            <input type="text" class="form-control mb-2 mr-sm-2" name="basefare" id="inlineFormInputName2"
                              placeholder="Enter BaseFare" required>

                            <input type="hidden" name="cateupdate" value="yes">
                            <button type="submit" class=" ml-5 btn btn-info btn-rounded  btn-sm"
                              onclick="">Update</button>
                          </form>
                        </div>
                      </div>
                    </div>

                    <div class=" mt-5col-12 grid-margin stretch-card">
                      <div class="card">
                        <div class="card-body">
                          <h4 class="card-title">Add New Cab Category</h4>


                          <div class="row">
                            <div class="col-md-6">
                              <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Category Name</label>
                                <div class="col-sm-9">
                                  <input type="text" name="catename" id="catename" class="form-control" required />
                                </div>

                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Base Fare</label>
                                <div class="col-sm-9">
                                  <input type="text" name="basefare" id="basefare" class="form-control" required />
                                </div>

                              </div>
                              <span id="errorContainer"></span>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group row">
                              <div class="col-sm-9">
                                <input type="hidden" name="cateadd" value="yes">
                                <button id="add" class=" ml-5 btn btn-primary btn-rounded  btn-sm"
                                  onclick="validateForm()">Add Category</button>


                              </div>
                            </div>
                          </div>

                        </div>



                      </div>
                    </div>
                  </div>


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
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
    </div>
    <?php
    // Your PHP code here
    include ("partials/_scripts.php");
    ?>
    <script>
      function deletecab(cateid) {
        $.ajax({
          type: 'POST',
          url: 'function.php',
          data: {
            cateid: cateid,
            catedelete: 'yes',
            categoryisdelete: true
          },
          dataType: 'json',
          success: function (data) {

          }
        });
        location.reload();

      }



      function validateForm() {
        var catename = document.getElementById("catename").value;
        var basefare = document.getElementById("basefare").value;
        var errorContainer = document.getElementById("errorContainer");
        var isValid = true;

        // Reset previous error messages
        errorContainer.innerHTML = "";

        // Validate Category Name
        if (catename.trim() === "") {
          displayError("Category Name is required.");
          isValid = false;
        }

        // Validate Base Fare
        if (isNaN(basefare) || basefare <= 0) {
          displayError("Base Fare must be a valid positive number.");
          isValid = false;
        }

        // If all validations pass, submit the form
        if (isValid) {
          $.ajax({
            type: 'POST',
            url: 'function.php',
            data: {
              catename: catename,
              basefare: basefare,
              cateadd: 'yes'
            },
            dataType: 'json',  // Specify the expected data type
            success: function (data) {
            }

          });
          location.reload();
        }
      }

      function displayError(message) {
        var errorContainer = document.getElementById("errorContainer");
        var errorMessage = document.createElement("p");
        errorMessage.className = "error";
        errorMessage.textContent = message;
        errorContainer.appendChild(errorMessage);
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