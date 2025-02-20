<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "../../conn.php"; // Database connection

if (isset($_POST['attachVisaRequirements'])) 
{
  $transactNo = $_POST['transaction_number'] ?? $_SESSION['transaction_number'] ?? null;
  $accId = $_POST['accId'];

  if (!$transactNo || !$accId) 
  {
    echo "Transaction number or Agent ID is missing.";
    exit;
  }

  $guestIds = $_POST['guestIds'];
  $passports = $_FILES['passports'] ?? null;
  $permits = $_FILES['permits'] ?? null;
  $validIds = $_FILES['validIds'] ?? null;
  $certificates = $_FILES['certificates'] ?? null;
  $guaranteedLetters = $_FILES['guaranteedLetters'] ?? null;

  $allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];
  $maxFileSize = 5 * 1024 * 1024;
  $currentDateTime = date('Y-m-d - H-i-s');

  function sanitizeFileName($fileName) 
  {
    return preg_replace('/[^a-zA-Z0-9_\-.]/', '_', $fileName);
  }

  $conn->begin_transaction(); // Start transaction
  $allFilesUploaded = true;

  for ($i = 0; $i < count($guestIds); $i++) 
  {
    $guestId = $guestIds[$i];
    $uploadDir = $_SERVER['DOCUMENT_ROOT'] . "/SMART-TRAVEL-MANAGEMENT-SYSTEM/Files Uploads/Visa Requirements Uploads" . DIRECTORY_SEPARATOR . $transactNo . DIRECTORY_SEPARATOR . $guestId;
    
    if (!is_dir($uploadDir) && !mkdir($uploadDir, 0777, true)) 
    {
      echo "Failed to create upload directory for guest $guestId.<br>";
      $allFilesUploaded = false;
      break;
    }

    $guestidDate =  $guestId . ' _ ' . $currentDateTime;
    $filePaths = ['passport' => null, 'permit' => null, 'validId' => null, 'certificate' => null, 'guaranteedLetter' => null];
    $files = ['passport' => $passports, 'permit' => $permits, 'validId' => $validIds, 'certificate' => $certificates, 'guaranteedLetter' => $guaranteedLetters];
    
    $atLeastOneFileUploaded = false;
    
    foreach ($files as $fileType => $fileArray) 
    {
      if (!isset($fileArray['tmp_name'][$i]) || empty($fileArray['tmp_name'][$i])) 
      {
        continue; // Skip empty file inputs
      }
        
      $fileTmpPath = $fileArray['tmp_name'][$i];
      $fileName = sanitizeFileName($fileArray['name'][$i]);
      $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
      $filePath = $uploadDir . DIRECTORY_SEPARATOR . ucfirst($fileType) . '_ ' . $guestidDate . '.' . $fileExtension;
      $filePaths[$fileType] = $filePath;
      $fileTypeDetected = mime_content_type($fileTmpPath);
      $fileSize = filesize($fileTmpPath);

      if ($fileArray['error'][$i] !== UPLOAD_ERR_OK) 
      {
        $_SESSION['status'] = "Error uploading file $fileName.";
        $allFilesUploaded = false;
        break;
      }

      if (!in_array($fileTypeDetected, $allowedTypes)) 
      {
        $_SESSION['status'] = "Invalid file type for $fileType. Only JPG, PNG, and PDF allowed.";
        $allFilesUploaded = false;
        break;
      }

      if ($fileSize > $maxFileSize) 
      {
        $_SESSION['status'] = "File $fileName exceeds 5MB limit.";
        $allFilesUploaded = false;
        break;
      }

      if (!move_uploaded_file($fileTmpPath, $filePath)) 
      {
        $_SESSION['status'] = "Error moving file $fileName.";
        $allFilesUploaded = false;
        break;
      }
      
      $atLeastOneFileUploaded = true;
    }

    if (!$atLeastOneFileUploaded) 
    {
      $_SESSION['status'] = "At least one file must be uploaded for guest $guestId.";
      $allFilesUploaded = false;
      break;
    }
    
    if ($allFilesUploaded) 
    {
      date_default_timezone_set('Asia/Taipei');
      $currentTime = date('Y-m-d H:i:s');
      $query = "INSERT INTO visarequirements (guestId, transactNo, accId, passport, permit, validId, certificate, dateSubmitted)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
      $stmt = $conn->prepare($query);
      
      if ($stmt) 
      {
          $stmt->bind_param("isssssss", $guestId, $transactNo, $accId, $filePaths['passport'], $filePaths['permit'],
              $filePaths['validId'], $filePaths['certificate'], $currentTime);
          
          if (!$stmt->execute()) 
          {
            $_SESSION['status'] = "Error executing query: " . $stmt->error;
            $allFilesUploaded = false;
            break;
          }
          $stmt->close();
      } 
      else 
      {
        $_SESSION['status'] = "Error preparing statement: " . $conn->error;
        $allFilesUploaded = false;
        break;
      }
    }
  }

  if ($allFilesUploaded) 
  {
    $conn->commit();
    $_SESSION['status'] = "Visa requirements uploaded successfully.";
  } 
  else 
  {
    $conn->rollback();
    $_SESSION['status'] = "Upload failed. Transaction rolled back.";
  }
  
  header("Location: ../agent-showGuest.php?id=" . htmlspecialchars($transactNo));
  exit();
}
?>
