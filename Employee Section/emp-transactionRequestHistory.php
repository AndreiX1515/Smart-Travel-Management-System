<div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">

  <div class="card-header px-4 py-2">
    <h6>Request History</h6>
  </div>

  <div class="request-table-wrapper">
    <table class="request-table">
      <thead>
        <tr>
          <th>REQUEST ID</th>
          <th>REQUEST TITLE</th>
          <th>REQUEST DETAILS</th>
          <th>REQUEST DATE</th>
          <th>STATUS</th>
        </tr>
      </thead>
      <tbody>
      <?php
            $sql1 = "SELECT request.requestId, concern.concernTitle, concerndetails.details, request.customRequest,
                      DATE_FORMAT(request.requestDate, '%m-%d-%Y') AS formattedRequestDate, 
                      request.requestStatus
                  FROM request
                  LEFT JOIN concern ON request.concernId = concern.concernId
                  LEFT JOIN concerndetails ON request.concernDetailsId = concerndetails.concernDetailsId
                  WHERE request.transactNo = '$transactNum'";

            $res1 = $conn->query($sql1);

            if ($res1->num_rows > 0) 
            {
              while ($row = $res1->fetch_assoc()) 
              {
                // Fetch the status from the database
                $status = $row['requestStatus']; // Ensure 'requestStatus' exists in the database row
        
                // Assign a corresponding Bootstrap badge class based on the status
                $badgeClass = '';
                switch ($status) 
                {
                  case 'Confirmed':
                      $badgeClass = 'text-bg-success'; // Green for Confirmed
                      break;
                  case 'Submitted':
                      $badgeClass = 'text-bg-secondary'; // Gray for Submitted
                      break;
                  case 'Rejected':
                      $badgeClass = 'text-bg-danger'; // Red for Rejected
                      break;
                  default:
                      $badgeClass = 'text-bg-info'; // Blue for any other status
                      break;
                }
          
                // Handle custom requests by checking if concernTitle or details are NULL
                $title = $row['concernTitle'] ?? 'Custom Request';
                $details = $row['details'] ?? $row['customRequest'];
        
                echo "<tr>
                        <td>{$row['requestId']}</td>
                        <td>{$title}</td>
                        <td>{$details}</td>
                        <td>{$row['formattedRequestDate']}</td>
                        <td>
                          <span class='badge rounded-pill {$badgeClass} p-2'>{$status}</span>
                        </td>
                      </tr>";
              }
            } 
            else 
            {
              echo "<tr><td colspan='100' style='text-align: center;'>No Requests Found</td></tr>";
            }
          ?>
      </tbody>
    </table>
  </div>

</div>
