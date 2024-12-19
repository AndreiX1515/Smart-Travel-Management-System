<?php
session_start(); // Start session to store user data

// Include your database connection
include 'conn.php'; // Adjust this to match your actual database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    
    // Validation: Check if fields are empty
    if (empty($email) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Please fill in both fields.']);
        exit;
    }
    
    // Validation: Check for valid email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Please provide a valid email address.']);
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
        // Fetch user data
        $user = $result->fetch_assoc();
    
        // Verify the password (plaintext comparison)
        if ($password === trim($user['upassword'])) {
            
            if ($user['account_status'] !== 'active') {
                echo json_encode(['success' => false, 'message' => 'Your account is inactive. Please contact support.']);
                exit;
            }

            // Single Session Logic Starts Here
            $accountid = $user['accountid'];
            $current_session_id = session_id();
            $ip_address = $_SERVER['REMOTE_ADDR'];
            $user_agent = $_SERVER['HTTP_USER_AGENT'];

            // Check if there's an existing active session for this user
            $session_check_stmt = $conn->prepare("SELECT session_id FROM user_sessions WHERE accountid = ?");
            $session_check_stmt->bind_param("i", $accountid);
            $session_check_stmt->execute();
            $session_check_result = $session_check_stmt->get_result();
            
            // Check if an existing session is found
            if ($session_check_result->num_rows > 0) {
                // Existing session found, get the session details
                $existing_session = $session_check_result->fetch_assoc();
                $existing_session_id = $existing_session['session_id'];

                // Delete the existing session from the user_sessions table
                $delete_stmt = $conn->prepare("DELETE FROM user_sessions WHERE session_id = ?");
                $delete_stmt->bind_param("s", $existing_session_id);
                $delete_stmt->execute();
                $delete_stmt->close();

                // Notify user (optional)
                echo json_encode(['success' => true, 'message' => 'Previous session terminated. You are now logged in on this device.']);
            } else {
                // No existing session found, proceed with new login
                echo json_encode(['success' => true, 'message' => 'Logged in successfully.']);
            }

            // Continue with the new session creation and login flow here
            // ...


            // Regenerate session ID to prevent session fixation
            session_regenerate_id(true);
            $new_session_id = session_id();

            $_SESSION['accountid'] = $user['accountid'];
            $_SESSION['email'] = $user['email']; // Already included
            $_SESSION['password'] = $user['password']; // Adding password to session (if necessary)
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['last_name'] = $user['last_name'];
            $_SESSION['middle_name'] = $user['middle_name'];
            $_SESSION['account_status'] = $user['account_status'];
            $_SESSION['created_at'] = $user['created_at'];
            $_SESSION['timeout'] = time(); // For session timeout


            // Insert the new session into the user_sessions table
            $insert_stmt = $conn->prepare("INSERT INTO user_sessions (session_id, accountid, ip_address, user_agent) VALUES (?, ?, ?, ?)");
            $insert_stmt->bind_param("siss", $new_session_id, $accountid, $ip_address, $user_agent);
            $insert_stmt->execute();
            $insert_stmt->close();

            // Return success response
            echo json_encode(['success' => true]);
        } else {
            // Invalid password
            echo json_encode(['success' => false, 'message' => 'Invalid email or password.']);
        }
    } else {
        // No user found with the provided email
        echo json_encode(['success' => false, 'message' => 'User not found.']);
    }

    // Close statement
    $stmt->close();
}
?>
