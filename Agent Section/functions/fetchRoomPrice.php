<?php
  ini_set('display_errors', 1);
  error_reporting(E_ALL);  
  require "../../conn.php"; // Move up to the parent directory 

  if (isset($_POST['roomId'])) 
  {
    $roomId = $_POST['roomId'];

    // Fetch distinct origins based on the selected packageId
    $sql = mysqli_query($conn, "SELECT roomId, price, availRooms FROM fitrooms WHERE roomId = '$roomId' ORDER BY price ASC");

    if (mysqli_num_rows($sql) > 0) 
    {
      $room = mysqli_fetch_assoc($sql);
      $roomPrice = $room['price']; // Extract price value
      $avail = $room['availRooms'];
    } 
    else 
    {
      $roomPrice = 'Error';
    }

    // Return both the origin options and package price as a JSON response
    echo json_encode(array
    (
      "roomPrice" => $roomPrice,
      "avail" => $avail
    ));
  }
?>
