<?php
session_start();
header("Content-Type: application/json");
require_once "../../conn copy.php"; // Database connection

if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_POST["itinerary"])) {
    echo json_encode(["status" => "error", "message" => "Invalid request method or missing itinerary."]);
    exit;
}

$itinerary = json_decode($_POST["itinerary"], true);
if (!$itinerary) {
    echo json_encode(["status" => "error", "message" => "JSON decode failed: " . json_last_error_msg()]);
    exit;
}

try {
    $conn->beginTransaction(); // Start transaction

    $itineraryName = "Untitled Itinerary"; 
    $userId = 0 ?? 1; // Ensure user session ID is set

    // Insert into itineraries table
    $stmtItinerary = $conn->prepare("INSERT INTO itineraries (userId, itineraryName) VALUES (?, ?)");
    $stmtItinerary->execute([$userId, $itineraryName]);
    $itineraryId = $conn->lastInsertId();
    
    error_log("Inserted itinerary ID: " . $itineraryId);

    // Prepare reusable statements
    $stmtDay = $conn->prepare("INSERT INTO itineraryDays (itineraryId, dayNumber) VALUES (?, ?)");
    $stmtArea = $conn->prepare("INSERT INTO itineraryAreas (itineraryId, dayId, areaName) VALUES (?, ?, ?)");
    $stmtHotel = $conn->prepare("INSERT INTO itineraryHotels (dayId, hotelName) VALUES (?, ?)");
    $stmtMeal = $conn->prepare("INSERT INTO itineraryMealPlans (dayId, mealPlan) VALUES (?, ?)");
    $stmtActivity = $conn->prepare("INSERT INTO itineraryActivities (dayId, activityName) VALUES (?, ?)");

    // Process each day's itinerary
    foreach ($itinerary as $dayData) {
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
