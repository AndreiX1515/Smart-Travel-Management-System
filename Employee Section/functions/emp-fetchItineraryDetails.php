<?php
require_once '../../conn.php'; // Include database connection

header('Content-Type: application/json');

if (isset($_GET['itineraryId'])) {
       $itineraryId = intval($_GET['id']); // Sanitize input
   
       $sql = "SELECT itineraryName, noOfDays, packageName, periodStart, periodEnd, guideName, 
                      countryCode, contactNumber, city1, hotel1, city2, hotel2, city3, hotel3
               FROM itineraries
               WHERE itineraryId = ?";
   
       $stmt = $conn->prepare($sql);
       $stmt->bind_param("i", $itineraryId);
       $stmt->execute();
       $result = $stmt->get_result();
       $itinerary = $result->fetch_assoc();
   
       if ($itinerary) {
           echo json_encode($itinerary);
       } else {
           echo json_encode(["error" => "Itinerary not found"]);
       }
   }
?>

