<?php
session_start();
require_once "../../conn copy.php"; // Ensure correct database connection

header("Content-Type: application/json");

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    // Check if required fields exist
    if (!isset($_POST["package"], $_POST["period_start"], 
        $_POST["period_end"], $_POST["itinerary"])) {

        echo json_encode(["status" => "error", "message" => "Missing required fields."]);
        exit;
    }

    // Decode JSON itinerary data
    $itineraryData = json_decode($_POST["itinerary"], true);
    if (!$itineraryData) {
        echo json_encode(["status" => "error", "message" => "Invalid itinerary JSON."]);
        exit;
    }

    $accountId = $_SESSION['accountId'] ?? 1; // Use session's accountId
    $voucherCode = "VOUCHER-" . strtoupper(uniqid()); // Generate unique voucher code
    
    // Start database transaction
    $conn->beginTransaction();
    
    // Insert into vouchers table
    $stmtVoucher = $conn->prepare("INSERT INTO vouchers (accountId, voucherCode) VALUES (?, ?)");
    $stmtVoucher->execute([$accountId, $voucherCode]);
    $voucherId = $conn->lastInsertId(); // Get the inserted voucherId

    // Check if voucher was successfully inserted
    if (!$voucherId) {
        throw new Exception("Voucher insertion failed.");
    }
    
    // Prepare for itinerary insert
    $itineraryName = "Itinerary for $voucherCode"; // Set itinerary name based on voucher code
    $userId = 1; // This can be dynamically fetched from session
    $stmtItinerary = $conn->prepare("INSERT INTO itineraries 
    (userId, itineraryName, noOfDays, packageName, periodStart, periodEnd, guideName, countryCode, contactNumber, 
     city1, hotel1, city2, hotel2, city3, hotel3, voucherId) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    // Insert into itineraries table
    $stmtItinerary->execute([
        $userId, $itineraryName, $noOfDays, $packageName, $periodStart, $periodEnd, $guideName, $countryCode, $contactNumber,
        $city1, $hotel1, $city2, $hotel2, $city3, $hotel3, $voucherId
    ]);

    $itineraryId = $conn->lastInsertId(); // Get inserted itinerary ID
    error_log("Inserted itinerary ID: " . $itineraryId);

    // Prepare reusable statements for itinerary details
    $stmtDay = $conn->prepare("INSERT INTO itineraryDays (itineraryId, dayNumber) VALUES (?, ?)");
    $stmtArea = $conn->prepare("INSERT INTO itineraryAreas (itineraryId, dayId, areaName) VALUES (?, ?, ?)");
    $stmtHotel = $conn->prepare("INSERT INTO itineraryHotels (dayId, hotelName) VALUES (?, ?)");
    $stmtMeal = $conn->prepare("INSERT INTO itineraryMealPlans (dayId, mealPlan) VALUES (?, ?)");
    $stmtActivity = $conn->prepare("INSERT INTO itineraryActivities (dayId, activityName) VALUES (?, ?)");

    // Process each day's itinerary
    foreach ($itineraryData as $dayData) {
        $dayNumber = $dayData["day"] ?? 0;
        $areas = $dayData["areas"] ?? [];
        $hotels = $dayData["hotels"] ?? [];
        $mealPlans = $dayData["meal_plans"] ?? [];
        $activities = $dayData["itineraries"] ?? [];

        // Insert Day
        $stmtDay->execute([$itineraryId, $dayNumber]);
        $dayId = $conn->lastInsertId();
        error_log("Inserted day ID: " . $dayId);

        // Insert Areas for this Day
        foreach ($areas as $area) {
            $stmtArea->execute([$itineraryId, $dayId, $area]);
            error_log("Inserted area: " . $area);
        }

        // Insert Hotels for this Day
        foreach ($hotels as $hotel) {
            $stmtHotel->execute([$dayId, $hotel]);
            error_log("Inserted hotel: " . $hotel);
        }

        // Insert Meal Plans for this Day
        foreach ($mealPlans as $meal) {
            $stmtMeal->execute([$dayId, $meal]);
            error_log("Inserted meal plan: " . $meal);
        }

        // Insert Activities for this Day
        foreach ($activities as $activity) {
            if (!empty($activity)) {
                $stmtActivity->execute([$dayId, $activity]);
                error_log("Inserted activity: " . $activity);
            }
        }
    }

    $conn->commit();
    echo json_encode(["status" => "success", "message" => "Itinerary saved successfully!"]);

} catch (PDOException $e) {
    $conn->rollBack();
    error_log("❌ Database Error: " . $e->getMessage());
    echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
} catch (Exception $e) {
    $conn->rollBack();
    error_log("❌ General Error: " . $e->getMessage());
    echo json_encode(["status" => "error", "message" => "Error: " . $e->getMessage()]);
}

exit;
?>
