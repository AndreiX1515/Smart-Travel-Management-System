<?php
  ini_set('display_errors', 1);
  error_reporting(E_ALL);  
  require "../../conn.php"; // Move up to the parent directory 

  if (isset($_POST['hotelId'])) 
  {
    $hotelId = $_POST['hotelId'];

    // Fetch distinct origins based on the selected packageId
    $sql = mysqli_query($conn, "SELECT roomId, rooms, availRooms FROM fitrooms 
                                WHERE hotelId = '$hotelId' ORDER BY price ASC");

    $roomOptions = '<option selected disabled>Select Room</option>'; // Default option

    if (mysqli_num_rows($sql) > 0) 
    {
      while ($res = mysqli_fetch_array($sql)) 
      {
        $roomOptions .= '<option value="' . $res['roomId'] . '">' . $res['rooms'] . ' Available Rooms Left: '. $res['availRooms']. '</option>';
      }
    } 
    else 
    {
      $roomOptions = '<option selected disabled>No Rooms Available</option>';
    }

    // Return both the origin options and package price as a JSON response
    echo json_encode(array
    (
      "roomOptions" => $roomOptions
    ));
  }
?>
