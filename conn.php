<?php

// Create a connection
$conn = mysqli_connect("localhost", "root", "", "testing");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
} 
?>
