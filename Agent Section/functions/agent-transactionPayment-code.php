<?php
  session_start();
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);
  require "../../conn.php"; // Move up to the parent directory

  if (isset($_POST['payment'])) 
  {
    $transactNo = $_POST['transactNo'];
    $accountId = $_POST['accountId'];
    $paymentTitle = $_POST['paymentTitle'];
    $paymentType = $_POST['paymentType'];
    $amount = $_POST['amount'];
    $proof = $_POST['proof'];

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
        $uploadFileDir = '../../uploads/';
        
        // Create a unique file name using transaction number and current date in mm-dd-yyyy format (no time)
        $newFileName = $transactNo . '-' . date('m-d-Y_H-i') . '.' . $fileExtension;
        $destPath = $uploadFileDir . $newFileName; // Updated to use the new file name

        // Move the file to the upload directory
        if (move_uploaded_file($fileTmpPath, $destPath)) 
        {
          // Start a transaction
          $conn->begin_transaction();
          // Prepare the SQL statement for insertion into the payment table
          $sql1 = "INSERT INTO payment (transactNo, accountId, paymentTitle, paymentType, amount, proof, paymentDate, paymentStatus) 
                    VALUES (?, ?, ?, ?, ?, ?, NOW(), 'Pending')";
          $stmt1 = $conn->prepare($sql1);

          // Check if the statement was prepared successfully
          if (!$stmt1) 
          {
            $_SESSION['status'] = "Booking SQL preparation failed: " . $conn->error;
            $conn->rollback();  // Rollback transaction
            header("Location: ../agent-transactions.php");
            exit(0);
          }

          // Bind and execute the payment insertion
          $stmt1->bind_param('sissis', $transactNo, $accountId, $paymentTitle, $paymentType, $amount, $destPath);
          
          if ($stmt1->execute()) 
          {
            $conn->commit();
            $_SESSION['status'] = "Payment uploaded and saved successfully!";
            header("Location: ../agent-transactions.php");
            exit(0);
          } 
          else 
          {
            $_SESSION['status'] = "Database error on payment insert: " . $stmt1->error;
            $conn->rollback();  // Rollback the transaction if there is an error
            header("Location: ../agent-transactions.php");
            exit(0);
          }
        } 
        else 
        {
          $_SESSION['status'] = "File upload failed. Please try again.";
          header("Location: ../agent-transactions.php");
          exit(0);
        }
      } 
      else 
      {
        $_SESSION['status'] = "Invalid file type. Allowed types: " . implode(", ", $allowedExtensions);
        header("Location: ../agent-transactions.php");
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
      header("Location: ../agent-transactions.php");
      exit(0);
    }
  }
?>
