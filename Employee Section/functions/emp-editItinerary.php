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

    // Step 1: Normalize the original name
    $originalName = trim($itineraryDetails["itineraryName"] ?? "Untitled Itinerary");
    $originalName = ucwords(strtolower($originalName));

    // Step 2: Check if itineraryId exists and if the record exists in the DB
    $itineraryIdFromJson = $itineraryDetails["itineraryId"] ?? null;
    $isEdit = false;

    if ($itineraryIdFromJson) {
        $stmtCheck = $conn->prepare("SELECT itineraryId FROM itineraries WHERE userId = ? AND itineraryId = ?");
        $stmtCheck->execute([$userId, $itineraryIdFromJson]);
        $existing = $stmtCheck->fetch();

        if ($existing) {
            $isEdit = true;
        }
    }

    // Step 3: Prepare base name
    $baseName = $originalName;

    // Prevent duplicate appending of " - Edited"
    if ($isEdit) {
        // Remove any existing " - Edited" and trim it
        $baseName = preg_replace('/\s+-\s+Edited/i', '', $baseName);
        $baseName .= " - Edited";
    }

    // Step 4: Check for name conflicts and append (1), (2), etc.
    $templateName = $baseName;
    $counter = 1;

    $stmtCheckName = $conn->prepare("SELECT COUNT(*) FROM itineraries WHERE userId = ? AND itineraryName = ?");
    $stmtCheckName->execute([$userId, $templateName]);
    $nameCount = $stmtCheckName->fetchColumn();

    while ($nameCount > 0) {
        $templateName = $baseName . " ($counter)";
        $stmtCheckName->execute([$userId, $templateName]);
        $nameCount = $stmtCheckName->fetchColumn();
        $counter++;
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
    $templateName ?? '',
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
