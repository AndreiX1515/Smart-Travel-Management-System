<?php
  require "../../conn.php"; // Include the DB connection

  if (isset($_POST['packageId']) && isset($_POST['origin']) && isset($_POST['year']) && isset($_POST['month'])) 
  {
    $packageId = $_POST['packageId'];
    $origin = $_POST['origin'];
    $year = $_POST['year'];  // Year parameter
    $month = $_POST['month']; // Month parameter
    
    // SQL to fetch flights where the year and month match the selected year and month
    $sql = "
        SELECT flightId, DATE_FORMAT(flightDepartureDate, '%M %d, %Y') AS flightDate, flightPrice
        FROM flight 
        WHERE packageId = '$packageId' 
        AND origin = '$origin' 
        AND YEAR(flightDepartureDate) = '$year' 
        AND MONTH(flightDepartureDate) = '$month' 
        ORDER BY flightDepartureDate ASC";

    // Debugging: Print the SQL query (optional)
    // echo $sql;
    
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) 
    {
      echo '<option selected disabled>Select Flight Date</option>';
      while ($res = mysqli_fetch_array($result)) 
      {
        // Format the flight price to two decimal places
        $formattedPrice = number_format($res['flightPrice'], 2);
        $flightId = $res['flightId'];
        // Format the option with the flight date and price
        echo '<option value="' . $flightId . '">' . $res['flightDate'] . ' &nbsp;&nbsp;&nbsp;&nbsp; || &nbsp;&nbsp;&nbsp;&nbsp; Package Price: ₱ ' . $formattedPrice . '</option>';
      }
    } 
    else 
    {
      // Add "Custom Flight" option when no flights are available
      echo '<option selected disabled>No Flights Available</option>';
    }
  }
?>
