<?php

require_once "../../../conn copy.php";
header('Content-Type: application/json');

// Optional BOM check
if (ord(substr(file_get_contents(__FILE__), 0, 1)) === 239) {
    echo json_encode([
        'status' => 'error',
        'message' => 'BOM detected at start of file'
    ]);
    exit;
}

try {
    $stmt = $conn->query("SELECT mealId, mealType, mealName FROM itineraryDataMealPlan ORDER BY mealType, mealName");
    $meals = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $structured = [
        'breakfast' => [],
        'lunch' => [],
        'dinner' => []
    ];

    foreach ($meals as $meal) {
        $type = $meal['mealType'];
        if (isset($structured[$type])) {
            $structured[$type][] = [
                'id' => (int) $meal['mealId'],
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
