<?php
// Your PHP code here

if (isset($_SESSION["adminlogin"]) && $_SESSION["adminlogin"] == true && isset($_SESSION["adminusername"])) {
  include("path.php");
  ?>
  <nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
    <div class="navbar-brand-wrapper d-flex align-items-center">
      <a class="navbar-brand brand-logo" href="/E-cab/admin">
        <img src="images/logo.svg" alt="logo" class="logo-dark" />
      </a>
      <a class="navbar-brand brand-logo-mini" href="index.html"><img src="images/logo-mini.svg" alt="logo" /></a>
    </div>
    <div class="navbar-menu-wrapper d-flex align-items-center flex-grow-1">
      <h5 class="mb-0 font-weight-bold d-none d-lg-flex">ADMIN DESHBOARD</h5>
      <ul class="navbar-nav navbar-nav-right ml-auto">


        <li class="nav-item dropdown d-none d-xl-inline-flex user-dropdown">

          <img class="img-xs rounded-circle ml-2" src="images/faces/face8.jpg" alt="Profile image"> <span
            class="m-2 font-weight-normal">
            <?php
            echo $_SESSION["adminusername"];
            ?>
          </span>
          <a class="m-2 btn btn-dark btn-rounded btn-sm" href="/E-cab/admin/logout">Log-out </a>

        </li>
      </ul>
      <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
        data-toggle="offcanvas">
        <span class="icon-menu"></span>
      </button>
    </div>
  </nav>



  <?php
  // Your PHP code here
} else {
  header("Location:/E-cab/admin/login.php");

}
?>