<?php
session_start();

// If this is a POST request, process it
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Fetch data from POST
    $email = $_POST["email"];  // The email value sent from the AJAX request
    $accountId = $_POST["accountId"];  // The account ID sent from the AJAX request
    $flightid = $_POST["flightid"];  // The flight ID sent from the AJAX request

    // Optionally, log the data for debugging purposes
    error_log("Email: " . $email);
    error_log("Account ID: " . $accountId);
    error_log("Flight ID: " . $flightid);

    // Update the session variables with the new values
    $_SESSION['email'] = $email;
    $_SESSION['accountId'] = $accountId;
    $_SESSION['flightid'] = $flightid;

    // If you want to change more session variables, do it here
    $_SESSION['agent_accountId'] = $accountId;
    $_SESSION['agent_agentId'] =  '';  // You can assign default values if needed
    $_SESSION['agent_agentCode'] = '';  // Same as above
    $_SESSION['agent_agentRole'] =  '';
    $_SESSION['agent_agentType'] =  '';
    $_SESSION['agent_fName'] =  '';
    $_SESSION['agent_lName'] = '';
    $_SESSION['agent_mName'] =  '';
    $_SESSION['agent_branchId'] =  '';
    $_SESSION['password'] = '';

    // Return a JSON response
    echo json_encode(["status" => "success"]);
    exit;
}

// If accessed directly without a POST request, return an error
echo json_encode(["status" => "error", "message" => "Invalid request"]);
exit;
?>
