<?php
  // include 'session_validate.php'; // This will check if the session is valid
  require '../conn.php';
  session_start();

  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);
  
  // Fetch session variables directlys
  $email = $_SESSION['email'] ?? ''; // Use null coalescing operator to avoid undefined index
  // $firstName = $_SESSION['first_name'] ?? '';
  // $lastName = $_SESSION['last_name'] ?? '';
  // $middleName = $_SESSION['middle_name'] ?? '';
  $accId = $_SESSION['accountId'] ?? '';
  
  // $fullName = htmlspecialchars($lastName . ', ' . $firstName . ($middleName ? ' ' . substr($middleName, 0, 1) . '.' : ''));
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <!-- Bootstrap CSS CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Add this in the <head> or before </body> -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


  <!-- Font Awesome Icon Kit CDN (stable version) -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">

  <!-- Bootstrap CSS CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

  <link rel="stylesheet" href="assets\css\client-transactionHistory.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="assets\css\client-navbar.css?v=<?php echo time(); ?>"> 
 
  <!-- Include the necessary CSS and JS for intlTelInput -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css">
  
  <title>Flight Booking</title>
  
  <!-- Custom CSS -->
  <style>
    .card-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    h4 {
      margin: 20px 0;
    }
  </style>
</head>
<body>

  <?php 
    include '../Client Section/Includes/client-navbar.php'; 
  ?>

  <div class="container">
    <div class="table-container">

      <div class="d-flex justify-content-between align-items-center">
        <a href="../Client Section/index.php" class="back-button">Back to Home</a>

        <div class="session-info">
          <h6>Session ID: <span><?php echo $_SESSION['accountId']; ?></span></h6>
        </div>
      </div>

      <table>
        <thead>
          <tr>
            <th class="col"><?php echo $accId; ?></th>
            <th scope="col">Transaction Number</th>
            <th scope="col">Package Name</th>
            <th scope="col">Flight Date</th>
            <th scope="col">Total Pax</th>
            <th scope="col">Amount To Pay</th>
            <th scope="col">Downpayment Total</th>
            <th scope="col">Status</th>
            <th scope="col"></th>
          </tr>
        </thead>
        <tbody>
          <?php
            $accId = $_SESSION['accountId'];
            $sql1 = "SELECT b.transactNo AS `T.N`, p.packageName AS `PACKAGE`,
                          DATE_FORMAT(b.bookingDate, '%m-%d-%Y') AS `TRANSACTION DATE`, b.bookingType as bookingType,
                          DATE_FORMAT(f.flightDepartureDate, '%m-%d-%Y') AS `FLIGHT DATE`, b.pax AS `TOTAL PAX`,
                          CONCAT(b.lName, ', ', b.fName, ' ', CASE WHEN b.mName = 'N/A' THEN '' 
                            ELSE CONCAT(SUBSTRING(b.mName, 1, 1), '.') END, ' ', CASE WHEN b.suffix = 'N/A' THEN '' 
                            ELSE b.suffix END) AS `CONTACT NAME`,
                          b.email AS `CONTACT EMAIL`, CONCAT(b.countryCode, ' ', b.contactNo) AS `CONTACT PHONE`, b.status AS `STATUS`
                      FROM 
                          booking b
                      LEFT JOIN 
                          flight f ON b.flightId = f.flightId
                      LEFT JOIN 
                          package p ON b.packageId = p.packageId
                      WHERE 
                          b.accountId = '$accId'";

            $res1 = $conn->query($sql1);

            if ($res1->num_rows > 0) 
            {
              while ($row = $res1->fetch_assoc()) 
              {
                $transactNo = $row['T.N'];
                $pax = $row['TOTAL PAX'];

                $status = isset($row['STATUS']) ? $row['STATUS'] : 'Unknown';
                $statusClass = '';

                switch ($status) {
                    case 'Confirmed':
                        $statusClass = 'bg-success text-white'; // Green background, white text
                        break;
                    case 'Cancelled':
                        $statusClass = 'bg-danger text-white'; // Red background, white text
                        break;
                    case 'Pending':
                        $statusClass = 'bg-warning text-dark'; 
                        break;
                    default:
                        $statusClass = 'bg-secondary text-white'; 
                }

                echo "<tr data-url='client-transactionStatus.php?id=" . htmlspecialchars($transactNo) . "'>
                        <td>{$transactNo}</td>
                        <td>{$row['CONTACT NAME']}</td>
                        <td> 
                          <div class='d-flex flex-column'>
                            <span><strong>Email: </strong>" . $row['CONTACT EMAIL'] ." </span>
                            <span><strong>Contact Number: </strong> " . $row['CONTACT PHONE'] ."</span>
                          </div>
                        </td>

                        <td>{$row['PACKAGE']}</td>
                        <td>{$row['TRANSACTION DATE']}</td>
                        <td>{$row['FLIGHT DATE']}</td>
                        <td style='text-align: center; font-weight: bold;'>
                            {$row['TOTAL PAX']}
                        </td>
                        <td>
                          <span class='badge p-2 rounded-pill {$statusClass} '>
                              {$status}
                          </span>
                      </td>
                </tr>";
              }
            }
          ?>
        </tbody>
      </table>
    </div>
  </div>
  
  <!-- <script src="heartbeat.js"></script>  -->

  <?php include '../Client Section/Includes/scripts.php'; ?>

  <!-- Row Click Selection JS -->
<script>
  document.addEventListener("DOMContentLoaded", function() 
  {
    document.querySelectorAll("tr[data-url]").forEach(function(row) 
    {
      row.addEventListener("click", function() 
      {
        const transactionNumber = row.getAttribute("data-url").split('=')[1]; // Extract transaction number from the URL

        console.log("Transaction Number: ", transactionNumber); // Debugging line

        // Use AJAX to send the transaction number to the server
        $.ajax(
        {
          url: '../Agent Section/functions/fetchTransactNo.php', // The PHP file to handle the session setting
          type: 'POST',
          data: { transaction_number: transactionNumber },
          success: function(response)
          {
            console.log("Response: ", response); // Debugging line

            // Redirect to the next page after successfully setting the session
            window.location.href = row.getAttribute("data-url"); // Use the original URL stored in data-url attribute
          },
          error: function(xhr, status, error) 
          {
            console.error("AJAX Error: " + status + " " + error); // Enhanced error logging
          }
        });
      });
    });
  });
</script>
</body>
</html>