<?php

session_start();
require "../../conn.php"; // Move up to the parent directory

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Initialize response array
$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize form data
    $fName = $_POST['firstName'];
    $lName = $_POST['lastName'];
    $mName = $_POST['middleName'];
    $Suffix = $_POST['Suffix'];
    $countryCode = $_POST['countryCode'];
    $contactNo = $_POST['contactNo'];
    $password = $_POST['password'];
    $branchId = $_POST['branchId'];
    $position = $_POST['empPosition'];
    
    // Validation: Ensure required fields are not empty
    if (empty($fName) || empty($lName) || empty($password) || empty($branchId)) {
        $response['status'] = 'error';
        $response['message'] = 'Required fields cannot be empty.';
        echo json_encode($response);
        exit();
    }

    // Hash the password before storing it
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Fetch the latest employeeId from the database
    $sql_max_id = "SELECT MAX(CAST(SUBSTRING(employeeId, 2) AS UNSIGNED)) AS maxId FROM employee";
    $result_max_id = mysqli_query($conn, $sql_max_id);

    if (!$result_max_id) {
        $response['status'] = 'error';
        $response['message'] = "Error fetching max employee ID: " . mysqli_error($conn);
        echo json_encode($response);
        exit();
    }

    $max_id_row = mysqli_fetch_assoc($result_max_id);
    $nextId = ($max_id_row['maxId'] !== null) ? $max_id_row['maxId'] + 1 : 1;

    // Format the new employeeId
    if ($nextId < 10) {
        $newEmployeeId = 'E00' . $nextId;
    } elseif ($nextId < 100) {
        $newEmployeeId = 'E0' . $nextId;
    } else {
        $newEmployeeId = 'E' . $nextId;
    }

    // Insert into accounts table
    $sql_account = "INSERT INTO accounts (email, password, otp, accountStatus, accountType, createdAt) 
                    VALUES (?, ?, '', 'active', 'employee', NOW())";
    $stmt = mysqli_prepare($conn, $sql_account);
    mysqli_stmt_bind_param($stmt, "ss", $newEmployeeId, $hashed_password);

    if (mysqli_stmt_execute($stmt)) {
        $accountId = mysqli_insert_id($conn);

        // Insert employee details
        $sql_employee = "INSERT INTO employee (employeeId, accountId, fName, lName, mName, position,countryCode, contactNo, branch) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt_employee = mysqli_prepare($conn, $sql_employee);
        mysqli_stmt_bind_param($stmt_employee, "sisssssss", $newEmployeeId, $accountId, $fName, $lName, $mName, $position, $countryCode, $contactNo, $branchId);

        if (mysqli_stmt_execute($stmt_employee)) {
            $response['status'] = 'success';
            $response['message'] = "Employee added successfully with ID: $newEmployeeId!";
        } else {
            $response['status'] = 'error';
            $response['message'] = "Error inserting into employees table: " . mysqli_error($conn);
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = "Error inserting into accounts table: " . mysqli_error($conn);
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'Invalid request method.';
}

// Send the response as JSON
echo json_encode($response);

// Close the database connection
mysqli_close($conn);

?>
