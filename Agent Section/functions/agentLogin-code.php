<?php
require "../../conn.php";
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$response = []; // Initialize response array

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (!empty($username) && !empty($password)) {
        $sql1 = "SELECT * FROM accounts WHERE email = ? and accountType = 'agent'";
        $stmt1 = $conn->prepare($sql1);
        $stmt1->bind_param('s', $username);
        $stmt1->execute();
        $result1 = $stmt1->get_result();
        
        if ($result1->num_rows > 0) {
            $user = $result1->fetch_assoc();

            if ($password === $user['password']) {
                $sql2 = "SELECT * FROM agent WHERE accountId = ?";
                $stmt2 = $conn->prepare($sql2);
                $stmt2->bind_param('i', $user['accountId']);
                $stmt2->execute();
                $agentResult = $stmt2->get_result();

                if ($agentResult->num_rows > 0) {
                    $agent = $agentResult->fetch_assoc();

                    // Single session logic
                    $accountId = $agent['accountId'];
                    $current_session_id = session_id();
                    $ip_address = $_SERVER['REMOTE_ADDR'];
                    $user_agent = $_SERVER['HTTP_USER_AGENT'];

                    $session_check_stmt = $conn->prepare("SELECT session_id FROM user_sessions WHERE accountid = ?");
                    $session_check_stmt->bind_param("i", $accountId);
                    $session_check_stmt->execute();
                    $session_check_result = $session_check_stmt->get_result();

                    if ($session_check_result->num_rows > 0) {
                        $existing_session = $session_check_result->fetch_assoc();
                        $existing_session_id = $existing_session['session_id'];

                        $delete_stmt = $conn->prepare("DELETE FROM user_sessions WHERE session_id = ?");
                        $delete_stmt->bind_param("s", $existing_session_id);
                        $delete_stmt->execute();
                        $delete_stmt->close();

                        $response['message'] = "Previous session terminated. You are now logged in on this device.";
                    }

                    session_regenerate_id(true);
                    $new_session_id = session_id();

                    $_SESSION['accountId'] = $agent['accountId'];
                    $_SESSION['agentId'] = $agent['agentId'];
                    $_SESSION['fName'] = $agent['fName'];
                    $_SESSION['mName'] = $agent['mName'];
                    $_SESSION['lName'] = $agent['lName'];
                    $_SESSION['branch'] = $agent['branch'];
                    $_SESSION['timeout'] = time();

                    $insert_stmt = $conn->prepare("INSERT INTO user_sessions (session_id, accountid, ip_address, user_agent) VALUES (?, ?, ?, ?)");
                    $insert_stmt->bind_param("siss", $new_session_id, $accountId, $ip_address, $user_agent);
                    $insert_stmt->execute();
                    $insert_stmt->close();

                    $response['success'] = true;
                }
            } else {
                $response['success'] = false;
                $response['message'] = "Incorrect username or password. Please try again.";
            }
        } else {
            $response['success'] = false;
            $response['message'] = "Incorrect username or password. Please try again.";
        }

        $stmt1->close();
    } else {
        $response['success'] = false;
        $response['message'] = "Please fill in both fields.";
    }
} else {
    $response['success'] = false;
    $response['message'] = "Invalid request.";
}

header('Content-Type: application/json');
echo json_encode($response);
?>
