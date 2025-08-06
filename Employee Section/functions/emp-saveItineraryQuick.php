<?php
session_start();
require_once "../../conn copy.php";

header("Content-Type: application/json");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// Step 1: Parse JSON input
$rawData = file_get_contents("php://input");
$jsonData = json_decode($rawData, true);

if (!$jsonData || !isset($jsonData["templateData"], $jsonData["itineraryData"])) {
    echo json_encode(["success" => false, "message" => "Invalid JSON input or missing fields."]);
    exit;
}

// Extract decoded JSON data
$templateData = $jsonData["templateData"];
$itineraryData = $jsonData["itineraryData"];


// =================================================================== //


// Step 2: Validate required fields
$requiredFields = ['itineraryId', 'to', 'periods', 'guide', 'countryCode', 'contactNo', 'name'];

foreach ($requiredFields as $field) {
    if (!isset($templateData[$field]) || empty($templateData[$field])) {
        echo json_encode([
            'success' => false,
            'message' => "Missing or empty field: $field"
        ]);
        exit;
    }
}

// ✅ Nested validation for 'periods'
if (
    !isset($templateData['periods']['start']) || empty($templateData['periods']['start']) ||
    !isset($templateData['periods']['end']) || empty($templateData['periods']['end'])
) {
    echo json_encode([
        'success' => false,
        'message' => "Missing or empty start or end date in 'periods'"
    ]);
    exit;
}


// =================================================================== //

try {
    // Step 3: Prepare data for insertion
    // Map templateData fields
    $userId = $templateData["userId"] ?? 0;
    $templateName = trim($templateData["name"]);
    $to = $templateData["to"];
    $startDate = $templateData["periods"]["start"];
    $endDate = $templateData["periods"]["end"];
    $guideId = $templateData["guide"] ?? 0;

    $packageName = $itineraryData["packageId"];
    $noOfDays = $itineraryData["noOfDays"];
    $periodStart = $itineraryData["periodStart"];
    $periodEnd = $itineraryData["periodEnd"];
    $guideAccountId = $itineraryData["guideId"];

    $isConnectToVoucher = !empty($templateData["isConnectToVoucher"]);
    $voucherId = isset($templateData["voucherId"]) ? (int) $templateData["voucherId"] : null;

    // Duplicate name check
    $checkStmt = $conn->prepare("SELECT COUNT(*) FROM itineraries WHERE itineraryName = ?");
    $checkStmt->execute([$templateName]);

    if ($checkStmt->fetchColumn() > 0) {
        echo json_encode(["success" => false, "status" => "exists", "message" => "Template name already exists."]);
        exit;
    }

    // Begin transaction
    $conn->beginTransaction();

    if ($isConnectToVoucher === true && $voucherId > 0) {
        $stmtItinerary = $conn->prepare("INSERT INTO itineraries (
            itineraryName, voucherId, isConnectToVoucher, noOfDays,
            packageId, periodStart, periodEnd, guideId, createdBy, createdAt
        ) VALUES (?, ?, 1, ?, ?, ?, ?, ?, ?, NOW())");

        $stmtItinerary->execute([
            $templateName,
            $voucherId,
            $noOfDays,
            $packageName,
            $periodStart,
            $periodEnd,
            $guideAccountId,
            $userId
        ]);
    } else {
        $stmtItinerary = $conn->prepare("INSERT INTO itineraries (
            itineraryName, isConnectToVoucher, noOfDays,
            packageId, periodStart, periodEnd, guideId, createdBy, createdAt
        ) VALUES (?, 0, ?, ?, ?, ?, ?, ?, NOW())");

        $stmtItinerary->execute([
            $templateName,
            $noOfDays,
            $packageName,
            $periodStart,
            $periodEnd,
            $guideAccountId,
            $userId
        ]);
    }

    $itineraryId = (int) $conn->lastInsertId();

    $stmtCityHotel = $conn->prepare("INSERT INTO itinerarytourareashotels (itineraryId, orderNo, cityId, city, hotelId, hotel) VALUES (?, ?, ?, ?, ?, ?)");

    foreach ($itineraryData["cities"] as $order => $cityData) {
        $cityId = $cityData["cityId"] ?? 0;
        $hotelId = $cityData["hotelId"] ?? 0;
        $cityName = $cityData["city"] ?? '';
        $hotelName = $cityData["hotel"] ?? '';

        $stmtCityHotel->execute([
            $itineraryId,
            $order,
            $cityId,
            $cityName,
            $hotelId,
            $hotelName
        ]);
    }

    $stmtDay = $conn->prepare("INSERT INTO itinerarydays (itineraryId, dayNumber) VALUES (?, ?)");
    $stmtArea = $conn->prepare("INSERT INTO itineraryareas (itineraryId, dayId, areaName, areaId) VALUES (?, ?, ?, ?)");
    $stmtHotel = $conn->prepare("INSERT INTO itineraryhotels (itineraryId, dayId, hotelId, createdAt) VALUES (?, ?, ?, NOW())");
    $stmtMeal = $conn->prepare("INSERT INTO itinerarymealplans (itineraryId, dayId, mealId) VALUES (?, ?, ?)");
    $stmtActivity = $conn->prepare("INSERT INTO itineraryactivities (itineraryId, dayId, activityName) VALUES (?, ?, ?)");

    function getAreaNamesFromIds(PDO $conn, array $areaIds): array
    {
        if (empty($areaIds))
            return [];
        $placeholders = implode(',', array_fill(0, count($areaIds), '?'));
        $query = "SELECT areaId, areaName FROM itinerarydataarea WHERE areaId IN ($placeholders)";
        $stmt = $conn->prepare($query);
        $stmt->execute($areaIds);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $areaMap = [];
        foreach ($result as $row) {
            $areaMap[(int) $row['areaId']] = $row['areaName'];
        }
        $converted = [];
        foreach ($areaIds as $id) {
            if (isset($areaMap[$id])) {
                $converted[] = ['areaId' => $id, 'areaName' => $areaMap[$id]];
            }
        }
        return $converted;
    }

    foreach ($itineraryData["days"] as $dayData) {
        $dayNumber = (int) $dayData["day"];
        $areas = $dayData["areas"] ?? [];
        $meals = $dayData["meals"] ?? [];
        $hotels = $dayData["hotels"] ?? [];
        $activities = $dayData["activities"] ?? [];

        $stmtDay->execute([$itineraryId, $dayNumber]);
        $dayId = $conn->lastInsertId();

        $areaData = getAreaNamesFromIds($conn, $areas);
        foreach ($areaData as $area) {
            $stmtArea->execute([
                $itineraryId,
                $dayId,
                $area['areaName'],
                $area['areaId']
            ]);
        }

        foreach ($hotels as $hotelId) {
            $stmtHotel->execute([$itineraryId, $dayId, $hotelId]);
        }

        foreach ($meals as $meal) {
            $stmtMeal->execute([$itineraryId, $dayId, $meal]);
        }

        foreach ($activities as $activity) {
            if (!empty(trim($activity))) {
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