<div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab" tabindex="0">
  <div class="card-header px-3 py-1">
    <h6>Payment History</h6>
  </div>

  <div class="request-table-wrapper">
    <table class="request-table">
      <?php
      $sql1 = "SELECT *, FORMAT(amount, 2) AS amount, DATE_FORMAT(paymentDate, '%m-%d-%Y') AS paymentDate 
                  FROM payment 
                  WHERE transactNo = '$transactNum'";

      $res1 = $conn->query($sql1);

      if ($res1->num_rows > 0) {
          // Table header is displayed only if there are results
          echo "
          <thead>
              <tr>
                  <th>PAYMENT ID</th>
                  <th>PAYMENT TITLE</th>
                  <th>PAYMENT TYPE</th>
                  <th>AMOUNT</th>
                  <th>PROOF OF PAYMENT</th>
                  <th>PAYMENT DATE</th>
                  <th>PAYMENT STATUS</th>
              </tr>
          </thead>
          <tbody>";

          while ($row = $res1->fetch_assoc()) {
              // Fetch the payment status from the database
              $status = $row['paymentStatus'];

              // Assign a corresponding Bootstrap badge class based on the status
              $badgeClass = '';

              switch($status) {
                  case 'Submitted':
                      $badgeClass = 'bg-primary'; // Blue for Submitted
                      break;
                  case 'Approved':
                      $badgeClass = 'bg-success'; // Green for Approved
                      break;
                  default:
                      $badgeClass = 'bg-secondary'; // Gray for unknown statuses
                      break;
              }

              echo "
              <tr>
                  <td>{$row['paymentId']}</td>
                  <td>{$row['paymentTitle']}</td>
                  <td>{$row['paymentType']}</td>
                  <td>₱ {$row['amount']}</td>
                  <td>
                      <a href='functions/view-file.php?file=" . urlencode($row['filePath']) . "' target='_blank'>View File</a> 
                      <a href='functions/download.php?file=" . urlencode($row['filePath']) . "' target='_blank'>Download File</a>
                  </td>
                  <td>{$row['paymentDate']}</td>
                  <td>
                      <span class='badge rounded-pill {$badgeClass} py-2'>{$status}</span>
                  </td>
              </tr>";
          }
          echo "</tbody>";
      } else {
          // Display a "No Payment Found" message and hide the table
          echo "
          <thead style='display: none;'></thead>
          <tbody>
              <tr style='display: none;'></tr> <!-- Ensures no empty table rows -->
          </tbody>
          <div class='no-requests-container'>
              <span>No Payment Found</span>
          </div>";
      }
      ?>
  </table>

  </div>
</div>