<?php
require "conn.php";

if (isset($_POST['submit'])) 
{
  $fname = $_POST['fname'];
  $mname = $_POST['mname'];
  $lname = $_POST['lname'];
  $suffix = $_POST['suffix'];

  $houseNo = $_POST['houseNo'];
  $street = $_POST['street'];
  $subdivision = $_POST['subdivision'];
  $barangay = $_POST['barangay'];
  $city = $_POST['city'];
  $country = $_POST['country'];

  $age = $_POST['age'];
  $birthdate = $_POST['birthdate'];

  $passportNo = $_POST['passportNo'];
  $visaStatus = $_POST['visaStatus'];

  $email = $_POST['email'];
  $contactNo = $_POST['contactNo'];

  // Assuming you're getting the package name from the form
  $packageName = $_POST['packageName'];

  // Correct the SQL query by removing the trailing comma and ensuring it matches the fields
  $sql1 = "INSERT INTO booking (packageName, firstName, lastName, middleName, suffix, houseNo, street, 
  subdivision, barangay, city, country, age, birthdate, passportNo, visaStatus, contactNo, emailAddress) 
  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

  // Prepare the statement and bind the parameters
  $stmt1 = $conn->prepare($sql1);

  // Ensure the parameters match the SQL query
  $stmt1->bind_param('ssssssssssissssis', 
    $packageName, $fname, $lname, $mname, $suffix, 
    $houseNo, $street, $subdivision, $barangay, $city, $country, 
    $age, $birthdate, $passportNo, $visaStatus, $contactNo, $email);

  // Execute the query
  if ($stmt1->execute()) 
  {
    echo "Booking successfully inserted.";
  } 
  else 
  {
    echo "Database error: " . $stmt1->error;
  }
}
?>
