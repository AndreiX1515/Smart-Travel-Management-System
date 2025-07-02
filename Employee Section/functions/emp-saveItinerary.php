<?php
session_start();
require_once "../../conn copy.php";

header("Content-Type: application/json");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    if (!isset($_POST["package"], $_POST["period_start"], $_POST["period_end"], $_POST["itinerary"])) {
        echo json_encode(["status" => "error", "message" => "Missing required fields."]);
        exit;
    }

    // Extract top-level POST data
    $userId = $_POST["userId"] ?? 0;
    $packageName = $_POST["package"];
    $templateName = trim($_POST["templateName"]);
    $noOfDays = $_POST["noOfDays"];
    $periodStart = $_POST["period_start"];
    $periodEnd = $_POST["period_end"];
    $countryCode = $_POST["countryCode"] ?? "";
    $contactNumber = $_POST["contactNumber"] ?? "";
    $guideName = $_POST["guide"] ?? "";
    $guideAccountId = $_POST["guideAccountId"] ?? "";

    $cityHotels = json_decode($_POST["cityHotels"], true);
    $itineraryData = json_decode($_POST["itinerary"], true);

    if (!$itineraryData || !$cityHotels) {
        echo json_encode(["status" => "error", "message" => "Invalid JSON in itinerary or cityHotels."]);
        exit;
    }

    // Check if the itinerary name already exists
    $checkStmt = $conn->prepare("SELECT COUNT(*) FROM itineraries WHERE itineraryName = ?");
    $checkStmt->execute([$templateName]);
    $nameExists = $checkStmt->fetchColumn();

    if ($nameExists > 0) {
        echo json_encode(["status" => "exists", "message" => "Template name already exists. Please choose another one."]);
        exit;
    }

    $conn->beginTransaction();

    // Insert into itineraries
    $stmtItinerary = $conn->prepare("INSERT INTO itineraries 
        (itineraryName, isConnectToVoucher, noOfDays, packageId, periodStart, periodEnd, guideId, createdBy, createdAt)
        VALUES (?, 0, ?, ?, ?, ?, ?, ?, NOW())");

    $stmtItinerary->execute([
        $templateName, $noOfDays, $packageName, $periodStart, $periodEnd, $guideAccountId, $userId
    ]);

    $itineraryId = $conn->lastInsertId();
    error_log("📝 Created itinerary ID: $itineraryId");

    // Insert into itineraryTourAreasHotels
    $stmtCityHotel = $conn->prepare("INSERT INTO itinerarytourareashotels (itineraryId, orderNo, city, hotel) VALUES (?, ?, ?, ?)");
    for ($i = 1; $i <= 3; $i++) {
        $city = $cityHotels["city$i"] ?? "";
        $hotel = $cityHotels["hotel$i"] ?? "";
        if ($city || $hotel) {
            $stmtCityHotel->execute([$itineraryId, $i, $city, $hotel]);
            error_log("🏨 City/Hotel $i → $city / $hotel");
        }
    }

    // Prepare day & detail inserts
    $stmtDay = $conn->prepare("INSERT INTO itinerarydays (itineraryId, dayNumber) VALUES (?, ?)");
    $stmtArea = $conn->prepare("INSERT INTO itineraryareas (itineraryId, dayId, areaName) VALUES (?, ?, ?)");
    $stmtHotel = $conn->prepare("INSERT INTO itineraryhotels (itineraryId, dayId, hotelName) VALUES (?, ?, ?)");
    $stmtMeal = $conn->prepare("INSERT INTO itinerarymealplans (itineraryId, dayId, mealId) VALUES (?, ?, ?)");
    $stmtActivity = $conn->prepare("INSERT INTO itineraryactivities (itineraryId, dayId, activityName) VALUES (?, ?, ?)");

    foreach ($itineraryData as $dayData) {
        $dayNumber = $dayData["day"] ?? 0;
        $areas = $dayData["areas"] ?? [];
        $hotels = $dayData["hotels"] ?? [];
        $meals = $dayData["meal_plans"] ?? [];
        $activities = $dayData["itineraries"] ?? [];

        $stmtDay->execute([$itineraryId, $dayNumber]);
        $dayId = $conn->lastInsertId();
        error_log("📅 Inserted day #$dayNumber → dayId: $dayId");

        foreach ($areas as $area) {
            $stmtArea->execute([$itineraryId, $dayId, $area]);
        }

        foreach ($hotels as $hotel) {
            $stmtHotel->execute([$itineraryId, $dayId, $hotel]);
        }

        foreach ($meals as $meal) {
            if (!is_numeric($meal)) {
                error_log("⚠️ Skipping non-numeric meal value: " . var_export($meal, true));
                continue;
            }

            $intMeal = (int)$meal;

            // Skip if zero or less (assuming no mealId = 0 exists)
            if ($intMeal <= 0) {
                error_log("⚠️ Skipping invalid mealId (<= 0): $intMeal");
                continue;
            }

            error_log("🍽️ Inserting mealId: $intMeal for dayId: $dayId");
            $stmtMeal->execute([$itineraryId, $dayId, $intMeal]);
        }




        foreach ($activities as $activity) {
            if (!empty($activity)) {
                $stmtActivity->execute([$itineraryId, $dayId, $activity]);
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
