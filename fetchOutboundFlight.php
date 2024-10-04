<?php
include 'conn.php'; // Ensure you include the correct database connection

if (isset($_POST['packageId']) && isset($_POST['origin'])) 
{
  $packageId = $_POST['packageId'];
  $origin = $_POST['origin'];

  // Correct SQL to fetch outbound and return flight schedules
  $sql = mysqli_query($conn, "
      SELECT flightId,
          CONCAT(
              DATE_FORMAT(flightDepartureDate, '%M %d, %Y'), ' ', 
              TIME_FORMAT(flightDepartureTime, '%H:%i'), ' - ', 
              DATE_FORMAT(flightArrivalDate, '%M %d, %Y'), ' ', 
              TIME_FORMAT(flightArrivalTime, '%H:%i')
          ) AS onboardFlightSched
      FROM flight 
      WHERE packageId = '$packageId' AND origin = '$origin' 
      ORDER BY onboardFlightSched ASC");

  if (mysqli_num_rows($sql) > 0) 
  {
    echo '<option selected disabled>Select Outbound Flight</option>';
    while ($res = mysqli_fetch_array($sql)) 
    {
        echo '<option value="' . $res['flightId'] . '">' . $res['onboardFlightSched'] . '</option>';
    }
  } 
  else 
  {
    echo '<option selected disabled>No Outbound Flights Available</option>';
  }
}

?>