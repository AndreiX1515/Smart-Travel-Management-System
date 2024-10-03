<?php
require "conn.php";
session_start();

if (isset($_POST['bookNow'])) 
{
    // Assume that each of these arrays contains multiple entries
    $agentId = $_POST['agentId'];
    $fNames = $_POST['fName'];  
    $mNames = $_POST['mName'];  
    $lNames = $_POST['lName'];  
    $suffixes = $_POST['suffix'];  
    $houseNos = $_POST['houseNo'];  
    $streets = $_POST['street'];  
    $subdivisions = $_POST['subdivision'];  
    $barangays = $_POST['barangay'];  
    $cities = $_POST['city'];  
    $countries = $_POST['country'];  
    $ages = $_POST['age'];  
    $birthdates = $_POST['birthdate'];  
    $passportNos = $_POST['passportNo'];  
    $passportExps = $_POST['passportExp'];  
    $emails = $_POST['email'];  
    $contactNos = $_POST['contactNo'];  
    $sexes = $_POST['sex'];  
    $nationalities = $_POST['nationality'];  
    $flightIds = $_POST['flightId'];

    $pax = count($fNames); // Number of passengers

    // Get the last bookingId and increment it for the new transaction
    $result = $conn->query("SELECT MAX(bookingId) AS lastBookingId FROM booking");
    $row = $result->fetch_assoc();

    // Check if a bookingId exists, if not, start from 0
    if ($row && $row['lastBookingId'] !== null) {
        $lastBookingId = $row['lastBookingId'];
        $newBookingId = $lastBookingId + 1; // Increment by 1 for the new transaction
    } else {
        $newBookingId = 1; // If no previous bookingId exists, start from 1
    }

    // Format the new bookingId with 7 digits, including leading zeros
    $formattedCounter = str_pad($newBookingId, 7, '0', STR_PAD_LEFT);

    // Generate the new transactNo
    $transactNo = 'TRANS-' . $formattedCounter;

    // Start a transaction
    $conn->begin_transaction();

    // Prepare the SQL statement for insertion
    $sql1 = "INSERT INTO booking (accountId, transactNo ,agentId, flightId, pax)  
        VALUES (?, ?, ?, ?, ?)";

    $stmt1 = $conn->prepare($sql1);

    if (!$stmt1) 
    {
        $_SESSION['status'] = "Booking SQL preparation failed: " . $conn->error;
        $conn->rollback();  // Rollback transaction
        header("Location: bookingform.php");
        exit(0);
    }
    
    // Bind and execute the booking insertion
    $accountId = $_SESSION['accountId']; // Assuming the user is logged in
    $stmt1->bind_param('isiii', $accountId, $transactNo, $agentId, $flightIds, $pax);
    
    if (!$stmt1->execute()) 
    {
        $_SESSION['status'] = "Database error on booking insert: " . $stmt1->error;
        $conn->rollback();  // Rollback the transaction if there is an error
        header("Location: insert-multiple-data.php");
        exit(0);
    }
        
    // Check if the statement was prepared successfully
    if (!$stmt1) 
    {
        $_SESSION['status'] = "Booking SQL preparation failed: " . $conn->error;
        $conn->rollback();  // Rollback transaction
        header("Location: bookingform.php");
        exit(0);
    }

    // Prepare the SQL statement for insertion
    $sql2 = "INSERT INTO guest (transactNo, flightId, fName, lName, mName, suffix, birthdate, age, sex, nationality, 
        contactNo, emailAdd, houseNo, street, subdivision, barangay, city, country, passportNo, passportExp)  
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt2 = $conn->prepare($sql2);
    
    // Check if the statement was prepared successfully
    if (!$stmt2) {
        $_SESSION['status'] = "Booking SQL preparation failed: " . $conn->error;
        $conn->rollback();  // Rollback transaction
        header("Location: bookingform.php");
        exit(0);
    }

    // Loop through all the entries and insert them one by one
    foreach ($fNames as $index => $fName) {
        $mName = $mNames[$index];
        $lName = $lNames[$index];
        $suffix = $suffixes[$index];
        $houseNo = $houseNos[$index];
        $street = $streets[$index];
        $subdivision = $subdivisions[$index];
        $barangay = $barangays[$index];
        $city = $cities[$index];
        $country = $countries[$index];
        $age = $ages[$index];
        $birthdate = $birthdates[$index];
        $passportNo = $passportNos[$index];
        $passportExp = $passportExps[$index];
        $email = $emails[$index];
        $contactNo = $contactNos[$index];
        $sex = $sexes[$index];
        $nationality = $nationalities[$index];
        $flightId = $flightIds[$index];
        $agentId = $agentIds[$index];

        // Bind parameters for each booking
        $stmt2->bind_param('sisssssissssssssssss', 
            $transactNo, $flightId ,$fName, $lName, $mName, $suffix, 
            $birthdate, $age, $sex, $nationality, 
            $contactNo, $email, $houseNo, $street, $subdivision, 
            $barangay, $city, $country, $passportNo, $passportExp);

        // Execute the query
        if (!$stmt2->execute()) 
        {
            $_SESSION['status'] = "Database error on inserting booking for $fName $lName: " . $stmt2->error;
            $conn->rollback();  // Rollback the transaction if there is an error
            header("Location: bookingform.php");
            exit(0);
        }
    }

    // If no errors, commit the transaction
    $conn->commit();

    $_SESSION['status'] = "All bookings successfully inserted.";
    header("Location: bookingform.php");
    exit(0);

    // Close the statement
    $stmt1->close();
}

// Close the database connection
$conn->close();
?>
