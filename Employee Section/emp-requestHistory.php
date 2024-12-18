
<?php 
	session_start(); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Employee - Transactions</title>
	<?php include '../Employee Section/includes/emp-head.php' ?>
	<link rel="stylesheet" href="../Employee Section/assets/css/emp-dashboard.css?v=<?php echo time(); ?>">
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
				<!-- Search -->
				<div class="search-wrapper position-relative">
					<input type="text" placeholder="Search..." class="form-control search-input" oninput="toggleClearButton(this)"/>
					<button type="button" class="clear-button" onclick="clearInput(this)" style="display: none;">
						<i class="fas fa-times"></i>
					</button>
				</div>
    
				<!-- Dropdowns -->
				<div class="dropdowns d-flex align-items-center gap-3">
					<!-- Items per Page Dropdown -->
					<div class="dropdown">
						<button class="btn btn-outline-secondary dropdown-toggle" type="button" id="itemsPerPageDropdown"
							data-bs-toggle="dropdown" aria-expanded="false">
							Items per Page
						</button>
						<ul class="dropdown-menu" aria-labelledby="itemsPerPageDropdown">
							<li><a class="dropdown-item" href="#">5</a></li>
							<li><a class="dropdown-item" href="#">10</a></li>
							<li><a class="dropdown-item" href="#">50</a></li>
							<li><a class="dropdown-item" href="#">100</a></li>
						</ul>
					</div>

					<!-- Date Range Dropdown -->
					<div class="dropdown">
						<button class="btn btn-outline-secondary dropdown-toggle" type="button" id="dateRangeDropdown" data-bs-toggle="dropdown"
							aria-expanded="false">
							Date Range
						</button>
						<ul class="dropdown-menu" aria-labelledby="dateRangeDropdown">
							<li><a class="dropdown-item" href="#">Today</a></li>
							<li><a class="dropdown-item" href="#">This Week</a></li>
							<li><a class="dropdown-item" href="#">This Month</a></li>
							<li><a class="dropdown-item" href="#">Custom Range</a></li>
						</ul>
					</div>

					<!-- Filter Options Dropdown -->
					<div class="dropdown">
						<button class="btn btn-outline-secondary dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown" 
							aria-expanded="false">
							Filter Options
						</button>
						<ul class="dropdown-menu" aria-labelledby="filterDropdown">
							<li><a class="dropdown-item" href="#">Status</a></li>
							<li><a class="dropdown-item" href="#">Category</a></li>
							<li><a class="dropdown-item" href="#">Priority</a></li>
							<li><a class="dropdown-item" href="#">Custom Filter</a></li>
						</ul>
					</div>

					<!-- Export Options Dropdown -->
					<div class="dropdown">
						<button class="btn btn-outline-secondary dropdown-toggle" type="button" id="exportDropdown" data-bs-toggle="dropdown"
							aria-expanded="false">
							Export
						</button>
						<!-- <ul class="dropdown-menu" aria-labelledby="exportDropdown">
								<li><a class="dropdown-item" href="#">Export as CSV</a></li>
								<li><a class="dropdown-item" href="#">Export as Excel</a></li>
								<li><a class="dropdown-item" href="#">Export as PDF</a></li>
						</ul> -->
					</div>

					<div class="clear-button-wrapper">
						<button class="btn btn-danger">
							<i class="fa-solid fa-circle-xmark"></i>
						</button>
					</div>
				</div>
      </div>
        
			<script>
				function toggleClearButton(input) 
				{
					const clearButton = input.nextElementSibling; // Get the button next to the input
					clearButton.style.display = input.value ? "block" : "none";
				}

				// Clear the input field
				function clearInput(button) 
				{
					const input = button.previousElementSibling; // Get the input field before the button
					input.value = "";
					button.style.display = "none"; // Hide the clear button
					input.focus(); // Refocus on the input
				}
			</script>

			<div class="table-wrapper">
				<table class="">
					<thead>
						<tr>
							<th>Request Id</th>
              <th>Transact No</th>
              <th>Agent Name</th>
              <th>Request Title</th>
              <th>Request Details</th>
              <th>Specific Details</th>
              <th>Total Pax</th>
              <th>Total Amount</th>
              <th>Request Date</th>
              <th>Status</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$sql1 = "SELECT r.requestId, r.transactNo AS `TransactNo`,
													CONCAT(a.lName, ', ', a.fName, 
															IF(a.mName IS NOT NULL AND a.mName != '', CONCAT(' ', LEFT(a.mName, 1), '.'), '')) AS AgentName,
													c.concernTitle AS `RequestTitle`, cd.details AS `RequestDetails`, b.pax AS `TotalPax`,
													r.requestCost as requestCost,
													r.customRequest as customRequest, r.details as details, DATE_FORMAT(r.requestDate, '%m-%d-%Y') AS `RequestDate`, 
													r.requestStatus AS `Status`
											FROM 
													request r
											LEFT JOIN 
													concern c ON r.concernId = c.concernId
											LEFT JOIN 
													concerndetails cd ON r.concernDetailsId = cd.concernDetailsId
											LEFT JOIN 
													booking b ON r.transactNo = b.transactNo
											LEFT JOIN 
													payment p ON b.transactNo = p.transactNo
											LEFT JOIN 
													agent a ON b.agentId = a.agentId
											GROUP BY 
													r.requestId";

							$res1 = $conn->query($sql1);

							if ($res1->num_rows > 0) 
							{
								while ($row = $res1->fetch_assoc()) 
								{
									// Determine the badge class based on the status
									$status = $row['Status'];
									$badgeClass = '';
									switch ($status) 
									{
										case 'Confirmed':
												$badgeClass = 'text-bg-success'; // Green for Confirmed
												break;
										case 'Submitted':
												$badgeClass = 'text-bg-secondary'; // Gray for Submitted
												break;
										case 'Rejected':
												$badgeClass = 'text-bg-danger'; // Red for Rejected
												break;
										default:
												$badgeClass = 'text-bg-info'; // Blue for other statuses
												break;
									}

									// Ensure that title and details are displayed properly
									$title = $row['RequestTitle'] ?? 'Custom Request';
									$details = $row['RequestDetails'] ?? $row['customRequest'];

									// Output table row with data-transactno attribute
									echo "<tr>
													<td>{$row['requestId']}</td>
													<td>{$row['TransactNo']}</td>
													<td>{$row['AgentName']}</td>
													<td>{$title}</td>
													<td>{$details}</td>
													<td>{$row['details']}</td>
													<td>{$row['TotalPax']}</td>
													<td>{$row['requestCost']}</td>
													<td>{$row['RequestDate']}</td>
													<td>
														<span class='badge rounded-pill {$badgeClass} p-2'>{$status}</span>
													</td>
												</tr>";
								}
							} else {
								echo "<tr><td colspan='9' style='text-align: center;'>No Requests Found</td></tr>";
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

    // Show the hidden div
    toggleButton.addEventListener('click', function () {
        hiddenDiv.style.display = 'block';
    });

    // Hide the hidden div
    closeButton.addEventListener('click', function () {
        hiddenDiv.style.display = 'none';
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
