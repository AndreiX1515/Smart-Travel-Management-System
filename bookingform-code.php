<?php
require "conn.php";
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
    $flightPrices = $_POST['flightPrice'];
    $totalPrice = $_POST['totalPrice'];

    $pax = count($fNames); // Number of passengers

    // Get the last bookingId and increment it for the new transaction
    $result = $conn->query("SELECT MAX(bookingId) AS lastBookingId FROM booking");
    if (!$result) {
        $_SESSION['status'] = "Error fetching last booking ID: " . $conn->error;
        header("Location: bookingform.php");
        exit(0);
    }

    $row = $result->fetch_assoc();
    $newBookingId = ($row && $row['lastBookingId'] !== null) ? $row['lastBookingId'] + 1 : 1;
    $formattedCounter = str_pad($newBookingId, 7, '0', STR_PAD_LEFT);
    $transactNo = 'TRANS-' . $formattedCounter;

    // Start a transaction
    $conn->begin_transaction();

    // Prepare the SQL statement for insertion into the booking table
    $sql1 = "INSERT INTO booking (accountId, transactNo, agentId, pax, totalPrice, bookingDate) VALUES (?, ?, ?, ?, ?, NOW())";
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
    $accountId = $_SESSION['accountid']; // Assuming the user is logged in
    $stmt1->bind_param('isiid', $accountId, $transactNo, $agentId, $pax, $totalPrice);
    
    if (!$stmt1->execute()) 
    {
        $_SESSION['status'] = "Database error on booking insert: " . $stmt1->error;
        $conn->rollback();  // Rollback the transaction if there is an error
        header("Location: bookingform.php");
        exit(0);
    }

    // Prepare the SQL statement for insertion into the guest table
    $sql2 = "INSERT INTO guest (transactNo, flightId, fName, lName, mName, suffix, birthdate, age, sex, nationality, 
        contactNo, emailAdd, houseNo, street, subdivision, barangay, city, country, passportNo, passportExp)  
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

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
        $houseNo = $houseNos[$index] ?? '';
        $street = $streets[$index] ?? '';
        $subdivision = $subdivisions[$index] ?? '';
        $barangay = $barangays[$index] ?? '';
        $city = $cities[$index] ?? '';
        $country = $countries[$index] ?? '';
        $age = $ages[$index] ?? null;
        $birthdate = $birthdates[$index] ?? null;
        $passportNo = $passportNos[$index] ?? '';
        $passportExp = $passportExps[$index] ?? null;
        $email = $emails[$index] ?? '';
        $contactNo = $contactNos[$index] ?? '';
        $sex = $sexes[$index] ?? '';
        $nationality = $nationalities[$index] ?? '';
        $flightId = $flightIds[$index];
    
        // Output guest details for debugging
        /* echo "Guest $index: <br>";
        echo "First Name: $fName <br>";
        echo "Middle Name: $mName <br>";
        echo "Last Name: $lName <br>";
        echo "Suffix: $suffix <br>";
        echo "House No: $houseNo <br>";
        echo "Street: $street <br>";
        echo "Subdivision: $subdivision <br>";
        echo "Barangay: $barangay <br>";
        echo "City: $city <br>";
        echo "Country: $country <br>";
        echo "Age: $age <br>";
        echo "Birthdate: $birthdate <br>";
        echo "Passport No: $passportNo <br>";
        echo "Passport Exp: $passportExp <br>";
        echo "Email: $email <br>";
        echo "Contact No: $contactNo <br>";
        echo "Sex: $sex <br>";
        echo "Nationality: $nationality <br>";
        echo "Flight ID: $flightId <br><br>"; */
    
        // Bind parameters for each guest entry
        $stmt2->bind_param('sisssssissssssssssss', 
            $transactNo, $flightId, $fName, $lName, $mName, $suffix, 
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

    // Close the statements
$stmt1->close();
$stmt2->close();

// Close the database connection
$conn->close();
}


?>
