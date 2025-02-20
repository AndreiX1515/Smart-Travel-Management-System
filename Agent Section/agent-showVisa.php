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
          <th>GUEST ID</th>
          <th>GUEST NAME</th>
          <th>PASSPORT</th>
          <th>PERMIT</th>
          <th>VALID ID</th>
          <th>CERTIFICATE</th>
        </tr>
      </thead>
      <tbody>
        <?php
          $sql1 = "SELECT v.guestId, CONCAT(g.fName, ' ', 
                    IF(g.mName = 'N/A' OR g.mName IS NULL, '', CONCAT(SUBSTRING(g.mName, 1, 1), '. ')),
                    g.lName, IF(g.suffix = 'N/A' OR g.suffix IS NULL, '', CONCAT(' ', g.suffix))) AS guestName,
                    v.passport AS passport,v.permit AS permit, v.validId AS validId, v.certificate AS certificate
                  FROM visarequirements v
                  INNER JOIN guest g ON v.guestId = g.guestId
                  WHERE v.transactNo = '$transactionNumber'
                    AND (v.passport IS NOT NULL OR v.permit IS NOT NULL OR v.validId IS NOT NULL OR v.certificate IS NOT NULL)";

          $res1 = $conn->query($sql1);

          if ($res1->num_rows > 0) 
          {
            while ($row = $res1->fetch_assoc()) 
            {
              echo "<tr>
                  <td>{$row['guestId']}</td>
                  <td>{$row['guestName']}</td>

                  <td>";
                  echo !empty($row['passport']) 
                      ? "<a href='functions/view-file.php?file=" . urlencode($row['passport']) . "' target='_blank'>View File</a> 
                        <a href='functions/download.php?file=" . urlencode($row['passport']) . "' target='_blank'>Download File</a>" 
                      : "<button class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#uploadModal' 
                                data-guestid='{$row['guestId']}' data-filetype='passport'>Upload Passport</button>";
                  echo "</td>

                  <td>";
                  echo !empty($row['permit']) 
                      ? "<a href='functions/view-file.php?file=" . urlencode($row['permit']) . "' target='_blank'>View File</a> 
                        <a href='functions/download.php?file=" . urlencode($row['permit']) . "' target='_blank'>Download File</a>" 
                      : "<button class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#uploadModal' 
                                data-guestid='{$row['guestId']}' data-filetype='permit'>Upload Permit</button>";
                  echo "</td>

                  <td>";
                  echo !empty($row['validId']) 
                      ? "<a href='functions/view-file.php?file=" . urlencode($row['validId']) . "' target='_blank'>View File</a> 
                        <a href='functions/download.php?file=" . urlencode($row['validId']) . "' target='_blank'>Download File</a>" 
                      : "<button class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#uploadModal' 
                                data-guestid='{$row['guestId']}' data-filetype='validId'>Upload Valid ID</button>";
                  echo "</td>

                  <td>";
                  echo !empty($row['certificate']) 
                      ? "<a href='functions/view-file.php?file=" . urlencode($row['certificate']) . "' target='_blank'>View File</a> 
                        <a href='functions/download.php?file=" . urlencode($row['certificate']) . "' target='_blank'>Download File</a>" 
                      : "<button class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#uploadModal' 
                                data-guestid='{$row['guestId']}' data-filetype='certificate'>Upload Certificate</button>";
                  echo "</td>
                  </tr>";


            }
          } 
          else 
          {
            echo "<tr><td colspan='6' style='text-align: center;'>No Visa Status </td></tr>";
          }
        ?>
      </tbody>
    </table>


  </div>
</div>


<!-- Bootstrap Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="uploadModalLabel">Upload File</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="uploadForm" action="../Agent Section/functions/agent-visaRequirementsUpdate-code.php" method="POST" enctype="multipart/form-data">
        <div class="modal-body">
          <input type="" name="guestId" id="modalGuestId">
          <input type="" name="fileType" id="modalFileType">
          <input type="" name="transactNo" value="<?php echo $transactionNumber; ?>">

          <div class="mb-3">
            <label for="fileInput" class="form-label">Select File</label>
            <input type="file" class="form-control" name="file" id="fileInput" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" name="upload" class="btn btn-success">Upload</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  var uploadModal = document.getElementById('uploadModal');
  uploadModal.addEventListener('show.bs.modal', function (event) 
  {
    var button = event.relatedTarget; // Button that triggered the modal
    var guestId = button.getAttribute('data-guestid');
    var fileType = button.getAttribute('data-filetype');

    document.getElementById('modalGuestId').value = guestId;
    document.getElementById('modalFileType').value = fileType;

    // Change modal title dynamically
    document.getElementById('uploadModalLabel').innerText = "Upload " + fileType.charAt(0).toUpperCase() + fileType.slice(1);
  });
</script>

