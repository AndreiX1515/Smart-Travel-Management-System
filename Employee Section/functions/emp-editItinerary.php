<?php
session_start();
require_once "../../conn copy.php";

header("Content-Type: application/json");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function validateItineraryData($data) {
    // Basic format validation (if necessary)
    $itineraryDetails = $data['itineraryDetails'];

    // Validate number of days (must be a positive integer if present)
    if (isset($itineraryDetails["noOfDays"]) && !is_numeric($itineraryDetails["noOfDays"])) {
        return ["status" => "error", "message" => "Invalid number of days."];
    }

    // Validate period start and end dates
    if (isset($itineraryDetails["periodStart"]) && empty($itineraryDetails["periodStart"])) {
        return ["status" => "error", "message" => "Invalid start date."];
    }

    if (isset($itineraryDetails["periodEnd"]) && empty($itineraryDetails["periodEnd"])) {
        return ["status" => "error", "message" => "Invalid end date."];
    }

    return null; // No issues, data is valid
}

try {
    if (!isset($_POST["itinerary"])) {
        echo json_encode(["status" => "error", "message" => "Missing itinerary data."]);
        exit;
    }

    $userId = 1; // Replace with session-based user ID in production
    $liveItineraryData = json_decode($_POST["itinerary"], true);

    // Run the validation layer (still checks format)
    $validationResponse = validateItineraryData($liveItineraryData);
    if ($validationResponse !== null) {
        echo json_encode($validationResponse);
        exit;
    }

    $itineraryDetails = $liveItineraryData["itineraryDetails"];
    $daysDetails = $liveItineraryData["daysDetails"];

    // Set template name (if itineraryId exists in the data, append " - Edited")
    $originalName = trim($itineraryDetails["itineraryName"] ?? "Untitled Itinerary");
    $originalName = ucwords(strtolower($originalName));  // Capitalize first letter of each word


    $templateName = $originalName;  // Start with the original name

    // Check if itineraryId exists and if it already exists in the database
    $itineraryIdFromJson = $itineraryDetails["itineraryId"] ?? null;

    if ($itineraryIdFromJson) {
        // Check if the itineraryId already exists in the database
        $stmtCheck = $conn->prepare("SELECT itineraryId FROM itineraries WHERE userId = ? AND itineraryId = ?");
        $stmtCheck->execute([$userId, $itineraryIdFromJson]);
        $existing = $stmtCheck->fetch();

        if ($existing) {
            // If itineraryId exists, append " - Edited" to the itinerary name
            $templateName = $originalName . " - Edited";
        }
    }

    // Start database transaction
    $conn->beginTransaction();

    // Insert into the itineraries table
    $stmtInsert = $conn->prepare("
    INSERT INTO itineraries (userId, itineraryName, noOfDays, packageName, periodStart, periodEnd, guideName, countryCode, contactNumber, city1, hotel1, city2, hotel2, city3, hotel3)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmtInsert->execute([
    $userId,
    $itineraryDetails["itineraryName"] ?? '',
    $itineraryDetails["noOfDays"] ?? 0,
    $itineraryDetails["packageName"] ?? '',
    $itineraryDetails["periodStart"] ?? '',
    $itineraryDetails["periodEnd"] ?? '',
    $itineraryDetails["guideName"] ?? '',
    $itineraryDetails["countryCode"] ?? '',
    $itineraryDetails["contactNumber"]?? '',
    $itineraryDetails["cities"][0]["city"] ?? '',  // city1
    $itineraryDetails["cities"][0]["hotel"] ?? '', // hotel1
    $itineraryDetails["cities"][1]["city"] ?? '',  // city2
    $itineraryDetails["cities"][1]["hotel"] ?? '', // hotel2
    $itineraryDetails["cities"][2]["city"] ?? '',  // city3
    $itineraryDetails["cities"][2]["hotel"] ?? ''  // hotel3
    ]);



    

    $itineraryId = $conn->lastInsertId();

    // Prepare reusable statements for day-wise data
    $stmtDay = $conn->prepare("INSERT INTO itineraryDays (itineraryId, dayNumber) VALUES (?, ?)");
    $stmtArea = $conn->prepare("INSERT INTO itineraryAreas (itineraryId, dayId, areaName) VALUES (?, ?, ?)");
    $stmtHotel = $conn->prepare("INSERT INTO itineraryHotels (dayId, hotelName) VALUES (?, ?)");
    $stmtMeal = $conn->prepare("INSERT INTO itineraryMealPlans (dayId, mealPlan) VALUES (?, ?)");
    $stmtActivity = $conn->prepare("INSERT INTO itineraryActivities (dayId, activityName) VALUES (?, ?)");

    // Insert days and activities
    foreach ($daysDetails as $dayData) {
        $dayNumber = $dayData["day"] ?? 0;
        $areas = $dayData["areas"] ?? [];
        $hotels = $dayData["hotels"] ?? [];
        $meals = $dayData["meals"] ?? [];
        $activities = $dayData["activities"] ?? [];

        $stmtDay->execute([$itineraryId, $dayNumber]);
        $dayId = $conn->lastInsertId();

        foreach ($areas as $area) {
            $stmtArea->execute([$itineraryId, $dayId, trim($area)]);
        }

        foreach ($hotels as $hotel) {
            $stmtHotel->execute([$dayId, trim($hotel)]);
        }

        foreach ($meals as $meal) {
            $stmtMeal->execute([$dayId, trim($meal)]);
        }

        foreach ($activities as $activity) {
            if (!empty(trim($activity))) {
                $stmtActivity->execute([$dayId, trim($activity)]);
            }
        }
    }

    $conn->commit();
    echo json_encode(["status" => "success", "message" => "Itinerary saved successfully!"]);

} catch (PDOException $e) {
    $conn->rollBack();
    error_log("❌ DB Error: " . $e->getMessage());
    echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
} catch (Exception $e) {
    error_log("❌ General Error: " . $e->getMessage());
    echo json_encode(["status" => "error", "message" => "Error: " . $e->getMessage()]);
}
exit;
?>
