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

    // Extract main itinerary details
    $packageName = $_POST["package"];

    $templateName = $_POST["templateName"];

    $noOfDays = $_POST["noOfDays"];

    $periodStart = $_POST["period_start"];
    $periodEnd = $_POST["period_end"];
    $countryCode = $_POST["countryCode"] ?? "";
    $contactNumber = $_POST["contactNumber"] ?? "";
    $guideName = $_POST["guide"] ?? "";
    



    // City & Hotel details
    $city1 = $_POST["city1"] ?? "";
    $hotel1 = $_POST["hotel1"] ?? "";
    $city2 = $_POST["city2"] ?? "";
    $hotel2 = $_POST["hotel2"] ?? "";
    $city3 = $_POST["city3"] ?? "";
    $hotel3 = $_POST["hotel3"] ?? "";

    // Decode JSON itinerary data
    $itineraryData = json_decode($_POST["itinerary"], true);
    if (!$itineraryData) {
        echo json_encode(["status" => "error", "message" => "Invalid itinerary JSON."]);
        exit;
    }

    $userId = 1; 
    $itineraryName = "Itinerary for $packageName"; // Default itinerary name
    $conn->beginTransaction(); // Start transaction

    $stmtItinerary = $conn->prepare("INSERT INTO itineraries 
    (userId, itineraryName, noOfDays, packageName, periodStart, periodEnd, guideName, countryCode, contactNumber, 
     city1, hotel1, city2, hotel2, city3, hotel3) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmtItinerary->execute([
        $userId, $templateName, $noOfDays, $packageName, $periodStart, $periodEnd, $guideName, $countryCode, $contactNumber,
        $city1, $hotel1, $city2, $hotel2, $city3, $hotel3
    ]);

    $itineraryId = $conn->lastInsertId();
    error_log("Inserted itinerary ID: " . $itineraryId);

    // Prepare reusable statements for itinerary details
    $stmtDay = $conn->prepare("INSERT INTO itineraryDays (itineraryId, dayNumber) VALUES (?, ?)");
    $stmtArea = $conn->prepare("INSERT INTO itineraryAreas (itineraryId, dayId, areaName) VALUES (?, ?, ?)");
    $stmtHotel = $conn->prepare("INSERT INTO itineraryHotels (dayId, hotelName) VALUES (?, ?)");
    $stmtMeal = $conn->prepare("INSERT INTO itineraryMealPlans (dayId, mealPlan) VALUES (?, ?)");
    $stmtActivity = $conn->prepare("INSERT INTO itineraryActivities (dayId, activityName) VALUES (?, ?)");

    // Process each day's itinerary
    foreach ($itineraryData as $dayData) { // ✅ FIXED: Used $itineraryData instead of $itinerary
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
    error_log("❌ General Error: " . $e->getMessage());
    echo json_encode(["status" => "error", "message" => "Error: " . $e->getMessage()]);
}

exit;
?>
