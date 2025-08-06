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
    // $guideAccountId = $_POST["guideAccountId"] ?? "";

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

    $voucherId = isset($_POST["voucherId"]) ? (int) $_POST["voucherId"] : NULL;

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
            itineraryName, isConnectToVoucher, voucherId,  noOfDays,
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
            $guideName,
            $userId
        ]);


    } else {
        error_log("🟡 Inserting itinerary WITHOUT voucher (isConnectToVoucher = false)");

        $stmtItinerary = $conn->prepare("
        INSERT INTO itineraries (
            itineraryName, noOfDays,
            packageId, periodStart, periodEnd, guideId, createdBy
        ) VALUES (?, ?, ?, ?, ?, ?, ?)
    ");

        $stmtItinerary->execute([
            $templateName,
            $noOfDays,
            $packageName,
            $periodStart,
            $periodEnd,
            $guideName,
            $userId
        ]);
    }

    // ✅ Capture the inserted itinerary ID
    $itineraryId = (int) $conn->lastInsertId();
    error_log("📝 Created itinerary ID: $itineraryId");

    // 🔁 Update the voucher to link to this itinerary
    // if ($isConnectToVoucher === true && $voucherId > 0) {
    //     $stmtUpdateVoucher = $conn->prepare("
    //     UPDATE vouchers
    //     SET itineraryId = ?, isConnectToItinerary = 1
    //     WHERE voucherId = ?
    // ");
    //     $stmtUpdateVoucher->execute([$itineraryId, $voucherId]);

    //     $rowsAffected = $stmtUpdateVoucher->rowCount();
    //     error_log("🔗 Voucher $voucherId now linked to itinerary $itineraryId (rows affected: $rowsAffected)");
    // }


   // Insert Statement for itineraryTourAreasHotels
   $stmtCityHotel = $conn->prepare("INSERT INTO itinerarytourareashotels (itineraryId, orderNo, cityId, city, hotelId, hotel) VALUES (?, ?, ?, ?, ?, ?)");




    function resolveCityAndHotelNames($conn, $cityId, $hotelId) {
        $cityName = '';
        $hotelName = '';

        if (!empty($cityId)) {
            $stmtCity = $conn->prepare("SELECT areaName FROM itinerarydataarea WHERE areaId = ?");
            $stmtCity->execute([$cityId]);
            if ($row = $stmtCity->fetch(PDO::FETCH_ASSOC)) {
                $cityName = $row['areaName'];
            }
        }

        if (!empty($hotelId)) {
            $stmtHotel = $conn->prepare("SELECT hotelName FROM hotels WHERE hotelId = ?");
            $stmtHotel->execute([$hotelId]);
            if ($row = $stmtHotel->fetch(PDO::FETCH_ASSOC)) {
                $hotelName = $row['hotelName'];
            }
        }

        return [
            'cityName' => $cityName,
            'hotelName' => $hotelName
        ];
    }



    // Assume $cityHotels['cities'] is populated from the JSON input
    foreach ($cityHotels['cities'] as $index => $entry) {
        $cityId = isset($entry['city']) ? (int)$entry['city'] : 0;
        $hotelId = isset($entry['hotel']) ? (int)$entry['hotel'] : 0;

        if ($cityId || $hotelId) {
            $names = resolveCityAndHotelNames($conn, $cityId, $hotelId);
            $cityName = $names['cityName'];
            $hotelName = $names['hotelName'];

            error_log("🔍 Attempting Insert for #$index → itineraryId: $itineraryId, cityId: $cityId ($cityName), hotelId: $hotelId ($hotelName)");

            try {
                $success = $stmtCityHotel->execute([
                    $itineraryId,
                    $index,
                    $cityId,
                    $cityName,
                    $hotelId,
                    $hotelName
                ]);

                if ($success) {
                    error_log("✅ Inserted City/Hotel #$index successfully");
                } else {
                    error_log("❌ Insert failed for #$index");
                }
            } catch (PDOException $e) {
                error_log("❌ PDO Exception during insert #$index: " . $e->getMessage());
                echo "Insert failed on row $index: " . $e->getMessage() . "<br>";
            }
        } else {
            error_log("⚠️ Skipped Insert #$index due to missing cityId and hotelId");
        }
    }





    // Prepare insert statements
    $stmtDay = $conn->prepare("INSERT INTO itinerarydays (itineraryId, dayNumber) VALUES (?, ?)");
    $stmtArea = $conn->prepare("INSERT INTO itineraryareas (itineraryId, dayId, areaName, areaId) VALUES (?, ?, ?, ?)");
    $stmtHotel = $conn->prepare("INSERT INTO itineraryhotels (itineraryId, dayId, hotelId, createdAt) VALUES (?, ?, ?, NOW())");
    $stmtMeal = $conn->prepare("INSERT INTO itinerarymealplans (itineraryId, dayId, mealId) VALUES (?, ?, ?)");
    $stmtActivity = $conn->prepare("INSERT INTO itineraryactivities (itineraryId, dayId, activityName) VALUES (?, ?, ?)");


    function getHotelIdsFromNames(PDO $conn, array $hotelNames): array
    {
        if (empty($hotelNames))
            return [];

        $hotelNames = array_map('strtolower', $hotelNames); // normalize
        $placeholders = implode(',', array_fill(0, count($hotelNames), '?'));
        $query = "SELECT hotelId, LOWER(hotelName) as hotelName FROM hotels WHERE LOWER(hotelName) IN ($placeholders)";
        $stmt = $conn->prepare($query);

        if (!$stmt) {
            error_log("❌ Failed to prepare hotel query: " . $conn->errorInfo()[2]);
            return [];
        }

        $stmt->execute($hotelNames);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $hotelMap = [];
        foreach ($result as $row) {
            $hotelMap[$row['hotelName']] = (int) $row['hotelId'];
        }

        $converted = [];
        foreach ($hotelNames as $name) {
            if (isset($hotelMap[$name])) {
                $converted[] = $hotelMap[$name];
            } else {
                error_log("⚠️ Hotel not found in DB: $name");
            }
        }

        error_log("🔁 Final hotel IDs: " . json_encode($converted));
        return $converted;
    }



    // Function: Convert Area Ids to Names
    function getAreaNamesFromIds(PDO $conn, array $areaIds): array
    {
        if (empty($areaIds)) return [];

        $placeholders = implode(',', array_fill(0, count($areaIds), '?'));
        $query = "SELECT areaId, areaName FROM itinerarydataarea WHERE areaId IN ($placeholders)";
        $stmt = $conn->prepare($query);

        if (!$stmt) {
            error_log("❌ Failed to prepare area name query: " . $conn->errorInfo()[2]);
            return [];
        }

        $stmt->execute($areaIds);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $areaMap = [];
        foreach ($result as $row) {
            $areaMap[(int)$row['areaId']] = $row['areaName'];
        }

        $converted = [];
        foreach ($areaIds as $id) {
            if (isset($areaMap[$id])) {
                $converted[] = [
                    'areaId' => $id,
                    'areaName' => $areaMap[$id]
                ];
            } else {
                error_log("⚠️ Area ID not found in DB: $id");
            }
        }

        error_log("🔁 Final area ID-name pairs: " . json_encode($converted));
        return $converted;
    }


    foreach ($itineraryData as $dayData) {
        $dayNumber = $dayData["day"] ?? 0;
        $areas = $dayData["areas"] ?? [];
        $meals = $dayData["meal_plans"] ?? [];
        $hotelIds = array_filter($dayData["hotels"] ?? [], fn($v) => is_numeric($v) && $v > 0);
        $activities = $dayData["itineraries"] ?? [];

        // Insert day
        $stmtDay->execute([$itineraryId, $dayNumber]);
        $dayId = $conn->lastInsertId();
        error_log("📅 Inserted day #$dayNumber → dayId: $dayId");



         // Insert areas
        $areaData = getAreaNamesFromIds($conn, $areas); // $areas should be array of areaIds

        foreach ($areaData as $area) {
            $stmtArea->execute([
                $itineraryId,
                $dayId,
                $area['areaName'],
                $area['areaId']
            ]);
        }

        // Insert hotels (directly as IDs)
        foreach ($hotelIds as $hotelId) {
            // error_log("🏨 Inserting hotelId $hotelId for dayId $dayId, itineraryId $itineraryId");
            $ok = $stmtHotel->execute([$itineraryId, $dayId, $hotelId]);
            if (!$ok) {
                error_log("❌ Hotel insert failed: " . json_encode($stmtHotel->errorInfo()));
            }
        }

        // Insert meals
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
                error_log("⚠️ Invalid mealId (<= 0): $intMeal");
            }
        }

        // Insert activities
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