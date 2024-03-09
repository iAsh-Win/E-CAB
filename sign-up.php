<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="Permissions-Policy" content="unload=()">


  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
    rel="stylesheet" />
  <!-- done  p-->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
    rel="stylesheet" />
  <!-- josef font -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100..700;1,100..700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
    rel="stylesheet">
  <!-- bootrap---------------- -->
  <!-- <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous"> -->
  <!-- bootrap---------------- -->
  <title>E-CAB | Sign up to Book a Ride</title>
  <link rel="stylesheet" href="./static/css/style.css">
  <link rel="stylesheet" href="./static/css/login.css">
  <link rel="icon" type="image/png" href="./static/pictures/favicon.png">

</head>

<body>
  <nav>
    <div class="nav-items">
      <div class="logo">
        <a href="index">
          <img src="./static/pictures/logo2.png" class="logo-img" alt="">
        </a>
      </div>
    </div>
  </nav>


  <main>

    <section class="display">
      <div class="ride-text">Signup to Continue</div>
      <div class="container" style="height: 555px">
        <!-- <div class="form-elements center">
          <span class="invalid" style="position: absolute;">User already exist</span>
        </div> -->
        <span class="invalid center1" id="noExist" style="position: absolute;"></span>
        <form action="" id="registerForm">
          <div class="form-elements">
            <label for="Name">Name</label>
            <input type="text" name="Name" maxlength="50" pattern="^[a-zA-Z]+(?: [a-zA-Z]+)*$"
              title="Please enter only single spaces between words." required>



          </div>
          <div class="form-elements">
            <label for="emain">Email</label>
            <input type="email" name="email" id="email" maxlength="50" required inputmode="email">
          </div>
          <div class="form-elements">
            <label for="Mobile">Mobile</label>
            <input type="text" name="Mobile" maxlength="10" pattern="[0-9]{1,10}"
              title="Please enter only numbers (up to 10 digits)" required inputmode="numeric">
          </div>
          <div class="form-elements">
            <label for="Password">Set Password</label>
            <input type="password" name="Password" maxlength="8" required>
          </div>
          <div class="form-elements">
            <label for="Password">Verify Password</label>
            <input type="password" name="verifyPassword" maxlength="8" id="verifyPassword" required>
          </div>
          <div class="form-elements">
            <span class="invalid" id="invalid"></span>
          </div>



          <div class="form-elements">
            <button type="submit" class="submit noeffct" id="submit">Signup</button>


          </div>
        </form>



        <div class="form-elements m0">
          <button class="submit noeffct" onclick="redirectToGoogle()"><svg xmlns="http://www.w3.org/2000/svg" x="0px"
              y="0px" width="40" height="40" viewBox="0,0,256,256">
              <g transform="translate(39.68,39.68) scale(0.69,0.69)">
                <g fill="none" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt"
                  stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0"
                  font-family="none" font-weight="none" font-size="none" text-anchor="none"
                  style="mix-blend-mode: normal">
                  <g transform="scale(5.33333,5.33333)">
                    <path
                      d="M43.611,20.083h-1.611v-0.083h-18v8h11.303c-1.649,4.657 -6.08,8 -11.303,8c-6.627,0 -12,-5.373 -12,-12c0,-6.627 5.373,-12 12,-12c3.059,0 5.842,1.154 7.961,3.039l5.657,-5.657c-3.572,-3.329 -8.35,-5.382 -13.618,-5.382c-11.045,0 -20,8.955 -20,20c0,11.045 8.955,20 20,20c11.045,0 20,-8.955 20,-20c0,-1.341 -0.138,-2.65 -0.389,-3.917z"
                      fill="#ffc107"></path>
                    <path
                      d="M6.306,14.691l6.571,4.819c1.778,-4.402 6.084,-7.51 11.123,-7.51c3.059,0 5.842,1.154 7.961,3.039l5.657,-5.657c-3.572,-3.329 -8.35,-5.382 -13.618,-5.382c-7.682,0 -14.344,4.337 -17.694,10.691z"
                      fill="#ff3d00"></path>
                    <path
                      d="M24,44c5.166,0 9.86,-1.977 13.409,-5.192l-6.19,-5.238c-2.008,1.521 -4.504,2.43 -7.219,2.43c-5.202,0 -9.619,-3.317 -11.283,-7.946l-6.522,5.025c3.31,6.477 10.032,10.921 17.805,10.921z"
                      fill="#4caf50"></path>
                    <path
                      d="M43.611,20.083h-1.611v-0.083h-18v8h11.303c-0.792,2.237 -2.231,4.166 -4.087,5.571c0.001,-0.001 0.002,-0.001 0.003,-0.002l6.19,5.238c-0.438,0.398 6.591,-4.807 6.591,-14.807c0,-1.341 -0.138,-2.65 -0.389,-3.917z"
                      fill="#1976d2"></path>
                  </g>
                </g>
              </g>
            </svg>Continue with Google!</a>
        </div>


        <!-- php code   -->







        <!-- php code   -->
      </div>
    </section>

    <div class="display2">
      <section class="display hide">
        <div class="ride-text">Enter OTP</div>

        <div class="container" style="height: 175px">

          <div class="form-elements">
            <label for="Name">Enter OTP</label>
            <span class="invalid" style="color:rgb(11, 11, 23)">OTP sent on Email</span>
          </div>
          <div class="otps">
            <input type="text" class="otp" name="otp" maxlength="1" oninput="moveToNext(this)" id="otpInput1"
              inputmode="numeric">
            <input type="text" class="otp" name="otp" maxlength="1" oninput="moveToNext(this)" id="otpInput2"
              inputmode="numeric">
            <input type="text" class="otp" name="otp" maxlength="1" oninput="moveToNext(this)" id="otpInput3"
              inputmode="numeric">
            <input type="text" class="otp" name="otp" maxlength="1" oninput="moveToNext(this)" id="otpInput4"
              inputmode="numeric">
          </div>
          <div class="form-elements">
            <span class="invalid" id="invalid2"></span>
          </div>

          <div class="form-elements">
            <a onclick="resendOtp()" id="resendOtp">Resend OTP <span id="timer"></span></a>

          </div>



        </div>
      </section>
    </div>
  </main>

  <!-- jQuery first, then Popper.js, then Bootstrap JS
      <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
      <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script> -->

  <?php
  require_once 'vendor/autoload.php';
  use Google\Client;
  use Google\Service\Oauth2;

  $clientID = '386892763233-aktsv8unlbdmmkqb5kqmvden71pprv3e.apps.googleusercontent.com';
  $clientSecret = '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';
  $redirectUri = 'http://localhost/E-cab/function';
  $client = new Client();
  $client->setClientId($clientID);
  $client->setClientSecret($clientSecret);
  $client->setRedirectUri($redirectUri);
  $client->addScope("email");
  $client->addScope("profile");
  // echo "<a href='" . $client->createAuthUrl() . "'>Google Login</a>";        ?>


  <script>
    function redirectToGoogle() {
      // Redirect the user to the Google authentication URL
      window.location.href = <?php echo json_encode($client->createAuthUrl()); ?>;
    }
  </script>

  <script>
    var otpsent = "";
    var myObj = {};

    function validateEmail() {

      var emailInput = document.getElementById('email');
      var email = emailInput.value.trim();
      var emailRegex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/;

      if (!emailRegex.test(email)) {
        return false;
      }


      return true;
    }
    window.onload = function () {

      var verifyPasswordInput = document.getElementById('verifyPassword');
      var password = document.querySelector('input[name="Password"]');
      var errorText = document.getElementById('invalid');
      errorText.textContent = "";



      registerForm.addEventListener('submit', function (event) {
        event.preventDefault(); // Prevent form submission

        if (!validateEmail()) {
          errorText.textContent = "Email is inValid"
          console.log("email is Invalid")
        }
        else {
          if (verifyPasswordInput.value == password.value) {
            errorText.textContent = "";
            var formData = new FormData(registerForm);


            // Convert FormData to JSON object
            formData.forEach(function (value, key) {
              myObj[key] = value;
            });
            console.log(myObj);
            // api calling
            fetch('function.php', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                // Other headers as needed
              },
              body: JSON.stringify({
                operation: 'checkEmail',
                email: myObj['email'],
                // Other data as needed
              }),
            })
              .then(response => response.json())
              .then(data => {
                console.log(data);
                if (data["isExist"] == true) {
                  document.getElementById("noExist").innerText = "User Already Exist";
                  setTimeout(() => {
                    document.getElementById("noExist").innerText = "";
                  }, 5000)
                }

                else if (data["isExist"] == false) {
                  var element = document.getElementsByClassName("display")[1];
                  var element2 = document.getElementsByClassName("display")[0];
                  element2.classList.add('hide');
                  element.classList.remove('hide');
                  element.classList.add('show');
                  otpsent = data["OTP"];


                }
                else {
                  document.getElementById("noExist").innerText = "ERROR";
                  setTimeout(() => {
                    document.getElementById("noExist").innerText = "";
                  }, 5000)
                }

              })
              .catch(error => {
                console.error('Fetch error catch:', error);
              });
            // api phase ended 
          }
          else {
            errorText.textContent = "Both Passwords must be same"

          }
        }
      });


    };



    // Declare a global variable to store the OTP
    var otp = "";

    var errorText = document.getElementById('invalid2');

    // Function to move focus to the next input box
    function moveToNext(currentInput) {
      var maxLength = parseInt(currentInput.getAttribute("maxlength"));
      var currentInputIndex = Array.from(currentInput.parentElement.children).indexOf(currentInput);

      // Check if the entered value is numeric
      if (!isNaN(currentInput.value)) {
        // Move focus to the next input box
        if (currentInput.value.length === maxLength && currentInputIndex < currentInput.parentElement.children.length - 1) {
          currentInput.parentElement.children[currentInputIndex + 1].focus();
        }

        // Check if all input boxes are filled
        if (checkAllInputsFilled()) {
          // Perform OTP validation and store in the global variable
          validateOTP();
        }
      } else {
        // Clear the input if a non-numeric value is entered
        currentInput.value = "";
      }
    }

    // Function to check if all input boxes are filled with numeric values
    function checkAllInputsFilled() {
      var inputs = document.querySelectorAll('input[id^="otpInput"]');
      return Array.from(inputs).every(function (input) {
        return input.value.length === parseInt(input.getAttribute("maxlength")) && !isNaN(input.value);
      });
    }

    // Function to validate the OTP and store it in the global variable
    function validateOTP() {
      const hasProperties = Object.keys(myObj).length > 0;
      if (hasProperties) {
        errorText.textContent = "";
        var inputs = document.querySelectorAll('input[id^="otpInput"]');


        Array.from(inputs).forEach(function (input) {
          otp += input.value;
        });

        if (otp != otpsent) {
          errorText.textContent = "Incorrect OTP";

        } else {
          errorText.textContent = "";
          // api calling
          fetch('function.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              // Other headers as needed
            },
            body: JSON.stringify({
              operation: 'registerUser',
              myObj,
              // Other data as needed
            }),
          })
            .then(response => response.json())
            .then(data => {
            
              // Check if the PHP script indicates a successful operation
              if (data.success) {
                // Redirect on the client side
                window.location.href = 'Deshboard';
              } else {
                console.error('Registration failed:', data.error);
              }

            })
            .catch(error => {
              console.error('Fetch error catch:', error);
            });
          // api phase ended 
        }
        otp = "";
      }
      else {
        console.error('Error is targeting...');
      }


    }
  </script>



  <script>
    const durationInSeconds = 60;
    let timerInterval;

    function updateTimerDisplay(seconds) {
      const minutes = Math.floor(seconds / 60);
      const remainingSeconds = seconds % 60;
      document.getElementById("timer").textContent = ": " + `${minutes}:${remainingSeconds < 10 ? '0' : ''}${remainingSeconds}`;
    }

    function startTimer() {
      let seconds = durationInSeconds;
      updateTimerDisplay(seconds);

      timerInterval = setInterval(function () {
        seconds--;
        updateTimerDisplay(seconds);

        if (seconds <= 0) {
          document.getElementById("resendOtp").disabled = false;


          clearInterval(timerInterval);
          document.getElementById("timer").textContent = ''; // Clear the timer display
        }
      }, 1000);
    }

    function resendOtp() {
      var resendOtp = document.getElementById("resendOtp"); // Declare htag using var or let

      errorText.textContent = ""

      if (!resendOtp.disabled) {
        var inputs = document.querySelectorAll('.otp'); // Use '.otp' instead of 'otp'
        for (let i = 0; i < inputs.length; i++) { // Use 'let i' and iterate over inputs.length
          inputs[i].value = ""; // Access the individual element using inputs[i]
        } // Use ! operator for better readability
        startTimer();
        errorText.textContent = ""
        otpsent = "";
        resendOtp.disabled = true;


        const hasProperties = Object.keys(myObj).length > 0;

        if (hasProperties) {
          // api calling
          fetch('function.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              // Other headers as needed
            },
            body: JSON.stringify({
              operation: 'resendOTP',
              email: myObj['email'],
              // Other data as needed
            }),
          })
            .then(response => response.json())
            .then(data => {
              console.log(data);
              otpsent = data["OTP-resent"];

            }
            )
            .catch(error => {
              console.error('Fetch error catch:', error);
            });
          // api phase ended 
        } else {
          console.error('Error is targeting...');
        }

      }
    }
  </script>


</body>

</html>