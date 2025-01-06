<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "../../conn.php"; // Database connection

if (isset($_POST['attachVisaRequirements'])) {

    // Retrieve transaction number and agent ID from session or form
    $transactNo = $_POST['transaction_number'] ?? $_SESSION['transaction_number'] ?? null;
    $accId = $_POST['accId'];

    if (!$transactNo || !$accId) 
    {
        echo "Transaction number or Agent ID is missing.";
        exit;
    }

    $guestIds = $_POST['guestIds'];
    $passports = $_FILES['passports'];
    $permits = $_FILES['permits'];
    $validIds = $_FILES['validIds'];
    $certificates = $_FILES['certificates'];
    $guaranteedLetters = $_FILES['guaranteedLetters'];

    // Allowed file types and max size (5MB)
    $allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];
    $maxFileSize = 5 * 1024 * 1024;
    
    $currentDateTime = date('Y-m-d - H-i-s'); // Format: YYYY-MM-DD_HH-MM-SS

    function sanitizeFileName($fileName) {
        return preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', $fileName);
    }

    for ($i = 0; $i < count($guestIds); $i++) {
        $guestId = $guestIds[$i];

        // Create upload directory
        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . "/SMART-TRAVEL-MANAGEMENT-SYSTEM/Files Uploads/Visa Requirements Uploads" . DIRECTORY_SEPARATOR . $transactNo . DIRECTORY_SEPARATOR . $guestId;

        if (!is_dir($uploadDir) && !mkdir($uploadDir, 0777, true)) {
            echo "Failed to create upload directory for guest $guestId.<br>";
            continue;
        }
        
        // Concatenate guest ID with the current date and time
        $guestidDate =  $guestId . ' _ ' . $currentDateTime;

        // File paths with dynamic extension
        $filePaths = [
            'passport' => '',
            'permit' => '',
            'validId' => '',
            'certificate' => '',
            'guaranteedLetter' => ''
        ];

        $files = [
            'passport' => $passports,
            'permit' => $permits,
            'validId' => $validIds,
            'certificate' => $certificates,
            'guaranteedLetter' => $guaranteedLetters
        ];

        $fileUploaded = true;

        foreach ($files as $fileType => $fileArray) {
            $fileTmpPath = $fileArray['tmp_name'][$i];
            $fileName = sanitizeFileName($fileArray['name'][$i]);
        
            // Get the file extension
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
            // Generate the destination file path dynamically based on the original extension
            $filePath = $uploadDir . DIRECTORY_SEPARATOR . ucfirst($fileType) . '_ ' . $guestidDate . '.' . $fileExtension;
        
            // Store the dynamically created path in the array
            $filePaths[$fileType] = $filePath;
        
            $fileTypeDetected = mime_content_type($fileTmpPath);
            $fileSize = filesize($fileTmpPath);
        
            if ($fileArray['error'][$i] !== UPLOAD_ERR_OK) {
                $_SESSION['status'] = "Error uploading file $fileName: " . $fileArray['error'][$i] . "<br>";
                $fileUploaded = false;
                break;
            }
        
            if (!in_array($fileTypeDetected, $allowedTypes)) {
                $_SESSION['status'] = "Invalid file type for $fileType. Only JPG, PNG, and PDF files are allowed.<br>";
                $fileUploaded = false;
                break;
            }
        
            if ($fileSize > $maxFileSize) {
                $_SESSION['status'] = "File $fileName exceeds the maximum allowed size (5MB).<br>";
                $fileUploaded = false;
                break;
            }
        
            if (!move_uploaded_file($fileTmpPath, $filePath)) {
                $_SESSION['status'] = "Error moving file $fileName to destination.<br>";
                $fileUploaded = false;
                break;
            }
        }

        if ($fileUploaded) {
            date_default_timezone_set('Asia/Taipei');
            $currentTime = date('Y-m-d H:i:s');

            $query = "INSERT INTO visarequirements (guestId, transactNo, accId, passport, permit, validId, certificate, dateSubmitted)
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $conn->prepare($query);
            if ($stmt) {
                $stmt->bind_param(
                    "isssssss",
                    $guestId, $transactNo, $accId,
                    $filePaths['passport'], $filePaths['permit'],
                    $filePaths['validId'], $filePaths['certificate'], $currentTime
                );

                if ($stmt->execute()) {
                    $_SESSION['status'] = "Visa requirements uploaded successfully for guest $guestId.";
                } else {
                    $_SESSION['status'] = "Error executing query: " . $stmt->error . "<br>";
                }
                $stmt->close();
            } else {
                $_SESSION['status'] = "Error preparing statement: " . $conn->error . "<br>";
            }
        }
    }

    $_SESSION['status'] = "Visa requirements have been successfully uploaded.";
    header("Location: ../agent-showGuest.php?id=" . htmlspecialchars($transactNo));
    exit();
}
?>
