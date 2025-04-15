<?php
session_start();
require "../conn.php";

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title></title>

  <?php include "../Agent Section/includes/head.php"; ?>

  <link rel="stylesheet" href="../Agent Section/assets/css/agent-transaction.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="../Agent Section/assets/css/navbar-sidebar.css?v=<?php echo time(); ?>">
</head>

<body>

  <div class="body-container">
    <?php include "../Agent Section/includes/sidebar.php"; ?>

    <div class="main-content-container">
      <div class="navbar">
        <h5 class="title-page">Payment History</h5>
      </div>

      <?php
        $statusTab = isset($_GET['status']) ? $_GET['status'] : '';
      ?>

      <div class="main-content">
        <div class="table-wrapper">

          <!-- Filter Inputs -->
          <div class="table-header">
            <div class="search-wrapper">
              <div class="search-input-wrapper">
                <input type="text" id="search" placeholder="Search here..">
              </div>
            </div>

            <div class="second-header-wrapper">
              <div class="date-range-wrapper flightbooking-wrapper">
                <div class="date-range-inputs-wrapper">
                  <div class="input-with-icon">
                    <input type="text" class="datepicker" id="FlightStartDate" placeholder="Flight Date" readonly>
                    <i class="fas fa-calendar-alt calendar-icon"></i>
                  </div>
                </div>
              </div>

              <div class="buttons-wrapper">
                <button id="clearSorting" class="btn btn-secondary">
                  Clear
                </button>
              </div>
            </div>
          </div>

          <!-- Table  -->
          <div class="table-container">
            <table id="product-table" class="product-table">
              <thead>
                <tr>
                  <th>TRANSACTION NO</th>
                  <th>AMOUNT</th>
                  <th>PROOF OF PAYMENT</th>
                  <th>PAYMENT DATE</th>
                  <th>STATUS</th>
                  <th>REMARKS</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  if ($agentRole != 'Head Agent')
                  {
                    $sql1 = "SELECT g.guestId, g.transactNo, g.fName, g.mName, g.lName, g.suffix, g.birthdate, g.age, g.sex, g.nationality, 
                              g.passportNo, g.passportExp, f.flightDepartureDate
                            FROM `guest` g
                            JOIN `booking` b ON g.transactNo = b.transactNo
                            JOIN `flight` f ON b.flightId = f.flightId
                            WHERE b.accountId = $accountId
                            ORDER BY f.flightDepartureDate ASC";

                    // Execute the query
                    $result1 = $conn->query($sql1);

                    // Check if query execution was successful
                    if (!$result1) 
                    {
                      die("Query error: " . $conn->error);
                    }

                    // Fetch results and display rows
                    if ($result1->num_rows > 0) 
                    {
                      while ($row = $result1->fetch_assoc()) 
                      {
                        if ($row['suffix'] === 'N/A')
                        {
                          $row['suffix'] = '';
                        }

                        // Sanitize and format guest name
                        $guestName = $row['fName'] . ' ' . $row['suffix'] . ' ' . $row['lName'];

                        // Format dates
                        $birthdate = !empty($row['birthdate']) ? date('Y M d', strtotime($row['birthdate'])) : 'N/A';
                        $departureDate = !empty($row['flightDepartureDate']) ? date('Y-m-d', strtotime($row['flightDepartureDate'])) : 'N/A';

                        echo "<tr>
                                <td>" . $row['transactNo'] . "</td>
                                <td>" . $row['age'] . "</td>
                                <td>" . ($row['fName'] ?? '') . ' ' . ($row['suffix'] ?? '') . "</td>
                                <td>" . $row['lName'] . "</td>
                                <td>" . $guestName . "</td>
                                <td>" . $birthdate . "</td>
                                <td>" . $row['nationality'] . "</td>
                                <td>" . $row['passportNo'] . "</td>
                                <td>" . $row['passportExp'] . "</td>
                                <td>" . $row['sex'] . "</td>
                                <td>" . $departureDate . "</td>
                              </tr>";
                      }
                    }
                  }
                  else
                  {
                    $sql1 = "SELECT b.transactNo, p.paymentId, p.amount, p.filePath, p.paymentDate, p.paymentStatus, p.paymentRemarks
                            FROM `booking` b
                            JOIN `payment` p ON b.transactNo = p.transactNo
                            WHERE b.agentCode = '$agentCode'
                            ORDER BY p.paymentId ASC";

                    // Execute the query
                    $result1 = $conn->query($sql1);

                    // Check if query execution was successful
                    if (!$result1) 
                    {
                      die("Query error: " . $conn->error);
                    }

                    // Fetch results and display rows
                    if ($result1->num_rows > 0) 
                    {
                      while ($row = $result1->fetch_assoc()) 
                      {
                        $amount = number_format($row['amount'], 2);
                        $date = date("F-d-Y", strtotime($row['paymentDate']));
                        $remarks = !empty($row['paymentRemarks']) ? $row['paymentRemarks'] : 'N/A';

                        $status = isset($row['paymentStatus']) ? $row['paymentStatus'] : 'Unknown';
                        $statusClass = '';

                        switch ($status) 
                        {
                          case 'Approved':
                            $statusClass = 'bg-success text-white'; // Green background, white text
                            break;
                          case 'Rejected':
                            $statusClass = 'bg-danger text-white'; // Red background, white text
                            break;
                          case 'Submitted':
                            $statusClass = 'bg-warning text-dark';
                            break;
                          default:
                            $statusClass = 'bg-secondary text-white';
                        }

                        echo "<tr>
                                <td>" . $row['transactNo'] . "</td>
                                <td>₱ " . $amount . "</td>
                                <td>
                                  <a href='functions/view-file.php?file=" . urlencode($row['filePath']) . "' target='_blank'>View File</a> 
                                  <a href='functions/download.php?file=" . urlencode($row['filePath']) . "' target='_blank'>Download File</a> 
                                </td>
                                <td>" . $date . "</td>
                                <td>
                                  <span class='badge p-2 rounded-pill {$statusClass}'>
                                    {$status}
                                  </span>
                                </td>
                                <td>" . $remarks . "</td>
                              </tr>";
                      }
                    }
                  }
                  
                ?>
              </tbody>
            </table>
          </div>


          <div class="table-footer">
            <div class="pagination-controls">
              <button id="prevPage" class="pagination-btn">Previous</button>
              <span id="pageInfo" class="page-info">Page 1 of 10</span>
              <button id="nextPage" class="pagination-btn">Next</button>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>


  <?php require "../Agent Section/includes/scripts.php"; ?>

</body>

</html>