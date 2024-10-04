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
      ) AS returnFlightSched
      FROM flight 
      WHERE flightId = '$outboundFlight'");

  if (mysqli_num_rows($sql) > 0) 
  {
    $res = mysqli_fetch_array($sql);
    echo $res['returnFlightSched']; // Output the return flight to populate the field
  } 
  else 
  {
    echo 'No Return Flight Available';
  }
}

?>