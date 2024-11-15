
<!-- Guest Table -->
<div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
  <div class="tab-wrapper">
    <div class="d-flex justify-content-end align-items-center p-3 mt-2">
      <div class="d-flex justify-content-end gap-2">
        <?php
          // Check if 'id' is passed in the URL
          if (isset($_GET['id'])) 
          {
            $transactionNumber = htmlspecialchars($_GET['id']);
          }

          // Run the query to get guest count and pax
          $query2 = "SELECT 
                        COALESCE(COUNT(guest.transactNo), 0) AS guest_count, 
                        COALESCE(booking.pax, 0) AS pax 
                      FROM booking 
                      LEFT JOIN guest ON guest.transactNo = booking.transactNo 
                      WHERE booking.transactNo = '$transactionNumber'";

          $result2 = $conn->query($query2);

          // Initialize guest_count and pax variables
          $guest_count = 0;
          $pax = 0;

          // Check if the query returned results
          if ($result2 && $result2->num_rows > 0) 
          {
            // Fetch the result
            $row2 = $result2->fetch_assoc();
            $guest_count = $row2['guest_count'];
            $pax = $row2['pax'];
          }

          // Determine whether to disable the button
          $disable_button = ($guest_count >= $pax) ? 'disabled' : ''; // Disable if guest_count >= pax
          ?>

          <!-- Add Guest Button -->
          <button type="button" class="btn btn-primary" 
                <?php echo $disable_button; ?> 
                onclick="if (!this.disabled) { window.location.href = 'agent-addGuest.php'; }">
            Add Guest Information
          </button>

        <button type="button" class="btn btn-primary">
          View Guest Files
        </button>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#visaModal">
          Attach Visa Requirements
        </button>
      </div>
    </div>

    <div class="table-container p-3">
      <table class="product-table">
        <thead>
          <tr>
            <th>GUEST ID</th>
            <th>FIRST NAME</th>
            <th>LAST NAME</th>
            <th>MIDDLE NAME</th>
            <th>SUFFIX</th>
            <th>BIRTHDATE</th>
            <th>AGE</th>
            <th>SEX</th>
            <th>NATIONALITY</th>
            <th>CONTACT NO.</th>
            <th>OTHER CONTACT NO.</th>
            <th>EMAIL</th>
            <th>ADDRESS</th>
            <th>PASSPORT NO.</th>
            <th>PASSPORT EXP.</th>
            <th>VISA STATUS</th>
          </tr>
        </thead>
        <tbody>
          <?php
            $sql1= "SELECT *, DATE_FORMAT(birthdate, '%M %d, %Y') AS birthdate, CONCAT(countryCode, contactNo) AS contactNo,
                    CASE 
                      WHEN countryCode2 IS NULL OR contactNo2 IS NULL THEN 'N/A'
                      ELSE CONCAT(countryCode2, contactNo2)
                    END AS contactNo2, CONCAT(addressLine1, ', ', 
                    CASE 
                      WHEN addressLine2 IS NOT NULL AND addressLine2 != '' THEN CONCAT(addressLine2, ', ') 
                      ELSE '' 
                    END, city, ', ', state, ', ', zipcode, ', ', country) AS address
                    FROM guest 
                    WHERE transactNo = '$transactionNumber'";

            $res1 = $conn->query($sql1);

            if ($res1->num_rows > 0) 
            {
              while ($row = $res1->fetch_assoc()) 
              {
                echo "<tr>
                        <td>{$row['guestId']}</td>
                        <td>{$row['fName']}</td>
                        <td>{$row['lName']}</td>
                        <td>{$row['mName']}</td>
                        <td>{$row['suffix']}</td>
                        <td>{$row['birthdate']}</td>
                        <td>{$row['age']}</td>
                        <td>{$row['sex']}</td>
                        <td>{$row['nationality']}</td>
                        <td>{$row['contactNo']}</td>
                        <td>{$row['contactNo2']}</td>
                        <td>{$row['emailAdd']}</td>
                        <td>{$row['address']}</td>
                        <td>{$row['passportNo']}</td>
                        <td>{$row['passportExp']}</td>
                        <td>{$row['visaStatus']}</td>
                      </tr>";
              }
            } 
            else 
            {
              echo "<tr><td colspan='100' style='text-align: center;'>No Guest found</td></tr>";
            }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Attach Visa Requirements Modal -->
<div class="modal fade" id="visaModal" tabindex="-1" aria-labelledby="visaModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title" id="visaModalLabel">
            Visa Requirements for Transaction No: <?php echo htmlspecialchars($_SESSION['transaction_number'] ?? ''); ?>
        </h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form action="../Agent Section/functions/agent-addVisaRequirements-code.php" method="POST" enctype="multipart/form-data">
        <div class="modal-body">
          <!-- Hidden input for transaction number -->
          <input type="hidden" name="transaction_number" value="<?php echo htmlspecialchars($_SESSION['transaction_number'] ?? ''); ?>">
          <?php
            // Assuming you have a database connection established
            $transactionNumber = $_SESSION['transaction_number'] ?? '';
            $query1 = "SELECT guestId, CONCAT(
                lName, ', ', fName, ' ', 
                CASE WHEN mName = 'N/A' THEN '' ELSE CONCAT(SUBSTRING(mName, 1, 1), '.') END, ' ',
                CASE WHEN suffix = 'N/A' THEN '' ELSE suffix END
            ) AS `FULLNAME` FROM guest WHERE transactNo = '$transactionNumber'";

            // Execute the query
            $res1 = mysqli_query($conn, $query1);

            if ($res1) 
            {
              // Count the number of guests
              $guestCount = mysqli_num_rows($res1);

              // Display the name and guestId for each guest inside input fields
              while ($row = mysqli_fetch_assoc($res1)) 
              {
                $guestId = $row['guestId'];
                $name = $row['FULLNAME'];

                // Create input fields for each guest
                echo "<div class='mb-3'>";
                echo "<label for='guest-$guestId' class='form-label'>Guest ID: $guestId</label>";
                echo "<input type='text' class='form-control' id='guest-$guestId' name='guestIds[]' value='$guestId' readonly>";

                echo "<label for='name-$guestId' class='form-label'>Name</label>";
                echo "<input type='text' class='form-control' id='name-$guestId' name='guestNames[]' value='$name' readonly>";

                echo "<h6 class='form-label'>Visa Requirements</h6>";

                echo "<label for='passport-$guestId' class='form-label'>Passport</label>";
                echo "<input type='file' class='form-control' id='passport-$guestId' name='passports[]' />";

                echo "<label for='permit-$guestId' class='form-label'>Permit</label>";

                echo "<input type='file' class='form-control' id='permit-$guestId' name='permits[]' />";

                echo "<label for='validId-$guestId' class='form-label'>Valid Id</label>";

                echo "<input type='file' class='form-control' id='validId-$guestId' name='validIds[]' />";

                echo "<label for='certificate-$guestId' class='form-label'>Certificate</label>";
                echo "<input type='file' class='form-control' id='certificate-$guestId' name='certificates[]' />";

                echo "<label for='guaranteedLetter-$guestId' class='form-label'>Guaranteed Letter</label>";
                echo "<input type='file' class='form-control' id='guaranteedLetter-$guestId' name='guaranteedLetters[]' />";
                echo "</div>";
              }
            } 
            else 
            {
              echo "<p>Error: " . mysqli_error($conn) . "</p>";
            }
          ?>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" name="attachVisaRequirements" class="btn btn-primary">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>