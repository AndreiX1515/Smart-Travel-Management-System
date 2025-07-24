<?php
session_start();
require_once "../../conn copy.php";

header("Content-Type: application/json");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ========== VALIDATION FUNCTION ==========
function validateItineraryData($data)
{
    $itineraryDetails = $data['itineraryDetails'];

    if (isset($itineraryDetails["noOfDays"]) && !is_numeric($itineraryDetails["noOfDays"])) {
        return ["status" => "error", "message" => "Invalid number of days."];
    }

    if (isset($itineraryDetails["periodStart"]) && empty($itineraryDetails["periodStart"])) {
        return ["status" => "error", "message" => "Invalid start date."];
    }

    if (isset($itineraryDetails["periodEnd"]) && empty($itineraryDetails["periodEnd"])) {
        return ["status" => "error", "message" => "Invalid end date."];
    }

    return null;
}

// ========== CHECK POST INPUT ==========
if (!isset($_POST["itinerary"])) {
    echo json_encode(["status" => "error", "message" => "Missing itinerary data."]);
    exit;
}

$userId = 1; // Replace this with session ID in production
$liveItineraryData = json_decode($_POST["itinerary"], true);



// ========== RUN VALIDATION ==========
$validationResponse = validateItineraryData($liveItineraryData);
if ($validationResponse !== null) {
    echo json_encode($validationResponse);
    exit;
}




// ========== ASSIGN VARIABLES ==========
$itineraryDetails = $liveItineraryData["itineraryDetails"];
$daysDetails = $liveItineraryData["daysDetails"];

$originalName = trim($itineraryDetails["itineraryName"] ?? "Untitled Itinerary");
$originalName = ucwords(strtolower($originalName));

$itineraryIdFromJson = $itineraryDetails["itineraryId"] ?? null;
$isEdit = false;

if ($itineraryIdFromJson) {
    $stmtCheck = $conn->prepare("SELECT itineraryId FROM itineraries WHERE itineraryId = ?");
    $stmtCheck->execute([$itineraryIdFromJson]); // wrap in array
    $existing = $stmtCheck->fetch();

    if ($existing) {
        $isEdit = true;
    }
}

// Add userId for specific user itineraries
// $stmtCheck = $conn->prepare("SELECT itineraryId FROM itineraries WHERE userId = ? AND itineraryId = ?");
// $stmtCheck->execute([$userId, $itineraryIdFromJson]);



// Handle " - Edited" appending
$baseName = $originalName;
if ($isEdit) {
    $baseName = preg_replace('/\s+-\s+Edited/i', '', $baseName);
    $baseName .= " - Edited";
}

// Ensure uniqueness
$templateName = $baseName;
$counter = 1;


$stmtCheckName = $conn->prepare("SELECT COUNT(*) FROM itineraries WHERE itineraryName = ?");
$stmtCheckName->execute([$templateName]);
$nameCount = $stmtCheckName->fetchColumn();


while ($nameCount > 0) {
    $templateName = $baseName . " ($counter)";
    $stmtCheckName->execute([$templateName]);
    $nameCount = $stmtCheckName->fetchColumn();
    $counter++;
}

// ========== BEGIN DB TRANSACTION ==========
try {
    $conn->beginTransaction();

    // Insert itinerary into `itineraries`
    $stmtInsert = $conn->prepare("
        INSERT INTO itineraries (
            itineraryName, noOfDays, packageId,
            periodStart, periodEnd, guideId
        ) VALUES (?, ?, ?, ?, ?, ?)
    ");

    $stmtInsert->execute([
        $templateName ?? '',
        $itineraryDetails["noOfDays"] ?? 0,
        $itineraryDetails["packageId"] ?? '',
        $itineraryDetails["periodStart"] ?? '',
        $itineraryDetails["periodEnd"] ?? '',
        $itineraryDetails["guideId"] ?? '',
    ]);

    $itineraryId = $conn->lastInsertId();

    // Insert into itineraryTourAreasHotels (city1-3 and hotel1-3)
    $stmtTourAH = $conn->prepare("
        INSERT INTO itineraryTourAreasHotels (itineraryId, orderNo, city, hotel, createdAt)
        VALUES (?, ?, ?, ?, NOW())
    ");

    $cities = $itineraryDetails["cities"] ?? [];
    foreach ($cities as $index => $pair) {
        $city = $pair["city"] ?? '';
        $hotel = $pair["hotel"] ?? '';
        if (!empty($city) || !empty($hotel)) {
            $stmtTourAH->execute([
                $itineraryId,
                $index + 1,
                trim($city),
                trim($hotel)
            ]);
        }
    }

    // Prepare reusable insert statements
    $stmtDay      = $conn->prepare("INSERT INTO itineraryDays (itineraryId, dayNumber) VALUES (?, ?)");
    $stmtArea     = $conn->prepare("INSERT INTO itineraryAreas (itineraryId, dayId, areaName) VALUES (?, ?, ?)");
    $stmtHotel    = $conn->prepare("INSERT INTO itineraryHotels (itineraryId, dayId, hotelId) VALUES (?, ?, ?)");
    $stmtMeal     = $conn->prepare("INSERT INTO itineraryMealPlans (itineraryId, dayId, mealId) VALUES (?, ?, ?)");
    $stmtActivity = $conn->prepare("INSERT INTO itineraryActivities (itineraryId, dayId, activityName) VALUES (?, ?, ?)");

    foreach ($daysDetails as $dayData) {
        $dayNumber  = $dayData["day"] ?? 0;
        $areas      = $dayData["areas"] ?? [];
        $hotels     = $dayData["hotels"] ?? [];
        $meals      = $dayData["meals"] ?? [];
        $activities = $dayData["activities"] ?? [];

        $stmtDay->execute([$itineraryId, $dayNumber]);
        $dayId = $conn->lastInsertId();

        foreach ($areas as $area) {
            if (!empty(trim($area))) {
                $stmtArea->execute([$itineraryId, $dayId, trim($area)]);
            }
        }

        foreach ($hotels as $hotel) {
            if (!empty(trim($hotel))) {
                $stmtHotel->execute([$itineraryId, $dayId, trim($hotel)]);
            }
        }

        foreach ($meals as $meal) {
            if (!empty(trim($meal))) {
                $stmtMeal->execute([$itineraryId, $dayId, trim($meal)]);
            }
        }

        foreach ($activities as $activity) {
            if (!empty(trim($activity))) {
                $stmtActivity->execute([$itineraryId, $dayId, trim($activity)]);
            }
        }
    }

    $conn->commit();
    echo json_encode(["status" => "success", "message" => "Itinerary saved successfully!"]);

} catch (Exception $e) {
    $conn->rollBack();
    echo json_encode(["status" => "error", "message" => "Error saving itinerary: " . $e->getMessage()]);

} catch (PDOException $e) {
    $conn->rollBack();
    echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);

} catch (Exception $e) {
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }
    error_log("❌ General Error: " . $e->getMessage());
    echo json_encode(["status" => "error", "message" => "Error: " . $e->getMessage()]);
}

exit;
?>