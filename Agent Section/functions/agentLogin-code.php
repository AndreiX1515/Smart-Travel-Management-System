<?php
require "../../conn.php"; // Move up to the parent directory
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
        $sql1 = "SELECT * FROM accounts WHERE email = ? and accountType = 'agent'";
        $stmt1 = $conn->prepare($sql1);
        $stmt1->bind_param('s', $username); // Bind the username as a string
        $stmt1->execute();
        $result1 = $stmt1->get_result();
        
        if ($result1->num_rows > 0) {
            // Fetch the agent's row
            $user = $result1->fetch_assoc();
            
            // Directly compare the plain text password (not recommended for production)
            if ($password === $user['password']) {

                // Fetch additional agent details using the accountId
                $sql2 = "SELECT * FROM agent WHERE accountId = ?";
                $stmt2 = $conn->prepare($sql2);
                $stmt2->bind_param('i', $user['accountId']); // Bind accountId as an integer
                $stmt2->execute();
                $agentResult = $stmt2->get_result();

                if ($agentResult->num_rows > 0) 
                {
                    $agent = $agentResult->fetch_assoc();
                    $_SESSION['accountId'] = $agent['accountId'];
                    $_SESSION['agentId'] = $agent['agentId'];
                    $_SESSION['fName'] = $agent['fName'];
                    $_SESSION['mName'] = $agent['mName'];
                    $_SESSION['lName'] = $agent['lName'];
                    $_SESSION['branch'] = $agent['branch'];
                }
                
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
        
        $stmt1->close();
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
