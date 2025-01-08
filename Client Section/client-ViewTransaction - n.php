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
  <title>Client Transaction History</title>
  <?php include '../Client Section/Includes/head.php' ?>
  <link rel="stylesheet" href="../Client Section/assets/css/client-transactionStatus.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="../Client Section/assets/css/client-navbar.css?v=<?php echo time(); ?>">
</head>

<body>
  <?php include '../Client Section/Includes/client-navbar.php'; ?>

  <div class="main-container">
				<div class="left-side-container">
						<div class="navTabs-wrapper">
									<ul class="nav nav-pills" id="pills-tab" role="tablist">
											<li class="nav-item" role="presentation">
													<button class="nav-link active" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Flight Seat Tracker</button>
											</li>

											<li class="nav-item" role="presentation">
													<button class="nav-link " id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Payment and Requests Table</button>
											</li>
											
											<!-- <li class="nav-item" role="presentation">
													<button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#pills-contact" type="button" role="tab" aria-controls="pills-contact" aria-selected="false">Contact</button>
											</li>
											<li class="nav-item" role="presentation">
													<button class="nav-link" id="pills-disabled-tab" data-bs-toggle="pill" data-bs-target="#pills-disabled" type="button" role="tab" aria-controls="pills-disabled" aria-selected="false" disabled>Disabled</button>
											</li> -->
									</ul>
							</div>
							
					<div class="content-container">
							<div class="content-header">
											<div class="back-button-wrapper">
															<a href="client-transactionHistoryyy.php" class="back-button-link">
																			<i class="fa-solid fa-arrow-left me-2"></i> Back to Transaction Page
															</a>
											</div>

											<div class="d-flex flex-row justify-content-between align-items-center">
															<h4>Transaction Number: BU1-000026</h4>
															<span class="status-pill status-pending"> <span> </span>Pending</span>
											</div>
							</div>

							<!-- <hr class="hr-line"> -->

							<div class="content-one">
									<div class="row">
											<div class="col-md-4">
															<h4>Transaction Date:</h4>
															<p>01/01/2025</p>
											</div>
											<div class="col-md-4">
															<h4>Flight Date:</h4>
															<p>01/01/2025</p>
											</div>

											<div class="col-md-4">
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

											<div class="row">
															<div class="col-md-4 d-flex flex-row justify-content-between">
																			<h4 class="fw-bold">Contact Name</h4>
																			<p class="text-dark">01-05-2024</p>
															</div>
											</div>

											<div class="row">
															<div class="col-md-4 d-flex flex-row justify-content-between">
																			<h4 class="fw-bold">Contact No</h4>
																			<p class="text-dark">+6398794568</p>
															</div>
											</div>

											<div class="row">
															<div class="col-md-4 d-flex flex-row justify-content-between">
																			<h4 class="fw-bold">Email</h4>
																			<p class="text-dark">asdasdasdasd@gmail.com</p>
															</div>
											</div>

											</div>
							</div>

							<div class="content-three">
									<div class="title-header">
													<h5>Guest Information</h5>
									</div>

									<hr class="hr-line">
									
									<div class="content-body">
										<div class="table-wrapper-one">
																	<table class="guest-table">
																					<thead>
																									<tr>
																													<th>ID</th>
																													<th>Name</th>
																													<th>Contact</th>
																													<th>Relationship</th>
																													<th>Status</th>
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

			<div class="right-side-container">
					<div class="right-side-sub-container">
								<div class="container-wrapper">
										<div class="header-section">
											<div class="header-content">
													<h4>Visa Requirement</h4>
											</div>
										</div>

										<div class="body-section">
												<div class="table-wrapper">
																<table class="guest-table">
																				<thead>
																								<tr>
																												<th>ID</th>
																												<th>Name</th>
																												<th>Contact</th>
																												<th>Relationship</th>
																												<th>Status</th>
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
																				</tbody>
																</table>
												</div>
								  </div>

								</div>

					</div>

					<div class="right-side-sub-container">
					<div class="container-wrapper">
										<div class="header-section">
											<div class="header-content">
													<h4>Request History</h4>
											</div>
										</div>

										<div class="body-section">
												<div class="table-wrapper">
																<table class="guest-table">
																				<thead>
																								<tr>
																												<th>ID</th>
																												<th>Name</th>
																												<th>Contact</th>
																												<th>Relationship</th>
																												<th>Status</th>
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
																				</tbody>
																</table>
												</div>
								  </div>

								</div>
					</div>

					<div class="right-side-sub-container">
					<div class="container-wrapper">
										<div class="header-section">
											<div class="header-content">
													<h4>Payment History</h4>
											</div>
										</div>

										<div class="body-section">
												<div class="table-wrapper">
																<table class="guest-table">
																				<thead>
																								<tr>
																												<th>ID</th>
																												<th>Name</th>
																												<th>Contact</th>
																												<th>Relationship</th>
																												<th>Status</th>
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

																				</tbody>
																</table>
												</div>
								  </div>

								</div>
					</div>
			</div>
</div>
  
  <?php include 'includes/scripts.php'; ?>

  <!-- <script src="heartbeat.js"></script> -->

  
</body>

</html>