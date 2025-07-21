<?php
require_once '../../../conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['itineraryId'])) {
    $itineraryId = $_POST['itineraryId'];

    $stmt = $conn->prepare("DELETE FROM itineraries WHERE itineraryId = ?");
    $stmt->bind_param("i", $itineraryId);

    if ($stmt->execute()) {
        echo "Deleted successfully";
    } else {
        echo "Error deleting itinerary: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request";
}
?>
