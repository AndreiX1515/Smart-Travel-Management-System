<?php
  session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transactions</title>
    <?php include '../Employee Section/includes/emp-head.php' ?>
    <link rel="stylesheet" href="../Employee Section/assets/css/emp-transactionInfo.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../Employee Section/assets/css/emp-sidebar-navbar.css?v=<?php echo time(); ?>">
    
</head>
<body>


<?php include '../Employee Section/includes/emp-sidebar.php' ?>

<!-- Main Container -->
<div class="main-container">
  <nav class="navbar navbar-expand-lg navbar-custom mt-2">
    <div class="back-button-wrapper py-3 px-4">
      <button class="back-button" onclick="window.location.href='../Employee Section/emp-transaction.php';">
        <i class="fas fa-arrow-left"></i>
      </button>

      <?php 
        if (isset($_GET['id'])) 
        {
          // Sanitize the input to prevent XSS attacks
          $transactionId = htmlspecialchars($_GET['id']);
        }
      ?>

      <div class="title">
        <h1>TRANSACTION ID: <?php echo $transactionId; ?></h1>
      </div>

      <!-- Navbar items and functionality can be added here -->
    </div>
  </nav>

  <div class="main-content">
    <div class="header">
      <div class="status-wrapper">
         <!-- <span class="status">Pending</span>
         <span class="date">March, 2024</span>
         <button class="more-options">
             <i class="fas fa-ellipsis-h"></i>
         </button> -->
      </div>
    </div>

    <div class="first-part-wrapper mt-1">
      <div class="transaction-info-wrapper">
        <div class="card-header py-2 mb-2">
          <h6>Transaction Information</h6>
        </div>

        <?php
          $query1 = "SELECT booking.*, package.packageName, flight.flightDepartureDate FROM booking 
                      JOIN package ON booking.packageId = package.packageId
                      LEFT JOIN flight ON booking.flightId = flight.flightId
                      WHERE transactNo = '$transactionId'";

          $result1 = $conn->query($query1);

          if ($result1->num_rows > 0) 
          {
            // Output data of each row
            while ($row1 = $result1->fetch_assoc()) 
            {
              $transactNum = $row1['transactNo'];
              $fName = $row1['fName'];
              $mName = $row1['mName'];
              $lName = $row1['lName'];
              $suffix = $row1['suffix'];
              $countryCode = $row1['countryCode'];
              $contact = $row1['contactNo'];
              $email = $row1['email'];
              $packageName = $row1['packageName'];
              $flightDate = $row1['flightDepartureDate'];
              $pax = $row1['pax'];
              $status = $row1['status'];
              $price = $row1['totalPrice'];
              $flightId = $row1['flightId']; // Fetch flightId

              // Construct the full name using the conditions for middle name and suffix
              $fullName = $lName . ", " . $fName . " " . 
                          ($suffix !== 'N/A' ? $suffix . " " : "") .  // Add space after suffix only if it's not 'N/A'
                          ($mName !== 'N/A' ? substr($mName, 0, 1) . ". " : "");  // Add middle initial with dot only if it's not 'N/A'
              $contactNo = $countryCode . $contact;

              // Check if flightId is NULL and set flightDate accordingly
              if (is_null($flightId)) 
              {
                $flightDate = "Land Package Only";
              }

              $status = isset($row1['status']) ? $row1['status'] : 'Unknown';

              // Initialize an empty class string
              $statusClass = '';

              // Assign classes based on the status value using switch
              switch ($status) 
              {
                case 'Confirmed':
                    $statusClass = 'bg-success text-white'; // Green background, white text
                    break;
                case 'Cancelled':
                    $statusClass = 'bg-danger text-white'; // Red background, white text
                    break;
                case 'Pending':
                    $statusClass = 'bg-warning text-dark'; // Yellow background, dark text
                    break;
                default:
                    $statusClass = 'bg-secondary text-white'; // Gray background, white text
                    break;
              }
            }
          } 
          else 
          {
            echo "0 results";
          }
        ?>

        <div class="row g-3 mb-1">
          <div class="col-md-5 mb-2 me-4 d-flex flex-column gap-1">
            <p><strong>Transaction No:</strong> <?php echo $transactNum; ?></p>
            <p><strong>Total Pax:</strong> <?php echo $pax; ?></p>
            <p><strong>Package:</strong> <?php echo $packageName; ?></p>
            <p><strong>Flight Date:</strong> <?php echo $flightDate; ?></p>
            <p class="align-items-center">
              <strong>Status:</strong> 
              <span class="badge rounded-pill bg-warning text-dark fs-7 pt-2" style="padding: 0.3rem 0.6rem; display: inline-block;">
                <?php echo $status; ?>
              </span>
            </p>
          </div>

          <div class="col-md-5 mb-3 d-flex flex-column gap-1">
            <p><strong>Contact Person:</strong> <?php echo $fullName; ?></p>
            <p><strong>Contact No:</strong> <?php echo $contactNo; ?></p>
            <p><strong>Email:</strong> <?php echo $email;?></p>
          </div>
        </div>
      </div>

      <div class="guest-info-table-wrapper">
        <div class="card-header px-2 py-1">
          <h6>Guest Informations</h6>
        </div>

        <div class="guest-table-wrapper ">
          <table class="table-stripped">
          <?php
          $sql1 = "SELECT *, DATE_FORMAT(birthdate, '%M %d, %Y') AS birthdate, CONCAT(countryCode, ' ', contactNo) AS contactNo,
                      CASE 
                        WHEN countryCode2 IS NULL OR contactNo2 IS NULL THEN 'N/A'
                        ELSE CONCAT(countryCode2, ' ', contactNo2)
                      END AS contactNo2, CONCAT(addressLine1, ', ', 
                      CASE 
                        WHEN addressLine2 IS NOT NULL AND addressLine2 != '' THEN CONCAT(addressLine2, ', ') 
                        ELSE '' 
                      END, city, ', ', state, ', ', zipcode, ', ', country) AS address
                      FROM guest 
                      WHERE transactNo = '$transactNum'";

          $res1 = $conn->query($sql1);

          if ($res1->num_rows > 0) {
              // Only display the table header if rows exist
              echo "
              <thead>
                  <tr>
                      <th>ID</th>
                      <th>Contact Name</th>
                      <th>Birthdate</th>
                      <th>Age</th>
                      <th>Sex</th>
                      <th>Nationality</th>
                      <th>Contact No</th>
                      <th>Other Contact</th>
                      <th>Email</th>
                      <th>Address</th>
                      <th>Passport No.</th>
                      <th>Passport Exp.</th>
                      <th>Visa Status</th>
                  </tr>
              </thead>
              <tbody>";
              while ($row = $res1->fetch_assoc()) {
                  $fullName = $row['fName'] . ' ' . $row['mName'] . ' ' . $row['lName'];
                  if (!empty($row['suffix']) && $row['suffix'] !== 'N/A') {
                      $fullName .= ' ' . $row['suffix'];
                  }

                  $guestId = htmlspecialchars($row['guestId']);
                  $birthdate = htmlspecialchars($row['birthdate']);
                  $age = htmlspecialchars($row['age']);
                  $sex = htmlspecialchars($row['sex']);
                  $nationality = htmlspecialchars($row['nationality']);
                  $contactNo = htmlspecialchars($row['contactNo']);
                  $contactNo2 = htmlspecialchars($row['contactNo2']);
                  $emailAdd = htmlspecialchars($row['emailAdd']);
                  $address = htmlspecialchars($row['address']);
                  $passportNo = htmlspecialchars($row['passportNo']);
                  $passportExp = htmlspecialchars($row['passportExp']);

                  echo "
                  <tr>
                      <td>{$guestId}</td>
                      <td>{$fullName}</td>
                      <td>{$birthdate}</td>
                      <td>{$age}</td>
                      <td>{$sex}</td>
                      <td>{$nationality}</td>
                      <td>{$contactNo}</td>
                      <td>{$contactNo2}</td>
                      <td>{$emailAdd}</td>
                      <td>{$address}</td>
                      <td>{$passportNo}</td>
                      <td>{$passportExp}</td>
                      <td>{$row['visaStatus']}</td>
                  </tr>";
              }
              echo "</tbody>";
          } else {
              // Hide the table header and display a message
              echo "
              <thead style='display: none;'></thead>
              <tbody>
                  <tr style='display: none;'></tr> <!-- Ensures no empty table rows -->
              </tbody>
              <div class='no-requests-container'>
                  <span>No Guest Found</span>
              </div>";
          }
          ?>
        </table>
      </div>

    </div>
  </div>

  <div class="nav-pills-wrapper">
    <ul class="nav nav-pills " id="pills-tab" role="tablist">
      <li class="nav-item" role="presentation">
        <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Request History</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Payment History</button>
      </li>
      <!-- <li class="nav-item" role="presentation">
        <button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#pills-contact" type="button" role="tab" aria-controls="pills-contact" aria-selected="false">Contact</button>
      </li> -->
      <!-- <li class="nav-item" role="presentation">
        <button class="nav-link" id="pills-disabled-tab" data-bs-toggle="pill" data-bs-target="#pills-disabled" type="button" role="tab" aria-controls="pills-disabled" aria-selected="false" disabled>Disabled</button>
      </li> -->
    </ul>
  </div>

  <div class="tab-content" id="pills-tabContent">
    <?php include '../Employee Section/emp-transactionRequestHistory.php' ?>
    <?php include '../Employee Section/emp-transactionPaymentHistory.php' ?>
  </div>
 
  </div>
</div>

<?php include '../Employee Section/includes/emp-scripts.php' ?>


</body>
</html>
