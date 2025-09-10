<div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab" tabindex="0">

    <div class="request-card-wrapper">
        <?php
        $sql1 = "SELECT request.requestId, concern.concernTitle, concerndetails.details, request.customRequest, 
                DATE_FORMAT(request.requestDate, '%m-%d-%Y') AS formattedRequestDate, 
                request.requestStatus
            FROM request
            LEFT JOIN concern ON request.concernId = concern.concernId
            LEFT JOIN concerndetails ON request.concernDetailsId = concerndetails.concernDetailsId
            WHERE request.transactNo = '$transactNum'";

        $res1 = $conn->query($sql1);

        if ($res1->num_rows > 0) {
            while ($row = $res1->fetch_assoc()) {
                $status = $row['requestStatus'];
                $badgeClass = '';
                switch ($status) {
                    case 'Confirmed':
                        $badgeClass = 'text-bg-success';
                        break;
                    case 'Submitted':
                        $badgeClass = 'text-bg-secondary';
                        break;
                    case 'Rejected':
                        $badgeClass = 'text-bg-danger';
                        break;
                    default:
                        $badgeClass = 'text-bg-info';
                        break;
                }

                $title = $row['concernTitle'] ?? 'Custom Request';
                $details = $row['details'] ?? $row['customRequest'];

                echo "
                    <div class='request-card'>
                        <div class='request-card-body'>
                            <div class='request-meta'>
                                <h5 class='request-title'>{$title}</h5>
                                <span class='badge rounded-pill {$badgeClass} p-2'>{$status}</span>
                            </div>

                            <p class='request-details'>{$details}</p>
                            <span class='request-date'>{$row['formattedRequestDate']}</span>
                            
                        </div>
                    </div>";
            }

        } else {
            echo "
            <div class='no-requests-container' onclick='redirectWithId(123)'>
              <div class='drag-drop-content'>
                <i class='fas fa-clipboard-list upload-icon'></i>
                <span class='main-text'>No Request as of the Moment</span>
                <span class='accent-text'>Currently no request inserted.</span>
              </div>
            </div>
            ";
        }
        ?>
    </div>


</div>