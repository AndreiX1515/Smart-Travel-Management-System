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
				<th>Flight Date</th>
				<th>Wholesale Price</th>
				<th>Retail Price</th>
				<th>Land Price</th>
				<th>Available Seats</th>
			</tr>
		</thead>
		<tbody>
			<?php
				$sql = "SELECT t.tripId, t.startDate, t.endDate, t.landPrice, t.wholesalePrice, t.retailPrice, t.availableSeats, p.packageId, p.packageName,
									e.employeeId, e.fName, e.mName, e.lName, f1.flightNumber AS outboundFlightNumber, f1.departureDate AS outboundDepartureDate, 
									f1.departureTime AS outboundDepartureTime, f1.arrivalDate AS outboundArrivalDate, f1.arrivalTime AS outboundArrivalTime,
									f2.flightNumber AS returnFlightNumber, f2.departureDate AS returnDepartureDate, f2.departureTime AS returnDepartureTime,
									f2.arrivalDate AS returnArrivalDate, f2.arrivalTime AS returnArrivalTime, a1.airlineName AS outboundAirlineName, 
									a2.airlineName AS returnAirlineName
								FROM trip t
								JOIN package p ON t.packageId = p.packageId
								LEFT JOIN employee e ON t.employeeId = e.employeeId
								LEFT JOIN tripFlight tf1 ON tf1.tripId = t.tripId AND tf1.legType = 'OUTBOUND'
								LEFT JOIN flight f1 ON tf1.flightId = f1.flightId
								LEFT JOIN tripFlight tf2 ON tf2.tripId = t.tripId AND tf2.legType = 'RETURN'
								LEFT JOIN flight f2 ON tf2.flightId = f2.flightId
								LEFT JOIN airline a1 ON f1.airlineId = a1.airlineId
								LEFT JOIN airline a2 ON f2.airlineId = a2.airlineId
								ORDER BY t.startDate ASC";

				$result = $conn->query($sql);

				if ($result->num_rows > 0) {
					while ($row = $result->fetch_assoc()) {
						// Team OP
						$teamOp = $row['employeeId'] ? trim("{$row['fName']} {$row['mName']} {$row['lName']}"): 'No Team OP';

						// Outbound
						$outbound = $row['outboundFlightNumber'] 
								? "<strong>Airline:</strong> {$row['outboundAirlineName']}<br>" .
									"<strong>Outbound:</strong> {$row['outboundFlightNumber']}<br>" .
									"Dep: " . date('M d, Y H:i', strtotime($row['outboundDepartureDate']." ".$row['outboundDepartureTime'])) . "<br>" .
									"Arr: " . date('M d, Y H:i', strtotime($row['outboundArrivalDate']." ".$row['outboundArrivalTime']))
								: "—";

						// Return
						$return = $row['returnFlightNumber'] 
								? "<br><br><strong>Airline:</strong> {$row['returnAirlineName']}<br>" .
									"<strong>Return:</strong> {$row['returnFlightNumber']}<br>" .
									"Dep: " . date('M d, Y H:i', strtotime($row['returnDepartureDate']." ".$row['returnDepartureTime'])) . "<br>" .
									"Arr: " . date('M d, Y H:i', strtotime($row['returnArrivalDate']." ".$row['returnArrivalTime']))
								: "";

						echo "<tr>
										<td>{$teamOp}</td>
										<td>{$row['packageName']}</td>
										<td>{$outbound}{$return}</td>
										<td>₱ " . number_format($row['wholesalePrice'], 2) . "</td>
										<td>₱ " . number_format($row['retailPrice'], 2) . "</td>
										<td>₱ " . number_format($row['landPrice'], 2) . "</td>
										<td>{$row['availableSeats']}</td>
									</tr>";
					}
				} else {
					echo "<tr><td colspan='7' style='text-align:center;'>No trips found.</td></tr>";
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

<!-- Flight Update Modal -->
<!-- <div class="modal fade" id="flightModal" tabindex="-1" aria-labelledby="flightModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      
      <div class="modal-header">
        <h5 class="modal-title" id="flightModalLabel">Update Flight Schedule</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <form id="updateFlightForm">
          <input type="hidden" id="flightId" name="flightId">

					<div class="mb-3">
						<label for="packageId" class="form-label">Select Package</label>
						<select name="packageId" id="packageId" class="form-control" required>
							<option selected disabled>Select Package</option>
							<?php
								$sql1 = "SELECT * FROM package ORDER BY packageName";
								$res1 = $conn->query($sql1);
								
								if ($res1 -> num_rows > 0)
								{
									while($row = $res1->fetch_assoc())
									{
										echo "<option value='".$row['packageId']."'>".$row['packageName']."</option>";
									}
								}
								else
								{
									echo "No Package Found";
								}
							?>
            </select>
					</div>

					<div class="mb-3">
						<label for="employeeId" class="form-label">Select Team OP</label>
						<select name="employeeId" id="employeeId" class="form-control" required>
							<option selected disabled>Select Team OP</option>
							<option value="">No Team OP</option>
							<?php
								$sql1 = "SELECT * FROM employee";
								$res1 = $conn->query($sql1);
								
								if ($res1 -> num_rows > 0)
								{
									while($row = $res1->fetch_assoc())
									{
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
								}
								else
								{
									echo "No Package Found";
								}
							?>
            </select>
					</div>
								
					<h5>Outbound Flight</h5>
					
          <div class="mb-3">
            <label for="flightDepartureDate" class="form-label">Departure Date</label>
            <input type="date" class="form-control" id="flightDepartureDate" name="flightDepartureDate" required>
          </div>

					<div class="mb-3">
            <label for="flightDepartureTime" class="form-label">Departure Time</label>
            <input type="time" class="form-control" id="flightDepartureTime" name="flightDepartureTime" required>
          </div>

					<div class="mb-3">
            <label for="flightArrivalDate" class="form-label">Arrival Date</label>
            <input type="time" class="form-control" id="flightArrivalDate" name="flightArrivalDate" required>
          </div>

					<div class="mb-3">
            <label for="flightArrivalTime" class="form-label">Arrival Time</label>
            <input type="time" class="form-control" id="flightArrivalTime" name="flightArrivalTime" required>
          </div>

					<h5>Return Flight</h5>

          <div class="mb-3">
            <label for="returnDepartureDate" class="form-label">Return Departure Date</label>
            <input type="date" class="form-control" id="returnDepartureDate" name="returnDepartureDate" required>
          </div>

					<div class="mb-3">
            <label for="returnDepartureTime" class="form-label">Return Arrival Time</label>
            <input type="date" class="form-control" id="returnDepartureTime" name="returnDepartureTime" required>
          </div>

					<div class="mb-3">
            <label for="returnArrivalDate" class="form-label">Return Arrival Date</label>
            <input type="date" class="form-control" id="returnArrivalDate" name="returnArrivalDate" required>
          </div>

					<div class="mb-3">
            <label for="returnArrivalTime" class="form-label">Return Arrival Time</label>
            <input type="date" class="form-control" id="returnArrivalTime" name="returnArrivalTime" required>
          </div>

          <div class="mb-3">
            <label for="availSeats" class="form-label">Available Seats</label>
            <input type="number" class="form-control" id="availSeats" name="availSeats" required>
          </div>

					<div class="mb-3">
            <label for="retailPrice" class="form-label">Retail Price</label>
            <input type="number" class="form-control" id="retailPrice" name="retailPrice" required>
          </div>

					<div class="mb-3">
            <label for="wholesalePrice" class="form-label">Wholesale Price</label>
            <input type="number" class="form-control" id="wholesalePrice" name="wholesalePrice" required>
          </div>

					<div class="mb-3">
            <label for="landPrice" class="form-label">Land Price</label>
            <input type="number" class="form-control" id="landPrice" name="landPrice" required>
          </div>

          <button type="submit" class="btn btn-primary">Update</button>
        </form>
      </div>

    </div>
  </div>
</div> -->

<!-- <script>
	document.addEventListener("DOMContentLoaded", function() {
		const flightModal = document.getElementById('flightModal');
		
		flightModal.addEventListener('show.bs.modal', function (event) {
			const button = event.relatedTarget; // Row that triggered the modal
			
			// Extract data from data-* attributes
			const flightId = button.getAttribute('data-flightId');
			const packageId = button.getAttribute('data-packageId');
			const employeeId = button.getAttribute('data-op');
			const departureDate = button.getAttribute('data-departureDate');
			const departureTime = button.getAttribute('data-departureTime');
			const arrivalDate = button.getAttribute('data-arrivalDate');
			const arrivalTime = button.getAttribute('data-arrivalTime');
			const returnDepartureDate = button.getAttribute('data-returnDepartureDate');
			const returnDepartureTime = button.getAttribute('data-returnDepartureTime');
			const returnArrivalDate = button.getAttribute('data-returnArrivalDate');
			const returnArrivalTime = button.getAttribute('data-returnArrivalTime');
			const seats = button.getAttribute('data-seats');
			const retailPrice = button.getAttribute('data-retail');
			const wholesalePrice = button.getAttribute('data-wholesale');
			const landPrice = button.getAttribute('data-land');

			console.log(employeeId);
			// Fill modal inputs
			document.getElementById('flightId').value = flightId;
			document.getElementById('packageId').value = packageId;
			document.getElementById('employeeId').value = employeeId;
			document.getElementById('flightDepartureDate').value = departureDate;
			document.getElementById('flightDepartureTime').value = departureTime;
			document.getElementById('flightArrivalDate').value = arrivalDate;
			document.getElementById('flightArrivalTime').value = arrivalTime;
			document.getElementById('returnDepartureDate').value = returnDepartureDate;
			document.getElementById('returnDepartureTime').value = returnDepartureTime;
			document.getElementById('returnArrivalDate').value = returnArrivalDate;
			document.getElementById('returnArrivalTime').value = returnArrivalTime;
			document.getElementById('availSeats').value = seats;
			document.getElementById('retailPrice').value = retailPrice;
			document.getElementById('wholesalePrice').value = wholesalePrice;
			document.getElementById('landPrice').value = landPrice;
		});

		// Handle form submit (AJAX update)
		document.getElementById("updateFlightForm").addEventListener("submit", function(e) {
			e.preventDefault();
			const formData = new FormData(this);

			fetch("updateFlight.php", {
				method: "POST",
				body: formData
			})
			.then(res => res.text())
			.then(data => {
				alert(data); // Success message
				const modal = bootstrap.Modal.getInstance(flightModal);
				modal.hide();
				location.reload(); // Refresh table
			})
			.catch(err => console.error(err));
		});
	});
</script> -->
