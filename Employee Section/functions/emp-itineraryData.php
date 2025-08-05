<?php
header('Content-Type: application/json');

require_once '../../conn.php';

// Enable error reporting for debugging
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

error_log("✅ SCRIPT STARTED");

// Parse raw JSON input
$rawInput = file_get_contents('php://input');
error_log("📥 RAW INPUT: " . $rawInput);

$data = json_decode($rawInput, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    error_log("❌ JSON DECODE ERROR: " . json_last_error_msg());
    echo json_encode(['success' => false, 'message' => 'Invalid JSON']);
    exit;
}

// Extract itineraryId
$itineraryId = intval($data['itineraryId']);

error_log("ℹ️ itineraryId: $itineraryId");

// Validate itineraryId
if (!isset($data['itineraryId']) || !is_numeric($data['itineraryId'])) {
    error_log("❌ Invalid or missing itineraryId");
    echo json_encode(['success' => false, 'message' => 'Invalid itinerary ID']);
    exit;
}

$itinerary = [];

// --- MAIN ITINERARY DETAILS ---
$sql = "SELECT 
    i.itineraryId, i.itineraryName, i.noOfDays, i.packageId,
    i.periodStart, i.periodEnd, i.voucherId, v.voucherCode,
    e.accountId AS guideId, e.fName AS guideFirstName, e.lName AS guideLastName,
    e.countryCode, e.contactNo
  FROM itineraries i
  LEFT JOIN employee e ON i.guideId = e.accountId
  LEFT JOIN vouchers v ON i.voucherId = v.voucherId
  WHERE i.itineraryId = ?";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    error_log("❌ Prepare failed (main): " . $conn->error);
}
$stmt->bind_param("i", $itineraryId);
$stmt->execute();
$result = $stmt->get_result();

if (!$row = $result->fetch_assoc()) {
    error_log("❌ No itinerary found for ID $itineraryId");
    echo json_encode(['success' => false, 'message' => 'Itinerary not found']);
    exit;
}

$guideName = trim($row['guideFirstName'] . ' ' . $row['guideLastName']);
$itinerary = [
    'itineraryId' => $row['itineraryId'],
    'itineraryName' => $row['itineraryName'],
    'noOfDays' => $row['noOfDays'],
    'packageId' => $row['packageId'],
    'periodStart' => $row['periodStart'],
    'periodEnd' => $row['periodEnd'],
    'guideName' => $guideName,
    'guideId' => $row['guideId'],
    'countryCode' => $row['countryCode'],
    'contactNumber' => $row['contactNo'],
    'voucherId' => $row['voucherId'],
    'voucherCode' => $row['voucherCode'],
    'cities' => [],
    'days' => []
];

error_log("✅ Main itinerary loaded");

// --- CITIES & HOTELS ---
$sqlCityHotel = "SELECT cityId, city, hotelId, hotel FROM itinerarytourareashotels WHERE itineraryId = ? ORDER BY orderNo ASC";
$stmt = $conn->prepare($sqlCityHotel);
if (!$stmt) {
    error_log("❌ Prepare failed (city/hotel): " . $conn->error);
}
$stmt->bind_param("i", $itineraryId);
$stmt->execute();
$result = $stmt->get_result();

$index = 1;
while ($rowCity = $result->fetch_assoc()) {
    $itinerary['cities']["$index"] = [
        "cityId" => (int) $rowCity['cityId'],
        "city" => $rowCity['city'],
        "hotelId" => (int) $rowCity['hotelId'],
        "hotel" => $rowCity['hotel']
    ];
    $index++;
}
error_log("✅ Cities & hotels loaded");

// --- DAYS ---
$sqlDays = "
	SELECT 
        d.dayId, 
        d.dayNumber, 
        COALESCE(a.areaIds, '') AS areas,
        COALESCE(h.hotelIds, '') AS hotels,
        COALESCE(act.activities, '') AS activities,
        COALESCE(mp.mealIds, '') AS meals
    FROM itinerarydays d
    LEFT JOIN (
        SELECT dayId, GROUP_CONCAT(DISTINCT ida.areaId ORDER BY ida.areaId ASC SEPARATOR ',') AS areaIds
        FROM itineraryareas ia
        INNER JOIN itinerarydataarea ida ON ia.areaName = ida.areaName
        GROUP BY dayId
    ) a ON d.dayId = a.dayId
    LEFT JOIN (
        SELECT ih.dayId, GROUP_CONCAT(DISTINCT h.hotelId ORDER BY h.hotelId ASC SEPARATOR ',') AS hotelIds
        FROM itineraryhotels ih
        INNER JOIN hotels h ON ih.hotelId = h.hotelId
        GROUP BY ih.dayId
    ) h ON d.dayId = h.dayId
    LEFT JOIN (
        SELECT dayId, GROUP_CONCAT(activityName ORDER BY activityId ASC SEPARATOR ',') AS activities
        FROM itineraryactivities
        GROUP BY dayId
    ) act ON d.dayId = act.dayId
    LEFT JOIN (
        SELECT imp.dayId, GROUP_CONCAT(DISTINCT md.mealId ORDER BY md.mealId ASC SEPARATOR ',') AS mealIds
        FROM itinerarymealplans imp
        INNER JOIN itinerarydatamealplan md ON imp.mealId = md.mealId
        GROUP BY imp.dayId
    ) mp ON d.dayId = mp.dayId
    WHERE d.itineraryId = ?
    ORDER BY d.dayNumber ASC;

";

$stmt = $conn->prepare($sqlDays);
if (!$stmt) {
    error_log("❌ Prepare failed (days): " . $conn->error);
}
$stmt->bind_param("i", $itineraryId);
$stmt->execute();
$result = $stmt->get_result();

while ($day = $result->fetch_assoc()) {
    $itinerary['days'][] = [
        'day' => $day['dayNumber'],
        'areas' => $day['areas'] ? explode(',', $day['areas']) : [],
        'hotels' => $day['hotels'] ? explode(',', $day['hotels']) : [],
        'activities' => $day['activities'] ? explode(',', $day['activities']) : [],
        'meals' => $day['meals'] ? explode(',', $day['meals']) : []
    ];
}
error_log("✅ Days loaded");

// --- FINAL JSON OUTPUT ---
$response = [
    'success' => true,
    'itineraryId' => $itineraryId,
    'itinerary' => $itinerary
];

$jsonOutput = json_encode($response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
if ($jsonOutput === false) {
    error_log("❌ JSON ENCODE ERROR: " . json_last_error_msg());
    echo json_encode(['success' => false, 'message' => 'Failed to encode JSON']);
    exit;
}

error_log("✅ JSON ready for output");
echo $jsonOutput;
exit;
