
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
  <?php include '../Client Section/Includes/head.php'; ?>

  <title>Transaction History</title>

  <link rel="stylesheet" href="../Client Section/assets/css/client-transactionHistory.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="../Client Section/assets/css/client-navbar.css?v=<?php echo time(); ?>"> 
 
</head>

<body>

<?php include '../Client Section/Includes/client-navbar.php'; ?>

<div class="body-container">
  <div class="main-container">  
    <div class="content-header">
        <div class="back-button-wrapper">
            <a href="index.php" class="back-button-link"> <i class="fa-solid fa-arrow-left me-2"></i> Back to Homepage</a>
        </div>
        <h1>Transaction History</h1>
    </div>

    <div class="container-body">
      <div class="custom-tabs">
          <ul class="tab-nav" id="pills-tab" role="tablist">
            <li class="tab-item" role="presentation">
              <button class="tab-button active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">All</button>
            </li>
            <li class="tab-item" role="presentation">
              <button class="tab-button" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">
                  Completed
                  <span class="badge bg-primary ms-2 rounded-pill fw-bold align-center">5</span>
              </button>
            </li>
            <li class="tab-item" role="presentation">
              <button class="tab-button" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#pills-contact" type="button" role="tab" aria-controls="pills-contact" aria-selected="false">Pending</button>
            </li>
            <li class="tab-item" role="presentation">
              <button class="tab-button" id="pills-rejected-tab" data-bs-toggle="pill" data-bs-target="#pills-rejected" type="button" role="tab" aria-controls="pills-rejected" aria-selected="false">Rejected</button>
            </li>
          </ul>
      </div>

      <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">
    
          <input type="hidden" class="hidden" value="<?php echo $accId; ?>"></input>

          <div class="table-container"> 
    <table>
        <tbody>
            <?php
            $accId = $_SESSION['accountId'];
            $sql1 = "SELECT 
                        DATE_FORMAT(b.bookingDate, '%M %d, %Y') AS `TRANSACTION DATE`,
                        b.transactNo AS `T.N`, 
                        p.packageName AS `PACKAGE`,
                        b.bookingType as bookingType,
                        DATE_FORMAT(f.flightDepartureDate, '%m-%d-%Y') AS `FLIGHT DATE`,
                        b.pax AS `TOTAL PAX`,
                        CONCAT(b.lName, ', ', b.fName, ' ', 
                            CASE WHEN b.mName = 'N/A' THEN '' ELSE CONCAT(SUBSTRING(b.mName, 1, 1), '.') END, 
                            ' ', CASE WHEN b.suffix = 'N/A' THEN '' ELSE b.suffix END) AS `CONTACT NAME`,
                        b.email AS `CONTACT EMAIL`,
                        CONCAT(b.countryCode, ' ', b.contactNo) AS `CONTACT PHONE`,
                        b.status AS `STATUS`
                    FROM 
                        booking b
                    LEFT JOIN 
                        flight f ON b.flightId = f.flightId
                    LEFT JOIN 
                        package p ON b.packageId = p.packageId
                    WHERE 
                        b.accountId = '$accId'
                    ORDER BY 
                        b.bookingDate DESC";

            $res1 = $conn->query($sql1);

            $currentDate = '';
            if ($res1->num_rows > 0) {
                while ($row = $res1->fetch_assoc()) {
                    $transactionDate = $row['TRANSACTION DATE'];

                    // Display date header only when the date changes
                    if ($currentDate != $transactionDate) {
                        if ($currentDate != '') {
                            echo "</tbody>"; // Close the previous date group
                        }
                        $currentDate = $transactionDate;
                        echo "<thead>
                          <tr>
                            <th colspan='100%' class='transaction-date-header mt-4'>{$transactionDate}</th>
                          </tr>
                        </thead>";
                        echo "<tbody class='mt-4'>"; // Start a new group
                    }

                    $transactNo = $row['T.N'];
                    $pax = $row['TOTAL PAX'];
                    $status = isset($row['STATUS']) ? $row['STATUS'] : 'Unknown';

                    // Assign status class
                    $statusClass = match ($status) {
                        'Confirmed' => 'bg-success text-white',
                        'Cancelled' => 'bg-danger text-white',
                        'Pending' => 'bg-warning text-dark',
                        default => 'bg-secondary text-white',
                    };

                    // Render transaction row
                    echo "<tr data-url='client-ViewTransaction.php?id=" . htmlspecialchars($transactNo) . "' class='mt-2'>
                        <td>{$transactNo}</td>
                        <td>A001</td>
                        <td>{$row['CONTACT NAME']}</td>
                        <td>
                          <div class='d-flex flex-column'>
                            <span><strong>Email: </strong>{$row['CONTACT EMAIL']}</span>
                            <span><strong>Contact Number: </strong>{$row['CONTACT PHONE']}</span>
                          </div>
                        </td>
                        <td>{$row['PACKAGE']}</td>
                        <td>{$row['FLIGHT DATE']}</td>
                        <td>Total Pax: {$row['TOTAL PAX']}</td>
                        <td>
                          <span class='badge p-2 rounded-pill {$statusClass}'>{$status}</span>
                        </td>
                    </tr>";
                }
                echo "</tbody>"; // Close the last date group
            }
            ?>
        </tbody>
    </table>
</div>


        </div>

        <!-- <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab" tabindex="0">...</div>
        <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab" tabindex="0">...</div>
        <div class="tab-pane fade" id="pills-disabled" role="tabpanel" aria-labelledby="pills-disabled-tab" tabindex="0">...</div> -->
      </div>

    </div>

  </div>

</div>

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

        console.log("Transaction Number: ", transactionNumber);

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