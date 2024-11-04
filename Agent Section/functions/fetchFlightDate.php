<?php
  require "../../conn.php"; // Move up to the parent directory

  if (isset($_POST['packageId']) && isset($_POST['origin'])) 
  {
    $packageId = $_POST['packageId'];
    $origin = $_POST['origin'];
    
    // SQL to fetch flights where the month matches the selected month
    $sql = "
        SELECT flightId, DATE_FORMAT(flightDepartureDate, '%M %d, %Y') AS flightDate, flightPrice
        FROM flight 
        WHERE packageId = '$packageId' 
        AND origin = '$origin' 
        ORDER BY flightDate ASC";

    // Debugging: Print the SQL query
    // echo $sql;
    
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
      echo '<option selected disabled>Select Flight Date</option>';
      echo '<option value="Null">Custom Flight</option>'; // Add "Own Flight" option here
      while ($res = mysqli_fetch_array($result)) 
      {
        $formattedPrice = number_format($res['flightPrice'], 2);
        $flightId = $res['flightId'];
        echo '<option value="' . $res['flightId'] . '">' . $res['flightDate'] . '&nbsp;&nbsp;&nbsp;&nbsp; || &nbsp;&nbsp;&nbsp;&nbsp;'. 'Package Price: ₱ '. $formattedPrice . '</option>';
      }
    } 
    else 
    {
      echo '<option value="Null">Custom Flight</option>'; // Add "Own Flight" option here
      echo '<option selected disabled>No Flights Available</option>';
    }
  }

?>
