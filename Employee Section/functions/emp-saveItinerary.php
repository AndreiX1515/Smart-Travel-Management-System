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

    // 🧠 Extract and normalize voucher-related fields from POST
    $isConnectToVoucher = isset($_POST["isConnectToVoucher"]) && $_POST["isConnectToVoucher"] === "true";
    $voucherId = isset($_POST["voucherId"]) ? (int) $_POST["voucherId"] : 0;

    // ✅ Check if the voucher is already linked (only if both are valid)
    if ($isConnectToVoucher === true && $voucherId > 0) {
        $stmtCheck = $conn->prepare("SELECT itineraryId FROM vouchers WHERE voucherId = ?");
        $stmtCheck->execute([$voucherId]);
        $existing = (int) $stmtCheck->fetchColumn();

        if ($existing !== 0) {
            echo json_encode([
                "status" => "linked",
                "message" => "This voucher is already connected to itinerary ID: $existing."
            ]);
            exit;
        }
    }

    // ✅ Begin transaction AFTER voucher check
    $conn->beginTransaction();




    // ✍ Insert into `itineraries` table
    if ($isConnectToVoucher === true && $voucherId > 0) {
        error_log("🟢 Inserting itinerary with voucherId $voucherId (isConnectToVoucher = true)");

        $stmtItinerary = $conn->prepare("
        INSERT INTO itineraries (
            itineraryName, voucherId, isConnectToVoucher, noOfDays,
            packageId, periodStart, periodEnd, guideId, createdBy, createdAt
        ) VALUES (?, ?, 1, ?, ?, ?, ?, ?, ?, NOW())
    ");

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
        error_log("🟡 Inserting itinerary WITHOUT voucher (isConnectToVoucher = false)");

        $stmtItinerary = $conn->prepare("
        INSERT INTO itineraries (
            itineraryName, isConnectToVoucher, noOfDays,
            packageId, periodStart, periodEnd, guideId, createdBy, createdAt
        ) VALUES (?, 0, ?, ?, ?, ?, ?, ?, NOW())
    ");

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

    // ✅ Capture the inserted itinerary ID
    $itineraryId = (int) $conn->lastInsertId();
    error_log("📝 Created itinerary ID: $itineraryId");

    // 🔁 Update the voucher to link to this itinerary
    if ($isConnectToVoucher === true && $voucherId > 0) {
        $stmtUpdateVoucher = $conn->prepare("
        UPDATE vouchers
        SET itineraryId = ?, isConnectToItinerary = 1
        WHERE voucherId = ?
    ");
        $stmtUpdateVoucher->execute([$itineraryId, $voucherId]);

        $rowsAffected = $stmtUpdateVoucher->rowCount();
        error_log("🔗 Voucher $voucherId now linked to itinerary $itineraryId (rows affected: $rowsAffected)");
    }








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
    $stmtHotel = $conn->prepare("INSERT INTO itineraryhotels (itineraryId, dayId, hotelId) VALUES (?, ?, ?)");
    $stmtMeal = $conn->prepare("INSERT INTO itinerarymealplans (itineraryId, dayId, mealId) VALUES (?, ?, ?)");
    $stmtActivity = $conn->prepare("INSERT INTO itineraryactivities (itineraryId, dayId, activityName) VALUES (?, ?, ?)");


    function getHotelIdsFromNames(PDO $conn, array $hotelNames): array
    {
        if (empty($hotelNames))
            return [];

        $placeholders = implode(',', array_fill(0, count($hotelNames), '?'));
        $query = "SELECT hotelId, hotelName FROM hotels WHERE hotelName IN ($placeholders)";
        $stmt = $conn->prepare($query);

        if (!$stmt) {
            error_log("❌ Failed to prepare hotel query: " . $conn->errorInfo()[2]);
            return [];
        }

        $stmt->execute($hotelNames);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $hotelMap = [];
        foreach ($result as $row) {
            $hotelMap[$row['hotelName']] = $row['hotelId'];
        }

        $converted = [];
        foreach ($hotelNames as $name) {
            if (isset($hotelMap[$name])) {
                $converted[] = $hotelMap[$name];
            } else {
                error_log("⚠️ Hotel not found in DB: $name");
            }
        }

        return $converted;
    }


    foreach ($itineraryData as $dayData) {
        $dayNumber = $dayData["day"] ?? 0;
        $areas = $dayData["areas"] ?? [];
        $meals = $dayData["meal_plans"] ?? [];
        $activities = $dayData["itineraries"] ?? [];
        $hotelNames = array_map('trim', $dayData["hotels"] ?? []);

        // Insert day
        $stmtDay->execute([$itineraryId, $dayNumber]);
        $dayId = $conn->lastInsertId();
        error_log("📅 Inserted day #$dayNumber → dayId: $dayId");

        // Insert areas
        foreach ($areas as $area) {
            $stmtArea->execute([$itineraryId, $dayId, $area]);
        }

        // Convert hotel names to IDs and insert
        $hotelIds = getHotelIdsFromNames($conn, $hotelNames);
        foreach ($hotelIds as $hotelId) {
            $stmtHotel->execute([$itineraryId, $dayId, $hotelId]);
        }

        // Insert valid meals
        foreach ($meals as $meal) {
            if (!is_numeric($meal)) {
                error_log("⚠️ Skipping non-numeric meal value: " . var_export($meal, true));
                continue;
            }

            $intMeal = (int) $meal;
            if ($intMeal > 0) {
                error_log("🍽️ Inserting mealId: $intMeal for dayId: $dayId");
                $stmtMeal->execute([$itineraryId, $dayId, $intMeal]);
            } else {
                error_log("⚠️ Skipping invalid mealId (<= 0): $intMeal");
            }
        }

        // Insert activities
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