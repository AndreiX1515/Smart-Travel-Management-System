<?php
  require "../../conn.php"; // Adjust path if needed
  session_start(); // Start the session to access session variables

  if (isset($_POST['flightId']) && isset($_SESSION['agent_agentId'])) 
  {
    $flightId = $_POST['flightId'];
    $agentId = $_SESSION['agent_agentId'];

    // Prepare and execute the query
    $query = "SELECT 
                flight.flightId,
                flight.availSeats - IFNULL((
                    SELECT SUM(pax) 
                    FROM booking 
                    WHERE booking.flightId = flight.flightId 
                    AND booking.status = 'Confirmed'
                    AND booking.bookingType = 'Package'
                ), 0) AS totalSeatsLeft, -- Dynamically calculate remaining seats
                agentflightseats.flightSeatId,
                agentflightseats.agentId,
                agentflightseats.maxSeats,
                GREATEST(
                    agentflightseats.maxSeats - (SELECT IFNULL(SUM(pax), 0) 
                                                FROM booking 
                                                WHERE booking.flightId = agentflightseats.flightId 
                                                AND booking.agentId = agentflightseats.agentId 
                                                AND booking.status = 'Confirmed'
                                                AND booking.bookingType = 'Package'), 0
                ) AS availableSeats
              FROM agentflightseats
              JOIN flight ON flight.flightId = agentflightseats.flightId
              WHERE agentflightseats.agentId = ? 
              AND agentflightseats.flightId = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $agentId, $flightId); // Bind parameters to prevent SQL injection
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) 
    {
      $res = $result->fetch_assoc();
      $flightId = $res['flightId']; // Fetch the flight ID
      $totalSeatsLeft = $res['totalSeatsLeft']; // Dynamically calculated remaining seats
      $maxSeats = $res['availableSeats']; // Fetch the max available seats

      // Return a JSON response
      echo json_encode(array(
          "flightId" => $flightId,
          "totalSeatsLeft" => $totalSeatsLeft,
          "maxSeats" => $maxSeats
      ));
    } 
    else 
    {
      // No data found
      echo json_encode(array(
          "flightId" => null,
          "totalSeatsLeft" => $null,
          "maxSeats" => null
      ));
    }
    $stmt->close(); // Close the statement
    $conn->close(); // Close the connection
  } 
  else 
  {
    // If required data is missing, return null values
    echo json_encode(array(
        "flightId" => null,
        "totalSeatsLeft" => $null,
        "maxSeats" => null
    ));
  }
?>
