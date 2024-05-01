<?php
include ("path.php");

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
    $rides = "SELECT * FROM ride WHERE selectedCab='" . $row['cateid'] . "' ORDER BY book_time ASC";
    if ($result2 = mysqli_query($conn, $rides)) {

      // echo '<pre>';
      // print_r($rows);
      // echo '</pre>';

      ?>

      <!DOCTYPE html>
      <html lang="en">

      <head>
        <title>My Payments</title>
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
                      lastSavedLocation = {
                        latitude,
                        longitude
                      };
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







            <!-- partial -->
            <div class="main-panel">
              <div class="content-wrapper">
                <div class="page-header">
                  <h3 class="page-title">My Payments</h3>
                  <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item"><a href="index">Deshboard</a></li>
                      <li class="breadcrumb-item active" aria-current="page">My Payments</li>
                    </ol>
                  </nav>
                </div>

                <div class="row justify-content-center">
                  <div class="col-sm-4 grid-margin">
                    <div class="card">
                      <div class="card-body">
                        <h4 class="card-title">Payments Details</h4>
                        <p class="card-description">Enter Bank Details for payment</p>
                        <form class="forms-sample" id="paymentForm" action="mypayments" method="POST">
                          <div class="form-group">
                            <label for="name">Name as per Bank Passbook</label>
                            <input type="text" style="color: white;" class="form-control" id="name" placeholder="Name">
                          </div>
                          <div class="form-group">
                            <label for="accountNumber">Bank name</label>
                            <input type="text" style="color: white;" class="form-control" id="bank" placeholder="Bank name">
                          </div>
                          <div class="form-group">
                            <label for="accountNumber">Account Number</label>
                            <input type="text" style="color: white;" class="form-control" id="accountNumber"
                              placeholder="Account Number">
                          </div>
                          <div class="form-group">
                            <label for="ifscCode">IFSC Code</label>
                            <input type="text" style="color: white;" class="form-control" id="ifscCode"
                              placeholder="IFSC Code">
                          </div>
                          <div class="form-group">
                            <label for="branch">Branch</label>
                            <input type="text" style="color: white;" class="form-control" id="branch" placeholder="Branch">
                          </div>
                          <input type="hidden" name="hide" val="hide">
                          <div class="form-group">
                            <label for="mobileNumber">Mobile No</label>
                            <input type="text" style="color: white;" class="form-control" id="mobileNumber"
                              placeholder="Mobile No">
                          </div>
                          <p class="card-description" id="error" style="color:red;"></p>
                          <button type="submit" class="btn btn-primary me-2">Submit</button>
                          <button class="btn btn-dark" type="reset">Reset</button>
                        </form>
                        <p id="successMessage" style="color:green; display:none;">Details submitted successfully!</p>
                      </div>
                    </div>
                  </div>
                </div>
                <script>
                  document.getElementById('paymentForm').addEventListener('submit', function (event) {
                    // Prevent the default form submission
                    event.preventDefault();

                    // Clear previous error and success messages
                    const errorElement = document.getElementById('error');
                    const successElement = document.getElementById('successMessage');
                    errorElement.textContent = '';
                    successElement.textContent = '';
                    errorElement.style.display = 'none';
                    successElement.style.display = 'none';

                    // Retrieve form field values
                    const name = document.getElementById('name').value.trim();
                    const bank = document.getElementById('bank').value.trim();
                    const accountNumber = document.getElementById('accountNumber').value.trim();
                    const ifscCode = document.getElementById('ifscCode').value.trim();
                    const branch = document.getElementById('branch').value.trim();
                    const mobileNumber = document.getElementById('mobileNumber').value.trim();
                    const driverId = '<?php echo $row["driverid"]; ?>';

                    // Validation flags and error messages
                    let isValid = true;
                    const errorMessages = [];

                    // Validate each form field
                    if (!name) {
                      errorMessages.push('Name is required.');
                      isValid = false;
                    }
                    if (!accountNumber) {
                      errorMessages.push('Account Number is required.');
                      isValid = false;
                    }
                    if (!ifscCode) {
                      errorMessages.push('IFSC Code is required.');
                      isValid = false;
                    }
                    if (!branch) {
                      errorMessages.push('Branch is required.');
                      isValid = false;
                    }
                    if (!mobileNumber) {
                      errorMessages.push('Mobile Number is required.');
                      isValid = false;
                    }
                    if (!bank) {
                      errorMessages.push('Bank name is required.');
                      isValid = false;
                    }

                    // Display error messages if form is invalid
                    if (!isValid) {
                      errorElement.textContent = errorMessages.join('\n');
                      errorElement.style.display = 'block';
                      return;
                    }

                    // Create a data object to send
                    const data = {
                      name,
                      accountNumber,
                      ifscCode,
                      branch,
                      mobileNumber,
                      driverId,
                      bank,
                    };

                    // Send data using fetch API
                    fetch('mypays.php', {
                      method: 'POST',
                      headers: {
                        'Content-Type': 'application/json',
                      },
                      body: JSON.stringify(data),
                    })
                      .then(response => response.json())
                      .then(data => {
                        
                        // Handle the response from the server
                        if (data.success) {
                          // Display success message
                          successElement.textContent = 'Form submitted successfully!';
                          successElement.style.display = 'block';

                          // Reset the form fields
                          document.getElementById('paymentForm').reset();
                        } else {
                          // Display error message from server response
                          errorElement.textContent = data.message || 'Error submitting the form.';
                          errorElement.style.display = 'block';
                        }
                      })
                      .catch(error => {
                        // Display error message for network or other fetch errors
                        errorElement.textContent = 'Error submitting the form. Please try again later.';
                        errorElement.style.display = 'block';
                      });
                  });

                </script>



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