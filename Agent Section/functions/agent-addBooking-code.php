<?php
  session_start();
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);
  require "../../conn.php"; // Move up to the parent directory

  if (isset($_POST['bookNow'])) 
  {
    $accountId = $_SESSION['accountId'];
    $agentId = $_SESSION['agentId'];
    $fName = $_POST['fName'];  
    $mName = $_POST['mName'];  
    $lName = $_POST['lName'];  
    $suffix = $_POST['suffix'];
    $countryCode = $_POST['countryCode']; 
    $contactNo = $_POST['contactNo'];
    $email = $_POST['email'];
    $packageId = $_POST['packageName'];
    $flightId = $_POST['flightDate'];
    $totalPax = $_POST['totalPax'];
    $totalPrice = $_POST['totalPrice'];

    // Get the last bookingId and increment it for the new transaction
    $result = $conn->query("SELECT MAX(bookingId) AS lastBookingId FROM booking");
    if (!$result) 
    {
      $_SESSION['status'] = "Error fetching last booking ID: " . $conn->error;
      header("Location: agent-addBooking.php");
      exit(0);
    }

    $row = $result->fetch_assoc();
    $newBookingId = ($row && $row['lastBookingId'] !== null) ? $row['lastBookingId'] + 1 : 1;
    $formattedCounter = str_pad($newBookingId, 6, '0', STR_PAD_LEFT);
    $transactNo = $agentId. '-' . $formattedCounter;

    // Check if "Own Flight" is selected (value is 'Null')
    if ($flightId === 'Null') 
    {
      $flightId = NULL; // Set flightId to NULL if "Own Flight" is selected
    }

    // Start a transaction
    $conn->begin_transaction();

    // Prepare the SQL statement for insertion into the booking table
    $sql1 = "INSERT INTO booking (accountId, transactNo, agentId, flightId, packageId, fName, lName, mName, suffix, countryCode, contactNo, 
    email, pax, totalPrice, status, bookingDate) VALUES 
    (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending', NOW())";
    $stmt1 = $conn->prepare($sql1);

    // Check if the statement was prepared successfully
    if (!$stmt1) 
    {
      $_SESSION['status'] = "Booking SQL preparation failed: " . $conn->error;
      $conn->rollback();  // Rollback transaction
      header("Location: agent-addBooking.php");
      exit(0);
    }

    // Bind and execute the booking insertion
    // $accountId = $_SESSION['accountid']; // Assuming the user is logged in
    $stmt1->bind_param('issiisssssssid', $accountId, $transactNo, $agentId, $flightId, $packageId, $fName, $lName, $mName, $suffix, 
    $countryCode, $contactNo, $email, $totalPax, $totalPrice);
    
    if (!$stmt1->execute()) 
    {
      $_SESSION['status'] = "Database error on booking insert: " . $stmt1->error;
      $conn->rollback();  // Rollback the transaction if there is an error
      header("Location: ../agent-addBooking.php");
      exit(0);
    }

    // If no errors, commit the transaction
    $conn->commit();

    // Optionally redirect or provide a success message
    $_SESSION['status'] = "Booking successful!";
    header("Location: ../agent-addBooking.php");
    exit(0);
  }

?>