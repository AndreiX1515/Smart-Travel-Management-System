<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "../../conn.php"; // Move up to the parent directory

if (isset($_POST['pay'])) 
{
    $transactNo = $_POST['transactNo'];
    $accountId = $_SESSION['accountId'];
    $amount = $_POST['downpayment'];
    $flightid = $_POST["flightid"] ?? '';

    // Set the timezone (replace 'Asia/Taipei' with your preferred timezone if needed)
    date_default_timezone_set('Asia/Taipei');
    $paymentDate = (new DateTime())->format('Y-m-d H:i:s'); // Current date and time

    // Set the session variable for the current user in MySQL
    $conn->query("SET @current_user_id = $accountId");

    if (isset($_FILES['proofs']) && count($_FILES['proofs']['name']) > 0) 
    {
      $uploadDir = $_SERVER['DOCUMENT_ROOT'] . "/SMART-TRAVEL-MANAGEMENT-SYSTEM/Files Uploads/Payment Uploads" . DIRECTORY_SEPARATOR . $transactNo . DIRECTORY_SEPARATOR;
      $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf'];
      $maxFileSize = 4 * 1024 * 1024; // 4MB per file
      $uploadedFiles = []; // Array to store file paths

      if (!is_dir($uploadDir)) 
      {
        mkdir($uploadDir, 0777, true); // Create the directory if it doesn't exist
      }

      foreach ($_FILES['proofs']['name'] as $key => $fileName) 
      {
        $fileTmpPath = $_FILES['proofs']['tmp_name'][$key];
        $fileSize = $_FILES['proofs']['size'][$key];
        $fileType = $_FILES['proofs']['type'][$key];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (in_array($fileExtension, $allowedExtensions) && $fileSize <= $maxFileSize && $_FILES['proofs']['error'][$key] === UPLOAD_ERR_OK) 
        {
          // Generate a unique name for the file to avoid collisions
          $newFileName = $transactNo . '-' . date('m-d-Y_H-i') . '-' . uniqid() . '.' . $fileExtension;

          $destPath = $uploadDir . $newFileName;

          if (move_uploaded_file($fileTmpPath, $destPath)) 
          {
            // Store only the relative file path (directory + filename) in the array
            $uploadedFiles[] = $uploadDir . $newFileName;
          } 
          else 
          {
            $_SESSION['status'] = "Failed to upload file: $fileName";
            header("Location: ../client-bookingPayment.php");
            exit(0);
          }
        } 
        else 
        {
          $_SESSION['status'] = "File $fileName is invalid or exceeds size limit of 4MB.";
          header("Location: ../client-bookingPayment.php");
          exit(0);
        }
      }

      if (!empty($uploadedFiles)) 
      {
        $conn->begin_transaction();

        // Insert payment information into the payment table, including file paths
        $sql = "INSERT INTO payment (transactNo, accountId, paymentTitle, paymentType, amount, filePath, paymentDate, paymentStatus) 
                VALUES (?, ?, 'Package Payment', 'Downpayment', ?, ?, ?, 'Submitted')";
        $stmt = $conn->prepare($sql);

        if (!$stmt) 
        {
          $_SESSION['status'] = "Booking SQL preparation failed: " . $conn->error;
          $conn->rollback();
          header("Location: ../client-bookingPayment.php");
          exit(0);
        }

        // Loop through uploaded files and insert each file path into the database
        foreach ($uploadedFiles as $filePath) 
        {
          // Bind parameters for each file upload
          $stmt->bind_param('sidss', $transactNo, $accountId, $amount, $destPath, $paymentDate);

          if (!$stmt->execute()) 
          {
            $_SESSION['status'] = "Database error on payment insert: " . $stmt->error;
            $conn->rollback();
            header("Location: ../client-bookingPayment.php");
            exit(0);
          }
        }

        $conn->commit();
        $_SESSION['status'] = "Payment and proof files uploaded successfully!";

        $_SESSION['accountId'] = $accountId;
        $_SESSION['flightid'] = $flightid;

        // Log data for debugging
        error_log("Account ID: " . $accountId);
        error_log("Flight ID: " . $flightid);

        // Validate required fields
        if (empty($accountId)) {
            echo json_encode(["status" => "error", "message" => "No Account ID Found"]);
            exit;
        }

        // Fetch agent and branch details if the user is an agent
        $stmt = $conn->prepare("
            SELECT ag.accountId, ag.agentId, ag.agentCode, ag.agentRole, 
                  b.branchName, b.branchId 
            FROM agent ag
            JOIN branch b ON ag.branchId = b.branchId
            WHERE ag.accountId = ?
        ");
        $stmt->bind_param("i", $accountId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
          // Check if all the required data has values
          if (!empty($row['agentId']) && !empty($row['agentType']) && !empty($row['agentCode']) && 
              !empty($row['agentRole']) && !empty($row['branchId']) && !empty($row['branchName'])) {
      
              // Store agent details in the session
              $_SESSION['agentId'] = $row['agentId'];
              $_SESSION['agentType'] = $row['agentType'];
              $_SESSION['agentCode'] = $row['agentCode'];
              $_SESSION['agentRole'] = $row['agentRole'];
              $_SESSION['branchId'] = $row['branchId'];
              $_SESSION['branchName'] = $row['branchName'];
              
              // You can add debugging or logging here to ensure session data is stored
              // error_log("Session data stored successfully: " . print_r($_SESSION, true));
              
          } else {
              // If any required data is missing, handle the error
              echo json_encode(["status" => "error", "message" => "Missing required agent data."]);
              exit; // Stop further execution if required data is missing
          }
      }
      

        // Close the statement
        $stmt->close();


        header("Location: ../../Agent Section/agent-dashboard copy 2.php");
        exit(0);
      } 
      else 
      {
        $_SESSION['status'] = "No valid files uploaded.";
        header("Location: ../client-bookingpayment.php");
        exit(0);
      }
    } 
    else 
    {
      $_SESSION['status'] = "Proof of payment files are required.";
      header("Location: ../client-bookingPayment.php");
      exit(0);
    }
  }

  echo "MIME Type: " . $mimeType . "<br>";
?>
