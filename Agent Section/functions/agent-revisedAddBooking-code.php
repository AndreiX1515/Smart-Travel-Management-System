<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require "../../conn.php";

header('Content-Type: application/json'); // always return JSON

$response = [
    "success" => false,
    "message" => "",
    "redirectUrl" => null,
    "errors" => []
];

try {
    // Decode JSON body from AJAX request
    $rawInput = file_get_contents("php://input");
    $data = json_decode($rawInput, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Invalid JSON received: " . json_last_error_msg());
    }

    // Check if this is a booking request
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($data['bookNow'])) {
        $accountId = $_SESSION['agent_accountId'] ?? null;
        if (!$accountId) {
            throw new Exception("Session expired. Please log in again.");
        }

        // Collect fields from the JSON data structure
        $agentId = 'Agent';
        $agentCode = $data['agentCode'] ?? '';
        $fName = trim($data['fName'] ?? '');
        $mName = trim($data['mName'] ?? '');
        $lName = trim($data['lName'] ?? '');
        $suffix = !empty($data['suffix']) ? $data['suffix'] : 'N/A';
        $countryCode = $data['countryCode'] ?? '';
        $contactNo = $data['contactNo'] ?? '';
        $email = trim($data['email'] ?? '');
        $packageId = !empty($data['packageId']) ? $data['packageId'] : null;

        // Handle flight date/ID logic
        $flightId = !empty($data['flightId']) && $data['flightId'] !== 'Null' ? $data['flightId'] : null;
        if (empty($flightId) && !empty($data['flightDate']) && $data['flightDate'] !== 'Null') {
            $flightId = $data['flightDate']; // fallback to flightDate if flightId is empty
        }

        $totalPax = (int) ($data['totalPax'] ?? 0);
        $infantPax = (int) ($data['infantPax'] ?? 0);
        $totalPrice = (float) ($data['totalPrice'] ?? 0);

        // Determine booking type based on landOnly checkbox
        $bookingType = ($data['landOnly'] === '1') ? 'Land' : 'Package';
        $flightDetails = ($bookingType === 'Land') ? ($data['flightDetails'] ?? null) : null;
        $remarks = !empty($data['remarks']) ? $data['remarks'] : null;

        // Basic validation
        $validationErrors = [];

        if (empty($fName)) {
            $validationErrors[] = "First name is required";
        }
        if (empty($lName)) {
            $validationErrors[] = "Last name is required";
        }
        if (empty($email)) {
            $validationErrors[] = "Email is required";

        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $validationErrors[] = "Invalid email format";
        }
        if (empty($contactNo)) {
            $validationErrors[] = "Contact number is required";
        }
        if ($totalPax <= 0) {
            $validationErrors[] = "Total passengers must be greater than 0";
        }
        if ($totalPrice <= 0) {
            $validationErrors[] = "Total price must be greater than 0";
        }
        if (empty($agentCode)) {
            $validationErrors[] = "Agent code is missing";
        }

        // If there are validation errors, return them
        if (!empty($validationErrors)) {
            $response["errors"] = $validationErrors;
            $response["message"] = "Please correct the following errors: " . implode(", ", $validationErrors);
            echo json_encode($response);
            exit;
        }

        // Get last bookingId for transaction number generation
        $result = $conn->query("SELECT MAX(bookingId) AS lastBookingId FROM booking");
        if (!$result) {
            throw new Exception("Error fetching last booking ID: " . $conn->error);
        }

        $row = $result->fetch_assoc();
        $newBookingId = ($row && $row['lastBookingId'] !== null) ? $row['lastBookingId'] + 1 : 1;
        $formattedCounter = str_pad($newBookingId, 6, '0', STR_PAD_LEFT);
        $transactNo = $agentCode . '-' . $formattedCounter;

        // Set user for triggers/logging
        $conn->query("SET @current_user_id = $accountId");

        // Begin database transaction
        $conn->begin_transaction();

        // Prepare and execute booking insert
        $sql = "INSERT INTO booking (accountId, transactNo, accountType, agentCode, 
                flightId, packageId, fName, lName, mName, suffix, countryCode, 
                contactNo, email, pax, infantPax, totalPrice, bookingType, flightDetails, status, bookingDate) VALUES 
                (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Reserved', NOW())";

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Booking SQL preparation failed: " . $conn->error);
        }

        $stmt->bind_param(
            'isssiisssssssiidss',
            $accountId,
            $transactNo,
            $agentId,
            $agentCode,
            $flightId,
            $packageId,
            $fName,
            $lName,
            $mName,
            $suffix,
            $countryCode,
            $contactNo,
            $email,
            $totalPax,
            $infantPax,
            $totalPrice,
            $bookingType,
            $flightDetails
        );

        if (!$stmt->execute()) {
            throw new Exception("Database error on booking insert: " . $stmt->error);
        }

        // Get the inserted booking ID for potential future use
        $insertedBookingId = $conn->insert_id;

        // Commit the transaction
        $conn->commit();

        // Success response
        $response["success"] = true;
        $response["message"] = "Booking submitted successfully! Transaction No: " . $transactNo;
        $response["redirectUrl"] = "../Agent Section/agent-addBookingPayment.php?id=" . urlencode($transactNo);
        $response["transactNo"] = $transactNo;
        $response["bookingId"] = $insertedBookingId;

    } else {
        throw new Exception("Invalid request. Missing bookNow parameter.");
    }

} catch (Exception $e) {
    // Rollback transaction on error
    if ($conn->errno || (isset($conn) && $conn->connect_errno === 0)) {
        $conn->rollback();
    }

    $response["success"] = false;
    $response["message"] = $e->getMessage();

    // Log the error for debugging (optional)
    error_log("Booking Error: " . $e->getMessage() . " | Data: " . json_encode($data ?? []));
} finally {
    // Close statement if it exists
    if (isset($stmt)) {
        $stmt->close();
    }
}

// Return JSON response
echo json_encode($response);
exit;
?>