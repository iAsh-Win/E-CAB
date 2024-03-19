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
        <title>Driver Deshboard</title>
        <?php
        include ("partials/_links.php");

        ?>
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

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



            <!-- partial -->
            <div class="main-panel">
              <div class="content-wrapper">

                <div class="row ">
                  <div class="col-12 grid-margin">
                    <div class="card">
                      <div class="card-body">
                        <h4 class="card-title">Requested Bookings</h4>
                        <div class="table-responsive" id='table-container'>
                          <table class="table">
                            <thead>
                              <tr>

                                <th> Requested User </th>
                                <th> Source</th>
                                <th> Destination</th>
                                <th> Pickup-time</th>
                                <th> Payment Mode </th>
                                <th> Payment Status</th>
                                <th> Fare</th>
                                <th> Action</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php

                              while ($ride = mysqli_fetch_assoc($result2)) {
                                $bookings = mysqli_query($conn, "SELECT * FROM bookings WHERE rid='" . $ride['rid'] . "'");
                                $query = "SELECT * FROM users WHERE id=" . mysqli_real_escape_string($conn, $ride['uid']);
                                $result = mysqli_query($conn, $query);



                                $user = mysqli_fetch_assoc($result);
                                if ($bookings) {
                                  $bookingdata = mysqli_fetch_assoc($bookings);

                                  if ($bookingdata['status'] != 'Cancelled') {
                                    $bookid = $bookingdata['bookid'];
                                    $driverid = $row['driverid'];
                                    // --------------------------------------
                                    $ridez = "SELECT * FROM `driverrequest` WHERE bookid=$bookid";
                                    $rides = mysqli_query($conn, $ridez);

                                    while ($req = mysqli_fetch_assoc($rides)) {
                                      if ($req['driverID'] == $driverid && $req['status'] != 'Decline') {
                                        // =======================================================
                                        if ($req['status'] == 'pending' && $req['driverID'] == $driverid && $req['status'] != 'Decline') {
                                          // Check if any other driver has accepted the request
                                          $ridez = "SELECT * FROM `driverrequest` WHERE bookid=$bookid AND (status='Accepted' OR status='Pickup' OR status='Complete') AND driverID != $driverid";
                                          $rides_other_accepted = mysqli_query($conn, $ridez);
                                          if (mysqli_num_rows($rides_other_accepted) == 0) {
                                            $ridez = "SELECT * FROM `driverrequest` WHERE bookid=$bookid AND driverID=$driverid";
                                            $rides = mysqli_query($conn, $ridez);
                                            $req = mysqli_fetch_assoc($rides);
                                            if ($req) {


                                              echo '<tr>';
                                              echo '<td class="text-white"><span class="ps-2">' . htmlspecialchars($user['emailID']) . '</span></td>';
                                              echo '<td class="text-white">' . htmlspecialchars($ride['source']) . '</td>';
                                              echo '<td class="text-white">' . htmlspecialchars($ride['destination']) . '</td>';
                                              echo '<td class="text-white">' . $ride['booking_time'] . '</td>';
                                              echo '<td class="text-white">' . $bookingdata['payment_type'] . '</td>';

                                              if ($bookingdata['payment_type'] === 'online') {
                                                $paydetails = "SELECT * FROM payments WHERE bookID='" . mysqli_real_escape_string($conn, $bookingdata['bookid']) . "'";
                                                $result4 = mysqli_query($conn, $paydetails);

                                                if (!$result4) {
                                                  // Handle error more gracefully, log or display an error message
                                                  throw new Exception(mysqli_error($conn));
                                                }

                                                $Paymentdetails = mysqli_fetch_assoc($result4);

                                                echo '<td class="text-white">' . htmlspecialchars($Paymentdetails['status']) . '</td>';

                                                mysqli_free_result($result4); // Free the result set memory
                                              } else {
                                                echo '<td class="text-white">-</td>';
                                              }
                                              echo '<td class="text-white">' . $ride['fare'] . '</td>';
                                              if ($req['status'] == 'Accepted') {
                                                echo '<td id="' . $bookingdata['bookid'] . '">
                                                
                                                      <button type="button" id="pick-btn" class="btn btn-info btn-rounded btn-sm" onclick="Pick_btn(\'' . $bookingdata['bookid'] . '\', \'' . $req['reqID'] . '\')">Pickup</button> &nbsp <button type="button" id="dc-btn" class="btn btn-danger btn-rounded btn-sm" onclick="Dc_btn(\'' . $bookingdata['bookid'] . '\', \'' . $req['reqID'] . '\')">Decline</button>
                                          
                                                      </td>';
                                              } else if ($req['status'] == 'Pickup') {
                                                echo '<td id="' . $bookingdata['bookid'] . '">
                                                  <button type="button" id="com-btn" class="btn btn-success btn-rounded btn-sm" onclick="Complete_btn(\'' . $bookingdata['bookid'] . '\', \'' . $req['reqID'] . '\')">Complete</button>
                                      
                                                  </td>';
                                              } else if ($req['status'] == 'pending') {
                                                echo '<td id="' . $bookingdata['bookid'] . '">
                                                  <button type="button" id="ac-btn" class="btn btn-info btn-rounded btn-sm" onclick="Accept_btn(\'' . $bookingdata['bookid'] . '\', \'' . $req['reqID'] . '\')">Accept</button>
                                      
                                                  </td>';
                                              } 
                                              else {
                                                echo '<td id="' . $bookingdata['bookid'] . '">
                                                  <div class="badge badge-outline-success">Completed</div>
                                      
                                                  </td>';
                                              }

                                              echo '</tr>';
                                            }
                                          }
                                        } else if ($req['status'] != 'pending' && $req['driverID'] == $driverid && $req['status'] != 'Decline') {
                                          $ridez = "SELECT * FROM `driverrequest` WHERE bookid=$bookid AND (status='Accepted' OR status='Pickup' OR status='Complete') AND driverID = $driverid";
                                          $rides_other_accepted = mysqli_query($conn, $ridez);
                                          if (mysqli_num_rows($rides_other_accepted) == 1) {
                                            $ridez = "SELECT * FROM `driverrequest` WHERE bookid=$bookid AND driverID=$driverid";
                                            $rides = mysqli_query($conn, $ridez);
                                            $req = mysqli_fetch_assoc($rides);
                                            if ($req) {


                                              echo '<tr>';
                                              echo '<td class="text-white"><span class="ps-2">' . htmlspecialchars($user['emailID']) . '</span></td>';
                                              echo '<td class="text-white">' . htmlspecialchars($ride['source']) . '</td>';
                                              echo '<td class="text-white">' . htmlspecialchars($ride['destination']) . '</td>';
                                              echo '<td class="text-white">' . $ride['booking_time'] . '</td>';
                                              echo '<td class="text-white">' . $bookingdata['payment_type'] . '</td>';

                                              if ($bookingdata['payment_type'] === 'online') {
                                                $paydetails = "SELECT * FROM payments WHERE bookID='" . mysqli_real_escape_string($conn, $bookingdata['bookid']) . "'";
                                                $result4 = mysqli_query($conn, $paydetails);

                                                if (!$result4) {
                                                  // Handle error more gracefully, log or display an error message
                                                  throw new Exception(mysqli_error($conn));
                                                }

                                                $Paymentdetails = mysqli_fetch_assoc($result4);

                                                echo '<td class="text-white">' . htmlspecialchars($Paymentdetails['status']) . '</td>';

                                                mysqli_free_result($result4); // Free the result set memory
                                              } else {
                                                echo '<td class="text-white">-</td>';
                                              }
                                              echo '<td class="text-white">' . $ride['fare'] . '</td>';
                                              if ($req['status'] == 'Accepted') {
                                                echo '<td id="' . $bookingdata['bookid'] . '">
                                                
                                                      <button type="button" id="pick-btn" class="btn btn-info btn-rounded btn-sm" onclick="Pick_btn(\'' . $bookingdata['bookid'] . '\', \'' . $req['reqID'] . '\')">Pickup</button> &nbsp <button type="button" id="dc-btn" class="btn btn-danger btn-rounded btn-sm" onclick="Dc_btn(\'' . $bookingdata['bookid'] . '\', \'' . $req['reqID'] . '\')">Decline</button>
                                          
                                                      </td>';
                                              } else if ($req['status'] == 'Pickup') {
                                                echo '<td id="' . $bookingdata['bookid'] . '">
                                                  <button type="button" id="com-btn" class="btn btn-success btn-rounded btn-sm" onclick="Complete_btn(\'' . $bookingdata['bookid'] . '\', \'' . $req['reqID'] . '\')">Complete</button>
                                      
                                                  </td>';
                                              } else if ($req['status'] == 'pending') {
                                                echo '<td id="' . $bookingdata['bookid'] . '">
                                                  <button type="button" id="ac-btn" class="btn btn-info btn-rounded btn-sm" onclick="Accept_btn(\'' . $bookingdata['bookid'] . '\', \'' . $req['reqID'] . '\')">Accept</button>
                                      
                                                  </td>';
                                              } else {
                                                echo '<td id="' . $bookingdata['bookid'] . '">
                                                  <div class="badge badge-outline-success">Completed</div>
                                      
                                                  </td>';
                                              }

                                              echo '</tr>';
                                            }
                                          }


                                        }
                                        // =======================================================
                        





                                      } else if ($req['driverID'] != $driverid && $req['status'] == 'Decline') {
                                        $ridez = "SELECT * FROM `driverrequest` WHERE bookid=$bookid AND driverID=$driverid";
                                        $rides = mysqli_query($conn, $ridez);
                                        $req = mysqli_fetch_assoc($rides);
                                        if ($req) {


                                          echo '<tr>';
                                          echo '<td class="text-white"><span class="ps-2">' . htmlspecialchars($user['emailID']) . '</span></td>';
                                          echo '<td class="text-white">' . htmlspecialchars($ride['source']) . '</td>';
                                          echo '<td class="text-white">' . htmlspecialchars($ride['destination']) . '</td>';
                                          echo '<td class="text-white">' . $ride['booking_time'] . '</td>';
                                          echo '<td class="text-white">' . $bookingdata['payment_type'] . '</td>';

                                          if ($bookingdata['payment_type'] === 'online') {
                                            $paydetails = "SELECT * FROM payments WHERE bookID='" . mysqli_real_escape_string($conn, $bookingdata['bookid']) . "'";
                                            $result4 = mysqli_query($conn, $paydetails);

                                            if (!$result4) {
                                              // Handle error more gracefully, log or display an error message
                                              throw new Exception(mysqli_error($conn));
                                            }

                                            $Paymentdetails = mysqli_fetch_assoc($result4);

                                            echo '<td class="text-white">' . htmlspecialchars($Paymentdetails['status']) . '</td>';

                                            mysqli_free_result($result4); // Free the result set memory
                                          } else {
                                            echo '<td class="text-white">-</td>';
                                          }
                                          echo '<td class="text-white">' . $ride['fare'] . '</td>';
                                          if ($req['status'] == 'Accepted') {
                                            echo '<td id="' . $bookingdata['bookid'] . '">
                        
                                                  <button type="button" id="pick-btn" class="btn btn-info btn-rounded btn-sm" onclick="Pick_btn(\'' . $bookingdata['bookid'] . '\', \'' . $req['reqID'] . '\')">Pickup</button> &nbsp <button type="button" id="dc-btn" class="btn btn-danger btn-rounded btn-sm" onclick="Dc_btn(\'' . $bookingdata['bookid'] . '\', \'' . $req['reqID'] . '\')">Decline</button>
                        
                                                  </td>';
                                          } else if ($req['status'] == 'Pickup') {
                                            echo '<td id="' . $bookingdata['bookid'] . '">
                                              <button type="button" id="com-btn" class="btn btn-success btn-rounded btn-sm" onclick="Complete_btn(\'' . $bookingdata['bookid'] . '\', \'' . $req['reqID'] . '\')">Complete</button>
                        
                                              </td>';
                                          } else if ($req['status'] == 'pending') {
                                            echo '<td id="' . $bookingdata['bookid'] . '">
                                              <button type="button" id="ac-btn" class="btn btn-info btn-rounded btn-sm" onclick="Accept_btn(\'' . $bookingdata['bookid'] . '\', \'' . $req['reqID'] . '\')">Accept</button>
                        
                                              </td>';
                                          }  else if ($req['status'] == 'Decline') {
                                            echo '<td id="' . $bookingdata['bookid'] . '">
                                            <div class="badge badge-outline-danger">Declined</div>
                        
                                              </td>';
                                          }
                                          else {
                                            echo '<td id="' . $bookingdata['bookid'] . '">
                                              <div class="badge badge-outline-success">Completed</div>
                        
                                              </td>';
                                          }

                                          echo '</tr>';
                                        }
                                      }else if ($req['driverID'] == $driverid && $req['status'] == 'Decline'){
                                        $ridez = "SELECT * FROM `driverrequest` WHERE bookid=$bookid AND driverID=$driverid";
                                        $rides = mysqli_query($conn, $ridez);
                                        $req = mysqli_fetch_assoc($rides);
                                        if ($req) {


                                          echo '<tr>';
                                          echo '<td class="text-white"><span class="ps-2">' . htmlspecialchars($user['emailID']) . '</span></td>';
                                          echo '<td class="text-white">' . htmlspecialchars($ride['source']) . '</td>';
                                          echo '<td class="text-white">' . htmlspecialchars($ride['destination']) . '</td>';
                                          echo '<td class="text-white">' . $ride['booking_time'] . '</td>';
                                          echo '<td class="text-white">' . $bookingdata['payment_type'] . '</td>';

                                          if ($bookingdata['payment_type'] === 'online') {
                                            $paydetails = "SELECT * FROM payments WHERE bookID='" . mysqli_real_escape_string($conn, $bookingdata['bookid']) . "'";
                                            $result4 = mysqli_query($conn, $paydetails);

                                            if (!$result4) {
                                              // Handle error more gracefully, log or display an error message
                                              throw new Exception(mysqli_error($conn));
                                            }

                                            $Paymentdetails = mysqli_fetch_assoc($result4);

                                            echo '<td class="text-white">' . htmlspecialchars($Paymentdetails['status']) . '</td>';

                                            mysqli_free_result($result4); // Free the result set memory
                                          } else {
                                            echo '<td class="text-white">-</td>';
                                          }
                                          echo '<td class="text-white">' . $ride['fare'] . '</td>';
                                          if ($req['status'] == 'Accepted') {
                                            echo '<td id="' . $bookingdata['bookid'] . '">
                        
                                                  <button type="button" id="pick-btn" class="btn btn-info btn-rounded btn-sm" onclick="Pick_btn(\'' . $bookingdata['bookid'] . '\', \'' . $req['reqID'] . '\')">Pickup</button> &nbsp <button type="button" id="dc-btn" class="btn btn-danger btn-rounded btn-sm" onclick="Dc_btn(\'' . $bookingdata['bookid'] . '\', \'' . $req['reqID'] . '\')">Decline</button>
                        
                                                  </td>';
                                          } else if ($req['status'] == 'Pickup') {
                                            echo '<td id="' . $bookingdata['bookid'] . '">
                                              <button type="button" id="com-btn" class="btn btn-success btn-rounded btn-sm" onclick="Complete_btn(\'' . $bookingdata['bookid'] . '\', \'' . $req['reqID'] . '\')">Complete</button>
                        
                                              </td>';
                                          } else if ($req['status'] == 'pending') {
                                            echo '<td id="' . $bookingdata['bookid'] . '">
                                              <button type="button" id="ac-btn" class="btn btn-info btn-rounded btn-sm" onclick="Accept_btn(\'' . $bookingdata['bookid'] . '\', \'' . $req['reqID'] . '\')">Accept</button>
                        
                                              </td>';
                                          }  else if ($req['status'] == 'Decline') {
                                            echo '<td id="' . $bookingdata['bookid'] . '">
                                            <div class="badge badge-outline-danger">Declined</div>
                        
                                              </td>';
                                          }
                                          else {
                                            echo '<td id="' . $bookingdata['bookid'] . '">
                                              <div class="badge badge-outline-success">Completed</div>
                        
                                              </td>';
                                          }

                                          echo '</tr>';
                                        }
                                      }



                                    }
                                    // ------------------------------------------
                        
                                  }


                                }
                              }
                              ?>

                            </tbody>

                            
                          </table>
                       
                          <!-- ------------------------- -->
                          <!-- <button type="button" id="dc-btn" class="btn btn-info btn-rounded btn-sm" onclick="Dc_btn(\'' . $bookingdata['bookid'] . '\')">Decline</button>
                                    <button type="button" id="pick-btn" class="btn btn-info btn-rounded btn-sm" onclick="Pick_btn(\'' . $bookingdata['bookid'] . '\')">Pickup</button>
                                    <button type="button" id="Com-btn" class="btn btn-info btn-rounded btn-sm" onclick="Complete_btn(\'' . $bookingdata['bookid'] . '\')">Complete</button> -->
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
                                    document.getElementById(val).innerHTML = '<button type="button" id="com-btn" class="btn btn-success btn-rounded btn-sm" onclick="Complete_btn(\'' + val + '\', \'' + reqID + '\')">Complete</button>';
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
                          </script>
                          <!-- ------------------ -->
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