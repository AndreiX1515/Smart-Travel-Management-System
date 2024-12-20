<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../conn.php';

ob_clean();
flush();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $accountType = 'guest';

    // Validation: Check if fields are empty
    if (empty($email) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Please fill in both fields']);
        exit;
    }

    // Validation: Check for valid email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Please provide a valid email address']);
        exit;
    }

    // Query the database for the user based on email
    $stmt = $conn->prepare("SELECT * FROM accounts WHERE email = ?");
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Database query error: ' . $conn->error]);
        exit;
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['accountId'] = $user['accountId'];

        // Verify password (plain-text comparison)
        if ($password === $user['password']) {
            if ($accountType !== $user['accountType']) {
                echo json_encode(['success' => false, 'message' => 'Invalid account type.']);
                exit;
            }

            if ($user['accountStatus'] !== 'active') {
                echo json_encode(['success' => false, 'message' => 'Your account is inactive. Please contact support.']);
                exit;
            }

            // Single Session Logic Starts Here
            $accountId = $user['accountId'];
            $currentSessionId = session_id();
            $ipAddress = $_SERVER['REMOTE_ADDR'];
            $userAgent = $_SERVER['HTTP_USER_AGENT'];

            // Check for existing active session
            $sessionCheckStmt = $conn->prepare("SELECT session_id FROM user_sessions WHERE accountid = ?");
            $sessionCheckStmt->bind_param("i", $accountId);
            $sessionCheckStmt->execute();
            $sessionCheckResult = $sessionCheckStmt->get_result();

            if ($sessionCheckResult->num_rows > 0) {
                $existingSession = $sessionCheckResult->fetch_assoc();
                $existingSessionId = $existingSession['session_id'];

                // Remove old session
                $deleteSessionStmt = $conn->prepare("DELETE FROM user_sessions WHERE session_id = ?");
                $deleteSessionStmt->bind_param("s", $existingSessionId);
                $deleteSessionStmt->execute();
                $deleteSessionStmt->close();
            }

            // Insert new session
            $insertStmt = $conn->prepare("
                INSERT INTO user_sessions (session_id, accountid, ip_address, user_agent) 
                VALUES (?, ?, ?, ?)
            ");
            $insertStmt->bind_param("siss", $currentSessionId, $accountId, $ipAddress, $userAgent);
            $insertStmt->execute();
            $insertStmt->close();

            // Update session data with user info
            $_SESSION['email'] = $user['email'];
            $_SESSION['accountStatus'] = $user['accountStatus'];
            $_SESSION['createdAt'] = $user['createdAt'];
            $_SESSION['timeout'] = time();

            echo json_encode(['success' => true, 'message' => 'Logged in successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Invalid email or password.']);
            }
    } else {
        echo json_encode(['success' => false, 'message' => 'User not found.']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>
