<?php
  require "../../conn.php"; // Adjust path if needed
  session_start(); // Start the session to access session variables

  if (isset($_POST['flightId']) && isset($_SESSION['agentId'])) 
  {
    $flightId = $_POST['flightId'];
    $agentId = $_SESSION['agentId'];

    // Prepare and execute the query
    $query = "SELECT 
                agentflightseats.flightSeatId,
                agentflightseats.agentId,
                agentflightseats.flightId,
                agentflightseats.maxSeats,
                agentflightseats.maxSeats - (SELECT SUM(pax) 
                                            FROM booking 
                                            WHERE booking.flightId = agentflightseats.flightId 
                                            AND booking.agentId = agentflightseats.agentId 
                                            AND booking.status = 'Confirmed') AS availableSeats
              FROM agentflightseats
              WHERE agentflightseats.agentId = ? 
              AND agentflightseats.flightId = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $agentId, $flightId); // Bind parameters to prevent SQL injection
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) 
    {
      $res = $result->fetch_assoc();
      $flightId = $res['flightId']; // Fetch the flight ID
      $maxSeats = $res['availableSeats']; // Fetch the max available seats

      // Return a JSON response
      echo json_encode(array(
        "flightId" => $flightId,
        "maxSeats" => $maxSeats
      ));
    } 
    else 
    {
      // No data found
      echo json_encode(array(
        "flightId" => null,
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
      "maxSeats" => null
    ));
  }
?>
