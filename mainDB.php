<?php
   $conn = mysqli_connect("localhost", "root", "", "e-cab");
   if (!$conn) {
       die("" . mysqli_connect_error());
   }
?>