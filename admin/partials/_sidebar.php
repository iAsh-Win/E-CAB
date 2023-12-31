<?php
// Your PHP code here

if (isset($_SESSION["adminlogin"]) && $_SESSION["adminlogin"] == true && isset($_SESSION["adminusername"])) {
  ?>
  <nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
      <li class="nav-item nav-profile">
        <a href="#" class="nav-link">
          <div class="profile-image">
            <img class="img-xs rounded-circle" src="images/faces/face8.jpg" alt="profile image">
            <div class="dot-indicator bg-success"></div>
          </div>
          <div class="text-wrapper">
            <p class="profile-name">
              <?php
              echo $_SESSION["adminusername"];
              ?>
            </p>
            <p class="designation">Administrator</p>
          </div>
        </a>
      </li>

      <li class="nav-item nav-category">
        <span class="nav-link">Dashboard</span>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/E-cab/admin">
          <span class="menu-title">Dashboard</span>
          <i class="icon-screen-desktop menu-icon"></i>
        </a>
      </li>
      <li class="nav-item nav-category"><span class="nav-link">CUSTOMERS</span></li>


      <li class="nav-item">
        <a class="nav-link" href="pages/icons/simple-line-icons.html">
          <span class="menu-title">Manage Customers</span>
          <i class="icon-globe menu-icon"></i>
        </a>
      </li>


      <li class="nav-item nav-category"><span class="nav-link">DRIVERS</span></li>

      <li class="nav-item">
        <a class="nav-link" href="pages/icons/simple-line-icons.html">
          <span class="menu-title">Manage Drivers</span>
          <i class="icon-globe menu-icon"></i>
        </a>

      <li class="nav-item nav-category"><span class="nav-link">BOOKINGS</span></li>

      <li class="nav-item">
        <a class="nav-link" href="pages/icons/simple-line-icons.html">
          <span class="menu-title">Manage Bookings</span>
          <i class="icon-globe menu-icon"></i>
        </a>


      <li class="nav-item nav-category"><span class="nav-link">CABS</span></li>
      <li class="nav-item">
        <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
          <span class="menu-title">All Cabs</span>
          <i class="icon-layers menu-icon"></i>
        </a>
        <div class="collapse" id="ui-basic">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item"> <a class="nav-link" href="/E-cab/admin">BMW</a></li>
            <li class="nav-item"> <a class="nav-link" href="/E-cab/admin">MERCIDISC</a></li>
          </ul>
        </div>
      </li>


      <li class="nav-item pro-upgrade">
        <span class="nav-link">
          <a class="btn btn-block px-0 btn-rounded btn-upgrade"
            href="#" > <i
              class="icon-badge mx-2"></i> Ashwin</a>
        </span>
      </li>
    </ul>
  </nav>
  <?php
  // Your PHP code here
} else {
  header("Location:/E-cab/admin/login.php");

}
?>