<?php

$servername = "localhost"; // Change if necessary
$username = "u528957090_smtadmin3"; // Your database username
$password = "Smarttravel1"; // Your database password
$dbname = "u528957090_SMTTEST01"; // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>


