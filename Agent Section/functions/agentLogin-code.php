<?php
require "../../conn.php";
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$response = []; // Initialize response array

session_start();
require "../../conn.php"; // Database connection

header("Content-Type: application/json");
$response = ["success" => false, "message" => "Invalid request."];

// Check if login form is submitted
if (isset($_POST['login'])) {
    $email = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!empty($email) && !empty($password)) {
        // Verify account existence and fetch details
        $sqlAccount = "SELECT * FROM accounts WHERE email = ?";
        $stmtAccount = $conn->prepare($sqlAccount);
        $stmtAccount->bind_param('s', $email);
        $stmtAccount->execute();
        $resultAccount = $stmtAccount->get_result();

        if ($resultAccount->num_rows > 0) {
            $account = $resultAccount->fetch_assoc();
            

            // Verify password (Simple comparison instead of password_verify)
            if ($password === $account['password']) {
                // Check if account is active
                if ($account['accountStatus'] === 'active') {

                    $accountId = $account['accountId'];
                    // Check if accountId exists in user_sessions
                    $sqlSession = "SELECT COUNT(*) AS sessionCount FROM user_sessions WHERE accountid = ?";
                    $stmtSession = $conn->prepare($sqlSession);
                    $stmtSession->bind_param('i', $accountId);
                    $stmtSession->execute();
                    $resultSession = $stmtSession->get_result();
                    $sessionData = $resultSession->fetch_assoc(); 
                    $stmtSession->close();

                    if ($sessionData['sessionCount'] > 0) {
                         // If a session exists, send an error response
                        $response['success'] = false;
                        $response['message'] = "You are logged in on another device. Please close from other tab or devices then reload before logging in again!";

                        
                    } else {
                        // Handle login based on account type
                        if ($accountType === 'agent') {
                            handleLogin($accountId, 'agent', "SELECT * FROM agent WHERE accountId = ?", ['branchId']);

                        } elseif ($accountType === 'employee') {
                            handleLogin($accountId, 'employee', "SELECT * FROM employee WHERE accountId = ?", ['position', 'countryCode', 'contactNo', 'branch']);

                        } elseif ($accountType === 'guest') {
                            handleLogin($accountId, 'guest', "SELECT * FROM agent WHERE accountId = ?", ['position', 'countryCode', 'contactNo', 'branch']);
                            
                        } else {
                            $response['message'] = "Invalid account type.";
                            echo json_encode($response);
                            exit;
                        }

                        // Store email and password in session
                        $_SESSION['email'] = $email;
                        $_SESSION['password'] = $password;

                        // Successful login response
                        $response['success'] = true;
                        $response['message'] = "Login successful.";
                        $response['accountType'] = $accountType;
                    }
                } else {
                    $response['message'] = "Your account is inactive. Please contact the administrator.";
                }
            } else {
                $response['message'] = "Incorrect password.";
            }
        } else {
            $response['message'] = "Account does not exist.";
        }

        $stmtAccount->close();
    } else {
        $response['message'] = "Please fill in both fields.";
    }
}

$conn->close();
echo json_encode($response);


// Function to handle login and session management
function handleLogin($accountId, $userType, $query, $additionalFields = [])
{
    global $conn, $response;

    // Prepare and execute query
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $accountId);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();  // Close statement after use

    if ($result->num_rows > 0) {
        $userDetails = $result->fetch_assoc();
        
        // Delegate to the correct session management function based on user type
        if ($userType === 'agent') {
            manageAgentSession($accountId, $userDetails, $userType, $additionalFields);
        } elseif ($userType === 'employee') {
            manageEmployeeSession($accountId, $userDetails, $userType, $additionalFields);
        } elseif ($userType === 'guest') {
            manageAgentSession($accountId, $userDetails, $userType, $additionalFields);
        } 

        $response['success'] = true;
        $response['message'] = ucfirst($userType) . " login successful.";
    } 
    
    else {
        $response['success'] = false;
        $response['message'] = ucfirst($userType) . " details not found.";
    }
}

// Function to manage session for agents
function manageAgentSession($accountId, $userData, $userType, $additionalFields)
{
    global $conn;

    $session_id = session_id();
    $ip_address = $_SERVER['REMOTE_ADDR'] ?? '';
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';

    date_default_timezone_set('Asia/Taipei'); // Set to your preferred timezone
    $login_time = date('Y-m-d H:i:s');
    $last_activity = $login_time;


    // Check if there's an existing session for this account
    $session_check_stmt = $conn->prepare("SELECT session_id FROM user_sessions WHERE accountid = ?");
    $session_check_stmt->bind_param("i", $accountId);
    $session_check_stmt->execute();
    $session_check_result = $session_check_stmt->get_result();

    if ($session_check_result->num_rows > 0) {
        // Terminate the existing session if one is found
        $existing_session = $session_check_result->fetch_assoc();
        $existing_session_id = $existing_session['session_id'];

        // Delete the old session
        $delete_stmt = $conn->prepare("DELETE FROM user_sessions WHERE session_id = ?");
        $delete_stmt->bind_param("s", $existing_session_id);
        $delete_stmt->execute();
        $delete_stmt->close();
    }

    // Regenerate session ID to ensure session security
    session_regenerate_id(true);
    $new_session_id = session_id();

    $_SESSION['accountId'] = $accountId;
    $_SESSION['userType'] = $userType;
    $_SESSION['fName'] = $userData['fName'] ?? '';
    $_SESSION['mName'] = $userData['mName'] ?? '';
    $_SESSION['lName'] = $userData['lName'] ?? '';
    $_SESSION['agentId'] = $userData['agentId'] ?? '';  // Added agent_id to session
    $_SESSION['agentCode'] = $userData['agentCode'] ?? '';  // Added agent_Code to session
    $_SESSION['agentRole'] = $userData['agentRole'] ?? '';  // Added agent_agentRole to session
    $_SESSION['agentType'] = $userData['agentType'] ?? '';  // Added agent_agentType to session
    $_SESSION['branchId'] = $userData['branchId'] ?? '';  // Added branch_id to session
    $_SESSION['timeout'] = time();

    // Store additional fields in session if provided
    // foreach ($additionalFields as $field) {
    //     $_SESSION['agent_' . $field] = $userData[$field] ?? null;
    // }

    // Insert new session into the user_sessions table
    $insert_stmt = $conn->prepare(
        "INSERT INTO user_sessions (session_id, accountid, login_time, last_activity, ip_address, user_agent) 
        VALUES (?, ?, ?, ?, ?, ?)"
    );
    $insert_stmt->bind_param("sissss", $new_session_id, $accountId, $login_time, $last_activity, $ip_address, $user_agent);
    $insert_stmt->execute();
    $insert_stmt->close();
}

// Function to manage session for employees
function manageEmployeeSession($accountId, $userData, $userType, $additionalFields)
{
    global $conn;

    $session_id = session_id();
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    $login_time = date('Y-m-d H:i:s');
    $last_activity = $login_time;

    // Check if there's an existing session for this account
    $session_check_stmt = $conn->prepare("SELECT session_id FROM user_sessions WHERE accountid = ?");
    $session_check_stmt->bind_param("i", $accountId);
    $session_check_stmt->execute();
    $session_check_result = $session_check_stmt->get_result();

    if ($session_check_result->num_rows > 0) {
        // Terminate the existing session if one is found
        $existing_session = $session_check_result->fetch_assoc();
        $existing_session_id = $existing_session['session_id'];

        // Delete the old session
        $delete_stmt = $conn->prepare("DELETE FROM user_sessions WHERE session_id = ?");
        $delete_stmt->bind_param("s", $existing_session_id);
        $delete_stmt->execute();
        $delete_stmt->close();
    }

    // Regenerate session ID to ensure session security
    session_regenerate_id(true);
    $new_session_id = session_id();

    // Store session data specifically for employee
    $_SESSION['employee_accountId'] = $accountId;
    $_SESSION['employee_userType'] = $userType;
    $_SESSION['employee_fName'] = $userData['fName'] ?? '';
    $_SESSION['employee_mName'] = $userData['mName'] ?? '';
    $_SESSION['employee_lName'] = $userData['lName'] ?? '';
    $_SESSION['employee_employeeId'] = $userData['employeeId'] ?? '';  
    $_SESSION['employee_accountId'] = $accountId;
    $_SESSION['employee_position'] = $userData['position'] ?? '';  
    $_SESSION['employee_timeout'] = time();

    // Store additional fields in session if provided
    foreach ($additionalFields as $field) {
        $_SESSION['employee_' . $field] = $userData[$field] ?? null;
    }

    // Insert new session into the user_sessions table
    $insert_stmt = $conn->prepare(
        "INSERT INTO user_sessions (session_id, accountid, login_time, last_activity, ip_address, user_agent) 
        VALUES (?, ?, ?, ?, ?, ?)"
    );
    $insert_stmt->bind_param("sissss", $new_session_id, $accountId, $login_time, $last_activity, $ip_address, $user_agent);
    $insert_stmt->execute();
    $insert_stmt->close();
}

// function manageGuestSession($accountId, $userData, $userType, $additionalFields)
// {
//     global $conn;

//     $session_id = session_id();
//     $ip_address = $_SERVER['REMOTE_ADDR'];
//     $user_agent = $_SERVER['HTTP_USER_AGENT'];
//     $login_time = date('Y-m-d H:i:s');
//     $last_activity = $login_time;

//     // Check if there's an existing session for this account
//     $session_check_stmt = $conn->prepare("SELECT session_id FROM user_sessions WHERE accountid = ?");
//     $session_check_stmt->bind_param("i", $accountId);
//     $session_check_stmt->execute();
//     $session_check_result = $session_check_stmt->get_result();

//     if ($session_check_result->num_rows > 0) {
//         // Terminate the existing session if one is found
//         $existing_session = $session_check_result->fetch_assoc();
//         $existing_session_id = $existing_session['session_id'];

//         // Delete the old session
//         $delete_stmt = $conn->prepare("DELETE FROM user_sessions WHERE session_id = ?");
//         $delete_stmt->bind_param("s", $existing_session_id);
//         $delete_stmt->execute();
//         $delete_stmt->close();
//     }

//     // Regenerate session ID to ensure session security
//     session_regenerate_id(true);
//     $new_session_id = session_id();

//     // Store session data specifically for employee
//     $_SESSION['employee_accountId'] = $accountId;
//     $_SESSION['employee_userType'] = $userType;
//     $_SESSION['employee_fName'] = $userData['fName'] ?? '';
//     $_SESSION['employee_mName'] = $userData['mName'] ?? '';
//     $_SESSION['employee_lName'] = $userData['lName'] ?? '';
//     $_SESSION['employee_employeeId'] = $userData['employeeId'] ?? '';  
//     $_SESSION['employee_accountId'] = $accountId;
//     $_SESSION['employee_position'] = $userData['position'] ?? '';  
//     $_SESSION['employee_timeout'] = time();

//     // Store additional fields in session if provided
//     foreach ($additionalFields as $field) {
//         $_SESSION['employee_' . $field] = $userData[$field] ?? null;
//     }

//     // Insert new session into the user_sessions table
//     $insert_stmt = $conn->prepare(
//         "INSERT INTO user_sessions (session_id, accountid, login_time, last_activity, ip_address, user_agent) 
//         VALUES (?, ?, ?, ?, ?, ?)"
//     );
//     $insert_stmt->bind_param("sissss", $new_session_id, $accountId, $login_time, $last_activity, $ip_address, $user_agent);
//     $insert_stmt->execute();
//     $insert_stmt->close();
// }

?>