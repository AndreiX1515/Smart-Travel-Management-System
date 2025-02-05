<?php
require '../../conn.php';
session_start();

// Default response assuming no booking
$response = ['hasBooking' => false];

// Retrieve accountId from POST request
$accountId = $_POST['accountId'] ?? ''; 

// If accountId is provided, proceed to check booking
if (!empty($accountId)) {
    // SQL query to count bookings for the given accountId
    $query = "SELECT COUNT(*) AS bookingCount FROM booking WHERE accountId = ?"; 

    if ($stmt = $conn->prepare($query)) {
        // Bind accountId parameter to the query
        $stmt->bind_param("i", $accountId);
        $stmt->execute();
        
        // Bind the result to the $bookingCount variable
        $stmt->bind_result($bookingCount);
        $stmt->fetch();
        $stmt->close();

        // If there are any bookings, update the response
        if ($bookingCount > 0) {
            $response['hasBooking'] = true;
        }
    }
}

// Output the response as a JSON object
echo json_encode($response);
?>
