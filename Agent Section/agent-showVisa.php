<?php
  // Check if 'id' is passed in the URL
  if (isset($_GET['id'])) 
  {
    $transactionNumber = htmlspecialchars($_GET['id']);
  } 
?>

<!-- Visa Requirements Table -->
<div class="tab-pane fade" id="pills-visa" role="tabpanel" aria-labelledby="pills-visa-tab" tabindex="0">
    <div class="d-flex justify-content-end align-items-center p-3">
      <!-- <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#requestModal" 
      data-transaction-id="<?= $transactionNumber ?>">Add Request</button> -->
    </div>
    <div class="table-container">
      <table class="product-table">
        <thead>
          <tr>
            <th>Guest Id</th>
            <th>Guest Name</th>
            <th>Passport</th>
            <th>Permit</th>
            <th>Valid Id</th>
            <th>Certificate</th>
          </tr>
        </thead>
        <tbody>
          <?php
            $sql1 = "Select g.guestId, v.*, CONCAT(g.fName, ' ', 
                    IF(g.mName = 'N/A' OR g.mName IS NULL, '', CONCAT(SUBSTRING(g.mName, 1, 1), '. ')),
                    g.lName, 
                    IF(g.suffix = 'N/A' OR g.suffix IS NULL, '', CONCAT(' ', g.suffix))) AS guestName from guest g 
            join visarequirements v on g.transactNo = v.transactNo
            where g.transactNo = '$transactionNumber'";

            $res1 = $conn->query($sql1);

            if ($res1->num_rows > 0) 
            {
              while ($row = $res1->fetch_assoc()) 
              {
               
                //  // Fetch the status from the database
                //  $status = $row['requestStatus']; // Ensure 'requestStatus' exists in the database row

                //  // Assign a corresponding Bootstrap badge class based on the status
                //  $badgeClass = '';

                //  switch ($status) {
                //      case 'Confirmed':
                //          $badgeClass = 'text-bg-success'; // Green for Confirmed
                //          break;
                //      case 'Submitted':
                //          $badgeClass = 'text-bg-secondary'; // Gray for Submitted
                //          break;
                //      case 'Rejected':
                //          $badgeClass = 'text-bg-danger'; // Red for Rejected
                //          break;
                //      default:
                //          $badgeClass = 'text-bg-info'; // Blue for any other status
                //          break;
                //  }
    
                echo "<tr>
                        <td>{$row['guestId']}</td>
                        <td>{$row['guestName']}</td>
                        <td>
                              <a href='functions/view-file.php?file=" . urlencode($row['passport']) . "' target='_blank'>View File</a> 
                              <a href='functions/download.php?file=" . urlencode($row['passport']) . "' target='_blank'>Download File</a> 
                          </td>
                        <td>{$row['permit']}</td>
                        <td>{$row['validId']}</td>
                        <td>{$row['certificate']}</td>
                      </tr>";
              }
            }
            else 
            {
              echo "<tr><td colspan='100' style='text-align: center;'>No Payment Found</td></tr>";
            }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</div>


