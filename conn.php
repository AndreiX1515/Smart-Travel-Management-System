<?php
  $conn = mysqli_connect("localhost", "root", "", "project");

  if ($conn) {
    echo "Connection Failed..." .mysqli_connect_error() or die;
  }
?>