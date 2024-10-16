<?php
require "conn.php";
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_POST['pay'])) 
{
  $transactNo = $_POST['transactNo'];  
  $downpayment = $_POST['downpayment'];  

  // Check if a file was uploaded
  if (isset($_FILES['proof']) && $_FILES['proof']['error'] == 0) 
  {
    $fileTmpPath = $_FILES['proof']['tmp_name'];
    $fileName = $_FILES['proof']['name'];
    $fileSize = $_FILES['proof']['size'];
    $fileType = $_FILES['proof']['type'];
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    // Allowed file extensions (you can modify this list as needed)
    $allowedExtensions = array('jpg', 'jpeg', 'png', 'PNG', 'gif', 'pdf');

    if (in_array($fileExtension, $allowedExtensions)) 
    {
      // Define the directory where the file will be uploaded
      $uploadFileDir = 'uploads/';
      $destPath = $uploadFileDir . $fileName;

      // Move the file to the upload directory
      if (move_uploaded_file($fileTmpPath, $destPath)) 
      {
        // Prepare the SQL statement for insertion into the payment table
        $sql1 = "INSERT INTO payment (transactNo, amount, paymentDate, proof) VALUES (?, ?, NOW(), ?)";
        $stmt1 = $conn->prepare($sql1);

        // Check if the statement was prepared successfully
        if (!$stmt1) 
        {
          $_SESSION['status'] = "Booking SQL preparation failed: " . $conn->error;
          $conn->rollback();  // Rollback transaction
           header("Location: bookingform.php"); // Redirect to display error
          exit(0);
        }

        // Bind and execute the payment insertion
        $stmt1->bind_param('sis', $transactNo, $downpayment, $destPath);
        
        if ($stmt1->execute()) 
        {
          $_SESSION['status'] = "Payment uploaded and saved successfully!";
           header("Location: bookingform.php"); // Redirect on success
          exit(0);
        } 
        else 
        {
          $_SESSION['status'] = "Database error on payment insert: " . $stmt1->error;
          $conn->rollback();  // Rollback the transaction if there is an error
          header("Location: bookingform.php"); // Redirect to display error
          exit(0);
        }
      } 
      else 
      {
        $_SESSION['status'] = "File upload failed. Please try again.";
         header("Location: bookingform.php"); // Redirect to display error
        exit(0);
      }
    } 
    else 
    {
      $_SESSION['status'] = "Invalid file type. Allowed types: " . implode(", ", $allowedExtensions);
       header("Location: bookingform.php"); // Redirect to display error
      exit(0);
    }
  } 
  else 
  {
    $_SESSION['status'] = "No file uploaded or an error occurred.";
     header("Location: bookingform.php"); // Redirect to display error
    exit(0);
  }
}

// After the above code, you can display the error message in the bookingform.php
?>
