<?php
require "../conn.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Initialize variables
$accountId = $_SESSION['employee_accountId'] ?? '';
$empId = $_SESSION['employee_employeeId'] ?? '';
$firstName =  $_SESSION['employee_fName'] ?? '';
$lastName =  $_SESSION['employee_lName'] ?? '';
$middleName = $_SESSION['employee_mName'] ?? '';  // Middle name is optional
$email = $_SESSION['email'] ?? '';
$emailAddress = $_SESSION['employee_emailAddress'] ?? '';
$password = $_SESSION['password'] ?? '';
$userType = $_SESSION['employee_userType'] ?? '';


// Format the full name: Get the first letter of the middle name and place it at the end
$middleNameInitial = $middleName ? substr($middleName, 0, 1) . '.' : '';
$fullName = htmlspecialchars($firstName . ' ' . $middleNameInitial . ' ' . $lastName);

// Position or role (assuming userType and accountType are available)
$position = htmlspecialchars(strtoupper($empId));
?>


<div class="sidebar">
	<ul class="nav flex-column nav-logo-wrapper">
		<li class="nav-item nav-logo-item-wrapper">
			<a class="nav-link logo-link" href="#">
				<div class="logo-content">
					<div class="logo-backdrop">
						<img src="../Assets/Logos/logo-tab.png" alt="Logo" class="sidebar-logo">
					</div>
					<span class="fw-bold">SMART TRAVEL</span>
				</div>
			</a>
		</li>
	</ul>

	<ul class="nav flex-column">
		<li class="nav-item">
			<a class="nav-link page-button" href="../Employee Section/emp-dashboard.php" data-page-name="Dashboard">
				<div class="icon-wrapper">
					<div class="icon"><i class="fa-solid fa-house"></i></div>
				</div>
				<div class="label-wrapper">
					<span class="label">Dashboard</span>
				</div>
			</a>
		</li>

		<li class="nav-item transaction">
			<a class="nav-link page-button" href="../Employee Section/emp-transaction.php" data-page-name="Transactions">
				<div class="icon-wrapper">
					<div class="icon"><i class="fa-solid fa-arrow-right-arrow-left"></i></div>
				</div>
				<div class="label-wrapper">
					<span class="label">Transactions</span>
				</div>
			</a>
		</li>

		<li class="nav-item transaction">
			<a class="nav-link page-button" href="../Employee Section/emp-requestHistory.php" data-page-name="Request History">
				<div class="icon-wrapper">
					<div class="icon"><i class="fa-solid fa-arrow-right-arrow-left"></i></div>
				</div>
				<div class="label-wrapper">
					<span class="label">Request History</span>
				</div>
			</a>
		</li>

		<li class="nav-item transaction">
			<a class="nav-link page-button" href="../Employee Section/emp-paymentHistory.php" data-page-name="Request History">
				<div class="icon-wrapper">
					<div class="icon"><i class="fa-solid fa-arrow-right-arrow-left"></i></div>
				</div>
				<div class="label-wrapper">
					<span class="label">Payment History</span>
				</div>
			</a>
		</li>

		<li class="nav-item transaction">
			<a class="nav-link page-button" href="../Employee Section/emp-guestList.php" data-page-name="Guest List">
				<div class="icon-wrapper">
					<div class="icon"><i class="fas fa-user"></i></div>
				</div>
				<div class="label-wrapper">
					<span class="label">Guest List</span>
				</div>
			</a>
		</li>

		<li class="nav-item transaction">
			<a class="nav-link page-button" href="../Employee Section/emp-visaRequirementsTable.php" data-page-name="Visa Requirements">
				<div class="icon-wrapper">
					<div class="icon"><i class="fas fa-file-lines"></i></div>
				</div>
				<div class="label-wrapper">
					<span class="label" style="font-size: 14px;">Visa Requirements</span>
				</div>
			</a>
		</li>

		<li class="nav-item dropdown">
			<a class="nav-link page-button" href="#" data-bs-toggle="collapse" data-bs-target="#manageBookingMenu" aria-expanded="false" aria-controls="manageBookingMenu" data-page-name="Operationals">
				<div class="icon-wrapper">
					<div class="icon"><i class="fa-solid fa-thumbs-up"></i></div>
				</div>
				<div class="label-wrapper">
					<span class="label">For Approvals</span>
				</div>
			</a>
			<div class="collapse" id="manageBookingMenu">
				<ul class="nav flex-column managebooking-menu-wrapper">
					<li class="nav-item transaction">
						<a class="nav-link page-button" href="../Employee Section/emp-tablePending.php" data-page-name="For Approvals - Booking">No Downpayment</a>
					</li>
					<li class="nav-item">
						<a class="nav-link page-button" href="../Employee Section/emp-tableRequest.php" data-page-name="For Approvals - Request">Request</a>
					</li>
					<li class="nav-item">
						<a class="nav-link page-button" href="../Employee Section/emp-tablePayment.php" data-page-name="For Approvals - Payment">Payment</a>
					</li>
				</ul>
			</div>
		</li>

		<li class="nav-item dropdown">
			<a class="nav-link page-button" href="#" data-bs-toggle="collapse" data-bs-target="#reportMenu" aria-expanded="false" aria-controls="reportMenu" data-page-name="Reports">
				<div class="icon-wrapper">
					<div class="icon"><i class="fa-regular fa-file"></i></div>
				</div>
				<div class="label-wrapper">
					<span class="label">Reports</span>
				</div>
			</a>

			<div class="collapse" id="reportMenu">
				<ul class="nav flex-column report-menu-wrapper">
					<li class="nav-item">
						<a class="nav-link page-button open-new-tab" href="../Employee Section/emp-itinerarytable.php" data-page-name="Itinerary" data-url="">Itinerary</a>
					</li>
					<li class="nav-item">
						<a class="nav-link page-button open-new-tab" href="../Employee Section/emp-voucherTable.php" data-page-name="Voucher" data-url="">Voucher</a>
					</li>
					<li class="nav-item">
						<a class="nav-link page-button" href="#" data-page-name="Ticket">Ticket</a>
					</li>
					<li class="nav-item">
						<a class="nav-link page-button" href="../Employee Section/emp-soa.php" data-page-name="SOA">SOA</a>
					</li>
				</ul>
			</div>
		</li>

	</ul>

	<div class="logout">
		<div class="profile-section">
			<div class="profile-left">
				<div class="name" style="font-size: <?php echo (strlen($fullName) >= 13) ? '15px' : '17px'; ?>;">
					<?php echo $fullName; ?>
				</div>
				<div class="empid fw-bold text-light" style="font-size: 14px;">EMP ID: <span class="fw-normal text-light"><?php echo $empId; ?></span></div>
			</div>
			<div class="profile-icon profile-icon-visible">
				<i class="fa-solid fa-user-circle"></i>
			</div>
		</div>

		<div class="nav-item" id="raiseTicketWrapper">
			<a class="nav-link" id="raiseTicket" href="#">
				<div class="icon-wrapper">
					<div class="icon" id="raiseTicketIcon">
						<i class="fas fa-ticket-alt"></i>
					</div>
				</div>
				<div class="label-wrapper">
					<span class="label">Raise Ticket</span>
				</div>
			</a>
		</div>

		<div class="nav-item">
			<a class="nav-link" id="changePasswordLink" href="#">
				<div class="icon-wrapper">
					<div class="icon" id="changePasswordIcon">
						<i class="fas fa-key"></i>
					</div>
				</div>
				<div class="label-wrapper">
					<span class="label">Change Password</span>
				</div>
			</a>
		</div>

		<div class="nav-item" id="logoutWrapper">
			<a class="nav-link" id="logout-link" data-bs-toggle="modal" data-bs-target="#logoutModal">
				<div class="icon-wrapper">
					<div class="icon" id="logoutIcon">
						<i class="fa-solid fa-right-from-bracket"></i>
					</div>
				</div>
				<div class="label-wrapper">
					<span class="label">Logout</span>
				</div>
			</a>
		</div>
	</div>
	
</div>

<!-- Logout Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="logoutModalLabel">Logout Confirmation</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				Are you sure you want to logout?
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-danger" id="confirmLogout">Yes, Logout</button>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function() {
		$('#confirmLogout').click(function() {
			$.ajax({
				url: '../Employee Section/functions/emp-logout.php',
				type: 'GET',
				dataType: 'json',
				success: function(response) {
					if (response.success) {
						window.location.href = '../Agent Section/agentLogin.php';
					} else {
						alert(response.message);
					}
				},
				error: function(jqXHR, textStatus, errorThrown) {
					console.error('AJAX Error:', textStatus, errorThrown);
					alert('An unexpected error occurred. Please try again.');
				}
			});
		});
	});
</script>


<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<div class="modal-title-wrapper">
					<h5 class="modal-title" id="changePasswordLabel">Change Password</h5>
					<small class="modal-subtext">Ensure your new password is secure and different from previous ones.</small>
				</div>
				<div class="modal-close-wrapper">
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
			</div>

			<form id="changePasswordForm">
				<div class="modal-body">
					<div class="mb-3">
						<label for="currentPassword" class="form-label">Current Password</label>
						<input type="password" class="form-control" id="currentPassword" name="currentPassword" placeholder="Enter current password">
						<small id="currentPasswordError" class="error-label text-danger"></small>
					</div>

					<div class="mb-3">
						<label for="newPassword" class="form-label">New Password</label>
						<input type="password" class="form-control" id="newPassword" name="newPassword" placeholder="Enter new password" required>
						<small id="newPasswordError" class="error-label text-danger"></small>
					</div>

					<div class="mb-3">
						<label for="confirmNewPassword" class="form-label">Confirm New Password</label>
						<input type="password" class="form-control" id="confirmNewPassword" name="confirmNewPassword" placeholder="Re-enter new password" required>
						<small id="confirmPasswordError" class="error-label text-danger"></small>
					</div>

					<div id="messageAlert"> </div>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary">Change Password</button>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- OTP Verification Modal -->
<div class="modal fade" id="otpVerificationModal" tabindex="-1" aria-labelledby="otpVerificationModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered ">
		<div class="modal-content otp-modal-content">

			<div class="modal-body">
				<div class="header-body-wrapper">
					<div class="otp-header">
						<div class="otp-icon-wrapper">
							<div class="otp-icon">
								<i class="fa-solid fa-lock"></i>
							</div>
						</div>

						<div class="header-body-content">
							<h5 class="modal-title">Verify OTP</h5>
							<p class="otp-subtext">To proceed with resetting your password, we’ve sent a verification code to your email address. <br> <span class="otp-email-mask">is****a8@gmail.com</span></p>
						</div>
					</div>
				</div>

				<form id="otpVerificationForm">
					<div id="otpFieldContainer" class="otp-field-container">
						<div class="otp-modal-container">
							<input type="text" class="otp-modal-input" maxlength="1" id="otp1">
							<input type="text" class="otp-modal-input" maxlength="1" id="otp2">
							<input type="text" class="otp-modal-input" maxlength="1" id="otp3">
							<input type="text" class="otp-modal-input" maxlength="1" id="otp4">
							<input type="text" class="otp-modal-input" maxlength="1" id="otp5">
							<input type="text" class="otp-modal-input" maxlength="1" id="otp6">
						</div>

						<small id="otpError" class="error-label text-danger"></small>
					</div>

					<div class="verify-button-wrapper">
						<button type="submit" class="btn btn-primary otp-verify-btn">Verify</button>
					</div>

					<div class="otpResent-button-wrapper">
						<p class="otp-resend-text">Didn’t receive code? </p> <a href="#" id="sendOtpBtn" class="otp-resend-link">Resend</a>
					</div>

					<div id="otpAlert"></div>
				</form>
			</div>
		</div>
	</div>
</div>

<!-- Change Password Modal Open Script -->
<script>
	document.getElementById('changePasswordLink').addEventListener('click', function(e) {
		e.preventDefault(); // Prevent default link behavior
		var myModal = new bootstrap.Modal(document.getElementById('changePasswordModal'));
		myModal.show();
	});
</script>

<!-- OTP Input Focus Script -->
<script>
	document.addEventListener("DOMContentLoaded", function() {
		const otpInputs = document.querySelectorAll("#otpVerificationModal .otp-modal-input");

		otpInputs.forEach((input, index) => {
			input.addEventListener("input", (e) => {
				if (e.target.value && index < otpInputs.length - 1) {
					otpInputs[index + 1].focus();
				}
			});

			input.addEventListener("keydown", (e) => {
				if (e.key === "Backspace" && index > 0 && !e.target.value) {
					otpInputs[index - 1].focus();
				}
			});
		});
	});
</script>

<!-- jQuery Script for Change Password Modal -->
<script>
	$(document).ready(function() {

		let currentPassword, newPassword, confirmNewPassword;

		// Function for OTP Modal Alert
		function showOtpAlert(message, status) {

			// Set the color and background based on the status
			let backgroundColor, textColor, borderColor;

			// Determine the color scheme based on the provided status
			if (status === 'success') {
				backgroundColor = '#d4edda'; // Green background
				textColor = '#155724'; // Dark green text
				borderColor = '#c3e6cb'; // Green border
			} else if (status === 'error') {
				backgroundColor = '#f8d7da'; // Red background
				textColor = '#721c24'; // Dark red text
				borderColor = '#f5c6cb'; // Red border
			} else {
				backgroundColor = '#fff3cd'; // Yellow background (default for warnings)
				textColor = '#856404'; // Dark yellow text
				borderColor = '#ffeeba'; // Yellow border
			}

			// Apply the styles and show the alert
			$('#otpAlert').text(message).css({
				'background-color': backgroundColor,
				'color': textColor,
				'border': `1px solid ${borderColor}`
			}).show();

			// Hide the alert after 3.5 seconds (3500 milliseconds)
			setTimeout(function() {
				$('#otpAlert').fadeOut();
			}, 3500);
		}

		// Function for CP Alert
		function showCPAlert(message, status) {

			// Set the color and background based on the status
			let backgroundColor, textColor, borderColor;

			// Determine the color scheme based on the provided status
			if (status === 'success') {
				backgroundColor = '#d4edda'; // Green background
				textColor = '#155724'; // Dark green text
				borderColor = '#c3e6cb'; // Green border
			} else if (status === 'error') {
				backgroundColor = '#f8d7da'; // Red background
				textColor = '#721c24'; // Dark red text
				borderColor = '#f5c6cb'; // Red border
			} else {
				backgroundColor = '#fff3cd'; // Yellow background (default for warnings)
				textColor = '#856404'; // Dark yellow text
				borderColor = '#ffeeba'; // Yellow border
			}

			// Apply the styles and show the alert
			$('#messageAlert').text(message).css({
				'background-color': backgroundColor,
				'color': textColor,
				'border': `1px solid ${borderColor}`
			}).show();

			// Hide the alert after 3.5 seconds (3500 milliseconds)
			setTimeout(function() {
				$('#otpAlert').fadeOut();
			}, 3500);
		}

		// Function to send OTP
		function sendOtp(currentPassword, emailAddress) {

			if (!emailAddress || emailAddress === 'null' || emailAddress.trim() === '') {
				showOtpAlert('Email address is missing. Please update your profile to receive OTP.', 'error');
				console.warn('Attempted to send OTP without a valid email address.');
				return;
			}

			sessionStorage.setItem('emailAddress', emailAddress);

			$.ajax({
				url: '../Employee Section/functions/General/emp-sendOtpCPassword.php',
				type: 'POST',
				data: {
					currentPassword: currentPassword,
					emailAddress: emailAddress
				},

				dataType: 'json',
				success: function(response) {
					if (response.status === 'success') {
						console.log('OTP Sent:', response.otp);

						// Mask the email for display
						function maskEmail(email) {
							const parts = email.split('@');
							const username = parts[0];
							const domain = parts[1];
							const maskedUsername = username.charAt(0) + '******' + username.charAt(username.length - 1);
							return maskedUsername + '@' + domain;
						}

						showOtpAlert('OTP has been sent to your email address.', 'success');

						setTimeout(function() {
							$('#changePasswordModal').modal('hide');
							$('#otpVerificationModal').modal('show');

							// Append accountId to form if provided
							if (response.accountId) {
								$('#otpVerificationForm').append('<input type="hidden" name="accountId" value="' + response.accountId + '">');
							}

							// Mask and display the email address
							const maskedEmail = maskEmail(emailAddress);
							$('#otpVerificationModal .otp-email-mask').text(maskedEmail);

							console.log('Masked Email:', maskedEmail);
						}, 500);

					} else {
						// Handle specific error message from backend
						console.log('Error Sending OTP:', response.message);
						showOtpAlert(response.message || 'Failed to send OTP. Please try again.', 'error');
					}
				},

				error: function(xhr, status, error) {
					console.log('AJAX Error:', error);
					showOtpAlert('An error occurred while sending OTP. Please try again later.', 'error');
				}
			});
		}

		// Handle form submission for change password
		$('#changePasswordForm').on('submit', function(e) {
			e.preventDefault(); // Prevent default form submission

			// Clear previous error messages and hide error labels
			document.getElementById('currentPasswordError').textContent = '';
			document.getElementById('newPasswordError').textContent = '';
			document.getElementById('confirmPasswordError').textContent = '';
			document.getElementById('messageAlert').style.display = 'none';

			currentPassword = document.getElementById('currentPassword').value;
			newPassword = document.getElementById('newPassword').value;
			confirmNewPassword = document.getElementById('confirmNewPassword').value;

			// You can now use these variables elsewhere in your code
			console.log(currentPassword, newPassword, confirmNewPassword);

			// Validate New Password
			if (newPassword.length < 8) {
				document.getElementById('newPasswordError').textContent = 'Password must be at least 8 characters long.';
				document.getElementById('newPasswordError').style.display = 'block';
				return;
			}

			// Validate New Password and Confirm Password
			if (newPassword !== confirmNewPassword) {
				document.getElementById('confirmPasswordError').textContent = 'Passwords do not match.';
				document.getElementById('confirmPasswordError').style.display = 'block';
				return;
			}

			$.ajax({
				url: '../Employee Section/functions/General/emp-changePassword.php',
				type: 'POST',
				data: {
					currentPassword: currentPassword,
					newPassword: newPassword
				},

				success: function(response) {
					response = JSON.parse(response);

					if (response.status === 'error') {
						document.getElementById('currentPasswordError').textContent = response.message;
						document.getElementById('currentPasswordError').style.display = 'block';
					} 
					
					else if (response.status === 'success') {
						const accountId = response.accountId;
						const emailAddress = response.emailAddress;

						// Store in sessionStorage
						sessionStorage.setItem('emailAddress', emailAddress);


						console.log('Email Address:', emailAddress); // Debugging log
						showCPAlert(response.message, 'success');

						if (!emailAddress || emailAddress === 'null' || emailAddress.trim() === '') {
							showCPAlert('Unable to send OTP. Email address is missing.', 'error');
							return;
						}

						// Proceed to send OTP
						setTimeout(function() {
							sendOtp(currentPassword, emailAddress); // Reusable OTP function
						}, 500);
					}

				},

				error: function(xhr, status, error) {
					console.log('AJAX Error:', error);
					showCPAlert('An error occurred while validating the password.', 'error');
				}
			});
		});

		// OTP verification form submission
		$('#otpVerificationForm').on('submit', function(e) {
			e.preventDefault(); // Prevent normal form submission

			let otp = '';
			let newPassword = document.getElementById('newPassword').value;
			let accountIdVerify = <?= $accountId; ?>;


			$('.otp-modal-input').each(function() {
				otp += $(this).val();
			});

			console.log('OTP entered:', otp); // Debugging log

			if (otp.length !== 6) {
				$('#otpError').text('Please enter all 6 digits of the OTP.');
				console.warn('Invalid OTP length. OTP must be 6 digits.');
				return;
			}

			console.log('Sending OTP verification request...'); // Debugging log

			$.ajax({
				url: '../Employee Section/functions/General/emp-newPasswordChange.php',
				type: 'POST',
				data: {
					otp: otp,
					newPassword: newPassword,
					accountId: accountIdVerify
				},
				dataType: 'json',
				success: function(response) {
					console.log('OTP verification response:', response); // Debugging log
					if (response.status === 'success') {
						console.log('OTP Verified Successfully'); // Debugging log

						let newPassword = $('#newPassword').val();
						let accountId = <?= $accountId; ?>;

						if (!newPassword || newPassword.trim() === '') {
							console.warn('New password is empty or invalid.');
							showOtpAlert(response.message, 'error');
							return;
						}

						// Password Change AJAX Request
						console.log('Sending password change request...'); // Debugging log
						$.ajax({
							url: '../Employee Section/functions/General/emp-newPasswordChange.php',
							method: 'POST',
							data: {
								newPassword: newPassword,
								accountId: accountId
							},
							dataType: 'json',
							success: function(res) {
								console.log('Password change response:', res); // Debugging log
								if (res.status === 'success') {
									console.log('Password changed successfully'); // Debugging log

									showOtpAlert(response.message, response.status);

									setTimeout(function() {
										console.log('Reloading the page...');
										location.reload(); // Reload the page after 3 seconds
									}, 3000);

								} else {
									console.warn('Password change failed:', res.message);
									$('#messageAlert').show().text(res.message).css({
										'background-color': '#f8d7da',
										'color': '#721c24',
										'border': '1px solid #f5c6cb'
									});
								}
							},
							error: function(xhr, status, error) {
								console.error("AJAX Error (Password Change):", error);
								console.log("Response Text (Password Change):", xhr.responseText);

								$('#messageAlert').show().text('An error occurred while updating the password. Please try again.').css({
									'background-color': '#f8d7da',
									'color': '#721c24',
									'border': '1px solid #f5c6cb'
								});
							}
						});

					} else if (response.status === 'error') {
						console.error('OTP Verification Failed:', response.message); // Error logging
						showOtpAlert(response.message, response.status);
					} else {
						console.warn('Unexpected response status:', response.status); // Warn for unexpected status
						showOtpAlert(response.message, response.status);
					}

				},
				error: function(xhr, status, error) {
					console.error("AJAX Error (OTP Verification):", error);
					console.log("Response Text (OTP Verification):", xhr.responseText);
					$('#otpError').text('An error occurred during OTP verification. Please try again.');
				}
			});
		});


		// Resend OTP functionality
		$('#sendOtpBtn').click(function(e) {
			e.preventDefault();

			const currentPassword = document.getElementById('currentPassword').value;

			// Safe email assignment from PHP
			let emailAddress = <?= isset($emailAddress) ? json_encode($emailAddress) : 'null'; ?>;


			// Early layer: if PHP email is already empty/null
			if (!emailAddress || emailAddress === 'null' || emailAddress.trim() === '') {
				console.warn('Email from PHP session is missing. Checking sessionStorage as fallback.');
				emailAddress = sessionStorage.getItem('emailAddress');

				// If still not found, alert the user
				if (!emailAddress || emailAddress === 'null' || emailAddress.trim() === '') {
					console.warn('Email address not found in PHP session or sessionStorage.');
					showOtpAlert('Unable to send OTP. No email address found. Please update your profile.', 'error');
					return;
				}
			}

			// Check if current password is empty
			if (!currentPassword || currentPassword.trim() === '') {
				showOtpAlert('Please enter your current password to resend OTP.', 'error');
				return;
			}

			// Final fallback: double-check before sending
			if (!emailAddress || emailAddress === 'null' || emailAddress.trim() === '') {
				showOtpAlert('Email address is still invalid. Cannot send OTP.', 'error');
				return;
			}

			// Proceed to send OTP
			sendOtp(currentPassword, emailAddress); // Reuse existing OTP sending function
			showOtpAlert('Resending OTP. Please wait...', 'success');
		});

	});
</script>

















<!-- <script>
	document.addEventListener("DOMContentLoaded", function() {
		// Add event listeners only to links with the "open-new-tab" class
		document.querySelectorAll(".open-new-tab").forEach(function(button) {
			button.addEventListener("click", function(event) {
				event.preventDefault(); // Prevent the default link behavior
				const url = button.getAttribute("data-url"); // Get the URL from the data-url attribute
				window.open(url, '_blank'); // Open the URL in a new tab
			});
		});
	});
</script> -->