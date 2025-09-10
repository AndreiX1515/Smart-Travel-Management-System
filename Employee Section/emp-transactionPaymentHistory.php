<div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab" tabindex="0">

  <div class="payment-card-wrapper">
      <?php
      $sql1 = "SELECT *, FORMAT(amount, 2) AS amount, DATE_FORMAT(paymentDate, '%m-%d-%Y') AS paymentDate FROM payment WHERE transactNo = '$transactNum'";

      $res1 = $conn->query($sql1);

      if ($res1->num_rows > 0) {
          while ($row = $res1->fetch_assoc()) {
              $status = $row['paymentStatus'];

              // Badge color
              $badgeClass = '';
              switch ($status) {
                  case 'Submitted':
                      $badgeClass = 'bg-primary';
                      break;
                  case 'Approved':
                      $badgeClass = 'bg-success';
                      break;
                  default:
                      $badgeClass = 'bg-secondary';
                      break;
              }

              // File handling
              $fileActions = "<span class='text-muted'>No File Uploaded</span>";
              if (!empty($row['filePath'])) {
                  $encodedFile = urlencode($row['filePath']);
                  $fileActions = "
                      <a href='../Agent Section/functions/view-file.php?file={$encodedFile}' target='_blank' class='action-link'>View</a>
                      <a href='../Agent Section/functions/download.php?file={$encodedFile}' target='_blank' class='action-link'>Download</a>
                  ";
              }

              echo "
              <div class='payment-card'>

                <div class='payment-card-header'>
                    <input type='hidden' class='payment-id' value='{$row['paymentId']}'>
                    <h5 class='payment-title'>{$row['paymentTitle']}</h5>
                    <span class='badge rounded-pill {$badgeClass} py-2 payment-status'>{$status}</span>
                </div>

                <div class='payment-card-body'>
                    
                    <p class='payment-type'>Type: {$row['paymentType']}</p>
                    <p class='payment-amount'>₱ {$row['amount']}</p>
                    <div class='payment-meta'>
                        <p class='payment-date'>{$row['paymentDate']}</p>
                        <div class='payment-actions'>
                            " . (!empty($row['filePath']) ? "
                                <a href='../Agent Section/functions/view-file.php?file={$encodedFile}' target='_blank'><i class='fas fa-eye'></i></a>
                                <a href='../Agent Section/functions/download.php?file={$encodedFile}' target='_blank'><i class='fas fa-download'></i></a>
                            " : "<span class='text-muted'>No File</span>") . "
                        </div>
                    </div>
                </div>

            </div>
            ";
          }
      } else {
          echo "
            <div class='no-requests-container' onclick='redirectWithId(123)'>
              <div class='drag-drop-content'>
                <i class='fas fa-user-slash upload-icon'></i>
                <span class='main-text'>No Request as of the Moment</span>
                <span class='accent-text'>Currently no payment inserted.</span>
              </div>
            </div>
          ";
      }
      ?>
  </div>


</div>