<?php
include 'conn.php'; // Ensure you include the correct database connection

if (isset($_POST['packageId'])) 
{
    $packageId = $_POST['packageId'];

    // Fetch distinct origins based on the selected packageId
    $sql = mysqli_query($conn, "SELECT DISTINCT origin FROM flight WHERE packageId = '$packageId' ORDER BY origin ASC");

    if (mysqli_num_rows($sql) > 0) 
    {
        echo '<option selected disabled>Select Origin</option>';
        while ($res = mysqli_fetch_array($sql)) 
        {
            echo '<option value="' . $res['origin'] . '">' . $res['origin'] . '</option>';
        }
    } 
    else 
    {
        echo '<option selected disabled>No Origins Available</option>';
    }
}

?>
