<?php
// upload.php - File upload handler with database metadata storage
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../../../conn copy.php';


// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// ===========================================
// PROCESS 2: CONFIGURATION AND VALIDATION SETUP
// ===========================================
// Upload directory configuration
$uploadDir = 'uploads/';
$maxFileSize = 10 * 1024 * 1024; // 10MB maximum file size
$allowedTypes = [
    'image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp',
    'application/pdf',
    'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    'text/plain', 'text/csv'
];

// Create upload directory if it doesn't exist
if (!is_dir($uploadDir)) {
    if (!mkdir($uploadDir, 0755, true)) {
        echo json_encode(['success' => false, 'message' => 'Failed to create upload directory']);
        exit;
    }
}

// ===========================================
// PROCESS 3: REQUEST DATA VALIDATION
// ===========================================
// Check if files were uploaded
if (!isset($_FILES['files']) || empty($_FILES['files']['name'][0])) {
    echo json_encode(['success' => false, 'message' => 'No files uploaded']);
    exit;
}

// Validate required metadata from modal form
$accId = isset($_POST['accId']) ? (int)$_POST['accId'] : 0;
$guestId = isset($_POST['guestId']) ? (int)$_POST['guestId'] : 0;
$fileType = isset($_POST['fileType']) ? trim($_POST['fileType']) : '';

// Validate required fields
if ($accId <= 0) {
    echo json_encode(['success' => false, 'message' => 'Account ID is required']);
    exit;
}

if ($guestId <= 0) {
    echo json_encode(['success' => false, 'message' => 'Guest ID is required']);
    exit;
}

if (empty($fileType)) {
    echo json_encode(['success' => false, 'message' => 'Document type must be selected']);
    exit;
}

// ===========================================
// PROCESS 4: GENERATE TRANSACTION NUMBER
// ===========================================
// Generate unique transaction number for this upload session
$transactNo = 'TXN' . date('Ymd') . '_' . uniqid();

// ===========================================
// PROCESS 5: FILE PROCESSING AND UPLOAD
// ===========================================
$uploadedFiles = [];
$errors = [];
$files = $_FILES['files'];
$fileCount = count($files['name']);

// Begin database transaction for data consistency
$conn->autocommit(false);

try {
    // Process each uploaded file
    for ($i = 0; $i < $fileCount; $i++) {
        // ===========================================
        // PROCESS 5A: EXTRACT FILE INFORMATION
        // ===========================================
        $fileName = $files['name'][$i];
        $fileTmpName = $files['tmp_name'][$i];
        $fileSize = $files['size'][$i];
        $uploadFileType = $files['type'][$i];
        $fileError = $files['error'][$i];

        // Skip empty file slots
        if ($fileError === UPLOAD_ERR_NO_FILE || empty($fileName)) {
            continue;
        }

        // ===========================================
        // PROCESS 5B: FILE VALIDATION
        // ===========================================
        // Check for upload errors
        if ($fileError !== UPLOAD_ERR_OK) {
            $errors[] = "Error uploading {$fileName}: " . getUploadErrorMessage($fileError);
            continue;
        }

        // Validate file size
        if ($fileSize > $maxFileSize) {
            $errors[] = "{$fileName} is too large. Maximum size is " . formatFileSize($maxFileSize);
            continue;
        }

        // Validate file type
        if (!in_array($uploadFileType, $allowedTypes)) {
            $errors[] = "{$fileName} has invalid file type: {$uploadFileType}";
            continue;
        }

        // ===========================================
        // PROCESS 5C: GENERATE UNIQUE FILENAME
        // ===========================================
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
        $baseName = pathinfo($fileName, PATHINFO_FILENAME);
        // Create unique filename: originalname_timestamp_randomid.extension
        $uniqueFileName = $baseName . '_' . time() . '_' . uniqid() . '.' . $fileExtension;
        $filePath = $uploadDir . $uniqueFileName;

        // ===========================================
        // PROCESS 5D: SAVE FILE TO SERVER
        // ===========================================
        if (!move_uploaded_file($fileTmpName, $filePath)) {
            $errors[] = "Failed to save {$fileName} to server";
            continue;
        }

        // ===========================================
        // PROCESS 5E: SAVE METADATA TO DATABASE
        // ===========================================
        $insertSql = "INSERT INTO visarequirements 
                     (transactNo, accId, guestId, fileType, filePath, docSubType, dateSubmitted) 
                     VALUES (?, ?, ?, ?, ?, ?, NOW())";
        
        $stmt = $conn->prepare($insertSql);
        if (!$stmt) {
            throw new Exception("Database prepare error: " . $conn->error);
        }

        // Bind parameters: transactNo, accId, guestId, fileType, filePath, docSubType
        $docSubType = $fileExtension; // Use file extension as document sub-type
        $stmt->bind_param("siisss", $transactNo, $accId, $guestId, $fileType, $filePath, $docSubType);

        if (!$stmt->execute()) {
            throw new Exception("Database insert error for {$fileName}: " . $stmt->error);
        }

        // Get the inserted record ID
        $requirementId = $conn->insert_id;
        $stmt->close();

        // ===========================================
        // PROCESS 5F: RECORD SUCCESSFUL UPLOAD
        // ===========================================
        $uploadedFiles[] = [
            'requirementId' => $requirementId,
            'original_name' => $fileName,
            'saved_name' => $uniqueFileName,
            'file_path' => $filePath,
            'file_size' => $fileSize,
            'file_type' => $uploadFileType,
            'doc_type' => $fileType,
            'transaction_no' => $transactNo,
            'upload_time' => date('Y-m-d H:i:s')
        ];
    }

    // ===========================================
    // PROCESS 6: FINALIZE TRANSACTION
    // ===========================================
    if (!empty($uploadedFiles)) {
        // Commit all database changes if files were successfully uploaded
        $conn->commit();
    } else {
        // Rollback if no files were uploaded successfully
        $conn->rollback();
    }

} catch (Exception $e) {
    // ===========================================
    // PROCESS 7: ERROR HANDLING AND ROLLBACK
    // ===========================================
    $conn->rollback();
    $errors[] = "Database error: " . $e->getMessage();
    
    // Clean up any uploaded files if database operation failed
    foreach ($uploadedFiles as $file) {
        if (file_exists($file['file_path'])) {
            unlink($file['file_path']);
        }
    }
    $uploadedFiles = []; // Clear uploaded files array
}

// ===========================================
// PROCESS 8: CLEANUP AND RESPONSE
// ===========================================
$conn->close();

// Prepare final response
$response = [
    'success' => !empty($uploadedFiles) && empty($errors),
    'transaction_no' => $transactNo,
    'uploaded_files' => $uploadedFiles,
    'errors' => $errors,
    'total_uploaded' => count($uploadedFiles),
    'total_errors' => count($errors),
    'metadata' => [
        'accId' => $accId,
        'guestId' => $guestId,
        'fileType' => $fileType,
        'uploadTime' => date('Y-m-d H:i:s')
    ]
];

echo json_encode($response);

// ===========================================
// HELPER FUNCTIONS
// ===========================================
function getUploadErrorMessage($errorCode) {
    switch ($errorCode) {
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            return 'File is too large';
        case UPLOAD_ERR_PARTIAL:
            return 'File was only partially uploaded';
        case UPLOAD_ERR_NO_TMP_DIR:
            return 'Missing temporary folder';
        case UPLOAD_ERR_CANT_WRITE:
            return 'Failed to write file to disk';
        case UPLOAD_ERR_EXTENSION:
            return 'File upload stopped by extension';
        default:
            return 'Unknown upload error';
    }
}

function formatFileSize($bytes) {
    if ($bytes === 0) return '0 B';
    $k = 1024;
    $sizes = ['B', 'KB', 'MB', 'GB'];
    $i = floor(log($bytes) / log($k));
    return round(($bytes / pow($k, $i)), 1) . ' ' . $sizes[$i];
}
?>