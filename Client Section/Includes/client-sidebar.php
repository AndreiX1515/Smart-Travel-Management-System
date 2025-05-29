<?php
require "../conn.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$accountId = $_SESSION['client_accountId'];
$agentId = $_SESSION['clientId'] ?? null;
$agentCode = $_SESSION['clientCode'] ?? null;
$agentRole = $_SESSION['clientRole'] ?? null;
$agentType = $_SESSION['clientType'] ?? null;
$fName = $_SESSION['client_fName'] ?? null;
$lName = $_SESSION['client_lName'] ?? null;
$mName = $_SESSION['client_mName'] ?? null;
$branchId = $_SESSION['client_branchId'] ?? null;
$email = $_SESSION['client_email'] ?? null;
$emailAddress = $_SESSION['client_emailAddress'] ?? null;
$password = $_SESSION['client_password'] ?? null;

// Fetch Branch Name
$sql1 = "SELECT branchName FROM branch WHERE branchId = ?";
$stmt1 = $conn->prepare($sql1);
$stmt1->bind_param("i", $branchId);
$stmt1->execute();
$result1 = $stmt1->get_result();

if ($result1->num_rows > 0) {
  $row = $result1->fetch_assoc();
  $branchName = $row['branchName'];
} else {
  $branchName = "No Branch";
}
$stmt1->close();

// Fetch Agent Info (to get companyId)
$sql2 = "SELECT companyId FROM client WHERE accountId = ?";
$stmt2 = $conn->prepare($sql2);
$stmt2->bind_param("i", $accountId);
$stmt2->execute();
$result2 = $stmt2->get_result();

if ($result2->num_rows > 0) {
  $row2 = $result2->fetch_assoc();
  $companyId = $row2['companyId'];

  // Fetch Company Name if companyId is NOT NULL
  if (!is_null($companyId)) {
    $sql3 = "SELECT companyName FROM company WHERE companyId = ?";
    $stmt3 = $conn->prepare($sql3);
    $stmt3->bind_param("i", $companyId);
    $stmt3->execute();
    $result3 = $stmt3->get_result();

    if ($result3->num_rows > 0) {
      $row3 = $result3->fetch_assoc();
      $companyName = $row3['companyName'];
    } else {
      $companyName = "Unknown Company";
    }
    $stmt3->close();
  } else {
    $companyName = null; // No company assigned
  }
} else {
  // Only set "No Branch" if branchName is still empty
  if (empty($branchName)) {
    $branchName = "No Branch";
  }
}

$stmt2->close();

// Format the full name
$fullName = htmlspecialchars($lName . ', ' . $fName . ($mName ? ' ' . substr($mName, 0, 1) . '.' : ''));

// Optional: hide password by default
$maskedPassword = '••••••••••';
?>

<?php
date_default_timezone_set('Asia/Taipei');
$current_date = date('D, F d, Y');
?>

<div class="sidebar" id="sidebar">

  <ul class="nav flex-column nav-logo-wrapper nav-logo-header">
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
      <a class="nav-link page-button" href="../Client Section/client-dashboard.php" data-page-name="Dashboard">
        <div class="icon-wrapper">
          <div class="icon"><i class="fa-solid fa-house"></i></div>
        </div>
        <div class="label-wrapper">
          <span class="label">Dashboard</span>
        </div>
      </a>
    </li>

    <!-- Packages -->
    <li class="nav-item transaction">
      <a class="nav-link page-button" href="../Client Section/client-transactions.php" data-page-name="Packages">
        <div class="icon-wrapper">
          <div class="icon"><i class="fa-solid fa-box"></i></div>
        </div>
        <div class="label-wrapper">
          <span class="label" style="font-size: 14px;">Packages</span>
        </div>
      </a>
    </li>

    <!-- Guest List -->
    <li class="nav-item transaction">
      <a class="nav-link page-button" href="../Client Section/client-guestInformationList.php"
        data-page-name="Guest List">
        <div class="icon-wrapper">
          <div class="icon"><i class="fa-solid fa-users"></i></div>
        </div>
        <div class="label-wrapper">
          <span class="label" style="font-size: 14px;">Guest List</span>
        </div>
      </a>
    </li>

    <!-- Request -->
    <li class="nav-item transaction">
      <a class="nav-link page-button" href="../Client Section/client-requestHistory.php" data-page-name="Request">
        <div class="icon-wrapper">
          <div class="icon"><i class="fa-solid fa-envelope-open-text"></i></div>
        </div>
        <div class="label-wrapper">
          <span class="label" style="font-size: 14px;">Request</span>
        </div>
      </a>
    </li>

    <!-- Payment -->
    <li class="nav-item transaction">
      <a class="nav-link page-button" href="../Client Section/client-paymentHistory.php" data-page-name="Payment">
        <div class="icon-wrapper">
          <div class="icon"><i class="fa-solid fa-money-bill-wave"></i></div>
        </div>
        <div class="label-wrapper">
          <span class="label" style="font-size: 14px;">Payment</span>
        </div>
      </a>
    </li>

    <!-- Rooming List -->
    <li class="nav-item transaction">
      <a class="nav-link page-button" href="../Client Section/client-roomingList.php" data-page-name="Rooming List">
        <div class="icon-wrapper">
          <div class="icon"><i class="fa-solid fa-bed"></i></div>
        </div>
        <div class="label-wrapper">
          <span class="label" style="font-size: 14px;">Rooming List</span>
        </div>
      </a>
    </li>

    <!-- SOA
    <li class="nav-item transaction">
      <a class="nav-link page-button" href="../Agent Section/agent-soa.php" data-page-name="SOA">
        <div class="icon-wrapper">
          <div class="icon"><i class="fa-solid fa-file-invoice-dollar"></i></div>
        </div>
        <div class="label-wrapper">
          <span class="label" style="font-size: 14px;">SOA</span>
        </div>
      </a>
    </li> -->

    <!-- Reports -->
    <li class="nav-item transaction">
      <a class="nav-link page-button" href="../Client Section/client-reports.php" data-page-name="Reports">
        <div class="icon-wrapper">
          <div class="icon"><i class="fa-solid fa-chart-line"></i></div>
        </div>
        <div class="label-wrapper">
          <span class="label" style="font-size: 14px;">Sales Reports</span>
        </div>
      </a>
    </li>


    <!-- Transactions -->
    <!-- <li class="nav-item dropdown">
      <a class="nav-link page-button" href="#" data-bs-toggle="collapse" data-bs-target="#manageBookingMenu"
        aria-expanded="false" aria-controls="manageBookingMenu" data-page-name="Operationals">
        <div class="icon-wrapper">
          <div class="icon"><i class="fa-solid fa-arrow-right-arrow-left"></i></div>
        </div>
        <div class="label-wrapper">
          <span class="label">Transactions</span>
        </div>
      </a>

      <div class="collapse" id="manageBookingMenu">
        <ul class="nav flex-column managebooking-menu-wrapper">
          <li class="nav-item transaction mb-0">
            <a class="nav-link page-button" href="../Employee Section/emp-tablePending.php"
              data-page-name="For Approvals - Booking">No Downpayment</a>
          </li>
          <li class="nav-item mb-0">
            <a class="nav-link page-button" href="../Employee Section/emp-tableRequest.php"
              data-page-name="For Approvals - Request">Request</a>
          </li>
          <li class="nav-item mb-0">
            <a class="nav-link page-button" href="../Employee Section/emp-tablePayment.php"
              data-page-name="For Approvals - Payment">Payment</a>
          </li>
        </ul>
      </div>
    </li> -->

  </ul>

  <div class="logout">
    <!-- <div class="separator"></div> -->

    <div class="profile-section">
      <div class="profile-left" id="profileLeft">
        <div class="name" style="font-size: <?php echo (strlen($fullName) >= 13) ? '14px' : '17px'; ?>;">
          <?php echo $fullName; ?>
        </div>
        <div class="empid fw-bold text-light" style="font-size: 14px;">
          Branch: <span class="fw-normal text-light"><?php echo $branchName; ?></span>
        </div>
      </div>
      <!-- <div class="profile-icon profile-icon-visible">
        <i class="fa-solid fa-user-circle"></i>
      </div> -->
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


<!-- Modals -->
<!-- Raise Ticket Modal -->
<div class="modal fade" id="raiseTicketModal" tabindex="-1" aria-labelledby="raiseTicketModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="raiseTicketModalLabel"><i class="fas fa-ticket-alt"></i> Raise a Ticket</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="ticketForm">
          <div class="mb-3">
            <label for="concernType" class="form-label">Concern</label>
            <select class="form-select" id="concernType" required>
              <option value="" selected disabled>Select Concern</option>
              <option value="Request for Additional User">Request for Additional User</option>
            </select>

            <!-- Hidden input field for Number of Users -->
            <div id="userCountContainer" style="display: none; margin-top: 10px;">
              <label for="numUsers" class="form-label">Number of Users</label>
              <input type="number" class="form-control" id="numUsers" min="1" placeholder="Enter number of users">

            </div>
          </div>

          <div class="alert alert-info mt-3" id="userCountContainer-note" style="display: none; font-size: 14px;">
            <p class="mb-1"><strong>Please provide user credentials using the template below:</strong></p>
            <p class="mb-1"><strong>- Full Name <span style="font-weight: 400;">(First Name, Last Name, Middle Name,
                  Suffix)</span>:</strong> </p>
            <p class="mb-1"><strong>- Company Name:</strong></p>
            <p class="mb-1"><strong>- Contact Number:</strong></p>
            <p class="mb-3"><strong>- Email:</strong></p>
            <p class="mb-0"><strong>Note:</strong> A default password will be assigned initially.</p>
          </div>



          <!-- JS for Number of Users -->
          <script>
            document.getElementById("concernType").addEventListener("change", function () {
              var userCountContainer = document.getElementById("userCountContainer");
              var userCountContainerNote = document.getElementById("userCountContainer-note");
              var ticketPriority = document.getElementById("ticketPriority");
              if (this.value === "Request for Additional User") {
                userCountContainer.style.display = "block";
                userCountContainerNote.style.display = "block";
                ticketPriority.style.display = "hidden";
              } else {
                userCountContainer.style.display = "none";
                userCountContainerNote.style.display = "none";
                ticketPriority.style.display = "block";
              }
            });
          </script>


          <div class="mb-3">
            <label for="ticketDescription" class="form-label">Description</label>
            <textarea class="form-control" id="ticketDescription" rows="4" required></textarea>
          </div>
          <div class="mb-3" id="ticketPriority" style="display: hidden;">
            <label for="ticketPriority" class="form-label">Priority</label>
            <select class="form-select" id="ticketPriority">
              <option value="" disabled selected>Select Severity</option>
              <option value="low">Low</option>
              <option value="medium" selected>Medium</option>
              <option value="high">High</option>
            </select>
          </div>
          <!-- <div class="mb-3">
                            <label for="ticketAttachment" class="form-label">Attachment (Optional)</label>
                            <input type="file" class="form-control" id="ticketAttachment">
                        </div> -->
          <button type="submit" class="btn btn-success w-100"> Submit Ticket</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Logout Confirmation Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="logoutModalLabel">Confirm Logout</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to logout?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <a href="#" class="btn btn-danger" id="logoutButton">Logout</a>

      </div>
    </div>
  </div>
</div>

<!-- Change Password Modal -->
<!-- <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header 
      <div class="modal-header">
        <div class="modal-title-wrapper">
          <h5 class="modal-title" id="changePasswordLabel">Change Password</h5>
          <small class="modal-subtext">Ensure your new password is secure and different from previous ones.</small>
        </div>
        <div class="modal-close-wrapper">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
      </div>

      <!-- Modal Body 
      <div class="modal-body">
        <form id="changePasswordForm">
          
          <!-- Current Password Field 
          <div class="mb-3">
            <label for="currentPassword" class="form-label">Current Password</label>
            <input type="password" class="form-control" id="currentPassword" placeholder="Enter current password" required>
            <small id="currentPasswordError" class="error-label text-danger"></small>
          </div>

          <!-- New Password Field 
          <div class="mb-3">
            <label for="newPassword" class="form-label">New Password</label>
            <input type="password" class="form-control" id="newPassword" placeholder="Enter new password" required>
            <small id="newPasswordError" class="error-label text-danger"></small>
          </div>

          <!-- Confirm New Password Field 
          <div class="mb-3">
            <label for="confirmNewPassword" class="form-label">Confirm New Password</label>
            <input type="password" class="form-control" id="confirmNewPassword" placeholder="Re-enter new password" required>
            <small id="confirmPasswordError" class="error-label text-danger"></small>
          </div>

          <!-- Success Message 
          <small id="updateMessage" class="text-success"></small>

          <!-- Modal Footer 
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-success">Update Password</button>
          </div>
        </form>
      </div>

    </div>
  </div>
</div> -->

<!-- <script>
$(document).ready(function () {
    let accountId = 
    <?php
    // echo $accountId; 
    ?>;

    // Real-time password length check
    $("#newPassword").on("input", function () {
        let newPassword = $(this).val().trim();
        console.log("New Password Length (input):", newPassword.length); // Debugging

        if (newPassword.length < 6) {
            $("#newPasswordError").text("Password must be at least 6 characters.").fadeIn();
        } else {
            $("#newPasswordError").fadeOut();
        }
    });

    // Real-time confirmation password match check
    $("#confirmNewPassword").on("input", function () {
        let newPassword = $("#newPassword").val().trim();
        let confirmNewPassword = $(this).val().trim();
        console.log("Confirm Password Match (input):", newPassword === confirmNewPassword);

        if (newPassword !== confirmNewPassword) {
            $("#confirmPasswordError").text("Passwords do not match.").fadeIn();
        } else {
            $("#confirmPasswordError").fadeOut();
        }
    });

  
    $(document).on("submit", "#changePasswordForm", function (e) {
        e.preventDefault(); 

      
        $(".error-label").text("").hide();
        $("#updateMessage").text("").hide();

        let currentPassword = $("#currentPassword").val().trim();
        let newPassword = $("#newPassword").val().trim();
        let confirmNewPassword = $("#confirmNewPassword").val().trim();
        let errorCount = 0;

       
        console.log("Form Submitted - New Password Length:", newPassword.length);

        
        if (newPassword.length < 6) {
            $("#newPasswordError").text("Password must be at least 6 characters.").fadeIn();
            errorCount++;
        }

      
        if (newPassword !== confirmNewPassword) {
            $("#confirmPasswordError").text("Passwords do not match.").fadeIn();
            errorCount++;
        }

     
        if (errorCount > 0) return;

        
        console.log("Submitting AJAX request...");
        console.log("Account ID:", accountId);
        console.log("Current Password:", currentPassword);
        console.log("New Password:", newPassword);

        $.ajax({
            url: "../Agent Section/functions/General/agent-changePassword.php", 
            type: "POST",
            data: {
                accountId: accountId,
                currentPassword: currentPassword,
                newPassword: newPassword
            },
            dataType: "json",
            success: function (response) {
                console.log("Server Response:", response); 

                if (response.status === "error") {
                    console.log("Error:", response.message);
                    $("#currentPasswordError").text(response.message).fadeIn();
                } else if (response.status === "success") {
                    console.log("Success: Password updated successfully!");
                    $("#updateMessage").text("Password updated successfully!").fadeIn();
                    $("#changePasswordForm")[0].reset(); // Reset the form
                    setTimeout(() => {
                        $("#changePasswordModal").modal("hide"); // Close modal after success
                    }, 1500);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log("AJAX Error:", textStatus, errorThrown);
                alert("Something went wrong. Please try again.");
            }
        });
    });

});
</script> -->

<!-- Ticket Submission Script -->
<script>
  $(document).ready(function () {
    $("#ticketForm").submit(function (event) {
      event.preventDefault(); // Prevent default form submission

      console.log("Form submission triggered."); // Debugging

      // Collect form data
      var concernType = $("#concernType").val();
      var numUsers = $("#numUsers").val() || ""; // Get only if field is visible
      var ticketDescription = $("#ticketDescription").val();
      var ticketPriority = $("#ticketPriority").val() || "medium"; // Default to "medium" if empty


      console.log("Collected form data:", {
        concernType: concernType,
        numUsers: numUsers,
        ticketDescription: ticketDescription,
        ticketPriority: ticketPriority
      }); // Debugging

      // Create data object
      var formData = {
        concernType: concernType,
        numUsers: concernType === "Request for Additional User" ? numUsers : "", // Send only if applicable
        ticketDescription: ticketDescription,
        ticketPriority: ticketPriority
      };

      console.log("Final form data before AJAX request:", formData); // Debugging

      // AJAX Request
      $.ajax({
        type: "POST",
        url: "../Agent Section/functions/agent-processTicket.php", // Change to your server-side script
        data: formData,
        dataType: "json",
        beforeSend: function () {
          console.log("AJAX request is about to be sent..."); // Debugging
        },
        success: function (response) {
          console.log("AJAX success response:", response); // Debugging

          if (response.status === "success") {
            alert("Ticket submitted successfully! Ticket ID: " + response.ticketId);
            console.log("Ticket successfully created with ID:", response.ticketId); // Debugging

            // Close the modal
            let modal = document.getElementById("raiseTicketModal"); // Replace with your modal's actual ID
            let modalInstance = bootstrap.Modal.getInstance(modal);
            if (modalInstance) {
              modalInstance.hide();
            }

            // Reset the form
            document.getElementById("ticketForm").reset(); // Replace with your form's actual ID


          } else {
            alert("Error: " + response.message);
            console.error("Server returned an error:", response.message); // Debugging
          }
        },
        error: function (xhr, status, error) {
          alert("An error occurred while submitting the ticket.");
          console.error("AJAX error:", status, error); // Debugging
          console.log("Response Text:", xhr.responseText); // Debugging
        }
      });
    });

    // Show/Hide Fields Based on Concern Selection
    $("#concernType").change(function () {
      console.log("Concern type changed to:", $(this).val()); // Debugging

      if ($(this).val() === "Request for Additional User") {
        $("#userCountContainer").show();
        $("#userCountContainer-note").show();
        $("#ticketPriority").hide();
        console.log("Showing additional user input fields."); // Debugging
      } else {
        $("#userCountContainer").hide();
        $("#userCountContainer-note").hide();
        $("#ticketPriority").show();
        console.log("Hiding additional user input fields."); // Debugging
      }
    });
  });
</script>


<script>
  function toggleSubMenu(submenuId) {
    const submenu = document.getElementById(submenuId);
    const sectionTitle = submenu.previousElementSibling;
    const chevron = sectionTitle.querySelector('.chevron-icon');

    // Check if the submenu is already open
    const isOpen = submenu.classList.contains('open');

    // Toggle the submenu: If it's open, close it; If it's closed, open it
    if (isOpen) {
      submenu.classList.remove('open');
      chevron.style.transform = 'rotate(0deg)';
    } else {
      submenu.classList.add('open');
      chevron.style.transform = 'rotate(180deg)';
    }
  }

  // Optionally: Automatically open the submenu when the page loads (Transaction submenu is open by default in this case)
  document.addEventListener('DOMContentLoaded', function () {
    const transactionSubmenu = document.getElementById('transactiontable-submenu');
    const transactionChevron = document.querySelector('#transactiontable-submenu').previousElementSibling.querySelector('.chevron-icon');

    // Set the default opened submenu (Transaction)
    transactionSubmenu.classList.add('open');
    transactionChevron.style.transform = 'rotate(180deg)';
  });
</script>