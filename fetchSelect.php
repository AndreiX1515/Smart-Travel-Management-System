<?php
include 'conn.php'; // Ensure you include the correct database connection

if (isset($_POST['origin'])) 
  {
    $origin = $_POST['origin'];

    // Query to fetch the outbound flight schedules for the selected origin
    $sql = "SELECT 
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
      $flights[] = $row;
    }

    // Return the flights as JSON
    echo json_encode($flights);

    $stmt->close();
  }

  if (isset($_POST['outboundFlight'])) 
  {
    $outboundFlight = $_POST['outboundFlight'];

    // Query to get the package name based on the outbound flight schedule
    $sql = "
        SELECT p.packageName 
        FROM flight f
        INNER JOIN package p ON f.packageId = p.packageId
        WHERE CONCAT(
          DATE_FORMAT(f.flightDepartureDate, '%M %d, %Y'), ' ', 
          TIME_FORMAT(f.flightDepartureTime, '%H:%i'), ' - ', 
          DATE_FORMAT(f.flightArrivalDate, '%M %d, %Y'), ' ', 
          TIME_FORMAT(f.flightArrivalTime, '%H:%i')
        ) = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $outboundFlight);
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
