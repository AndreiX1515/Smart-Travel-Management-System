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
             $sql1 = "SELECT g.guestId, 
                             CONCAT(g.fName, ' ', 
                                    IF(g.mName = 'N/A' OR g.mName IS NULL, '', CONCAT(SUBSTRING(g.mName, 1, 1), '. ')),
                                    g.lName, 
                                    IF(g.suffix = 'N/A' OR g.suffix IS NULL, '', CONCAT(' ', g.suffix))) AS guestName,
                             GROUP_CONCAT(v.passport) AS passport,
                             GROUP_CONCAT(v.permit) AS permit,
                             GROUP_CONCAT(v.validId) AS validId,
                             GROUP_CONCAT(v.certificate) AS certificate
                       FROM guest g
                       JOIN visarequirements v ON g.transactNo = v.transactNo
                       WHERE g.transactNo = '$transactionNumber'
                       GROUP BY g.guestId";

             $res1 = $conn->query($sql1);

             if ($res1->num_rows > 0) {
               while ($row = $res1->fetch_assoc()) {
                 echo "<tr>
                         <td>{$row['guestId']}</td>
                         <td>{$row['guestName']}</td>
                         <td>
                           <a href='../Agent Section/functions/view-file.php?file=" . urlencode($row['passport']) . "' target='_blank'>View File</a> 
                           <a href='../Agent Section/functions/download.php?file=" . urlencode($row['passport']) . "' target='_blank'>Download File</a> 
                         </td>

                         <td>
                           <a href='../Agent Section/functions/view-file.php?file=" . urlencode($row['permit']) . "' target='_blank'>View File</a> 
                           <a href='../Agent Section/functions/download.php?file=" . urlencode($row['permit']) . "' target='_blank'>Download File</a> 
                         </td>

                         <td>
                           <a href='../Agent Section/functions/view-file.php?file=" . urlencode($row['validId']) . "' target='_blank'>View File</a> 
                           <a href='../Agent Section/functions/download.php?file=" . urlencode($row['validId']) . "' target='_blank'>Download File</a> 
                         </td>

                         <td>
                           <a href='../Agent Section/functions/view-file.php?file=" . urlencode($row['certificate']) . "' target='_blank'>View File</a> 
                           <a href='../Agent Section/functions/download.php?file=" . urlencode($row['certificate']) . "' target='_blank'>Download File</a> 
                         </td>
                       </tr>";
               }
             } else {
               echo "<tr><td colspan='6' style='text-align: center;'>No Visa Status </td></tr>";
             }
           ?>
         </tbody>
       </table>


    </div>
  </div>



