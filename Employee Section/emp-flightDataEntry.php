<div class="card">
  <div class="card-body">
    <ul class="nav nav-tabs" id="entryTab" role="tablist">
      <li class="nav-item" role="presentation">
        <button class="nav-link active" id="manual-tab" data-bs-toggle="tab" data-bs-target="#manual" type="button" role="tab">Manual Entry</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="csv-tab" data-bs-toggle="tab" data-bs-target="#csv" type="button" role="tab">CSV/Excel Upload</button>
      </li>
    </ul>
  </div>

  <div class="tab-content pt-3">
    <!-- Manual Flight Entry -->
    <div class="tab-pane fade show active" id="manual" role="tabpanel">
      <form id="manualFlightForm" method="POST" action="../Employee Section/functions/emp-addFlightDate.php">
        <h5>Trip Details</h5>

        <div id="tripContainer">
          <!-- Trip Block TEMPLATE (Trip 1 / index 0) -->
          <div class="trip-block border p-3 mb-3" data-trip-index="0">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <h6 class="mb-0">Trip 1</h6>
              <button type="button" class="btn btn-sm btn-outline-danger removeTrip" title="Remove this trip">Remove Trip</button>
            </div>

            <!-- Trip Info -->
            <div class="row mb-3">
              <div class="col-md-3">
                <label>Team OP</label>
                <select name="employeeId[0]" class="form-control" required>
                  <option disabled selected>Select Team OP</option>
                  <option value="0">No Team OP</option>
                  <?php
                    $res = $conn->query("SELECT * FROM employee");
                    while($row = $res->fetch_assoc()){
                      $fName = $row['fName'] ?? null;
                      $mName = $row['mName'] ?? null;
                      $lName = $row['lName'] ?? null;
                      if (empty($fName) && empty($lName)) 
                      {
                        $fullName = "No Team OP";
                      } else {
                        $middleInitial = $mName ? strtoupper(substr($mName, 0, 1)) . '.' : '';
                        $fullName = $lName . ", " . $fName . " " . $middleInitial;
                      }
                      echo "<option value='".$row['employeeId']."'>".$fullName."</option>";
                    }
                  ?>
                </select>
              </div>
              <div class="col-md-3">
                <label>Package</label>
                <select name="packageId[0]" class="form-control" required>
                  <option disabled selected>Select Package</option>
                  <?php
                    $res = $conn->query("SELECT * FROM package ORDER BY packageName");
                    while($row = $res->fetch_assoc()){
                      echo "<option value='{$row['packageId']}'>{$row['packageName']}</option>";
                    }
                  ?>
                </select>
              </div>
              <div class="col-md-2">
                <label>Wholesale Price</label>
                <input type="number" step="0.01" min="1" name="wholesalePrice[0]" class="form-control" required>
              </div>
              <div class="col-md-2">
                <label>Retail Price</label>
                <input type="number" step="0.01" min="1" name="retailPrice[0]" class="form-control" required>
              </div>
              <div class="col-md-2">
                <label>Land Price</label>
                <input type="number" step="0.01" min="1" name="landPrice[0]" class="form-control" required>
              </div>
              <div class="col-md-2">
                <label>Available Seats</label>
                <input type="number" min="1" name="availableSeats[0]" class="form-control" required>
              </div>
            </div>

            <!-- Flight Legs (scoped to this trip) -->
            <h6 class="mb-2">Flight Legs</h6>
            <div class="table-responsive">
              <table class="table table-bordered flight-legs-table">
                <thead>
                  <tr>
                    <th>Leg Type</th>
                    <th>Airline</th>
                    <th>Origin</th>
                    <th>Flight Name</th>
                    <th>Flight No.</th>
                    <th>Departure Date</th>
                    <th>Departure Time</th>
                    <th>Arrival Date</th>
                    <th>Arrival Time</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>
                      <select name="legType[0][]" class="form-control">
                        <option value="OUTBOUND">Outbound</option>
                        <option value="RETURN">Return</option>
                        <option value="CONNECTION">Connecting</option>
                        <option value="ONE WAY">One Way</option>
                      </select>
                    </td>
                    <td>
                      <select name="airlineId[0][]" class="form-control" required>
                        <option disabled selected>Select Airline</option>
                        <?php
                          $airlines = $conn->query("SELECT airlineId, airlineName, IATA FROM airline ORDER BY airlineName");
                          while ($row = $airlines->fetch_assoc()) {
                            echo "<option value='{$row['airlineId']}'>{$row['airlineName']} ({$row['IATA']})</option>";
                          }
                        ?>
                      </select>
                    </td>
                    <td><input type="text" name="origin[0][]" class="form-control" required></td>
                    <td><input type="text" name="flightName[0][]" class="form-control" required></td>
                    <td><input type="text" name="flightNumber[0][]" class="form-control" required></td>
                    <td><input type="date" name="departureDate[0][]" class="form-control" required></td>
                    <td><input type="time" name="departureTime[0][]" class="form-control" required></td>
                    <td><input type="date" name="arrivalDate[0][]" class="form-control" required></td>
                    <td><input type="time" name="arrivalTime[0][]" class="form-control" required></td>
                    <td>
                      <button type="button" class="btn btn-success btn-sm addRowLeg">+</button>
                      <button type="button" class="btn btn-danger btn-sm removeRowLeg">-</button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <button type="button" class="btn btn-sm btn-outline-secondary addRowLeg">+ Add Flight Leg</button>
          </div>
        </div>

        <!-- Add another trip button -->
        <button type="button" class="btn btn-sm btn-primary" id="addTripBtn">+ Add Another Trip</button>

        <!-- Save -->
        <div class="mt-3 text-end">
          <button type="submit" class="btn btn-primary">Save Trip(s)</button>
        </div>
      </form>
    </div>

    <!-- CSV/Excel Upload -->
    <div class="tab-pane fade" id="csv" role="tabpanel">
      <form method="POST" id="flightUploadForm" action="../Employee Section/functions/emp-importFlightDate.php" enctype="multipart/form-data">
        <div class="mb-3">
          <label for="csvFile" class="form-label">Upload CSV or Excel File</label>
          <input type="file" name="flightFile" class="form-control" id="csvFile" accept=".csv, .xlsx, .xls" required>
        </div>
        <div class="text-end">
          <button type="submit" class="btn btn-primary">Upload</button>
          <!-- <a href="transactions.php" class="btn btn-secondary">Back to List</a> -->
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Add Another Trip Script -->
<script>
  let tripIndex = 0; // current highest trip index (Trip 1 = 0)

  // Helper: clear values in a node
  function clearInputs(scope) {
    scope.querySelectorAll('input').forEach(i => i.value = '');
    scope.querySelectorAll('select').forEach(s => s.selectedIndex = 0);
  }

  // Helper: reindex all [<number>] name parts within a scope to the new tripIndex
  function reindexNames(scope, newIndex) {
    scope.querySelectorAll('input[name], select[name], textarea[name]').forEach(el => {
      if (!el.name) return;
      el.name = el.name.replace(/\[\d+\]/, `[${newIndex}]`);
    });
  }

  // Helper: renumber all trips after add/remove
  function renumberTrips() {
    const tripBlocks = document.querySelectorAll('.trip-block');
    tripBlocks.forEach((block, idx) => {
      block.dataset.tripIndex = idx;
      const header = block.querySelector('h6');
      if (header) header.textContent = `Trip ${idx + 1}`;
      reindexNames(block, idx);
    });
    // update the global tripIndex
    tripIndex = tripBlocks.length - 1;
  }

  // Add another Trip (with its own flight legs table)
  document.getElementById('addTripBtn').addEventListener('click', function () {
    const container = document.getElementById('tripContainer');
    const firstBlock = container.querySelector('.trip-block'); // use Trip 1 as template
    const clone = firstBlock.cloneNode(true);

    // Reset to a single blank leg row
    const tbody = clone.querySelector('.flight-legs-table tbody');
    const firstRow = tbody.querySelector('tr').cloneNode(true);
    firstRow.querySelectorAll('input').forEach(i => i.value = '');
    firstRow.querySelectorAll('select').forEach(s => s.selectedIndex = 0);
    tbody.innerHTML = '';
    tbody.appendChild(firstRow);

    // Clear trip inputs (team OP, package, prices, etc.)
    clearInputs(clone);

    container.appendChild(clone);

    // Renumber everything so indexes and labels are consistent
    renumberTrips();
  });

  // Event delegation for adding/removing legs inside the correct trip
  document.addEventListener('click', function (e) {
    const addLeg = e.target.closest('.addRowLeg');
    const removeLeg = e.target.closest('.removeRowLeg');
    const removeTrip = e.target.closest('.removeTrip');

    // Add leg row (scoped to the trip-block)
    if (addLeg) {
      const tripBlock = addLeg.closest('.trip-block');
      const tbody = tripBlock.querySelector('.flight-legs-table tbody');
      const last = tbody.querySelector('tr:last-child');
      const clone = last.cloneNode(true);
      clone.querySelectorAll('input').forEach(i => i.value = '');
      clone.querySelectorAll('select').forEach(s => s.selectedIndex = 0);
      tbody.appendChild(clone);
    }

    // Remove leg row (keep at least one)
    if (removeLeg) {
      const tbody = removeLeg.closest('tbody');
      if (tbody.querySelectorAll('tr').length > 1) {
        removeLeg.closest('tr').remove();
      }
    }

    // Remove whole trip (keep at least one)
    if (removeTrip) {
      const allTrips = document.querySelectorAll('.trip-block');
      if (allTrips.length > 1) {
        removeTrip.closest('.trip-block').remove();
        renumberTrips(); // <- re-fix numbering + indexes
      }
    }
  });
</script>

<!-- Dynamic Row for Data Entry -->
<!-- <script>
  document.addEventListener('DOMContentLoaded', function () 
  {
    const tableBody = document.querySelector('#manualFlightBody');

    // Handle Add Row
    tableBody.addEventListener('click', function (e) 
    {
      if (e.target.classList.contains('addRow')) 
      {
        const currentRow = e.target.closest('tr');
        const newRow = currentRow.cloneNode(true);

        // Clear all input and select values in the cloned row
        newRow.querySelectorAll('input, select').forEach(el => 
        {
          if (el.tagName === 'SELECT') 
          {
            el.selectedIndex = 0;
          } 
          else if (el.type === 'date' || el.type === 'number' || el.type === 'text') 
          {
            el.value = '';
          }
        });

        tableBody.appendChild(newRow);
      }

      // Handle Remove Row
      if (e.target.classList.contains('removeRow')) 
      {
        const rows = tableBody.querySelectorAll('tr');
        if (rows.length > 1) 
        {
          e.target.closest('tr').remove();
        }
      }
    });

    // Optional: Auto-set return date to 5 days after departure date
    tableBody.addEventListener('change', function (e) 
    {
      if (e.target.classList.contains('departure-date')) 
      {
        const depDateInput = e.target;
        const retDateInput = depDateInput.closest('tr').querySelector('.return-date');

        if (depDateInput.value) 
        {
          const depDate = new Date(depDateInput.value);
          depDate.setDate(depDate.getDate() + 5);
          retDateInput.valueAsDate = depDate;
        }
      }
    });
  });
</script> -->

<!-- Auto compute the return date based on departure date -->
<!-- <script>
  $(document).on('change', '.departure-date', function () {
    const departureInput = $(this);
    const departureDate = new Date(departureInput.val());

    if (!isNaN(departureDate)) {
      const returnDate = new Date(departureDate);
      returnDate.setDate(returnDate.getDate() + 6);

      const yyyy = returnDate.getFullYear();
      const mm = String(returnDate.getMonth() + 1).padStart(2, '0');
      const dd = String(returnDate.getDate()).padStart(2, '0');
      const formattedReturnDate = `${yyyy}-${mm}-${dd}`;

      // Update the return date in the same row
      departureInput.closest('tr').find('.return-date').val(formattedReturnDate);
    }
  });
</script> -->

<!-- AJAX for flight Submition manual -->
<script>
  $(document).ready(function () {
    $('#manualFlightForm').on('submit', function (e) {
      e.preventDefault(); // Prevent default form submission

      const form = $(this);
      const serializedData = form.serialize();

      // 🔍 Show serialized data
      console.log('📦 Serialized Data (query string):', serializedData);

      // 🔍 Convert to key-value pairs for easier reading
      const formDataObj = {};
      form.serializeArray().forEach(function (item) {
        if (!formDataObj[item.name]) {
          formDataObj[item.name] = item.value;
        } else {
          // If it's already an array, push
          if (!Array.isArray(formDataObj[item.name])) {
            formDataObj[item.name] = [formDataObj[item.name]];
          }
          formDataObj[item.name].push(item.value);
        }
      });

      // 📝 Log expanded key-value data
      console.log('📤 Data to be sent (expanded):', formDataObj);

      $.ajax({
        url: form.attr('action'),
        type: form.attr('method'),
        data: serializedData,
        beforeSend: function () {
          console.log('🚀 Sending AJAX request to:', form.attr('action'));
        },
        success: function (response) {
          console.log('✅ Server responded with:', response);

          let json;
          try {
            json = typeof response === 'string' ? JSON.parse(response) : response;
          } catch (e) {
            console.warn('⚠️ Could not parse JSON response:', response);
            alert('Unexpected server response.');
            return;
          }

          if (json.status === 'success') {
            alert(json.message);
            console.log('🟢 Success:', json.message);
          } else {
            alert('⚠️ Server error: ' + json.message);
            console.warn('🔍 Details:', json.details);
          }
        },
        error: function (xhr, status, error) {
          console.error('❌ AJAX request failed.');
          console.log('Status:', status);
          console.log('Error:', error);
          console.log('Response:', xhr.responseText);
          alert('An AJAX error occurred: ' + error);
        }
      });
    });
  });
</script>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('flightUploadForm');

    form.addEventListener('submit', function (e) {
      e.preventDefault(); // prevent normal form submission

      const formData = new FormData(form);

      fetch(form.action, {
        method: 'POST',
        body: formData
      })
        .then(res => res.json())
        .then(data => {
          alert(data.message);
          // Optionally, reload page or redirect after successful upload:
          window.location.href = '../Employee Section/emp-flightList.php';
        })
        .catch(() => {
          alert('❌ Something went wrong during upload.');
        });
    });
  });
</script>
