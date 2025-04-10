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
$password = $_SESSION['password'] ?? '';
$userType = $_SESSION['employee_userType'] ?? '';  // Assuming user type is part of the session


// Format the full name: Get the first letter of the middle name and place it at the end
$middleNameInitial = $middleName ? substr($middleName, 0, 1) . '.' : ''; // First initial of middle name
$fullName = htmlspecialchars($firstName . ' ' . $middleNameInitial . ' ' . $lastName);  // Full name with middle name initial at the end

// Position or role (assuming userType and accountType are available)
$position = htmlspecialchars(strtoupper($empId));
?>


<div class="sidebar">
	<div class="sidebar-logo-section">
		<a class="nav-link logo-link" href="#">
			<div class="logo-content">
				<div class="logo-backdrop">
					<img src="../Assets/Logos/logo-tab.png" alt="Logo" class="sidebar-logo">
				</div>
				<span class="fw-bold">SMART TRAVEL</span>
			</div>
		</a>
	</div>

	<ul class="nav flex-column">
		<li class="nav-item">
			<a class="nav-link page-button" href="../Employee Section/emp-dashboard.php" data-page-name="Dashboard">
				<div class="icon"><i class="fa-solid fa-house"></i></div> <!-- Home Icon -->
				<span class="label">Dashboard</span>
			</a>
		</li>

		<!-- <li class="nav-item add-booking">
			<a class="nav-link page-button" href="" data-page-name="Add Booking">
				<div class="icon"><i class="fa-solid fa-user-plus"></i></div> 
				<span class="label">Add Booking</span>
			</a>
		</li> -->

		<li class="nav-item transaction">
			<a class="nav-link page-button" href="../Employee Section/emp-transaction.php" data-page-name="Transactions">
				<div class="icon"><i class="fa-solid fa-arrow-right-arrow-left"></i></div>
				<span class="label">Transactions</span>
			</a>
		</li>

		<!-- <li class="nav-item transaction">
			<a class="nav-link page-button" href="../Employee Section/emp-RequestHistory.php" data-page-name="Request History">
				<div class="icon"><i class="fas fa-history"></i></div>
				<span class="label">Request History</span>
			</a>
		</li> -->

		<!-- <li class="nav-item transaction">
			<a class="nav-link page-button" href="../Employee Section/emp-FlightSeatHistory.php" data-page-name="Flight Seat History">
				<div class="icon"><i class="fas fa-plane"></i></div>
				<span class="label" style="font-size: 14px;">Flight Seat History</span>
			</a>
		</li> -->

		<li class="nav-item transaction">
			<a class="nav-link page-button" href="../Employee Section/emp-guestList.php" data-page-name="Guest List">
				<div class="icon"><i class="fas fa-user"></i></div>
				<span class="label">Guest List</span>
			</a>
		</li>

		<li class="nav-item transaction">
			<a class="nav-link page-button" href="../Employee Section/emp-visaRequirementsTable.php" data-page-name="Visa Requirements">
				<div class="icon"><i class="fas fa-file-lines"></i></i></div>
				<span class="label" style="font-size: 14px;" >Visa Requirements</span>
			</a>
		</li>
		
		<!-- <li class="nav-item transaction">
			<a class="nav-link page-button" href="../Employee Section/emp-requestList.php" data-page-name="Guest List">
				<div class="icon"><i class="fas fa-user"></i></div>
				<span class="label">Request List</span>
			</a>
		</li> -->

		<!-- For Approvals Dropdown -->
		<li class="nav-item dropdown">
			<a class="nav-link page-button" href="#" id="manageBookingDropdown" role="button" data-bs-toggle="collapse" data-bs-target="#manageBookingMenu" aria-expanded="false" aria-controls="manageBookingMenu" data-page-name="Operationals">
				<div class="icon"><i class="fa-solid fa-thumbs-up"></i></div>
				<span class="label">For Approvals</span>
			</a>
			<div class="collapse" id="manageBookingMenu">
				<ul class="nav flex-column ms-3">
					<li class="nav-item transaction"> 
						<a class="nav-link page-button" href="../Employee Section/emp-tablePending.php" data-page-name="For Approvals - Booking">
							Booking
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link page-button" href="../Employee Section/emp-tableRequest.php" data-page-name="For Approvals - Request">
							Request
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link page-button" href="../Employee Section/emp-tablePayment.php" data-page-name="For Approvals - Payment">
							Payment
						</a>
					</li>
					<!-- <li class="nav-item">
						<a class="nav-link page-button" href="../Employee Section/emp-tableFIT.php" data-page-name="For Approvals - F.I.T">
							F.I.T
						</a>
					</li> -->
				</ul>
			</div>
		</li>

		<!-- Reports Dropdown -->
		<li class="nav-item dropdown">
			<a class="nav-link page-button" href="#" id="manageBookingDropdown" role="button" data-bs-toggle="collapse" data-bs-target="#manageBookingMenu" aria-expanded="false" aria-controls="manageBookingMenu" data-page-name="Operationals">
				<div class="icon"><i class="fa-regular fa-file"></i></i></div>
				<span class="label">Reports</span>
			</a>
			<div class="collapse" id="manageBookingMenu">
				<ul class="nav flex-column ms-3">
					<li class="nav-item">
						<a class="nav-link page-button open-new-tab" href="#" data-page-name="Itinerary" data-url="../Employee Section/emp-itinerarytable.php">Itinerary</a>
					</li>
					<li class="nav-item">
						<a class="nav-link page-button open-new-tab" href="#" data-page-name="Voucher" data-url="../Employee Section/emp-voucherTable.php">Voucher</a>
					</li>
					<li class="nav-item">
						<a class="nav-link page-button" href="#" data-page-name="Ticket">Ticket</a>
					</li>
					<li class="nav-item">
						<a class="nav-link page-button" href="../Employee Section/emp-soa.php" data-page-name="SOA">SOA</a>
					</li>
					<!-- <li class="nav-item">
						<a class="nav-link page-button" href="../Employee Section/emp-soaFIT.php" data-page-name="SOA">SOA FIT</a>
					</li> -->
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

		<a class="nav-link" href="#">
			<div class="icon"><i class="fas fa-eye"></i></div> <!-- View Password Icon -->
			<span class="label">View Password</span>
		</a>

		<!-- Logout Button (Triggers Modal) -->
		<a class="nav-link" id="logout-link" data-bs-toggle="modal" data-bs-target="#logoutModal">
			<div class="icon"><i class="fa-solid fa-right-from-bracket"></i></div> <!-- Logout Icon -->
			<span class="label">Logout</span>
		</a>
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

<!-- <script>
  document.addEventListener('DOMContentLoaded', () => {
    // Check if there's a saved title in local storage
    const savedTitle = localStorage.getItem('pageTitle');
    if (savedTitle) {
        document.getElementById('page-title').textContent = savedTitle;
    }

    const buttons = document.querySelectorAll('.page-button');
    buttons.forEach(button => {
        button.addEventListener('click', (event) => {
            event.preventDefault();
            const newPageName = button.getAttribute('data-page-name');
            document.getElementById('page-title').textContent = newPageName;

            // Save the title to local storage
            localStorage.setItem('pageTitle', newPageName);

            const newUrl = button.getAttribute('href');
            setTimeout(() => {
                window.location.href = newUrl;
            }, 25);
        });
    });
  });
</script> -->


<script>
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
</script>