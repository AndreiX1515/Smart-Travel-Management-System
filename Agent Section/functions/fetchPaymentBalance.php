<?php
require "../../conn.php"; // Move up to the parent directory 

if (isset($_POST['paymentTitle'])) {
    $paymentTitle = $_POST['paymentTitle'];
    $transactionNumber = $_POST['transactionNumber']; // Assuming it's alphanumeric like 'A001-000001'

    if ($paymentTitle == "Package Payment") {
        // Prepare and execute the query to get the total price
        $sql1 = "SELECT transactNo, totalPrice FROM booking WHERE transactNo = ?";
        $stmt = $conn->prepare($sql1);
        $stmt->bind_param("s", $transactionNumber); // Bind the transaction number as a string
        $stmt->execute();
        $result = $stmt->get_result();

        // Initialize totalPrice variable
        $totalPrice = 0.00;

        // Fetch the total price
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $totalPrice = $row['totalPrice'];

            // Prepare and execute the query to sum the approved payments for the same transactNo
            $sql2 = "SELECT SUM(amount) as totalPayment 
                     FROM payment 
                     WHERE transactNo = ? AND paymentTitle = 'Package Payment' AND paymentStatus = 'Approved'";

            $stmt2 = $conn->prepare($sql2);
            $stmt2->bind_param("s", $transactionNumber); // Bind the transaction number as a string
            $stmt2->execute();
            $result2 = $stmt2->get_result();

            // Initialize totalPayment variable
            $totalPayment = 0.00;

            // Check if there are any approved payments for the same transactNo
            if ($result2->num_rows > 0) {
                $paymentRow = $result2->fetch_assoc();
                $totalPayment = $paymentRow['totalPayment'];  // Sum of approved payments

                $totalPrice -= $totalPayment;  // Subtract the total payments from the total price
            }

            // Close the second statement
            $stmt2->close();
        }

        // Close the first statement
        $stmt->close();

        // Ensure the value is not negative
        $amountLeft = max($totalPrice, 0.00);  // Avoid negative balance

        // Debugging: Log the amountLeft
        error_log("Amount Left: " . $amountLeft);

        // Return the remaining balance as JSON
        echo json_encode(['amountLeft' => $amountLeft]);
    }
    // Close the database connection
    $conn->close();
}
?>
