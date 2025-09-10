<div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">

  <div class="card-body">
    <div class="guest-table-wrapper">
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
                          <tr class='table-row' data-guest-id='{$guestId}'>
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
            <div class='no-requests-container' onclick='redirectWithId(123)'>
              <div class='drag-drop-content'>
                <i class='fas fa-user-slash upload-icon'></i>
                <span class='main-text'>No Guest Information Found</span>
                <span class='accent-text'>Currently no guest info inserted.</span>
              </div>
            </div>
            ";
        }
        ?>
      </table>
    </div>
  </div>

</div>


<!-- <script>
function redirectWithId(id) {
  window.location.href = "yourpage.php?id=" + id;
}
</script>

<span class='sub-text'>Click here to add a new guest</span> -->
