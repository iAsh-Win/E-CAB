
<?php
  // Your PHP code here
 
  if(isset($_SESSION["adminlogin"]) && $_SESSION["adminlogin"]==true &&  isset($_SESSION["adminusername"]))
  {
?><footer class="footer">
  <div class="d-sm-flex justify-content-center justify-content-sm-between">
  <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Copyright © E-Cab <?php
      // Your PHP code here
     echo date("Y");
    ?></span>

<span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Created by HDA </span>
   
  </div>
</footer>
<?php
  // Your PHP code here
  }
  else{
    header("Location:/E-cab/admin/login.php");
            
  }
  ?>
