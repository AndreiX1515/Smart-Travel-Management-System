<?php
require "../conn.php"; // Move up to the parent directory
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$response = []; // Initialize response array

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if username and password are provided
    if (!empty($username) && !empty($password)) {
        // Prepare the SQL query to fetch the agent's details
        $sql = "SELECT * FROM agent WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $username); // Bind the username as a string
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            // Fetch the agent's row
            $agent = $result->fetch_assoc();
            
            // Directly compare the plain text password (not recommended for production)
            if ($password === $agent['password']) {
                
                $_SESSION['agentId'] = $agent['agentId'];
                $_SESSION['username'] = $agent['username'];
                $_SESSION['fName'] = $agent['fName'];
                $_SESSION['lName'] = $agent['lName'];
                $_SESSION['mName'] = $agent['mName'];
                $_SESSION['branch'] = $agent['branch'];
                
                // Prepare success response
                $response['success'] = true;
            } else {
                $response['success'] = false;
                $response['message'] = "Incorrect username or password. Please try again.";
            }
        } else {
            $response['success'] = false;
            $response['message'] = "Incorrect username or password. Please try again.";
        }
        
        $stmt->close();
    } else {
        $response['success'] = false;
        $response['message'] = "Please fill in both fields.";
    }
} else {
    $response['success'] = false;
    $response['message'] = "Invalid request.";
}

// Send the JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
