<?php
header('Content-Type: application/json');

// // Disable error output to avoid messing up JSON
// ini_set('display_errors', 0);
// error_reporting(0);

// // Clear any output buffering
// while (ob_get_level()) {
//     ob_end_clean();
// }

// // Check for accidental BOM in file
// $raw = file_get_contents(__FILE__);
// if (ord($raw[0]) === 239) {
//     echo json_encode([
//         'status' => 'error',
//         'message' => 'BOM detected at start of file'
//     ]);
//     exit;
// }

require_once "../../../conn copy.php";

try {
    $stmt = $conn->query("SELECT mealId, mealType, mealName FROM itinerarydatamealplan ORDER BY mealType, mealName");
    $meals = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $structured = [
        'breakfast' => [],
        'lunch' => [],
        'dinner' => [],
        'snack' => []
    ];

    foreach ($meals as $meal) {
        $type = strtolower($meal['mealType']);
        if (isset($structured[$type])) {
            $structured[$type][] = [
                'id' => (int)$meal['mealId'],
                'name' => $meal['mealName']
            ];
        }
    }

    echo json_encode([
        'status' => 'success',
        'data' => $structured
    ]);
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to fetch meals.',
        'error' => $e->getMessage()
    ]);
}
