<?php
session_start();
// $_SESSION["forgot_otp"]=null;

include ("path.php");
if (isset ($_SESSION["driver_login"]) && $_SESSION["driver_login"] == true && isset ($_SESSION["driveremail"]) && $_SESSION["driveremail"] != "") {
  header("location:" . BASE_URL);
} else {
  ?>
  <!DOCTYPE html>
  <html lang="en">

  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Forgot Password</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- End layout styles -->
    <link rel="shortcut icon" href="../static/pictures/favicon.png" />
    <style>
      .ptext {
        color: red;
        position: absolute;
      }

      .div2,
      .div3,
      .modi {
        display: none;
      }

      h6,
      a {
        text-decoration: none;
        /* Remove underline */
        color: white;
        /* Set text color to a blue shade */
        font-weight: bold;
        /* Make the text bold */
        cursor: pointer;
        /* Change cursor to indicate interactivity */
        transition: color 0.3s ease;
        /* Smooth transition for color change */
      }

      /* Change color on hover */
      h6,
      a:hover {
        cursor: pointer;
        color: #e74c3c;

      }
    </style>
  </head>

  <body>
    <div class="container-scroller">
      <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="row w-100 m-0">
          <div class="content-wrapper full-page-wrapper d-flex align-items-center auth lock-full-bg">
            <div class="card col-lg-4 mx-auto">
              <div class="card-body moma px-5 py-5">
                <h3 class="card-title text-left mb-3">Reset Your Password</h3>



                <div class="div1">
                  <div class="form-group">
                    <label>Enter Your Email *</label>
                    <input type="email" name="email" id="forgot_email" class="form-control p_input text-white" required>
                    <p class="ptext" id="myt"></p>
                  </div>
                  <div class="form-group">
                    <div class="text-center">

                      <button class="btn btn-info btn-block enter-btn m-2" id="send" onclick="send_otp()">Send
                        OTP</button>
                    </div>
                  </div>
                </div>

                <div class="div2">
                  <div class="form-group">
                    <label>Enter OTP *</label>
                    <input type="text" name="OTP" id="otp" class="form-control p_input text-white" required>
                    <p class="ptext" id="my"></p>
                  </div>
                  <div class="form-group">
                    <div class="text-center">

                      <button class="btn btn-success btn-block enter-btn m-2" id="varify" onclick="varify_otp()">Varify
                        OTP</button>
                    </div>
                    <h6 onclick="resend()" href="#" id="htag"> Resend OTP</h6> <label id="timer"></label>
                  </div>
                </div>

                <div class="div3">
                  <div class="form-group">
                    <label>Set New Password*</label>
                    <input type="password" id="pass1" class="form-control p_input text-white" required>

                  </div>
                  <div class="form-group">
                    <label>Rewrite New Password*</label>
                    <input type="password" id="pass2" class="form-control p_input text-white" required>
                    <p class="ptext" id="my1"></p>
                  </div>
                  <div class="form-group">
                    <div class="text-center">

                      <button class="btn btn-success btn-block enter-btn m-2" id="change" onclick="change()">Change
                        Password</button>
                    </div>

                  </div>
                </div>




              </div>

              <div class="card-body m-5 p-0 modi">
                <h3 class="card-title text-left mb-5 ">Your Password is Successfully Changed!</h3>
                <div class="form-group">
                  <a href="login.php">Go and Login to your Deshboard</a>
                </div>
              </div>

            </div>
          </div>
          <!-- content-wrapper ends -->
        </div>
        <!-- row ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="assets/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="assets/js/off-canvas.js"></script>
    <script src="assets/js/hoverable-collapse.js"></script>
    <script src="assets/js/misc.js"></script>
    <script src="assets/js/settings.js"></script>
    <script src="assets/js/todolist.js"></script>
    <script>
      var sessionotp;
      function isValidEmail(email) {
        // Regular expression for basic email validation
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
      }
      var inputField = document.getElementById("forgot_email");

      function showDiv2() {
        var elem = document.getElementsByClassName("div2");

        for (var i = 0; i < elem.length; i++) {
          elem[i].style.display = "block";
        }
      }

      function showDiv3() {
        var elem5 = document.getElementsByClassName("div3");

        for (var i = 0; i < elem5.length; i++) {
          elem5[i].style.display = "block";
        }
      }

      function showDiv1() {
        var elems = document.getElementsByClassName("div1");
        document.getElementById("myt").innerText = "Email is not Exist!";

        for (var i = 0; i < elems.length; i++) {
          elems[i].style.display = "block";
        }

      }


      function hideDiv1() {
        var elements = document.getElementsByClassName("div1");

        for (var i = 0; i < elements.length; i++) {
          elements[i].style.display = "none";
        }
      }

      function hideDiv2() {
        var elements1 = document.getElementsByClassName("div2");

        for (var i = 0; i < elements1.length; i++) {
          elements1[i].style.display = "none";
        }
      }
      function hideall() {
        var elements11 = document.getElementsByClassName("moma");

        for (var i = 0; i < elements11.length; i++) {
          elements11[i].style.display = "none";
        }

      }

      function showmodi() {
        var elems153 = document.getElementsByClassName("modi");

        for (var i = 0; i < elems153.length; i++) {
          elems153[i].style.display = "block";
        }

      }




      function checkemail(inputValue) {
        var postData = {
          key1: inputValue,
          key2: "div1"
        };
        $.ajax({
          type: "POST", // Use POST method
          url: "function.php", // Replace with your server-side endpoint
          data: postData,
          dataType: "text", // Data to be sent in the request body
          success: function (result) {
            console.log(result)
            if (result != "") { showDiv1(); }
            else {
              hideDiv1();
              showDiv2();
              $.ajax({
                type: "POST", // Use POST method
                url: "sendotp.php", // Replace with your server-side endpoint
                data: postData, // Data to be sent in the request body
                success: function (result1) {
                  
                  if (result1.includes("Error")) {

                    document.getElementById("my").innerText = "Check Your Internet Connection.";
                  }
                  else {
                   
                    sessionotp = parseInt(result1);
                  }

                },
              });

            }
          },
          error: function(xhr, status, error) {
            console.error("Error occurred: ", status, error);
            // Handle error here
        }

        });
      }



      var love = "no";
      var inc;


      function send_otp() {

        setTimeout(function () {
          document.getElementById("myt").innerText = "";
        }, 5000);

        var inputField = document.getElementById("forgot_email");
        var sendButton = document.getElementById("send");
        var inputValue = inputField.value;
        inc = inputValue;

        if (love == "yes") {

          checkemail(inputValue);



        }
        if (isValidEmail(inputField.value)) {

          checkemail(inputValue);



        }

        // Attach an event listener to the input field
        inputField.addEventListener("input", function () {
          // Check if the input value is empty
          if (inputField.value === "") {

            sendButton.disabled = true;
            inputField.placeholder = "Please Enter Your Email";
            document.getElementById("myt").innerText = "Please Enter Your Email.";
          }
          else if (!isValidEmail(inputField.value)) {
            // Input value is not a valid email, disable the button
            sendButton.disabled = true;


          } else {
            // Input value is not empty, enable the button
            love = "yes";
            sendButton.disabled = false;
          }

        });


        // Initial check to set the button state based on the input value
        inputField.dispatchEvent(new Event("input"));

      }





      function varify_otp() {
        // Get the entered OTP and trim any leading/trailing whitespaces
        let enteredOTP = document.getElementById("otp").value.trim();

        // Clear any previous error messages
        setTimeout(function () {
          document.getElementById("my").innerText = "";
        }, 5000);

        // Validate the entered OTP
        if (!/^\d{4}$/.test(enteredOTP)) {
          // Check if the entered OTP is not a 4-digit number
          document.getElementById("my").innerText = "Please Enter Valid 4 Digit OTP.";
          document.getElementById("myt").innerText = "Enter your OTP";
        } else if (parseInt(enteredOTP) === sessionotp) {


          hideDiv2();
          showDiv3();
        } else {
          document.getElementById("my").innerText = "OTP is not correct.";
        }
      }

      const durationInSeconds = 60;
      let timerInterval;

      function updateTimerDisplay(seconds) {
        const minutes = Math.floor(seconds / 60);
        const remainingSeconds = seconds % 60;
        document.getElementById("timer").textContent = `${minutes}:${remainingSeconds < 10 ? '0' : ''}${remainingSeconds}`;
      }

      function startTimer() {
        let seconds = durationInSeconds;
        updateTimerDisplay(seconds);

        timerInterval = setInterval(function () {
          seconds--;
          updateTimerDisplay(seconds);

          if (seconds <= 0) {
            document.getElementById("htag").disabled = false;

            htag.style.color = "white";
            clearInterval(timerInterval);
            document.getElementById("timer").textContent = ''; // Clear the timer display
          }
        }, 1000);
      }

      function resend() {
        var htag = document.getElementById("htag"); // Declare htag using var or let
        if (!htag.disabled) {  // Use ! operator for better readability
          startTimer();
          htag.style.color = "black";
          htag.disabled = true;

          postData = {
            key1: inc,
            key2: "div1"
          };
          $.ajax({
            type: "POST", // Use POST method
            url: "sendotp.php", // Replace with your server-side endpoint
            data: postData, // Data to be sent in the request body
            success: function (result1) {
              if (result1.includes("Error")) {

                document.getElementById("my").innerText = "Check Your Internet Connection.";
              }
              else {
                sessionotp = parseInt(result1);
              }

            },
          });


        } else { }
      }


      function change() {
        setTimeout(function () {
          document.getElementById("my1").innerText = "";
        }, 5000);

        var pass1 = document.getElementById("pass1").value;
        var pass2 = document.getElementById("pass2").value;

        // Check for valid input lengths
        if (pass1.length < 8 || pass2.length < 8) {
          document.getElementById("my1").innerText = "Password must be at least 8 characters long.";
        } else if (pass1 === pass2) {
          var Data = {
            param1: inc,
            param2: pass1,
            param3: "change"
          };

          $.ajax({
            type: "POST",
            url: "function.php",
            data: Data,
            dataType: "text",
            success: function (result3) {
              console.log("changed")
              if (result3 == "changed") {
                hideall();
                showmodi();
              }
              else {
                document.getElementById("my1").innerText = "Passwords can not be Changed.";
              }
            }
          });
        } else {
          document.getElementById("my1").innerText = "Passwords do not match.";
        }
      }




    </script>

    <!-- endinject -->
  </body>

  </html>

  <?php
}
?>