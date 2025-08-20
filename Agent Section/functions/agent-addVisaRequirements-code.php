<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "../../conn.php"; // DB connection

if (isset($_POST['attachVisaRequirements'])) {
  $transactNo = $_POST['transaction_number'] ?? $_SESSION['transaction_number'] ?? null;
  $accId = $_POST['accId'] ?? null;
  $guestIds = $_POST['guestIds'] ?? [];

  $allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];
  $maxFileSize = 5 * 1024 * 1024; // 5MB
  date_default_timezone_set('Asia/Taipei');
  $currentTime = date('Y-m-d H:i:s');

  if (!$transactNo || !$accId) {
    $_SESSION['status'] = "Transaction number or Agent ID missing.";
    header("Location: ../agent-showGuest.php?id=" . htmlspecialchars($transactNo));
    exit;
  }

  if (empty($guestIds) || !is_array($guestIds)) {
    $_SESSION['status'] = "No guests selected for upload.";
    header("Location: ../agent-showGuest.php?id=" . htmlspecialchars($transactNo));
    exit;
  }

  $documentTypes = [
    'passport',
    'permit',
    'validId',
    'certificate',
    'guaranteedLetter',
    'visaApplicationForm',
    'picture',
    'itinerary',
    'others' // include others now
  ];

  function sanitizeFileName($fileName) {
    return preg_replace('/[^a-zA-Z0-9_\-.]/', '_', $fileName);
  }

  $conn->begin_transaction();
  $allFilesUploaded = true;

  foreach ($guestIds as $guestId) {
    $uploadDir = $_SERVER['DOCUMENT_ROOT'] . "/SMART-TRAVEL-MANAGEMENT-SYSTEM/Files Uploads/Visa Requirements Uploads/$transactNo/$guestId";

    if (!is_dir($uploadDir) && !mkdir($uploadDir, 0777, true)) {
      $_SESSION['status'] = "Failed to create directory for guest $guestId.";
      $allFilesUploaded = false;
      break;
    }

    $guestidDate = $guestId . ' _ ' . date('Y-m-d - H-i-s');
    $atLeastOneFileUploaded = false;

    foreach ($documentTypes as $docType) {
      if (!isset($_FILES[$docType]['tmp_name'][$guestId])) {
        continue;
      }

      // Handle normal docs (passport, validId, guaranteedLetter, etc.)
      if (!in_array($docType, ['certificate', 'permit', 'others'])) {
        foreach ($_FILES[$docType]['tmp_name'][$guestId] as $key => $fileTmpPath) {
          if (empty($fileTmpPath)) continue;

          $rawFileName = $_FILES[$docType]['name'][$guestId][$key];
          $fileName = sanitizeFileName($rawFileName);
          $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
          $uniqueSuffix = uniqid('', true);
          $filePath = $uploadDir . DIRECTORY_SEPARATOR . ucfirst($docType) . "_{$guestidDate}_$uniqueSuffix.$fileExtension";

          $fileTypeDetected = mime_content_type($fileTmpPath);
          $fileSize = filesize($fileTmpPath);

          if ($_FILES[$docType]['error'][$guestId][$key] !== UPLOAD_ERR_OK ||
              !in_array($fileTypeDetected, $allowedTypes) ||
              $fileSize > $maxFileSize ||
              !move_uploaded_file($fileTmpPath, $filePath)) {
            $_SESSION['status'] = "Error uploading $fileName.";
            $allFilesUploaded = false;
            break 2;
          }

          $atLeastOneFileUploaded = true;

          $stmt = $conn->prepare("INSERT INTO visarequirements 
            (guestId, transactNo, accId, fileType, filePath, dateSubmitted, docSubType)
            VALUES (?, ?, ?, ?, ?, ?, NULL)");
          if (!$stmt) {
            $_SESSION['status'] = "SQL prepare error: " . $conn->error;
            $allFilesUploaded = false;
            break 2;
          }

          $stmt->bind_param("isssss", $guestId, $transactNo, $accId, $docType, $filePath, $currentTime);
          if (!$stmt->execute()) {
            $_SESSION['status'] = "SQL error: " . $stmt->error;
            $allFilesUploaded = false;
            $stmt->close();
            break 2;
          }
          $stmt->close();
        }
      }

      // Handle certificate/permit with subtypes
      if (in_array($docType, ['certificate', 'permit'])) {
        foreach ($_FILES[$docType]['tmp_name'][$guestId] as $subType => $tmpFiles) {
          foreach ($tmpFiles as $key => $fileTmpPath) {
            if (empty($fileTmpPath)) continue;

            $rawFileName = $_FILES[$docType]['name'][$guestId][$subType][$key];
            $fileName = sanitizeFileName($rawFileName);
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $uniqueSuffix = uniqid('', true);
            $filePath = $uploadDir . DIRECTORY_SEPARATOR . ucfirst($docType) . "_{$guestidDate}_$uniqueSuffix.$fileExtension";

            $fileTypeDetected = mime_content_type($fileTmpPath);
            $fileSize = filesize($fileTmpPath);

            if ($_FILES[$docType]['error'][$guestId][$subType][$key] !== UPLOAD_ERR_OK ||
                !in_array($fileTypeDetected, $allowedTypes) ||
                $fileSize > $maxFileSize ||
                !move_uploaded_file($fileTmpPath, $filePath)) {
              $_SESSION['status'] = "Error uploading $fileName.";
              $allFilesUploaded = false;
              break 3;
            }

            $atLeastOneFileUploaded = true;
            $subTypeValue = (!empty($subType) && !is_numeric($subType)) ? $subType : null;

            $stmt = $conn->prepare("INSERT INTO visarequirements 
              (guestId, transactNo, accId, fileType, filePath, dateSubmitted, docSubType)
              VALUES (?, ?, ?, ?, ?, ?, ?)");
            if (!$stmt) {
              $_SESSION['status'] = "SQL prepare error: " . $conn->error;
              $allFilesUploaded = false;
              break 3;
            }

            $stmt->bind_param("issssss", $guestId, $transactNo, $accId, $docType, $filePath, $currentTime, $subTypeValue);
            if (!$stmt->execute()) {
              $_SESSION['status'] = "SQL error: " . $stmt->error;
              $allFilesUploaded = false;
              $stmt->close();
              break 3;
            }
            $stmt->close();
          }
        }
      }

      // Handle "Others" (Option 1: customName[] + files[])
      if ($docType === "others") {
        $customNames = $_POST['others'][$guestId]['customName'] ?? [];
        $filesTmp    = $_FILES['others']['tmp_name'][$guestId]['files'] ?? [];
        $filesName   = $_FILES['others']['name'][$guestId]['files'] ?? [];
        $filesError  = $_FILES['others']['error'][$guestId]['files'] ?? [];
        $filesSize   = $_FILES['others']['size'][$guestId]['files'] ?? [];

        foreach ($filesTmp as $key => $fileTmpPath) {
          if (empty($fileTmpPath)) continue;

          $rawFileName = $filesName[$key];
          $fileName = sanitizeFileName($rawFileName);
          $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
          $uniqueSuffix = uniqid('', true);
          $filePath = $uploadDir . DIRECTORY_SEPARATOR . "Others_{$guestidDate}_$uniqueSuffix.$fileExtension";

          $fileTypeDetected = mime_content_type($fileTmpPath);
          $fileSize = filesize($fileTmpPath);

          if ($filesError[$key] !== UPLOAD_ERR_OK ||
              !in_array($fileTypeDetected, $allowedTypes) ||
              $fileSize > $maxFileSize ||
              !move_uploaded_file($fileTmpPath, $filePath)) {
            $_SESSION['status'] = "Error uploading $fileName.";
            $allFilesUploaded = false;
            break 2;
          }

          $atLeastOneFileUploaded = true;
          $description = $customNames[$key] ?? 'Other Document';

          $stmt = $conn->prepare("INSERT INTO visarequirements 
              (guestId, transactNo, accId, fileType, filePath, dateSubmitted, docSubType)
              VALUES (?, ?, ?, ?, ?, ?, ?)");
          if (!$stmt) {
            $_SESSION['status'] = "SQL prepare error: " . $conn->error;
            $allFilesUploaded = false;
            break 2;
          }

          $stmt->bind_param("issssss", $guestId, $transactNo, $accId, $docType, $filePath, $currentTime, $description);
          if (!$stmt->execute()) {
            $_SESSION['status'] = "SQL error: " . $stmt->error;
            $allFilesUploaded = false;
            $stmt->close();
            break 2;
          }
          $stmt->close();
        }
      }
    }

    if (!$atLeastOneFileUploaded) {
      $_SESSION['status'] = "At least one file must be uploaded for guest $guestId.";
      $allFilesUploaded = false;
      break;
    }
  }

  if ($allFilesUploaded) {
    $conn->commit();
    $_SESSION['status'] = "Visa requirements uploaded successfully.";
  } else {
    $conn->rollback();
    if (!isset($_SESSION['status'])) {
      $_SESSION['status'] = "Upload failed. Transaction rolled back.";
    }
  }

  header("Location: ../agent-showGuest.php?id=" . htmlspecialchars($transactNo));
  exit();
}
?>
