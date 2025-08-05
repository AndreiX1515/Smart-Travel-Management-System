<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "../../conn.php"; // DB connection

if (isset($_POST['attachVisaRequirements'])) 
{
  $transactNo = $_POST['transaction_number'] ?? $_SESSION['transaction_number'] ?? null;
  $accId = $_POST['accId'] ?? null;
  $guestIds = $_POST['guestIds'] ?? [];
  $docSubTypes = $_POST['docSubType'] ?? []; // sub-doc types for permit, certificate

  $allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];
  $maxFileSize = 5 * 1024 * 1024; // 5MB max
  date_default_timezone_set('Asia/Taipei');
  $currentTime = date('Y-m-d H:i:s');

  if (!$transactNo || !$accId) 
  {
    echo "Transaction number or Account ID missing.";
    exit;
  }

  $documentTypes = ['passport', 'permit', 'validId', 'certificate', 'guaranteedLetter'];

  function sanitizeFileName($fileName) 
  {
    return preg_replace('/[^a-zA-Z0-9_\-.]/', '_', $fileName);
  }

  $conn->begin_transaction();
  $allFilesUploaded = true;

  // Optional: Debug logs
  // file_put_contents('debug_post_client.txt', print_r($_POST, true));
  // file_put_contents('debug_files_client.txt', print_r($_FILES, true));

  foreach ($guestIds as $guestId) 
  {
    $uploadDir = $_SERVER['DOCUMENT_ROOT'] . "/SMART-TRAVEL-MANAGEMENT-SYSTEM/Files Uploads/Visa Requirements Uploads/$transactNo/$guestId";
    
    if (!is_dir($uploadDir) && !mkdir($uploadDir, 0777, true)) 
    {
      $_SESSION['status'] = "Failed to create directory for guest $guestId.";
      $allFilesUploaded = false;
      break;
    }

    $guestidDate = $guestId . ' _ ' . date('Y-m-d - H-i-s');
    $atLeastOneFileUploaded = false;

    foreach ($documentTypes as $docType) 
    {
      if (!isset($_FILES[$docType]['tmp_name'][$guestId])) continue;

      foreach ($_FILES[$docType]['tmp_name'][$guestId] as $subType => $tmpFiles) 
      {
        foreach ($tmpFiles as $key => $fileTmpPath) 
        {
          if (empty($fileTmpPath)) continue;

          $rawFileName = $_FILES[$docType]['name'][$guestId][$subType][$key];
          $fileName = sanitizeFileName($rawFileName);
          $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
          $uniqueSuffix = uniqid('', true);
          $filePath = $uploadDir . DIRECTORY_SEPARATOR . ucfirst($docType) . "_{$guestidDate}_$uniqueSuffix.$fileExtension";

          $fileTypeDetected = mime_content_type($fileTmpPath);
          $fileSize = filesize($fileTmpPath);

          if ($_FILES[$docType]['error'][$guestId][$subType][$key] !== UPLOAD_ERR_OK) 
          {
            $_SESSION['status'] = "Error uploading $fileName.";
            $allFilesUploaded = false;
            break 3;
          }

          if (!in_array($fileTypeDetected, $allowedTypes)) 
          {
            $_SESSION['status'] = "Invalid file type: $fileName.";
            $allFilesUploaded = false;
            break 3;
          }

          if ($fileSize > $maxFileSize) 
          {
            $_SESSION['status'] = "$fileName exceeds 5MB.";
            $allFilesUploaded = false;
            break 3;
          }

          if (!move_uploaded_file($fileTmpPath, $filePath)) 
          {
            $_SESSION['status'] = "Failed to move $fileName.";
            $allFilesUploaded = false;
            break 3;
          }

          $atLeastOneFileUploaded = true;

          $subTypeValue = $subType;
          // Insert record into `visarequirements` table
          $query = "INSERT INTO visarequirements (guestId, transactNo, accId, fileType, filePath, dateSubmitted, docSubType)
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
          $stmt = $conn->prepare($query);

          if ($stmt) 
          {
            $stmt->bind_param("issssss", $guestId, $transactNo, $accId, $docType, $filePath, $currentTime, $subTypeValue);
            if (!$stmt->execute()) 
            {
              $_SESSION['status'] = "SQL error: " . $stmt->error;
              $allFilesUploaded = false;
              break 3;
            }
            $stmt->close();
          } 
          else 
          {
            $_SESSION['status'] = "Prepare failed: " . $conn->error;
            $allFilesUploaded = false;
            break 3;
          }
        }
      }
    }

    if (!$atLeastOneFileUploaded) 
    {
      $_SESSION['status'] = "At least one file must be uploaded for guest $guestId.";
      $allFilesUploaded = false;
      break;
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

  header("Location: ../client-transactionInfo.php?id=" . htmlspecialchars($transactNo));
  exit();
}
?>
