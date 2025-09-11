<div class="table-header">
  <div class="search-wrapper">
    <div class="search-input-wrapper">
      <input type="text" id="search" name="search" placeholder="Search here..">
    </div>
  </div>

  <div class="second-header-wrapper">
    <div class="buttons-wrapper">
      <button id="clearSorting" class="btn btn-secondary">Clear Filters</button>
    </div>
  </div>
</div>

<div class="table-wrapper">
  <table class="product-table" id="product-table">
		<thead>
			<tr>
				<th>Team OP</th>
				<th>Package</th>
				<th>Departure Flight</th>
				<th>Return Flight</th>
				<th>Wholesale Price</th>
				<th>Retail Price</th>
				<th>Land Price</th>
				<th>Available Seats</th>
			</tr>
		</thead>
		<tbody>
			<?php
				$sql1 = "SELECT f.*, e.fName, e.mName, e.lName, p.packageName, a.airlineName, ra.airlineName as returnAirlineName
								FROM flight f
								LEFT JOIN employee e ON f.employeeId = e.employeeId
								JOIN package p ON f.packageId = p.packageId
								LEFT JOIN airline a ON f.airlineId = a.airlineId
								LEFT JOIN airline ra ON f.returnAirlineId = ra.airlineId";

				$res1 = $conn->query($sql1);

				if ($res1->num_rows > 0) {
					while ($row = $res1->fetch_assoc()) {
						$wholesaleFormatted = number_format($row['wholesalePrice'], 2);
						$landPriceFormatted = number_format($row['landPrice'], 2);
						$flightFormatted    = number_format($row['flightPrice'], 2);

						// Team OP name
						$fName = $row['fName'] ?? null;
						$mName = $row['mName'] ?? null;
						$lName = $row['lName'] ?? null;
						if (empty($fName) && empty($lName)) {
							$fullName = "No Team OP";
						} else {
							$middleInitial = $mName ? strtoupper(substr($mName, 0, 1)) . '.' : '';
							$fullName = $lName . ", " . $fName . " " . $middleInitial;
						}

						// Departure flight details
						$depDetails = "{$row['airlineName']} ({$row['flightCode']})<br>"
												. date('Y.m.d H:i', strtotime($row['flightDepartureDate'].' '.$row['flightDepartureTime']))
												. " → "
												. date('Y.m.d H:i', strtotime($row['flightArrivalDate'].' '.$row['flightArrivalTime']));

						// Return flight details
						$retDetails = "{$row['returnAirlineName']} ({$row['returnFlightCode']})<br>"
												. date('Y.m.d H:i', strtotime($row['returnDepartureDate'].' '.$row['returnDepartureTime']))
												. " → "
												. date('Y.m.d H:i', strtotime($row['returnArrivalDate'].' '.$row['returnArrivalTime']));

						echo "<tr data-flight='" . htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8') . "'>
										<td>{$fullName}</td>
										<td>{$row['packageName']}</td>
										<td>{$depDetails}</td>
										<td>{$retDetails}</td>
										<td>₱ {$wholesaleFormatted}</td>
										<td>₱ {$flightFormatted}</td>
										<td>₱ {$landPriceFormatted}</td>
										<td>{$row['availSeats']}</td>
									</tr>";
					}
				} else {
					echo "<tr><td colspan='8' style='text-align: center;'>No Flights Found</td></tr>";
				}
			?>
		</tbody>
</table>

</div>

<div class="table-footer">
  <div class="pagination-controls">
    <button id="prevPage" class="pagination-btn">Previous</button>
    <span id="pageInfo" class="page-info">Page 1 of 1</span>
    <button id="nextPage" class="pagination-btn">Next</button>
  </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editFlightModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Flight & Package Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="editFlightForm">
          <input type="text" id="flightId" name="flightId">

          <!-- General Information -->
          <h6 class="fw-bold mb-2">General Information</h6>
          <div class="row mb-3">
            <div class="col">
              <label>Package</label>
              <select id="packageId" name="packageId" class="form-select" required>
                <option disabled>-- Select Package --</option>
                <?php
                  $pkgSql = "SELECT packageId, packageName FROM package ORDER BY packageName";
                  $pkgRes = $conn->query($pkgSql);
                  while ($pkgRow = $pkgRes->fetch_assoc()) {
                    echo "<option value='{$pkgRow['packageId']}'>{$pkgRow['packageName']}</option>";
                  }
                ?>
              </select>
            </div>
            <div class="col">
              <label>Team OP (Employee)</label>
              <select id="employeeId" name="employeeId" class="form-select">
                <option value="">-- No Team OP --</option>
                <?php
                  $empSql = "SELECT employeeId, fName, mName, lName FROM employee ORDER BY lName, fName";
                  $empRes = $conn->query($empSql);
                  while ($empRow = $empRes->fetch_assoc()) {
                    $middleInitial = $empRow['mName'] ? strtoupper(substr($empRow['mName'],0,1))."." : "";
                    $fullName = $empRow['lName'] . ", " . $empRow['fName'] . " " . $middleInitial;
                    echo "<option value='{$empRow['employeeId']}'>{$fullName}</option>";
                  }
                ?>
              </select>
            </div>
          </div>

					<hr>

          <!-- Departure Flight -->
          <h6 class="fw-bold mb-2">Departure Flight</h6>
          <div class="row mb-3">
            <div class="col">
              <label>Airline</label>
              <select id="airlineId" name="airlineId" class="form-select" required>
                <option value="">-- Select Airline --</option>
                <?php
                  $airSql = "SELECT airlineId, airlineName, IATA FROM airline ORDER BY airlineName";
                  $airRes = $conn->query($airSql);
                  while ($airRow = $airRes->fetch_assoc()) {
                    $label = $airRow['airlineName'] . " (" . $airRow['IATA'] . ")";
                    echo "<option value='{$airRow['airlineId']}'>{$label}</option>";
                  }
                ?>
              </select>
            </div>
            <div class="col">
              <label>Flight Number</label>
              <input type="text" id="flightCode" name="flightCode" class="form-control">
            </div>
            <div class="col">
              <label>Departure Date</label>
              <input type="date" id="flightDepartureDate" name="flightDepartureDate" class="form-control">
            </div>
            <div class="col">
              <label>Departure Time</label>
              <input type="time" id="flightDepartureTime" name="flightDepartureTime" class="form-control">
            </div>
            <div class="col">
              <label>Arrival Date</label>
              <input type="date" id="flightArrivalDate" name="flightArrivalDate" class="form-control">
            </div>
            <div class="col">
              <label>Arrival Time</label>
              <input type="time" id="flightArrivalTime" name="flightArrivalTime" class="form-control">
            </div>
          </div>
					
					<hr>

          <!-- Return Flight -->
          <h6 class="fw-bold mb-2">Return Flight</h6>
          <div class="row mb-3">
            <div class="col">
              <label>Airline</label>
              <select id="returnAirlineId" name="returnAirlineId" class="form-select">
                <option value="">-- Select Airline --</option>
                <?php
                  $airSql2 = "SELECT airlineId, airlineName, IATA FROM airline ORDER BY airlineName";
                  $airRes2 = $conn->query($airSql2);
                  while ($airRow2 = $airRes2->fetch_assoc()) {
                    $label2 = $airRow2['airlineName'] . " (" . $airRow2['IATA'] . ")";
                    echo "<option value='{$airRow2['airlineId']}'>{$label2}</option>";
                  }
                ?>
              </select>
            </div>
            <div class="col">
              <label>Flight Number</label>
              <input type="text" id="returnFlightCode" name="returnFlightCode" class="form-control">
            </div>
            <div class="col">
              <label>Departure Date</label>
              <input type="date" id="returnDepartureDate" name="returnDepartureDate" class="form-control">
            </div>
            <div class="col">
              <label>Departure Time</label>
              <input type="time" id="returnDepartureTime" name="returnDepartureTime" class="form-control">
            </div>
            <div class="col">
              <label>Arrival Date</label>
              <input type="date" id="returnArrivalDate" name="returnArrivalDate" class="form-control">
            </div>
            <div class="col">
              <label>Arrival Time</label>
              <input type="time" id="returnArrivalTime" name="returnArrivalTime" class="form-control">
            </div>
          </div>

					<hr>

          <!-- Pricing & Availability -->
          <h6 class="fw-bold mb-2">Pricing & Availability</h6>
          <div class="row mb-3">
            <div class="col">
              <label>Wholesale Price</label>
              <input type="number" id="wholesalePrice" name="wholesalePrice" class="form-control" step="0.01">
            </div>
            <div class="col">
              <label>Retail Price</label>
              <input type="number" id="flightPrice" name="flightPrice" class="form-control" step="0.01">
            </div>
            <div class="col">
              <label>Land Price</label>
              <input type="number" id="landPrice" name="landPrice" class="form-control" step="0.01">
            </div>
            <div class="col">
              <label>Available Seats</label>
              <input type="number" id="availSeats" name="availSeats" class="form-control">
            </div>
          </div>

          <div class="text-end">
            <button type="submit" class="btn btn-primary">Save Changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>


<script>
$(document).ready(function () {
  // Row click handler
	$('#product-table tbody').on('click', 'tr', function () {
		const flight = $(this).data('flight');
		if (!flight) return;

		$('#flightId').val(flight.flightId);

		// Preselect dropdowns
		$('#packageId').val(flight.packageId);
		$('#airlineId').val(flight.airlineId);
		$('#employeeId').val(flight.employeeId || "");
		$('#returnAirlineId').val(flight.returnAirlineId);

		// Departure Flight
		$('#flightCode').val(flight.flightCode || "");
		$('#flightDepartureDate').val(flight.flightDepartureDate || "");
		$('#flightDepartureTime').val(flight.flightDepartureTime ? flight.flightDepartureTime.substring(0,5) : "");
		$('#flightArrivalDate').val(flight.flightArrivalDate || "");
		$('#flightArrivalTime').val(flight.flightArrivalTime ? flight.flightArrivalTime.substring(0,5) : "");

		// Return Flight
		$('#returnFlightCode').val(flight.returnFlightCode || "");
		$('#returnDepartureDate').val(flight.returnDepartureDate || "");
		$('#returnDepartureTime').val(flight.returnDepartureTime ? flight.returnDepartureTime.substring(0,5) : "");
		$('#returnArrivalDate').val(flight.returnArrivalDate || "");
		$('#returnArrivalTime').val(flight.returnArrivalTime ? flight.returnArrivalTime.substring(0,5) : "");

		// Pricing
		$('#wholesalePrice').val(flight.wholesalePrice || "");
		$('#flightPrice').val(flight.flightPrice || "");
		$('#landPrice').val(flight.landPrice || "");
		$('#availSeats').val(flight.availSeats || "");

		$('#editFlightModal').modal('show');
	});

  // Handle form submission (AJAX update)
  $('#editFlightForm').on('submit', function (e) {
    e.preventDefault();

    $.ajax({
      url: '../Employee Section/functions/emp-updateFlight.php',
      method: 'POST',     // ✅ force POST
      dataType: 'json',   // ✅ expect JSON
      data: $(this).serialize(),
      success: function (res) {
        console.log(res); // debug
        if (res.status === 'success') {
          alert(res.message);
          $('#editFlightModal').modal('hide');
          location.reload();
        } else {
          alert('Error: ' + res.message);
        }
      },
      error: function (xhr) {
        console.log(xhr.responseText);
        alert('AJAX Error: ' + xhr.status + ' ' + xhr.statusText);
      }
    });
  });
});

</script>
