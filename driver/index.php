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
    if ($row['active'] != 1) {
      $d = $row['driverid'];
      $subs = "SELECT * FROM `subscription` WHERE driverid=$d";
      $result55 = mysqli_query($conn, $subs);

      if ($result55) {
        if (mysqli_num_rows($result55) > 0) {
          $subscription = mysqli_fetch_assoc($result55);
          $endDate = date('Y-m-d', strtotime($subscription['end_date'])); // Extracting date only from end_date
          date_default_timezone_set('Asia/Kolkata');
          $today = date('Y-m-d'); // Only extracting date part for today

          if ($endDate != $today) {
            $name = $row["firstname"] . " " . $row["lastname"];
            $rides = "SELECT * FROM ride WHERE selectedCab='" . $row['cateid'] . "' ORDER BY book_time ASC";
            if ($result2 = mysqli_query($conn, $rides)) {
              ?>

              <!DOCTYPE html>
              <html lang="en">

              <head>
                <title>Driver Deshboard</title>
                <?php include ("partials/_links.php"); ?>
                <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
                <link rel="shortcut icon" href="../static/pictures/favicon.png" />
              </head>

              <body>

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
                <script>
                  // Function to fetch and update the table content
                  function fetchAndUpdateTable() {
                    $.ajax({
                      url: 'fetch_table.php', // Path to your server-side script
                      type: 'GET',
                      success: function (data) {
                        $('#table-container').html(data); // Update table content
                        checkForNewBookings();
                      },
                      error: function (xhr, status, error) {
                        console.error('Error fetching data:', error);
                      }
                    });
                  }

                  // Call fetchAndUpdateTable initially to load the table content
                  fetchAndUpdateTable();

                  // Set an interval to fetch and update the table content every 5 seconds
                  setInterval(fetchAndUpdateTable, 5000); // Adjust interval as needed
                </script>
                <!-- --------------------------------------------------------------------------- -->

                <div class="container-scroller">

                  <!-- partial:partials/_sidebar.html -->
                  <?php include ("partials/_sidebar.php"); ?>
                  <!-- partial -->
                  <div class="container-fluid page-body-wrapper">
                    <!-- partial:partials/_navbar.html -->
                    <?php include ("partials/_navbar.php"); ?>
                    <!-- partial -->
                    <div class="main-panel">
                      <div class="content-wrapper">

                        <div class="row ">
                          <div class="col-12 grid-margin">
                            <div class="card">
                              <div class="card-body">
                                <h4 class="card-title">Requested Bookings</h4>
                                <div class="table-responsive" id='table-container'>
                                  <!-- Table content will be dynamically updated here -->
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <!-- <div id="notification"></div> -->
                      </div>
                      <!-- content-wrapper ends -->
                      <script>
                        function Accept_btn(val, reqID) {
                          postData = {
                            operation: 'AcceptBook',
                            bookid: val,
                            driverid: <?php echo $row['driverid']; ?>,
                            reqID: reqID
                          };

                          $.ajax({
                            type: "POST",
                            url: "function.php", // Replace with your server-side endpoint
                            data: postData,
                            dataType: 'json', // Specify the expected data type
                            success: function (result1) {
                              console.log(result1);

                              // Check if the response status is 'done' indicating success
                              if (result1.status === 'done') {
                                document.getElementById(val).innerHTML = '<button type="button" id="pick-btn" class="btn btn-info btn-rounded btn-sm" onclick="Pick_btn(\'' + val + '\', \'' + result1.reqID + '\')">Pickup</button> &nbsp <button type="button" id="dc-btn" class="btn btn-danger btn-rounded btn-sm" onclick="Dc_btn(\'' + val + '\', \'' + result1.reqID + '\')">Decline</button>';
                              } else {
                                console.error("Error: " + result1.message);
                              }
                            },
                            error: function (xhr, status, error) {
                              console.error("AJAX Request Error:", status, error);
                            }
                          });
                        }



                        function Pick_btn(val, reqID) {

                          postData = {
                            operation: 'PickBook',
                            bookid: val,
                            driverid: <?php echo $row['driverid']; ?>,
                            reqID: reqID,
                          };

                          $.ajax({
                            type: "POST",
                            url: "function.php", // Replace with your server-side endpoint
                            data: postData,
                            dataType: 'json', // Specify the expected data type
                            success: function (result1) {
                              console.log(result1);

                              // Check if the response status is 'done' indicating success
                              if (result1.status === 'done') {
                                document.getElementById(val).innerHTML = '<button type="button" id="com-btn" class="btn btn-success btn-rounded btn-sm" onclick="Complete_btn(\'' + val + '\', \'' + reqID + '\')">Complete</button> &nbsp <button type="button" id="map-btn" class="btn btn-primary btn-rounded btn-sm" onclick="map_btn(\'' + val + '\', \'' + reqID + '\')">Start Map</button>';
                              } else {
                                console.error("Error: " + result1.message);
                              }
                            },
                            error: function (xhr, status, error) {
                              console.error("AJAX Request Error:", status, error);
                            }
                          });



                        }

                        function Complete_btn(val, reqID) {
                          postData = {
                            operation: 'CompleteRide',
                            bookid: val,
                            driverid: <?php echo $row['driverid']; ?>,
                            reqID: reqID,
                          };

                          $.ajax({
                            type: "POST",
                            url: "function.php", // Replace with your server-side endpoint
                            data: postData,
                            dataType: 'json', // Specify the expected data type
                            success: function (result1) {
                              console.log(result1);

                              // Check if the response status is 'done' indicating success
                              if (result1.status === 'done') {
                                document.getElementById(val).innerHTML = '<div class="badge badge-outline-success">Completed</div>';
                              } else {
                                console.error("Error: " + result1.message);
                              }
                            },
                            error: function (xhr, status, error) {
                              console.error("AJAX Request Error:", status, error);
                            }
                          });

                        }

                        function Dc_btn(val, reqID) {
                          postData = {
                            operation: 'DeclineRide',
                            bookid: val,
                            driverid: <?php echo $row['driverid']; ?>,
                            reqID: reqID,
                          };

                          $.ajax({
                            type: "POST",
                            url: "function.php", // Replace with your server-side endpoint
                            data: postData,
                            dataType: 'json', // Specify the expected data type
                            success: function (result1) {
                              console.log(result1);

                              // Check if the response status is 'done' indicating success
                              if (result1.status === 'done') {
                                document.getElementById(val).innerHTML = '<div class="badge badge-outline-danger">Declined</div>';
                              } else {
                                console.error("Error: " + result1.message);
                              }
                            },
                            error: function (xhr, status, error) {
                              console.error("AJAX Request Error:", status, error);
                            }
                          });
                        }


                        function map_btn(a, b) {
                          // Construct the URL with parameters a and b
                          var url = "map.php?a=" + encodeURIComponent(a) + "&b=" + encodeURIComponent(b);

                          // Redirect the browser to the constructed URL
                          window.location.href = url;
                        }

                      </script>

                      <script>
                        $('#not').hide();
                        $('#msgdot').hide();
                        let notificationShown = false; // Variable to track if notification has been shown

                        function checkForNewBookings() {
                          $.ajax({
                            url: 'notify.php', // Path to your server-side script to check for new bookings
                            type: 'GET',
                            success: function (data) {
                              if (data === 'true' && !notificationShown) {
                                // If new booking is found and notification not shown, display the notification
                                // $('#notification').html('<div class="alert alert-success" role="alert">New booking has arrived!</div>');
                                // $('#notification').show(); // Show the notification
                                $('#notdot').show();
                                $('#not').html('<div class="preview-thumbnail">' +
                                  '<div class="preview-icon bg-dark rounded-circle">' +
                                  '<i class="mdi mdi-calendar text-success"></i>' +
                                  '</div>' +
                                  '</div>' +
                                  '<div class="preview-item-content">' +
                                  '<p class="preview-subject mb-1">New booking has arrived!</p>' +
                                  '<p class="text-muted ellipsis mb-0"></p>' +
                                  '</div>');


                                // notificationShown = true; // Set notificationShown to true to indicate notification has been shown
                              } else {
                                // If no new booking or notification already shown, hide the notification
                                // $('#notification').hide();
                                $('#notdot').hide();
                                $('#not').hide();
                                $('#not').html('');

                              }
                            },
                            error: function (xhr, status, error) {
                              console.error('Error checking for new bookings:', error);
                            }
                          });
                        }
                      </script>

                      <!-- partial:partials/_footer.html -->
                      <?php include ("partials/_footer.php"); ?>
                      <!-- partial -->
                    </div>
                    <!-- main-panel ends -->
                  </div>
                  <!-- page-body-wrapper ends -->
                </div>

                <?php include ("partials/_scripts.php"); ?>

              </body>

              </html>

              <?php
            } else {
              header("location:" . BASE_URL . "pricing");
            }
          } else {
            header("location:" . BASE_URL . "pricing");
          }
        } else {
          header("location:" . BASE_URL . "pricing");
        }
      } else {
        header("location:" . BASE_URL . "pricing");
      }
    } else {
      header("location:" . BASE_URL . "pricing");
    }
  } else {
    header("location:" . BASE_URL . "login");
  }
} else {
  header("location:" . BASE_URL . "login");
}
exit;
?>