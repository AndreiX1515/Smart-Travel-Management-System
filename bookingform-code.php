<?php
require "conn.php";
session_start();

if (isset($_POST['bookNow'])) 
{
    // Assume that each of these arrays contains multiple entries
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

    // Start a transaction
    $conn->begin_transaction();

    // Prepare the SQL statement for insertion
    $sql1 = "INSERT INTO booking (transactNo, fName, lName, mName, suffix, birthdate, age, sex, nationality, 
        contactNo, emailAdd, houseNo, street, subdivision, barangay, city, country, passportNo, passportExp, 
        flightId)  
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt1 = $conn->prepare($sql1);
    
    // Check if the statement was prepared successfully
    if (!$stmt1) {
        $_SESSION['status'] = "Booking SQL preparation failed: " . $conn->error;
        $conn->rollback();  // Rollback transaction
        header("Location: insert-multiple-data.php");
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

        // Generate a unique transaction number
        $transactNo = uniqid('TRANS_');

        // Bind parameters for each booking
        $stmt1->bind_param('ssssssissssssssssssi', 
            $transactNo, $fName, $lName, $mName, $suffix, 
            $birthdate, $age, $sex, $nationality, 
            $contactNo, $email, $houseNo, $street, $subdivision, 
            $barangay, $city, $country, $passportNo, $passportExp, 
            $flightId);

        // Execute the query
        if (!$stmt1->execute()) 
        {
            $_SESSION['status'] = "Database error on inserting booking for $fName $lName: " . $stmt1->error;
            $conn->rollback();  // Rollback the transaction if there is an error
            header("Location: insert-multiple-data.php");
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
