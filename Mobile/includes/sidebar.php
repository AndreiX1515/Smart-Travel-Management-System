<?php
require "../conn.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Fetch accountType from accounts table
$query = "SELECT accountType FROM accounts WHERE accountId = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $accountId);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $_SESSION['accountType'] = $row['accountType']; // Store in session
}

$stmt->close();

// Assign session variables
$agentId = $_SESSION['agentId'] ?? '';
$agentCode = $_SESSION['agentCode'] ?? '';
$agentRole = $_SESSION['agentRole'] ?? '';
$agentType = $_SESSION['agentType'] ?? '';
$accountType = $_SESSION['accountType'] ?? ''; // Now included
$fName = $_SESSION['agent_fName'] ?? '';
$lName = $_SESSION['agent_lName'] ?? '';
$mName = $_SESSION['agent_mName'] ?? '';
$flightId = $_SESSION['agent_flightId'] ?? '';
$branchId = $_SESSION['agent_branchId'] ?? '';
// $email = $_SESSION['email'] ?? '';
// $password = $_SESSION['password'] ?? '';

// Fetch Branch Name
$sql1 = "SELECT branchName FROM branch WHERE branchId = ?";
$stmt1 = $conn->prepare($sql1);
$stmt1->bind_param("i", $branchId);
$stmt1->execute();
$result1 = $stmt1->get_result();  

if ($result1->num_rows > 0) 
{
  $row = $result1->fetch_assoc();
  $branchName = $row['branchName'];
} 
else 
{
  $branchName = "No Branch";
}
$stmt1->close();

// Fetch Agent Info (to get companyId)
$sql2 = "SELECT companyId FROM agent WHERE accountId = ?";
$stmt2 = $conn->prepare($sql2);
$stmt2->bind_param("i", $accountId);
$stmt2->execute();
$result2 = $stmt2->get_result();

if ($result2->num_rows > 0) 
{
  $row2 = $result2->fetch_assoc();
  $companyId = $row2['companyId'];

  // Fetch Company Name if companyId is NOT NULL
  if (!is_null($companyId)) 
  {
    $sql3 = "SELECT companyName FROM company WHERE companyId = ?";
    $stmt3 = $conn->prepare($sql3);
    $stmt3->bind_param("i", $companyId);
    $stmt3->execute();
    $result3 = $stmt3->get_result();

    if ($result3->num_rows > 0) 
    {
      $row3 = $result3->fetch_assoc();
      $companyName = $row3['companyName'];
    } 
    else 
    {
      $companyName = "Unknown Company"; // Fallback if no company record found
    }
    $stmt3->close();
  } 
  else 
  {
    $companyName = null; // No company assigned
  }
} 
else 
{
  // Only set "No Branch" if branchName is still empty
  if (empty($branchName)) 
  {
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



<!-- <?php 
// include '../Agent Section/includes/logoutViewPassModal.php'; 
?> -->

<!-- <script>
  $(document).ready(function() {
    $("#ticketForm").submit(function(event) {
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
            beforeSend: function() {
              console.log("AJAX request is about to be sent..."); // Debugging
            },
            success: function(response) {
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
          error: function(xhr, status, error) {
            alert("An error occurred while submitting the ticket.");
            console.error("AJAX error:", status, error); // Debugging
            console.log("Response Text:", xhr.responseText); // Debugging
          }
        });
    });

  // Show/Hide Fields Based on Concern Selection
  $("#concernType").change(function() {
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
</script> -->


<!-- <script>
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
  document.addEventListener('DOMContentLoaded', function() {
    const transactionSubmenu = document.getElementById('transactiontable-submenu');
    const transactionChevron = document.querySelector('#transactiontable-submenu').previousElementSibling.querySelector('.chevron-icon');

    // Set the default opened submenu (Transaction)
    transactionSubmenu.classList.add('open');
    transactionChevron.style.transform = 'rotate(180deg)';
  });
</script> -->

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