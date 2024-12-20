<?php
require "../conn.php";
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_POST['bookNow'])) 
{
    // Collect input data
    $agentId = $_POST['agentId'];
    $fNames = $_POST['fName'];  
    $mNames = $_POST['mName'];  
    $lNames = $_POST['lName'];  
    $suffixes = $_POST['suffix'];  
    $addressLines1 = $_POST['addressLine'];  
    $addressLines2 = $_POST['2ndaddressLine'];
    $cities = $_POST['city']; 
    $states = $_POST['state'];  
    $zipCodes = $_POST['zipCode'];  
    $countries = $_POST['country'];  
    $ages = $_POST['age'];  
    $birthdates = $_POST['birthdate'];  
    $passportNos = $_POST['passportNo'];  
    $passportExps = $_POST['passportExp'];  
    $emails = $_POST['email']; 
    $countryCodes = $_POST['countryCode']; 
    $contactNos = $_POST['contactNo'];
    // $countryCodes2 = $_POST['2ndCountryCode']; 
    $contactNos2 = $_POST['2ndcontactNo'];  
    $sexes = $_POST['sex'];   
    $nationalities = $_POST['nationality'];  
    $flightIds = $_POST['flightId'];
    $totalPrice = $_POST['totalPrice'];
    $packageId = $_POST['packageName'];
    $packagePrice = $_POST['packagePrice'];

    $pax = count($fNames); // Number of passengers

    // Get the last bookingId and increment it for the new transaction
    $result = $conn->query("SELECT MAX(bookingId) AS lastBookingId FROM booking");
    if (!$result) {
        $_SESSION['status'] = "Error fetching last booking ID: " . $conn->error;
        header("Location: ../bookingform.php");
        exit(0);
    }

    $row = $result->fetch_assoc();
    $newBookingId = ($row && $row['lastBookingId'] !== null) ? $row['lastBookingId'] + 1 : 1;
    $formattedCounter = str_pad($newBookingId, 7, '0', STR_PAD_LEFT);
    $transactNo = 'TRANS-' . $formattedCounter;

    // Check if "Own Flight" is selected (value is 'Null')
    if ($flightIds === 'Null') 
    {
        $flightIds = NULL; // Set flightId to NULL if "Own Flight" is selected
    }

    // Check if "Own Agent" is selected (value is 'Null')
    if ($agentId === 'Null') 
    {
        $agentId = NULL; // Set agentId to NULL if "Own Agent" is selected
    }

    // Start a transaction
    $conn->begin_transaction();

    // Prepare the SQL statement for insertion into the booking table
    $sql1 = "INSERT INTO booking (accountId, transactNo, agentId, flightId, packageId, pax, totalPrice, status, bookingDate) VALUES 
    (?, ?, ?, ?, ?, ?, ?, 'Pending', NOW())";
    $stmt1 = $conn->prepare($sql1);

    // Check if the statement was prepared successfully
    if (!$stmt1) 
    {
        $_SESSION['status'] = "Booking SQL preparation failed: " . $conn->error;
        $conn->rollback();  // Rollback transaction
        header("Location: bookingform.php");
        exit(0);
    }

    // Bind and execute the booking insertion
    $accountId = $_SESSION['accountId']; // Assuming the user is logged in
    $stmt1->bind_param('isiiiid', $accountId, $transactNo, $agentId, $flightIds, $packageId, $pax, $totalPrice);
    
    if (!$stmt1->execute()) 
    {
        $_SESSION['status'] = "Database error on booking insert: " . $stmt1->error;
        $conn->rollback();  // Rollback the transaction if there is an error
        header("Location: bookingform.php");
        exit(0);
    }

    // Prepare the SQL statement for insertion into the guest table
    $sql2 = "INSERT INTO guest (transactNo, flightId, fName, lName, mName, suffix, birthdate, age, sex, nationality, 
    countryCode, contactNo, countryCode2, contactNo2, emailAdd, addressLine1, addressLine2, city, state, zipCode, country, passportNo, passportExp)  
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt2 = $conn->prepare($sql2);

    // Check if the statement was prepared successfully
    if (!$stmt2) {
        $_SESSION['status'] = "Guest SQL preparation failed: " . $conn->error;
        $conn->rollback();  // Rollback transaction
        header("Location: bookingform.php");
        exit(0);
    }

    // Loop through all the entries and insert them one by one
    foreach ($fNames as $index => $fName) {
        // Safely get each guest's data
        $mName = $mNames[$index] ?? '';
        $lName = $lNames[$index] ?? '';
        $suffix = $suffixes[$index] ?? '';
        $age = $ages[$index] ?? null;
        $birthdate = $birthdates[$index] ?? null;
        $passportNo = $passportNos[$index] ?? '';
        $passportExp = $passportExps[$index] ?? null;
        $email = $emails[$index] ?? '';
        $countryCode = $countryCodes[$index] ?? '';
        $contactNo = $contactNos[$index] ?? '';
        $countryCode2 = $countryCodes2[$index] ?? '';
        $contactNo2 = $contactNos2[$index] ?? '';
        $sex = $sexes[$index] ?? '';
        $nationality = $nationalities[$index] ?? '';
        $addressLine1 = $addressLines1[$index] ?? '';
        $addressLine2 = $addressLines2[$index] ?? '';
        $city = $cities[$index] ?? '';
        $state = $states[$index] ?? '';
        $zipCode = $zipCodes[$index] ?? '';
        $country = $countries[$index] ?? '';

        // Bind parameters for each guest entry
        $stmt2->bind_param('sisssssisssssssssssssss', 
            $transactNo, $flightIds, $fName, $lName, $mName, $suffix, 
            $birthdate, $age, $sex, $nationality, $countryCode, $contactNo, 
            $countryCode2, $contactNo2, $email, $addressLine1, $addressLine2, 
            $city, $state, $zipCode, $country, 
            $passportNo, $passportExp);

        // Execute the statement for each guest entry
        if (!$stmt2->execute()) 
        {
            $_SESSION['status'] = "Guest insertion failed: " . $stmt2->error;
            $conn->rollback();  // Rollback transaction
            header("Location: ../bookingform.php");
            exit(0);
        }
    }

    // If no errors, commit the transaction
    $conn->commit();

    // Store the transaction number in session and redirect
    $_SESSION['transactNo'] = $transactNo;
    header("Location: ../payment.php");
    exit(0);
}

// Close the statements
$stmt1->close();
$stmt2->close();
?>
