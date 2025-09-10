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
        <div class="table-responsive">
          <table class="table table-bordered table-centered mb-0" id="manualEntryTable">
            <thead class="table-light">
              <tr>
                <th>Team OP</th>
                <th>Package</th>
                <th colspan="5" class="text-center">Departure</th>
                <th colspan="5" class="text-center">Return</th>
                <th colspan="4" class="text-center">Pricing & Seats</th>
              </tr>
              <tr>
                <th colspan="2"></th>
                <!-- Departure -->
                <th>Airline</th>
                <th>Origin</th>
                <th>Destination</th>
                <th>Flight No.</th>
                <th>Date & Time</th>
                <!-- Return -->
                <th>Airline</th>
                <th>Origin</th>
                <th>Destination</th>
                <th>Flight No.</th>
                <th>Date & Time</th>
                <!-- Pricing -->
                <th>Wholesale</th>
                <th>Flight</th>
                <th>Land</th>
                <th>Seats</th>
              </tr>
            </thead>
            <tbody id="manualFlightBody">
              <tr>
                <!-- Team OP -->
                <td>
                  <select name="employeeId[]" class="form-control">
                    <option selected disabled>Select Team OP</option>
                    <option value="">No Team OP</option>
                    <?php
                      $sql1 = "SELECT * FROM employee";
                      $res1 = $conn->query($sql1);
                      if ($res1 && $res1->num_rows > 0) {
                        while($row = $res1->fetch_assoc()) {
                          $fName = $row['fName'] ?? null;
                          $mName = $row['mName'] ?? null;
                          $lName = $row['lName'] ?? null;
                          $middleInitial = $mName ? strtoupper(substr($mName, 0, 1)) . '.' : '';
                          $fullName = $lName . ", " . $fName . " " . $middleInitial;
                          echo "<option value='".$row['employeeId']."'>".$fullName."</option>";
                        }
                      }
                    ?>
                  </select>
                </td>

                <!-- Package -->
                <td>
                  <select name="packageId[]" class="form-control" required>
                    <option selected disabled>Select Package</option>
                    <?php
                      $sql2 = "SELECT * FROM package ORDER BY packageName";
                      $res2 = $conn->query($sql2);
                      if ($res2 && $res2->num_rows > 0) {
                        while($row = $res2->fetch_assoc()) {
                          echo "<option value='".$row['packageId']."'>".$row['packageName']."</option>";
                        }
                      }
                    ?>
                  </select>
                </td>

                <!-- Departure -->
                <td>
                  <select name="airlineId[]" class="form-control" required>
                    <option selected disabled>Select Airline</option>
                    <?php
                      $sqlAir = "SELECT airlineId, airlineName, IATA FROM airline ORDER BY airlineName";
                      $resAir = $conn->query($sqlAir);
                      if ($resAir && $resAir->num_rows > 0) {
                        while ($row = $resAir->fetch_assoc()) {
                          echo "<option value='".$row['airlineId']."'>".$row['airlineName']." (".$row['IATA'].")</option>";
                        }
                      }
                    ?>
                  </select>
                </td>
                <td><input type="text" name="origin[]" class="form-control" required></td>
                <td><input type="text" name="destination[]" class="form-control" required></td>
                <td><input type="text" name="flightCode[]" class="form-control" required></td>
                <td>
                  <!-- Departure -->
                  <label class="small fw-bold d-block">Departure</label>
                  <input type="date" name="departureDate[]" class="form-control form-control-sm mb-1" required>
                  <input type="time" name="departureTime[]" class="form-control form-control-sm mb-2" required>

                  <hr class="my-1">

                  <!-- Arrival -->
                  <label class="small fw-bold d-block">Arrival</label>
                  <input type="date" name="arrivalDate[]" class="form-control form-control-sm mb-1" required>
                  <input type="time" name="arrivalTime[]" class="form-control form-control-sm" required>
                </td>

                <!-- Return -->
                <td>
                  <select name="returnAirlineId[]" class="form-control" required>
                    <option selected disabled>Select Airline</option>
                    <?php
                      $sqlAir = "SELECT airlineId, airlineName, IATA FROM airline ORDER BY airlineName";
                      $resAir = $conn->query($sqlAir);
                      if ($resAir && $resAir->num_rows > 0) {
                        while ($row = $resAir->fetch_assoc()) {
                          echo "<option value='".$row['airlineId']."'>".$row['airlineName']." (".$row['IATA'].")</option>";
                        }
                      }
                    ?>
                  </select>
                </td>
                <td><input type="text" name="returnOrigin[]" class="form-control" required></td>
                <td><input type="text" name="returnDestination[]" class="form-control" required></td>
                <td><input type="text" name="returnFlightCode[]" class="form-control" required></td>
                <td>
                  <!-- Departure -->
                  <label class="small fw-bold d-block">Departure</label>
                  <input type="date" name="returnDate[]" class="form-control mb-1" required>
                  <input type="time" name="returnDepartureTime[]" class="form-control" required>

                  <hr class="my-1">

                  <!-- Arrival -->
                  <label class="small fw-bold d-block">Arrival</label>
                  <input type="date" name="returnArrivalDate[]" class="form-control mb-1" required>
                  <input type="time" name="returnArrivalTime[]" class="form-control" required>
                </td>

                <!-- Prices & Seats -->
                <td><input type="number" name="wholesalePrice[]" step="0.01" class="form-control" min="1" required></td>
                <td><input type="number" name="flightPrice[]" step="0.01" class="form-control" min="1" required></td>
                <td><input type="number" name="landPrice[]" step="0.01" class="form-control" min="1" required></td>
                <td><input type="number" name="availSeats[]" class="form-control" min="1" required></td>

                <!-- Actions -->
                <td>
                  <button type="button" class="btn btn-success btn-sm addRow">+</button>
                  <button type="button" class="btn btn-danger btn-sm removeRow">-</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="mt-3 text-end">
          <button type="submit" class="btn btn-primary">Save Flights</button>
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

<!-- Dynamic Row for Data Entry -->
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const tableBody = document.querySelector('#manualFlightBody');

    // Handle Add / Remove Row
    tableBody.addEventListener('click', function (e) {
      // ➕ Add Row
      if (e.target.classList.contains('addRow')) {
        const currentRow = e.target.closest('tr');
        const newRow = currentRow.cloneNode(true);

        // Clear all values
        newRow.querySelectorAll('input, select').forEach(el => {
          if (el.tagName === 'SELECT') {
            el.selectedIndex = 0;
          } else if (['date', 'number', 'text'].includes(el.type)) {
            el.value = '';
          }
        });

        // Append right after the current row
        currentRow.after(newRow);
      }

      // ❌ Remove Row
      if (e.target.classList.contains('removeRow')) {
        const rows = tableBody.querySelectorAll('tr');
        if (rows.length > 1) {
          e.target.closest('tr').remove();
        }
      }
    });
  });
</script>

<!-- Auto compute the return date based on departure date (+6 days) -->
<script>
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
</script>

<!-- AJAX for flight Submission -->
<script>
  $(document).ready(function () {
    $('#manualFlightForm').on('submit', function (e) {
      e.preventDefault(); // Prevent default form submission

      const form = $(this);
      const serializedData = form.serialize();

      console.log('📦 Serialized Data:', serializedData);

      // Expanded object for debugging
      const formDataObj = {};
      form.serializeArray().forEach(function (item) {
        if (!formDataObj[item.name]) {
          formDataObj[item.name] = item.value;
        } else {
          if (!Array.isArray(formDataObj[item.name])) {
            formDataObj[item.name] = [formDataObj[item.name]];
          }
          formDataObj[item.name].push(item.value);
        }
      });
      console.log('📤 Data (expanded):', formDataObj);

      $.ajax({
        url: form.attr('action'),
        type: form.attr('method'),
        data: serializedData,
        beforeSend: function () {
          console.log('🚀 Sending AJAX request to:', form.attr('action'));
        },
        success: function (response) {
          console.log('✅ Server responded with:', response);

          let json = null;
          try {
            json = typeof response === 'string' ? JSON.parse(response) : response;
          } catch (e) {
            console.warn('⚠️ Could not parse JSON response.');
          }

          if (json && json.status === 'success') {
            alert(json.message);

            // Reset form values
            form[0].reset();

            // Reset form values
            form[0].reset();

            // Keep only the first row as a template
            const firstRowHtml = $('#manualFlightBody tr').first().prop('outerHTML');
            $('#manualFlightBody').html(firstRowHtml);

            // Clear any values inside the new row (to be safe)
            $('#manualFlightBody tr')
              .find('input, select')
              .val('')
              .prop('selectedIndex', 0);
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
