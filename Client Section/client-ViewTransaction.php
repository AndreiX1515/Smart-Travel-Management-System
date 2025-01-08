
<?php
  // include 'session_validate.php'; // This will check if the session is valid
  require '../conn.php';
  session_start();

  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);
  
  // Fetch session variables directlys
  $email = $_SESSION['email'] ?? ''; // Use null coalescing operator to avoid undefined index
  // $firstName = $_SESSION['first_name'] ?? '';
  // $lastName = $_SESSION['last_name'] ?? '';
  // $middleName = $_SESSION['middle_name'] ?? '';
  $accId = $_SESSION['accountId'] ?? '';
  
  // $fullName = htmlspecialchars($lastName . ', ' . $firstName . ($middleName ? ' ' . substr($middleName, 0, 1) . '.' : ''));
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include '../Client Section/Includes/head.php'; ?>

  <title>Template Page</title>

  <link rel="stylesheet" href="../Client Section/assets/css/client-portal.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="../Client Section/assets/css/client-transactionStatus.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="../Client Section/assets/css/client-navbar.css?v=<?php echo time(); ?>"> 
  
</head>

<body>


<?php include '../Client Section/Includes/client-navbar.php'; ?>

<div class="body-container">
  <div class="sub-container">
    <?php include '../Client Section/Includes/client-sidebar.php'; ?>
  </div>

  <div class="main-container">
				<div class="content-header">
						<div class="back-button-wrapper">
									<a href="client-transactionHistoryyy.php" class="back-button-link">
												<i class="fa-solid fa-arrow-left me-2"></i> Back to Transaction Page
									</a>
						</div>

						<div class="d-flex flex-row justify-content-between align-items-center">
									<h4>Transaction Number: BU1-000026</h4>
									<!-- <span class="status-pill status-pending"> <span> </span>Pending</span> -->
						</div>
				</div>



				<div class="top-container">
						<div class="left-top-container">
								<div class="content-one">
										<div class="row">
												<div class="col-md-3">
																				<h4>Transaction Date:</h4>
																				<p>01/01/2025</p>
												</div>

												<div class="col-md-3">
															<h4>Status:</h4>
															<p class="pill-status">Pending</p>
											</div>

												<div class="col-md-3">
																				<h4>Flight Date:</h4>
																				<p>01/01/2025</p>
												</div>

												<div class="col-md-3">
																				<h4>Price:</h4>
																				<p>₱ 45,163.77</p>
												</div>
										</div>
								</div>

								<div class="content-two">
										<div class="title-header">
												<h5>Transaction Details</h5>
										</div>

										<hr class="hr-line">
												
										<div class="content-body">
														<div class="row">
																	<div class="col-md-4 d-flex flex-row justify-content-between">
																				<h4 class="fw-bold">Name:</h4>
																				<p class="text-dark">Autumn Tour Package</p>
																	</div>
														</div>

														<div class="row">
																<div class="col-md-4 d-flex flex-row justify-content-between">
																		<h4 class="fw-bold">Contact Number</h4>
																		<p class="text-dark">2</p>
																</div>
														</div>

														<div class="row">
																<div class="col-md-4 d-flex flex-row justify-content-between">
																			<h4 class="fw-bold">Email</h4>
																			<p class="text-dark">01-05-2024</p>
																</div>
														</div>
										</div>

										<div class="content-two">
											<div class="title-header">
													<h5>Contact Person Details</h5>
											</div>

												<hr class="hr-line">
													
												<div class="content-body">
																<div class="row">
																			<div class="col-md-4 d-flex flex-row justify-content-between">
																						<h4 class="fw-bold">Package</h4>
																						<p class="text-dark">Autumn Tour Package</p>
																			</div>
																</div>

																<div class="row">
																		<div class="col-md-4 d-flex flex-row justify-content-between">
																					<h4 class="fw-bold">Total Pax</h4>
																					<p class="text-dark">2</p>
																		</div>
																</div>

																<div class="row">
																		<div class="col-md-4 d-flex flex-row justify-content-between">
																					<h4 class="fw-bold">Transaction Date</h4>
																					<p class="text-dark">01-05-2024</p>
																		</div>
																</div>

												</div>
										</div>


								</div>
						</div>

						<div class="right-top-container">
								<div class="right-sub-container">
										<div class="title-section">
												<h5>Payment History</h5>
										</div>
		
									<div class="content-body">
										<div class="table-wrapper-one">
												<table class="guest-table">
														<thead>
																<tr>
																				<th>ID</th>
																				<th>Information</th>
																				<th>Status</th>
																			
														</thead>
														<tbody>
															<tr>
																			<td>2</td>
																			<td>Jane Smith, Hotel Booking</td>
																			<td><span class="status-badge status-pending">Pending</span></td>
															</tr>
															<tr>
																			<td>3</td>
																			<td>Michael Brown, Car Rental</td>
																			<td><span class="status-badge status-pending">Pending</span></td>
															</tr>

															<tr> 
																			<td>2</td>
																			<td>Jane Smith, Hotel Booking</td>
																			<td><span class="status-badge status-pending">Pending</span></td>
															</tr>
															<tr>
																			<td>3</td>
																			<td>Michael Brown, Car Rental</td>
																			<td><span class="status-badge status-pending">Pending</span></td>
															</tr>

															<tr>
																			<td>2</td>
																			<td>Jane Smith, Hotel Booking</td>
																			<td><span class="status-badge status-pending">Pending</span></td>
															</tr>
															<tr>
																			<td>3</td>
																			<td>Michael Brown, Car Rental</td>
																			<td><span class="status-badge status-pending">Pending</span></td>
															</tr>

															
															
														
												
														
														</tbody>
												</table>
										</div>
								</div>

								</div>
								
								<div class="right-sub-container">
								<div class="title-section">
												<h5>Request History</h5>
										</div>
		
									<div class="content-body">
										<div class="table-wrapper-one">
												<table class="guest-table">
														<thead>
																<tr>
																				<th>ID</th>
																				<th>Information</th>
																				<th>Status</th>
																			
														</thead>
														<tbody>
															<tr>
																			<td>2</td>
																			<td>Jane Smith, Hotel Booking</td>
																			<td><span class="status-badge status-pending">Pending</span></td>
															</tr>
															<tr>
																			<td>3</td>
																			<td>Michael Brown, Car Rental</td>
																			<td><span class="status-badge status-pending">Pending</span></td>
															</tr>

															<tr> 
																			<td>2</td>
																			<td>Jane Smith, Hotel Booking</td>
																			<td><span class="status-badge status-pending">Pending</span></td>
															</tr>
															<tr>
																			<td>3</td>
																			<td>Michael Brown, Car Rental</td>
																			<td><span class="status-badge status-pending">Pending</span></td>
															</tr>

															<tr>
																			<td>2</td>
																			<td>Jane Smith, Hotel Booking</td>
																			<td><span class="status-badge status-pending">Pending</span></td>
															</tr>
															<tr>
																			<td>3</td>
																			<td>Michael Brown, Car Rental</td>
																			<td><span class="status-badge status-pending">Pending</span></td>
															</tr>

															
															
														
												
														
														</tbody>
												</table>
										</div>
								</div>
								
								</div>
						</div>
				</div>

				<div class="bottom-container">
						<div class="title-section">
										<h5>Guest Information</h5>
						</div>

						<hr class="hr-line">
									
						<div class="content-body">
							<div class="table-wrapper-one">
									<table class="guest-table">
											<thead>
													<tr>
																	<th>Guest ID</th>
																	<th>Name</th>
																	<th>Contact</th>
																	<th>Relationship</th>
																	<th>Visa Status</th>
													</tr>
											</thead>
											<tbody>
												<tr>
																<td>1</td>
																<td>John Doe</td>
																<td>(123) 456-7890</td>
																<td>Brother</td>
																<td><span class="status-badge status-pending">Pending</span></td>
												</tr>
												<tr>
																<td>2</td>
																<td>Jane Smith</td>
																<td>(987) 654-3210</td>
																<td>Sister</td>
																<td><span class="status-badge status-completed">Confirmed</span></td>
												</tr>
												<tr>
																<td>2</td>
																<td>Jane Smith</td>
																<td>(987) 654-3210</td>
																<td>Sister</td>
																<td><span class="status-badge status-completed">Confirmed</span></td>
												</tr>
												<tr>
																<td>2</td>
																<td>Jane Smith</td>
																<td>(987) 654-3210</td>
																<td>Sister</td>
																<td><span class="status-badge status-completed">Confirmed</span></td>
												</tr>
												<tr>
																<td>2</td>
																<td>Jane Smith</td>
																<td>(987) 654-3210</td>
																<td>Sister</td>
																<td><span class="status-badge status-completed">Confirmed</span></td>
												</tr>
											
											</tbody>
									</table>
							</div>
					</div>
		</div>

   </div> 
</div>








<?php include '../Client Section/Includes/scripts.php'; ?>
<!-- <script src="heartbeat.js"></script>  -->

<!-- Row Click Selection JS -->
<script>
  document.addEventListener("DOMContentLoaded", function() 
  {
    document.querySelectorAll("tr[data-url]").forEach(function(row) 
    {
      row.addEventListener("click", function() 
      {
        const transactionNumber = row.getAttribute("data-url").split('=')[1]; // Extract transaction number from the URL

        console.log("Transaction Number: ", transactionNumber);

        // Use AJAX to send the transaction number to the server
        $.ajax(
        {
          url: '../Agent Section/functions/fetchTransactNo.php', // The PHP file to handle the session setting
          type: 'POST',
          data: { transaction_number: transactionNumber },
          success: function(response)
          {
            console.log("Response: ", response); // Debugging line

            // Redirect to the next page after successfully setting the session
            window.location.href = row.getAttribute("data-url"); // Use the original URL stored in data-url attribute
          },
          error: function(xhr, status, error) 
          {
            console.error("AJAX Error: " + status + " " + error); // Enhanced error logging
          }
        });
      });
    });
  });
</script>

 </body>
</html>