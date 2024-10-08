<?php
include 'conn.php'; // Ensure you include the correct database connection

if (isset($_POST['outboundFlight'])) 
{
  $outboundFlight = $_POST['outboundFlight'];

  // Query to fetch the return flight based on the outbound flight ID
  $sql = mysqli_query($conn, "SELECT 
      CONCAT(
          DATE_FORMAT(returnDepartureDate, '%M %d, %Y'), ' ', 
          TIME_FORMAT(returnDepartureTime, '%H:%i'), ' - ', 
          DATE_FORMAT(returnArrivalDate, '%M %d, %Y'), ' ', 
          TIME_FORMAT(returnArrivalTime, '%H:%i')
      ) AS returnFlightSched, flightPrice
      FROM flight 
      WHERE flightId = '$outboundFlight'");

  if (mysqli_num_rows($sql) > 0) 
  {
    $res = mysqli_fetch_array($sql);
    $returnFlightSched = $res['returnFlightSched'];
    $flightPrice = $res['flightPrice']; // Fetching the flight price

    // Return a JSON response with both return flight schedule and price
    echo json_encode(array(
        "returnFlight" => $returnFlightSched,
        "flightPrice" => $flightPrice
    ));
  } 
  else 
  {
    // Return a JSON response if no return flight is available
    echo json_encode(array(
        "returnFlight" => 'No Return Flight Available',
        "flightPrice" => null // No price available
    ));
  }
}
?>
