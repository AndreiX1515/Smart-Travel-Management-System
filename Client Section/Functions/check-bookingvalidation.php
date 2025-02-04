<?php
require '../../conn.php';
session_start();

$response = ['hasBooking' => false];

$accountId = $_POST['accountId'] ?? ''; // Get accountId from POST

if (!empty($accountId)) {
    $query = "SELECT COUNT(*) AS bookingCount FROM booking WHERE accountId = ?"; // Check for any booking
    
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("i", $accountId);
        $stmt->execute();
        $stmt->bind_result($bookingCount);
        $stmt->fetch();
        $stmt->close();

        if ($bookingCount > 0) {
            $response['hasBooking'] = true;
        }
    }
}

// Return the response as JSON
echo json_encode($response);
?>
