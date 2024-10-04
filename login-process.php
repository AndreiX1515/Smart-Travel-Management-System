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

        // // Output the user data to the browser's console
        // echo "<script>console.log('Account Data: " . $user['upassw'] . "');</script>";

        // Verify the password using md5
        if (md5($password) === trim($user['upassword'])) {
            // Check if account is active
            if ($user['account_status'] !== 'active') {
                echo json_encode(['success' => false, 'message' => 'Your account is inactive. Please contact support.']);
                exit;
            }

            // Store user information in session
            $_SESSION['accountid'] = $user['accountid'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['last_name'] = $user['last_name'];
            $_SESSION['middle_name'] = $user['middle_name'];
            $_SESSION['account_status'] = $user['account_status'];
            $_SESSION['created_at'] = $user['created_at'];

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
