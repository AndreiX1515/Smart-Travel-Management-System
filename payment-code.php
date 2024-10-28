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
  $proof = $_POST['proof']; // image for proof of payment  

  // Check if a file was uploaded
  if (isset($_FILES['proof']) && $_FILES['proof']['error'] == 0) 
  {
    // Debugging: Print file upload details
    echo "<pre>";
    print_r($_FILES['proof']); // Debugging line
    echo "</pre>";

    $fileTmpPath = $_FILES['proof']['tmp_name'];
    $fileSize = $_FILES['proof']['size'];
    $fileType = $_FILES['proof']['type'];
    $fileExtension = strtolower(pathinfo($_FILES['proof']['name'], PATHINFO_EXTENSION));

    // Allowed file extensions (you can modify this list as needed)
    $allowedExtensions = array('jpg', 'jpeg', 'png', 'gif', 'pdf');

    if (in_array($fileExtension, $allowedExtensions)) 
    {
      // Define the directory where the file will be uploaded
      $uploadFileDir = 'uploads/';
      
      // Create a unique file name using transaction number and current date in mm-dd-yyyy format (no time)
      $newFileName = $transactNo . '-' . date('m-d-Y_H-i') . '.' . $fileExtension;
      $destPath = $uploadFileDir . $newFileName; // Updated to use the new file name

      // Move the file to the upload directory
      if (move_uploaded_file($fileTmpPath, $destPath)) 
      {
        // Start a transaction
        $conn->begin_transaction();
        // Prepare the SQL statement for insertion into the payment table
        $sql1 = "INSERT INTO payment (transactNo, amount, paymentDate, proof) VALUES (?, ?, NOW(), ?)";
        $stmt1 = $conn->prepare($sql1);

        // Check if the statement was prepared successfully
        if (!$stmt1) 
        {
          $_SESSION['status'] = "Booking SQL preparation failed: " . $conn->error;
          $conn->rollback();  // Rollback transaction
          header("Location: client-dashboard.php"); // Redirect to display error
          exit(0);
        }

        // Bind and execute the payment insertion
        $stmt1->bind_param('sis', $transactNo, $downpayment, $destPath);
        
        if ($stmt1->execute()) 
        {
          $_SESSION['status'] = "Payment uploaded and saved successfully!";
          header("Location: client-dashboard.php"); // Redirect on success
          exit(0);
        } 
        else 
        {
          $_SESSION['status'] = "Database error on payment insert: " . $stmt1->error;
          $conn->rollback();  // Rollback the transaction if there is an error
          header("Location: client-dashboard.php"); // Redirect to display error
          exit(0);
        }
      } 
      else 
      {
        $_SESSION['status'] = "File upload failed. Please try again.";
        header("Location: client-dashboard.php"); // Redirect to display error
        exit(0);
      }
    } 
    else 
    {
      $_SESSION['status'] = "Invalid file type. Allowed types: " . implode(", ", $allowedExtensions);
      header("Location: client-dashboard.php"); // Redirect to display error
      exit(0);
    }
  } 
  else 
  {
    // Debugging: Check for file upload errors
    if (isset($_FILES['proof']['error'])) 
    {
      $_SESSION['status'] = "File upload error: " . $_FILES['proof']['error'];
    } 
    else 
    {
      $_SESSION['status'] = "No file uploaded or an error occurred.";
    }
    header("Location: client-dashboard.php"); // Redirect to display error
    exit(0);
  }
}

// After the above code, you can display the error message in the bookingform.php
?>
