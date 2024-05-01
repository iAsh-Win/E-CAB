<?php
include ("path.php");

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
      ?>

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
                                                  <button type="button" id="map-btn" class="btn btn-primary btn-rounded btn-sm" onclick="map_btn(\'' . $bookingdata['bookid'] . '\', \'' . $req['reqID'] . '\')">Start Map</button>
                                      
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
                                                  <button type="button" id="com-btn" class="btn btn-success btn-rounded btn-sm" onclick="Complete_btn(\'' . $bookingdata['bookid'] . '\', \'' . $req['reqID'] . '\')">Complete</button> &nbsp <button type="button" id="map-btn" class="btn btn-primary btn-rounded btn-sm" onclick="map_btn(\'' . $bookingdata['bookid'] . '\', \'' . $req['reqID'] . '\')">Start Map</button>
                                      
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
                      } else if ($req['status'] == 'Decline') {
                        echo '<td id="' . $bookingdata['bookid'] . '">
                                            <div class="badge badge-outline-danger">Declined</div>
                        
                                              </td>';
                      } else {
                        echo '<td id="' . $bookingdata['bookid'] . '">
                                              <div class="badge badge-outline-success">Completed</div>
                        
                                              </td>';
                      }

                      echo '</tr>';
                    }
                  } else if ($req['driverID'] == $driverid && $req['status'] == 'Decline') {
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
                      } else if ($req['status'] == 'Decline') {
                        echo '<td id="' . $bookingdata['bookid'] . '">
                                            <div class="badge badge-outline-danger">Declined</div>
                        
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
                // ------------------------------------------
    
              }


            }
          }
          ?>

        </tbody>
      </table>

      <?php
    }
  }
}
?>