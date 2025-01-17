
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
			<?php 
				if(isset($_SESSION['status'])):
				?>
					<div class="alert alert-success alert-dismissible fade show" role="alert">
						<strong></strong> <?= $_SESSION['status']; ?>
						<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
					</div>
				<?php 
				unset($_SESSION['status']);
				endif;
			?>
			<div class="table-subheader d-flex align-items-center justify-content-between p-3 border-bottom bg-light">
				<div class="search-wrapper position-relative">
					<input type="text" placeholder="Search..." class="form-control search-input" oninput="toggleClearButton(this)"/>
					<button type="button" class="clear-button" onclick="clearInput(this)" style="display: none;">
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
              <!-- <th>Agent Name</th> -->
              <th>Request Title</th>
              <th>Request Details</th>
              <th>Specific Details</th>
              <th>Total Pax</th>
              <th>Total Amount</th>
              <th>Request Date</th>
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
											WHERE
												r.requestStatus = 'Confirmed'
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
									$requestId = $row['requestId'];

									// Output table row with data-transactno attribute
									echo "<tr data-transactno='{$row['TransactNo']}' data-requestid='{$requestId}' class='transaction-row'>
													<td>{$row['TransactNo']}</td>
													<td>{$title}</td>
													<td>{$details}</td>
													<td>{$row['details']}</td>
													<td>{$row['TotalPax']}</td>
													<td>{$row['requestCost']}</td>
													<td>{$row['RequestDate']}</td>
												</tr>";
								}
							} 
							else 
							{
								echo "<tr><td colspan='9' style='text-align: center;'>No Requests Found</td></tr>";
							}
						?>
					</tbody>
				</table>
			</div>
		</div>  

  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="transactionModal" tabindex="-1" aria-labelledby="transactionModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="transactionModalLabel">Transaction Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
			<form action="../Employee Section/functions/emp-requestUpdateAmount-code.php" method="POST">
				<div class="modal-body">
					<div class="mb-3">
						<label for="transactNo" class="form-label fw-bold">Transaction Number:</label>
						<span id="transactNo" class="text-primary"></span>
					</div>

					<input type="hidden" id="modalRequestId" name="requestId">

					<div class="mb-3">
						<label for="requestAmount" class="form-label">Enter Total Amount:</label>
						<input type="number" id="requestAmount" name="requestAmount" class="form-control" placeholder="Enter amount in PHP" 
							step="0.01" min="0" required>
					</div>
				</div>

				<div class="modal-footer">
					<button type="submit" name="updatePrice" class="btn btn-primary">Update Price</button>
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
				</div>
			</form>
    </div>
  </div>
</div>

<script>
	document.addEventListener('DOMContentLoaded', function () 
	{
		// Add click event listener to each table row
		document.querySelectorAll('.transaction-row').forEach(row => 
		{
			row.addEventListener('click', function () 
			{
				const transactNo = this.getAttribute('data-transactno'); // Fetch the TransactNo
				const requestId = this.getAttribute('data-requestid');
				document.getElementById('transactNo').innerText = transactNo; // Update modal content
				document.getElementById('modalRequestId').value = requestId;
				const modal = new bootstrap.Modal(document.getElementById('transactionModal')); // Initialize modal
				modal.show(); // Show modal
			});
		});
	});
</script>

<script>
	// DOM Elements
	const toggleButton = document.getElementById('toggleButton');
	const closeButton = document.getElementById('closeButton');
	const hiddenDiv = document.getElementById('hiddenDiv');

	// Toggle the hidden div and button text with an icon
	toggleButton.addEventListener('click', function () 
	{
		if (hiddenDiv.style.display === 'none' || hiddenDiv.style.display === '') 
		{
			hiddenDiv.style.display = 'block';
			toggleButton.innerHTML = '<i class="fas fa-times"></i> Close'; // Add "Close" icon and text
		} 
		else 
		{
			hiddenDiv.style.display = 'none';
			toggleButton.innerHTML = ' <i class="fas fa-filter"></i> Filters'; // Add "Filters" icon and text
		}
	});

	// Close the hidden div and reset the button text with an icon
	closeButton.addEventListener('click', function () 
	{
		hiddenDiv.style.display = 'none';
		toggleButton.innerHTML = '<i class="fas fa-filter"></i> Filters'; // Reset to "Filters" icon and text
	});
</script>

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

<?php include '../Employee Section/includes/emp-scripts.php' ?>

</body>
</html>
