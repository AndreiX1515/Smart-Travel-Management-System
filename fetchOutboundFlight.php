<?php
include 'conn.php'; // Ensure you include the correct database connection


if (isset($_POST['packageId']) && isset($_POST['origin']) && isset($_POST['month'])) 
{
  $packageId = $_POST['packageId'];
  $origin = $_POST['origin'];
  $month = $_POST['month']; // Get the month in text format (e.g., "October")
  
  // SQL to fetch flights where the month matches the selected month
  $sql = "
      SELECT flightId, DATE_FORMAT(flightDepartureDate, '%M %d, %Y') AS onboardFlightSched, flightPrice
      FROM flight 
      WHERE packageId = '$packageId' 
      AND origin = '$origin' 
      AND MONTHNAME(flightDepartureDate) = '$month' 
      ORDER BY flightDepartureDate ASC";

  // Debugging: Print the SQL query
  echo $sql;
  
  $result = mysqli_query($conn, $sql);
  
  if (mysqli_num_rows($result) > 0) {
    echo '<option selected disabled>Select Flight Available Dates</option>';
    while ($res = mysqli_fetch_array($result)) 
    {
      $formattedPrice = number_format($res['flightPrice'], 2);
      echo '<option value="' . $res['flightId'] . '">' . $res['onboardFlightSched'] . '&nbsp;&nbsp;&nbsp;&nbsp; || &nbsp;&nbsp;&nbsp;&nbsp;'. 'Package Price: ₱ '. $formattedPrice . '</option>';
    }
  } 
  else 
  {
    echo '<option selected disabled>No Flights Available</option>';
  }
}

?>
