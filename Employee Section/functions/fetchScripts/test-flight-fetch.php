<?php
// api/test-flight-data.php
// Test endpoint to debug API issues

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set JSON header first
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

// Start output buffering to catch any unexpected output
ob_start();

try {
    // Test 1: Basic JSON response
    $testResponse = [
        'success' => true,
        'message' => 'API endpoint is working',
        'timestamp' => date('Y-m-d H:i:s'),
        'php_version' => phpversion(),
        'server_info' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'
    ];

    // Test 2: Check if database connection file exists
    $dbConfigPath = '../../../conn.php';
    if (file_exists($dbConfigPath)) {
        $testResponse['database_config'] = 'Found';
        
        // Try to include database connection
        try {
            include_once $dbConfigPath;
            if (isset($conn) && $conn instanceof mysqli) {
                if ($conn->ping()) {
                    $testResponse['database_connection'] = 'Active';
                    
                    // Test a simple query
                    $result = $conn->query("SELECT 1 as test");
                    if ($result) {
                        $testResponse['database_query'] = 'Working';
                        
                        // Check if required tables exist
                        $tables = ['flight', 'employee', 'branch', 'booking'];
                        $existingTables = [];
                        
                        foreach ($tables as $table) {
                            $checkTable = $conn->query("SHOW TABLES LIKE '$table'");
                            if ($checkTable && $checkTable->num_rows > 0) {
                                $existingTables[] = $table;
                            }
                        }
                        
                        $testResponse['existing_tables'] = $existingTables;
                        $testResponse['missing_tables'] = array_diff($tables, $existingTables);
                        
                    } else {
                        $testResponse['database_query'] = 'Failed: ' . $conn->error;
                    }
                } else {
                    $testResponse['database_connection'] = 'Inactive';
                }
            } else {
                $testResponse['database_connection'] = 'Not initialized';
            }
        } catch (Exception $e) {
            $testResponse['database_connection'] = 'Error: ' . $e->getMessage();
        }
    } else {
        $testResponse['database_config'] = 'Not found at: ' . $dbConfigPath;
    }

    // Test 3: Check for any output buffer content (which would break JSON)
    $bufferContent = ob_get_contents();
    if (!empty(trim($bufferContent))) {
        $testResponse['buffer_content'] = $bufferContent;
        $testResponse['warning'] = 'Unexpected output detected - this will break JSON parsing';
    }

    // Clean the buffer
    ob_clean();

    // Output the JSON response
    echo json_encode($testResponse, JSON_PRETTY_PRINT);

} catch (Exception $e) {
    // Clean buffer on error
    ob_clean();
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
    ], JSON_PRETTY_PRINT);
}

// End output buffering
ob_end_flush();
?>