<?php
include 'conn.php'; // Ensure you include the correct database connection

// Check if the origin is set (for fetching outbound flights)
if (isset($_POST['origin'])) 
{
    $origin = $_POST['origin'];

    // Query to fetch the outbound flight schedules for the selected origin
    $sql = "SELECT flightId,
            CONCAT(
                DATE_FORMAT(flightDepartureDate, '%M %d, %Y'), ' ', 
                TIME_FORMAT(flightDepartureTime, '%H:%i'), ' - ', 
                DATE_FORMAT(flightArrivalDate, '%M %d, %Y'), ' ', 
                TIME_FORMAT(flightArrivalTime, '%H:%i')
            ) AS onboardFlightSched, 
            CONCAT(
                DATE_FORMAT(returnDepartureDate, '%M %d, %Y'), ' ', 
                TIME_FORMAT(returnDepartureTime, '%H:%i'), ' - ', 
                DATE_FORMAT(returnArrivalDate, '%M %d, %Y'), ' ', 
                TIME_FORMAT(returnArrivalTime, '%H:%i')
            ) AS returnFlightSched 
        FROM flight 
        WHERE origin = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $origin);
    $stmt->execute();
    $result = $stmt->get_result();

    $flights = [];
    while ($row = $result->fetch_assoc()) 
    {
        // Collect the flightId, onboardFlightSched, and returnFlightSched
        $flights[] = [
            'flightId' => $row['flightId'],
            'onboardFlightSched' => $row['onboardFlightSched'],
            'returnFlightSched' => $row['returnFlightSched']
        ];
    }

    // Return the flights as JSON
    echo json_encode($flights);

    $stmt->close();
}

// Check if the outbound flight is set (for fetching the package name based on the flight)
if (isset($_POST['outboundFlight'])) 
{
  $flightId = $_POST['outboundFlight'];  // You will now get the flightId from this POST request

  // Query to get the package name based on the flight ID
  $sql = "
      SELECT p.packageName 
      FROM flight f
      INNER JOIN package p ON f.packageId = p.packageId
      WHERE f.flightId = ?";

  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $flightId);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($row = $result->fetch_assoc()) 
  {
      echo json_encode(['packageName' => $row['packageName']]);
  } 
  else 
  {
      echo json_encode(['packageName' => 'No package found']);
  }

  $stmt->close();
}
?>
