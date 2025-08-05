<!-- Guest Table -->
<div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">

  <div class="tabs-wrapper">

    <div class="table-header">
      <?php
      // Check if 'id' is passed in the URL
      if (isset($_GET['id'])) {
        $transactionNumber = htmlspecialchars($_GET['id']);
        $_SESSION['transaction_number'] = $transactionNumber;
      }

      // Run the query to get guest count and pax
      $query2 = "SELECT COALESCE(COUNT(g.transactNo), 0) AS guest_count, 
                          b.pax AS pax 
                        FROM booking b
                        LEFT JOIN guest g ON g.transactNo = b.transactNo 
                        WHERE b.transactNo = '$transactionNumber'";

      $query3 = "SELECT COALESCE(COUNT(v.transactNo), 0) AS visa_count, 
                          b.pax AS pax 
                        FROM booking b
                        LEFT JOIN visarequirements v ON v.transactNo = b.transactNo 
                        WHERE b.transactNo = '$transactionNumber'";

      $result2 = $conn->query($query2);
      $result3 = $conn->query($query3);

      // Check if the query returned results
      if ($result2 && $result2->num_rows > 0) {
        // Fetch the result
        $row2 = $result2->fetch_assoc();
        $guest_count = $row2['guest_count'];
        $pax2 = $row2['pax'];
      }

      if ($result3 && $result3->num_rows > 0) {
        // Fetch the result
        $row3 = $result3->fetch_assoc();
        $visa_count = $row3['visa_count'];
        $pax3 = $row3['pax'];
      }

      // Determine whether to disable the button
      $disable_button = ($guest_count >= $pax2) ? 'disabled' : ''; // Disable if guest_count >= pax
      $disable_button2 = ($visa_count >= $pax3) ? 'disabled' : ''; // Disable if guest_count >= pax
      ?>

      <!-- Add Guest Button -->
      <button type="button" class="btn btn-primary" <?php echo $disable_button; ?>
        onclick="if (!this.disabled) { window.location.href = 'agent-addGuest.php'; }">
        Add Guest Information
      </button>

      <!-- <button type="button" class="btn btn-primary">
          View Guest Files
        </button> -->
      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#visaModal">
        Attach Visa Requirements
      </button>

    </div>

    <!-- <p>Pax: <?php echo $pax2; ?></p>
    <p>Guest Count: <?php echo $guest_count; ?></p>
    <p>Visa Count: <?php echo $visa_count; ?></p> -->


    <div class="table-container">
      <div class="table-wrapper-container">
        <table class="product-table">
          <thead>
            <tr>
              <th>GUEST ID</th>
              <th>NAME</th>
              <th>BIRTHDATE</th>
              <th>AGE</th>
              <th>SEX</th>
              <th>NATIONALITY</th>
              <th>CONTACT NO.</th>
              <th>OTHER CONTACT NO.</th>
              <th>EMAIL</th>
              <th>ADDRESS</th>
              <th>PASSPORT NO.</th>
              <th>PASSPORT ISSUED DATE</th>
              <th>PASSPORT EXP.</th>
              <th>VISA STATUS</th>
            </tr>
          </thead>
          <tbody>
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
                    WHERE transactNo = '$transactionNumber'";

            $res1 = $conn->query($sql1);

            if ($res1->num_rows > 0) {
              while ($row = $res1->fetch_assoc()) {
                // Define the full name variable with suffix
                // Define the full name without suffix first
                $fullName = $row['fName'] . ' ' . $row['mName'] . ' ' . $row['lName'];

                // Append suffix only if it is not "N/A"
                if (!empty($row['suffix']) && $row['suffix'] !== 'N/A') {
                  $fullName .= ' ' . $row['suffix']; // Append suffix if it exists and is not "N/A"
                }

                // Escape values for safety
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
                $passportIssuedDate = $row['passportIssuedDate'] ?? '';
                $passportExp = htmlspecialchars($row['passportExp']);

                echo "<tr data-url='agent-updateGuestInfo.php?id={$guestId}'>
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
                          <td>{$passportIssuedDate}</td>
                          <td>{$passportExp}</td>
                          <td>{$row['visaStatus']}</td>
                        </tr>";
              }
            } else {
              echo "<tr><td colspan='100' style='text-align: center;'>No Guest found</td></tr>";
            }
            ?>
          </tbody>
        </table>

      </div>
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

      <form action="../Agent Section/functions/agent-addVisaRequirements-code.php" method="POST"
        enctype="multipart/form-data">
        <div class="modal-body">
          <input type="hidden" name="transaction_number"
            value="<?php echo htmlspecialchars($_SESSION['transaction_number'] ?? ''); ?>">
          <input type="hidden" name="accId" value="<?php echo $accountId; ?>">

          <!-- Select Guest -->
          <div class="mb-4">
            <label for="guestSelect" class="form-label">Select Guest:</label>
            <select class="form-select" id="guestSelect" onchange="addGuestFields(this)">
              <option selected disabled>-- Select Guest --</option>
              <?php
              if ($res1) {
                $query1 = "SELECT g.guestId, CONCAT(g.lName, ', ', g.fName, ' ', 
                              CASE WHEN g.suffix = 'N/A' THEN '' ELSE g.suffix END, ' ',
                              CASE WHEN g.mName = 'N/A' THEN '' ELSE CONCAT(SUBSTRING(g.mName, 1, 1), '.') END) AS FULLNAME 
                            FROM guest g
                            WHERE g.transactNo = '$transactionNumber'";
                $res1 = mysqli_query($conn, $query1);
                while ($row = mysqli_fetch_assoc($res1)) {
                  $guestId = $row['guestId'];
                  $fullName = htmlspecialchars($row['FULLNAME']);
                  echo "<option value='$guestId'>$fullName</option>";
                }
              } else {
                echo "<option value=''>No guests available</option>";
              }
              ?>
            </select>
          </div>

          <!-- Container for all guests' visa requirements -->
          <div id="allGuestFields"></div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" name="attachVisaRequirements" class="btn btn-primary">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- New Visa Requirements Guest Script -->
<script>
  let addedGuests = new Set();

  function addGuestFields(selectElement) {
    const guestId = selectElement.value;
    const guestName = selectElement.options[selectElement.selectedIndex].text;

    if (!guestId || addedGuests.has(guestId)) {
      alert("Guest already added or invalid selection.");
      return;
    }

    console.log(`Adding fields for guestId: ${guestId}, guestName: ${guestName}`);
    addedGuests.add(guestId);

    const allGuestFieldsContainer = document.getElementById("allGuestFields");
    const guestFieldsHTML = `
      <div id="guestFields-${guestId}" class="guest-fields border rounded p-4 mt-4 bg-light shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h6 class="mb-0">Visa Requirements for <strong>${guestName}</strong></h6>
          <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeGuestFields('${guestId}')">
            Remove ${guestName}
          </button>
        </div>
        <input type="hidden" name="guestIds[]" value="${guestId}">

        <label class="form-label">Select Document to Upload:</label>
        <select class="form-select" onchange="showFileInput(this, ${guestId})">
          <option selected disabled>-- Select Document --</option>
          <option value="passport">Passport</option>
          <option value="permit">Permit</option>
          <option value="validId">Valid ID</option>
          <option value="certificate">Certificate</option>
          <option value="guaranteedLetter">Guaranteed Letter</option>
        </select>

        <div id="fileInputs-${guestId}"></div>
      </div>`;

    allGuestFieldsContainer.insertAdjacentHTML("beforeend", guestFieldsHTML);
  }

  function showFileInput(selectElement, guestId) {
    const fileInputsContainer = document.getElementById(`fileInputs-${guestId}`);
    const selectedValue = selectElement.value;

    console.log(`Document selected for guest ${guestId}: ${selectedValue}`);

    if (!selectedValue) {
      alert("Please select a document type.");
      return;
    }

    const insertStaticAbove = ["passport", "validId", "guaranteedLetter"];
    const inputId = `${selectedValue}-${guestId}`;

    if (document.getElementById(inputId)) {
      selectElement.selectedIndex = 0;
      console.warn(`Input for ${selectedValue} already exists for guest ${guestId}`);
      return;
    }

    if (selectedValue === "certificate") {
      const certContainerId = `certificateWrapper-${guestId}`;
      if (!document.getElementById(certContainerId)) {
        const subCertificateHTML = `
          <div id="${certContainerId}" class="mt-3 mb-3">
            <label class="form-label">Select Certificate Type:</label>
            <select class="form-select" onchange="showCertificateInput(this, ${guestId})">
              <option selected disabled>-- Select Certificate Type --</option>
              <option value="bankCert">Bank Certificate / Statement</option>
              <option value="coe">COE</option>
              <option value="com">COM</option>
              <option value="birthCert">Birth Certificate</option>
            </select>
            <div id="certificateInput-${guestId}"></div>
          </div>
        `;
        console.log(`Rendering certificate selector for guest ${guestId}`);
        fileInputsContainer.insertAdjacentHTML("beforeend", subCertificateHTML);
      }
    } else if (selectedValue === "permit") {
      const permitContainerId = `permitWrapper-${guestId}`;
      if (!document.getElementById(permitContainerId)) {
        const subPermitHTML = `
          <div id="${permitContainerId}" class="mt-3 mb-3">
            <label class="form-label">Select Permit Type:</label>
            <select class="form-select" onchange="showPermitInput(this, ${guestId})">
              <option selected disabled>-- Select Permit Type --</option>
              <option value="businessPermit">Business Permit / Mayor's Permit</option>
              <option value="secDti">SEC or DTI</option>
              <option value="itr">ITR</option>
            </select>
            <div id="permitInput-${guestId}"></div>
          </div>
        `;
        console.log(`Rendering permit selector for guest ${guestId}`);
        fileInputsContainer.insertAdjacentHTML("beforeend", subPermitHTML);
      }
    } else if (insertStaticAbove.includes(selectedValue)) {
      const labelText = selectElement.options[selectElement.selectedIndex].text;
      const fileInputHTML = `
        <div class="mt-3 mb-3 d-flex align-items-center" id="${inputId}">
          <label class="form-label me-2">${labelText}:</label>
          <input type="file" class="form-control me-2" name="${selectedValue}[${guestId}][]" style="width:70%" multiple>
          <button type="button" class="btn btn-danger btn-sm" onclick="this.parentElement.remove()">X</button>
        </div>`;
      const certWrapper = document.getElementById(`certificateWrapper-${guestId}`);
      const permitWrapper = document.getElementById(`permitWrapper-${guestId}`);
      const insertBefore = certWrapper || permitWrapper;

      console.log(`Appending basic input for ${selectedValue} to guest ${guestId}`);
      if (insertBefore) {
        fileInputsContainer.insertAdjacentHTML("afterbegin", fileInputHTML);
      } else {
        fileInputsContainer.insertAdjacentHTML("beforeend", fileInputHTML);
      }
    }

    selectElement.selectedIndex = 0;
  }

  function showCertificateInput(subSelectElement, guestId) {
    const certValue = subSelectElement.value;
    const certText = subSelectElement.options[subSelectElement.selectedIndex].text;
    const certInputContainer = document.getElementById(`certificateInput-${guestId}`);
    const inputId = `${certValue}-${guestId}`;
    const parentDocType = 'certificate';
    const fieldName = certValue;

    if (document.getElementById(inputId)) return;

    console.log(`Selected Certificate Subtype for guest ${guestId}: ${certValue}`);

    const hiddenInput = document.createElement('input');
    hiddenInput.type = 'hidden';
    hiddenInput.name = `docSubType[${guestId}][${parentDocType}][${fieldName}][]`;
    hiddenInput.value = certValue;
    hiddenInput.id = inputId;
    certInputContainer.appendChild(hiddenInput);

    const small = document.createElement('small');
    small.className = 'text-muted';
    small.textContent = `Selected: ${certText}`;
    certInputContainer.appendChild(small);

    appendFileInput(certInputContainer, fieldName, certText, guestId, inputId, parentDocType);

    subSelectElement.selectedIndex = 0;
  }

  function showPermitInput(subSelectElement, guestId) {
    const permitValue = subSelectElement.value;
    const permitText = subSelectElement.options[subSelectElement.selectedIndex].text;
    const permitInputContainer = document.getElementById(`permitInput-${guestId}`);
    const inputId = `${permitValue}-${guestId}`;
    const parentDocType = 'permit';
    const fieldName = permitValue;

    if (document.getElementById(inputId)) return;

    console.log(`Selected Permit Subtype for guest ${guestId}: ${permitValue}`);

    const hiddenInput = document.createElement('input');
    hiddenInput.type = 'hidden';
    hiddenInput.name = `docSubType[${guestId}][${parentDocType}][${fieldName}][]`;
    hiddenInput.value = permitValue;
    hiddenInput.id = inputId;
    permitInputContainer.appendChild(hiddenInput);

    const small = document.createElement('small');
    small.className = 'text-muted';
    small.textContent = `Selected: ${permitText}`;
    permitInputContainer.appendChild(small);

    appendFileInput(permitInputContainer, fieldName, permitText, guestId, inputId, parentDocType);

    subSelectElement.selectedIndex = 0;
  }

  function appendFileInput(container, fieldName, labelText, guestId, inputId, parentDocType = fieldName) {
    const fileInputHTML = `
      <div class="row align-items-center mt-3 mb-3" id="${inputId}">
        <div class="col-md-3">
          <label class="form-label">${labelText}:</label>
        </div>
        <div class="col-md-7">
          <input type="file" class="form-control" name="${parentDocType}[${guestId}][${fieldName}][]" multiple>
        </div>
        <div class="col-md-2">
          <button type="button" class="btn btn-danger btn-sm w-100" onclick="this.closest('.row').remove()">Remove</button>
        </div>
      </div>`;
    container.insertAdjacentHTML("beforeend", fileInputHTML);

    console.log(`File input added: name="${parentDocType}[${guestId}][${fieldName}][]"`);
  }

  function removeGuestFields(guestId) {
    document.getElementById(`guestFields-${guestId}`).remove();
    addedGuests.delete(guestId);
    console.log(`Removed guest fields for guestId: ${guestId}`);
    document.getElementById("guestSelect").selectedIndex = 0;
  }
</script>

<!-- Old Visa Requirements Guest Script -->
<!-- <script>
  let addedGuests = new Set();

  function addGuestFields(selectElement) {
    const guestId = selectElement.value;
    const guestName = selectElement.options[selectElement.selectedIndex].text;

    if (!guestId || addedGuests.has(guestId)) {
      alert("Guest already added or invalid selection.");
      return;
    }

    addedGuests.add(guestId);

    const allGuestFieldsContainer = document.getElementById("allGuestFields");
    const guestFieldsHTML = `
      <div id="guestFields-${guestId}" class="guest-fields border rounded p-3 mt-3">
        <h6>Visa Requirements for ${guestName}</h6>
        <input type="hidden" name="guestIds[]" value="${guestId}">

        <div class="mb-3">
          <label class="form-label">Select Document to Upload:</label>
          <select class="form-select" onchange="showFileInput(this, ${guestId})">
            <option selected disabled>-- Select Document --</option>
            <option value="passport">Passport</option>
            <option value="permit">Permit</option>
            <option value="validId">Valid ID</option>
            <option value="certificate">Certificate</option>
            <option value="guaranteedLetter">Guaranteed Letter</option>
          </select>
        </div>

        <div id="fileInputs-${guestId}"></div>

        <button type="button" class="btn btn-danger btn-sm mt-2" onclick="removeGuestFields('${guestId}')">
          Remove ${guestName}
        </button>
      </div>`;

    allGuestFieldsContainer.insertAdjacentHTML("beforeend", guestFieldsHTML);
  }

  function showFileInput(selectElement, guestId) {
    const fileInputsContainer = document.getElementById(`fileInputs-${guestId}`);

    if (!fileInputsContainer) {
      console.error(`Error: File input container not found for guestId: ${guestId}`);
      return;
    }

    const selectedValue = selectElement.value;

    if (!selectedValue) {
      alert("Please select a document type.");
      return;
    }

    // Allow multiple file inputs for each document type
    const fileInputHTML = `
      <div class="mb-3 d-flex align-items-center">
        <label class="form-label me-2">${selectElement.options[selectElement.selectedIndex].text}:</label>
        <input type="file" class="form-control me-2" name="${selectedValue}[${guestId}][]" style="width:70%" multiple>
        <button type="button" class="btn btn-danger btn-sm" onclick="this.parentElement.remove()">X</button>
      </div>`;

    fileInputsContainer.insertAdjacentHTML("beforeend", fileInputHTML);
  }

  function removeGuestFields(guestId) {
    document.getElementById(`guestFields-${guestId}`).remove();
    addedGuests.delete(guestId);
  }
</script>  -->

<!-- Working Properly Reset Modal When Closed -->
<script>
  document.addEventListener("DOMContentLoaded", function () {
    const visaModal = document.getElementById("visaModal");

    visaModal.addEventListener("hidden.bs.modal", function () {
      // Reset the form
      document.querySelector("#visaModal form").reset();

      // Only remove guest fields, but keep the "Select Guest" dropdown
      const allGuestFieldsContainer = document.getElementById("allGuestFields");
      const guestSelectWrapper = document.querySelector("#allGuestFields .mb-4"); // Keeps the select field
      allGuestFieldsContainer.innerHTML = ""; // Clear everything first
      if (guestSelectWrapper) {
        allGuestFieldsContainer.appendChild(guestSelectWrapper); // Restore select field
      }

      // Re-enable all previously disabled dropdown options
      const guestSelect = document.getElementById("guestSelect");
      for (let i = 0; i < guestSelect.options.length; i++) {
        guestSelect.options[i].disabled = false;
      }

      // Reset the guest dropdown selection
      guestSelect.selectedIndex = 0;
    });
  });
</script>

<!-- Specific Row Clickable Script -->
<script>
  document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll("tr[data-url]").forEach(function (row) {
      row.addEventListener("click", function () {
        window.location.href = row.getAttribute("data-url");
      });
    });
  });
  // Add event listener to each row for redirection
  const rows = document.querySelectorAll("tr[data-url]");

  rows.forEach(row => {
    row.addEventListener("click", function () {
      const url = row.getAttribute("data-url");
      window.location.href = url; // Redirect to the specified URL
    });
  });
</script>