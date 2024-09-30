<?php
require "conn.php";
session_start();

if (isset($_POST['bookNow'])) 
{
    // Assume that each of these arrays contains multiple entries
    $fNames = $_POST['fName'];  // array of first names
    $mNames = $_POST['mName'];  // array of middle names
    $lNames = $_POST['lName'];  // array of last names
    $suffixes = $_POST['suffix']; // array of suffixes
    $houseNos = $_POST['houseNo']; // array of house numbers
    $streets = $_POST['street']; // array of streets
    $subdivisions = $_POST['subdivision']; // array of subdivisions
    $barangays = $_POST['barangay']; // array of barangays
    $cities = $_POST['city']; // array of cities
    $countries = $_POST['country']; // array of countries
    $ages = $_POST['age']; // array of ages
    $birthdates = $_POST['birthdate']; // array of birthdates
    $passportNos = $_POST['passportNo']; // array of passport numbers
    $visaStatuses = $_POST['visaStatus']; // array of visa statuses
    $emails = $_POST['email']; // array of emails
    $contactNos = $_POST['contactNo']; // array of contact numbers
    $packageNames = $_POST['packageName']; // single package name

    // Prepare the SQL statement for insertion
    $sql1 = "INSERT INTO booking (packageName, firstName, lastName, middleName, suffix, houseNo, street, 
            subdivision, barangay, city, country, age, birthdate, passportNo, visaStatus, contactNo, emailAddress) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt1 = $conn->prepare($sql1);
    
    // Check if the statement was prepared successfully
    if (!$stmt1) {
        $_SESSION['status'] = "Booking SQL preparation failed: " . $conn->error;
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
        $visaStatus = $visaStatuses[$index];
        $email = $emails[$index];
        $contactNo = $contactNos[$index];
        $packageName = $packageNames[$index];

        // Bind parameters for each booking
        $stmt1->bind_param('sssssssssssisssis', 
            $packageName, $fName, $lName, $mName, $suffix, 
            $houseNo, $street, $subdivision, $barangay, $city, $country, 
            $age, $birthdate, $passportNo, $visaStatus, $contactNo, $email);

        // Execute the query
        if (!$stmt1->execute()) {
            $_SESSION['status'] = "Database error on inserting booking for $fName $lName: " . $stmt1->error;
            header("Location: insert-multiple-data.php");
            exit(0);
        }
    }

    $_SESSION['status'] = "All bookings successfully inserted.";
    header("Location: bookingform.php");
    exit(0);

    // Close the statement
    $stmt1->close();
}

// Close the database connection
$conn->close();
?>
