<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Driver Login</title>
    <link rel="stylesheet" href="assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="shortcut icon" href="assets/images/favicon.png" />
    <style>
        .ptext {
            color: red;
            position: absolute;
        }

        .div-container {
            overflow: hidden;
            transition: height 0.3s ease-in-out;
        }

        
        .div2 {
            display: none;
            padding: 10px;
            background-color: #eee;
            margin-bottom: 10px;
            border-radius: 5px;
            transition: opacity 0.3s ease-in-out;
        }

        .show {
            display: block;
            opacity: 1;
        }

        .hide {
            opacity: 0;
        }
    </style>
</head>

<body>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="row w-100 m-0">
                <div class="content-wrapper full-page-wrapper d-flex align-items-center auth lock-full-bg">
                    <div class="card col-lg-4 mx-auto">
                        <div class="card-body px-5 py-5">
                            <h3 class="card-title text-left mb-3">Reset Your Password</h3>

                            <div class="div-container">
                                <div class="div1">
                                    <div class="form-group">
                                        <label>Enter Your Email *</label>
                                        <input type="email" name="email" id="forgot_email"
                                            class="form-control p_input text-white" required>
                                        <p class="ptext" id="myt"></p>
                                    </div>
                                    <div class="form-group">
                                        <div class="text-center">
                                            <input type="hidden" name="driverlogin" value="yes">
                                            <button class="btn btn-info btn-block enter-btn m-2" id="send"
                                                onclick="send_otp()">Send
                                                OTP</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="div2">
                                    <div class="form-group">
                                        <label>Enter OTP *</label>
                                        <input type="text" name="OTP" id="otp" class="form-control p_input text-white">
                                    </div>
                                    <div class="form-group">
                                        <div class="text-center">
                                            <input type="hidden" name="driverlogin" value="yes">
                                            <button class="btn btn-success btn-block enter-btn"
                                                onclick="varify_otp()">Verify OTP</button>
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

    <script>
        function isValidEmail(email) {
            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }

        function showDiv2() {
            var elem = document.getElementsByClassName("div2");
            for (var i = 0; i < elem.length; i++) {
                elem[i].classList.add('show');
                elem[i].classList.remove('hide');
            }
        }

        function showDiv1() {
            var elems = document.getElementsByClassName("div1");
            for (var i = 0; i < elems.length; i++) {
                elems[i].classList.add('show');
                elems[i].classList.remove('hide');
            }
        }

        function hideDiv1() {
            var elems = document.getElementsByClassName("div1");
            for (var i = 0; i < elems.length; i++) {
                elems[i].classList.add('hide');
                elems[i].classList.remove('show');
            }
        }

        function checkemail(inputValue) {
            var postData = {
                key1: inputValue,
                key2: "div1"
            };
            $.ajax({
                type: "POST",
                url: "function.php",
                data: postData,
                success: function (result) {
                    if (result != "") {
                        showDiv1();
                    } else {
                        showDiv2();
                    }
                },
            });
        }

        setTimeout(function () {
            document.getElementById("myt").innerText = "";
        }, 5000);

        var love = "no";

        function send_otp() {
            var inputField = document.getElementById("forgot_email");
            var sendButton = document.getElementById("send");
            var inputValue = inputField.value;

            if (love == "yes") {
                hideDiv1();
                checkemail(inputValue);
            }

            if (isValidEmail(inputField.value)) {
                hideDiv1();
                checkemail(inputValue);
            }

            inputField.addEventListener("input", function () {
                if (inputField.value === "") {
                    hideDiv1();
                    sendButton.disabled = true;
                    inputField.placeholder = "Please Enter Your Email";
                    document.getElementById("myt").innerText = "Please Enter Your Email.";
                } else if (!isValidEmail(inputField.value)) {
                    sendButton.disabled = true;
                } else {
                    love = "yes";
                    sendButton.disabled = false;
                }
            });

            inputField.dispatchEvent(new Event("input"));
        }

        function varify_otp() {
            document.getElementById("otp").value = inputValue;
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="assets/vendors/js/vendor.bundle.base.js"></script>
    <script src="assets/js/off-canvas.js"></script>
    <script src="assets/js/hoverable-collapse.js"></script>
    <script src="assets/js/misc.js"></script>
    <script src="assets/js/settings.js"></script>
    <script src="assets/js/todolist.js"></script>
</body>

</html>