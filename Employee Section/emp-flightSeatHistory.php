<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee - Transactions</title>
    <?php include '../Employee Section/includes/emp-head.php' ?>
				<link rel="stylesheet" href="../Employee Section/assets/css/emp-tableRequestPayment.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../Employee Section/assets/css/emp-sidebar-navbar.css?v=<?php echo time(); ?>">
</head>
<body>

<?php include '../Employee Section/includes/emp-sidebar.php' ?>

<!-- Main Container -->
<div class="main-container">
   <?php include '../Employee Section/includes/emp-navbar.php' ?>

   <div class="main-content">
        <div class="table-container">
            <div class="table-subheader d-flex align-items-center justify-content-between p-3 border-bottom bg-light">
                <div class="search-wrapper position-relative">
                        <input
                                type="text"
                                placeholder="Search..."
                                class="form-control search-input"
                                oninput="toggleClearButton(this)"
                        />
                        <button 
                                type="button"
                                class="clear-button"
                                onclick="clearInput(this)"
                                style="display: none;"
                        >
                                <i class="fas fa-times"></i>
                        </button>
                </div>

                <button class="" id="toggleButton" onclick="toggleDiv()">
                <i class="fas fa-filter"></i> Filters  
                </button>
        </div>

        <!-- Hidden Div -->
        <div class="filter-div" id="hiddenDiv" style="display: none;">
                <div class="dropdowns d-flex align-items-center justify-content-end gap-3">
                        <div class="clear-button-wrapper">
                                <button class="btn btn-danger">
                                        <i class="fa-solid fa-circle-xmark"></i>
                                </button>
                        </div>
                </div>
        </div>

        <div class="table-wrapper">
            <table class="">
																<thead>
																<tr>
																	<th>Transact No</th>
																	<th>Agent Name</th>
																	<th>Package Name</th>
																	<th>Booking Date</th>
																	<th>Flight Date</th>
																	<th>Total Pax</th>
																	<th>Status</th>
																</tr>
																</thead>
																<tbody>
																<?php
																		$sql1 = "SELECT b.transactNo AS `T.N`,
																	CONCAT(a.lName, ', ', a.fName, 
																																																	IF(a.mName IS NOT NULL AND a.mName != '', CONCAT(' ', LEFT(a.mName, 1)), '')) AS agentName,
																	p.packageName AS `PACKAGE`, DATE_FORMAT(b.bookingDate, '%m-%d-%Y') AS `BOOKING DATE`,
																	DATE_FORMAT(f.flightDepartureDate, '%m-%d-%Y') AS `FLIGHT DATE`,
																	b.pax AS `TOTAL PAX`, b.status AS `STATUS`
																	FROM 
																																	booking b
																	LEFT JOIN 
																																	flight f ON b.flightId = f.flightId
																	LEFT JOIN 
																																	package p ON b.packageId = p.packageId
																	LEFT JOIN
																																	agent a ON b.agentId = a.agentId
																	WHERE 
																																	b.status = 'Pending' 
																	ORDER BY 
																																	b.transactNo DESC";

																		$res1 = $conn->query($sql1);

																		if ($res1->num_rows > 0) 
																		{
																		while ($row = $res1->fetch_assoc()) 
																		{
																				$transactNo = $row['T.N'];
																				$agentName = $row['agentName'];
																				$package = $row['PACKAGE'];
																				$bookingDate = $row['BOOKING DATE'];
																				$flightDate = $row['FLIGHT DATE'];
																				$totalPax = $row['TOTAL PAX'];
																				$status = $row['STATUS'];
																				
																				// Determine status class
																				$statusClass = '';
																				switch ($status) 
																				{
																				case 'Confirmed':
																						$statusClass = 'bg-success text-white'; // Green
																						break;
																				case 'Reject':
																						$statusClass = 'bg-danger text-white'; // Red
																						break;
																				case 'Pending':
																							$statusClass = 'bg-warning text-dark'; // Yellow
																							break;
																				default:
																							$statusClass = 'bg-secondary text-white'; // Gray
																				}

																				echo "<tr class='transaction-row' data-transactNo='{$transactNo}'>
																										<td>{$transactNo}</td>
																										<td>{$agentName}</td>
																										<td>{$package}</td>
																										<td>{$bookingDate}</td>
																										<td>{$flightDate}</td>
																										<td>{$totalPax}</td>
																										<td>
																										<span class='badge p-2 rounded-pill {$statusClass}'>{$status}</span>
																										</td>
																	</tr>";
				}
																		}
																		else 
																		{
																		echo "<tr><td colspan='10'>No bookings found</td></tr>";
																		}
																?>
																</tbody>
            </table>
       	 </div>
        </div>  
   </div>
</div>


<script>
			// DOM Elements
			const toggleButton = document.getElementById('toggleButton');
			const closeButton = document.getElementById('closeButton');
			const hiddenDiv = document.getElementById('hiddenDiv');

			// Toggle the hidden div and button text with an icon
			toggleButton.addEventListener('click', function () {
							if (hiddenDiv.style.display === 'none' || hiddenDiv.style.display === '') {
											hiddenDiv.style.display = 'block';
											toggleButton.innerHTML = '<i class="fas fa-times"></i> Close'; // Add "Close" icon and text
							} else {
											hiddenDiv.style.display = 'none';
											toggleButton.innerHTML = ' <i class="fas fa-filter"></i> Filters'; // Add "Filters" icon and text
							}
			});

			// Close the hidden div and reset the button text with an icon
			closeButton.addEventListener('click', function () {
							hiddenDiv.style.display = 'none';
							toggleButton.innerHTML = '<i class="fas fa-filter"></i> Filters'; // Reset to "Filters" icon and text
			});
</script>




<script>
function toggleClearButton(input) {
    const clearButton = input.nextElementSibling; // Get the button next to the input
    clearButton.style.display = input.value ? "block" : "none";
}

// Clear the input field
function clearInput(button) {
    const input = button.previousElementSibling; // Get the input field before the button
    input.value = "";
    button.style.display = "none"; // Hide the clear button
    input.focus(); // Refocus on the input
}
</script>






<?php include '../Employee Section/includes/emp-scripts.php' ?>


</body>
</html>
