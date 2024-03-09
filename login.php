<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

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
  <title>E-CAB | Login to Book a Ride</title>
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
    <section class="display" style="margin:4% 0px;">
      <div class="ride-text">Login to Continue</div>
      <div class="container" style="height: 415px;">
        <form action="" id="loginForm">
          <div class="form-elements">
            <label for="emain">Email</label>
            <input type="email" name="email" id="email" maxlength="50" required>

          </div>
          <span class="invalid" id="email-warn"></span>

          <div class="form-elements">
            <label for="Password">Password</label>
            <input type="password" name="Password" maxlength="8" required>
          </div>
          <span class="invalid pass"></span>
          <div class="form-elements">
            <a href="#">Forgot Password</a>

          </div>

          <div class="form-elements mt">
            <button type="submit" class="submit noeffct" id="submit">Login</button>


          </div>
        </form>
        <script>
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

            loginForm.addEventListener('submit', function (event) {

              event.preventDefault();
              if (!validateEmail()) {
                document.getElementById('email-warn').innerText = "Email is invalid";

              }
              else {
                document.getElementById('email-warn').innerText = "";
                var formData = new FormData(loginForm);
                formData.forEach(function (value, key) {
                  myObj[key] = value;
                });
                console.log(myObj);

                fetch('function.php', {
                  method: 'POST',
                  headers: {
                    'Content-Type': 'application/json',
                    // Other headers as needed
                  },
                  body: JSON.stringify({
                    operation: 'login',
                    email: myObj['email'],
                    password: myObj['Password'],
                    // Other data as needed
                  }),
                })
                  .then(response => response.json())
                  .then(data => {

               
                    if (data["users"] == 1) {
                    
                      window.location.replace('Deshboard');

                    }
                    else {
                      document.getElementById("user-warn").innerText = "User not found, Check your Email or Password";
                      setTimeout(() => { document.getElementById("user-warn").innerText = ""; }, 5000)
                    }
                  })
                  .catch(error => {
                    console.error('Fetch error catch:', error);
                  });
                // api phase ended 

              }

            })
          }
        </script>
        <div class="form-elements m0">
          <button class="submit" onclick="redirectToGoogle()"><svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="40" height="40"
              viewBox="0,0,256,256">
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
            </svg>Continue with Google!</button>
        </div>
        <div class="form-elements">
          <a href="sign-up" class="center">New User, Sign up now!</a>
          <span class="invalid center" id="user-warn"></span>

        </div>
      </div>
    </section>
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

</body>

</html>