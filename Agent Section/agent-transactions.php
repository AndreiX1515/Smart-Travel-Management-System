
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="../Agent Section/assets/css/agent-transaction.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../Agent Section/assets/css/navbar-sidebar.css?v=<?php echo time(); ?>">

</head>

<body>
<?php include '../Agent Section/includes/sidebar.php'; ?> 

<div class="main-content" id="mainContent">
  <?php include '../Agent Section/includes/navbar.php'; ?>

  <div class="content-wrapper">
  <div class="table-container">
   <div class="search-bar">
       <div class="left-side">
           <div class="search-input mb-3">
               <label for="search">Search</label>
               <input type="text" id="search" class="form-control mt-2" placeholder="Search">
           </div>
       </div>

       <div class="right-side">
           <div class="filter-group">
               <div class="filter-field mb-3 d-flex flex-column">
                   <label for="packages">Packages</label>
                   <select id="packages" class="custom-select mt-2">
                       <option>Packages</option>
                       <option>All</option>
                   </select>
               </div>

               <div class="filter-field mb-3 d-flex flex-column">
                   <label for="category">Category</label>
                   <select id="category" class="custom-select mt-2">
                       <option>Category</option>
                       <option>All</option>
                   </select>
               </div>

               <div class="filter-field mb-3 d-flex flex-column">
                   <label for="status">Status</label>
                   <select id="status" class="custom-select mt-2">
                       <option>Status</option>
                       <option>All</option>
                   </select>
               </div>

               <div class="filter-field mb-3 d-flex flex-column">
                   <label for="date-range">Date Range (Start - End)</label>
                   <div class="input-group date-range-picker mt-2">
                       <input type="date" class="form-control" id="startDate" placeholder="Start date">
                       <span class="input-group-text">→</span>
                       <input type="date" class="form-control" id="endDate" placeholder="End date">
                   </div>
               </div>

               <div class="filter-field d-flex justify-content-center">
                   <button class="search-button mt-3"><i class="fa-solid fa-magnifying-glass"></i></button>
               </div>
           </div>
       </div>
   </div>

   <!-- <hr style="border: 1px solid grey; margin: 5px 0 20px 0;"> -->

   <div class="table-actions">
       <div class="show-column">
           <span>Show: </span>
           <select>
               <option>10</option>
               <option>20</option>
               <option>30</option>
               <option>All</option>
           </select>
       </div>

       <!-- <div class="pagination">
           <button id="prev-page" class="pagination-button" onclick="prevPage()">&#8249;</button>
           <button class="pagination-number" onclick="goToPage(1)">1</button>
           <button class="pagination-number" onclick="goToPage(2)">2</button>
           <button id="page-info" class="pagination-number active">3</button>
           <span class="pagination-ellipsis">...</span>
           <button class="pagination-number" onclick="goToPage(10)">10</button>
           <button id="next-page" class="pagination-button" onclick="nextPage()">&#8250;</button>
       </div> -->
   </div>

   <table class="product-table">
       <thead>
           <tr>
               <th>ID</th>
               <th>Contact Person Name</th>
               <th>Contact Person Email</th>
               <th>Contact Person Phone Number</th>
               <th>Package Name</th>
               <th>Booking Date</th>
               <th>Flight Date</th>
               <th>Total Pax</th>
               <th>Status</th>
               <th></th>
           </tr>
       </thead>
       <tbody>

       <?php
         $sql1 = "SELECT
             b.transactNo AS `T.N`,
             p.packageName AS `PACKAGE`,
             b.bookingDate AS `TRANSACTION DATE`,
             CASE 
                 WHEN b.flightId IS NULL THEN 'Land Only'
                 ELSE DATE_FORMAT(f.flightDepartureDate, '%M %d, %Y')
             END AS `FLIGHT DATE`,
             b.pax AS `TOTAL PAX`,
             CONCAT(
                 b.lName, ', ', b.fName, ' ', 
                 CASE WHEN b.mName = 'N/A' THEN '' ELSE CONCAT(SUBSTRING(b.mName, 1, 1), '.') END, ' ',
                 CASE WHEN b.suffix = 'N/A' THEN '' ELSE b.suffix END
             ) AS `CONTACT NAME`,
             b.email AS `CONTACT EMAIL`,
             CONCAT(b.countryCode, b.contactNo) AS `CONTACT PHONE`,
             b.status AS `STATUS`
         FROM 
             booking b
         LEFT JOIN 
             flight f ON b.flightId = f.flightId
         LEFT JOIN 
             package p ON b.packageId = p.packageId
         LEFT JOIN
             agent a ON b.agentId = a.agentId
         WHERE 
             b.agentId = '$agentId' AND b.status = 'Pending'
         ORDER BY 
             b.transactNo DESC LIMIT 10";

         $res1 = $conn->query($sql1);

         if ($res1->num_rows > 0) {
             while ($row = $res1->fetch_assoc()) {
                 $transactNo = $row['T.N'];
                 echo "<tr>
                     <td>{$transactNo}</td>
                     <td>{$row['CONTACT NAME']}</td>
                     <td>{$row['CONTACT EMAIL']}</td>
                     <td>{$row['CONTACT PHONE']}</td>
                     <td>{$row['PACKAGE']}</td>
                     <td>{$row['TRANSACTION DATE']}</td>
                     <td>{$row['FLIGHT DATE']}</td>
                     <td>{$row['TOTAL PAX']}</td>
                     <td>{$row['STATUS']}</td>
                     <td>
                         <div class='dropdown'>
                             <button class='btn btn-link p-0 text-secondary' type='button' data-bs-toggle='dropdown' aria-expanded='false'>
                                 <i class='fas fa-ellipsis-v'></i>
                             </button>
                             <ul class='dropdown-menu'>

                                 <li><a class='dropdown-item' href='#' onclick='fetchTransaction(\"{$row['T.N']}\")'>Add Guests Information</a></li>

                                 <li> <a class='dropdown-item' href='#' data-bs-toggle='modal' data-bs-target='#updateBookingModal' data-transaction-id='{$row['T.N']}'>
                                       Update Booking </a> </li>

                                 <li><a class='dropdown-item' href='#' data-bs-toggle='modal' data-bs-target='#requestModal{$transactNo}'>Request</a></li>
                                 
                                 <li><a class='dropdown-item' href='#' data-bs-toggle='modal' data-bs-target='#paymentModal{$transactNo}'>Payment</a></li>
                             </ul>
                         </div>
                     </td>
                 </tr>";

               
                 // Modal for Request
                 echo "
                 <div class='modal fade' id='requestModal{$transactNo}' tabindex='-1' aria-labelledby='requestModalLabel{$transactNo}' aria-hidden='true'>
                     <div class='modal-dialog'>
                         <div class='modal-content'>
                             <div class='modal-header'>
                                 <h5 class='modal-title' id='requestModalLabel{$transactNo}'>Request for Transaction #{$transactNo}</h5>
                                 <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                             </div>
                             <div class='modal-body'>
                                 <!-- Form or content for request -->
                             </div>
                             <div class='modal-footer'>
                                 <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
                                 <button type='button' class='btn btn-primary'>Submit Request</button>
                             </div>
                         </div>
                     </div>
                 </div>";

                 // Modal for Payment
                 echo "
                 <div class='modal fade' id='paymentModal{$transactNo}' tabindex='-1' aria-labelledby='paymentModalLabel{$transactNo}' aria-hidden='true'>
                     <div class='modal-dialog'>
                         <div class='modal-content'>
                             <div class='modal-header'>
                                 <h5 class='modal-title' id='paymentModalLabel{$transactNo}'>Payment for Transaction #{$transactNo}</h5>
                                 <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                             </div>
                             <div class='modal-body'>
                                 <!-- Form or content for payment processing -->
                             </div>
                             <div class='modal-footer'>
                                 <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
                                 <button type='button' class='btn btn-primary'>Proceed with Payment</button>
                             </div>
                         </div>
                     </div>
                 </div>";
             }
         } else {
             echo "<tr><td colspan='10'>No bookings found</td></tr>";
         }
         ?>


       </tbody>
   </table>

   <div class="table-footer border-0">
       <div class="total-records">Total Records: <?php echo $res1->num_rows; ?></div>
       <div class="footer-pagination">
           <button class="pagination-button" onclick="prevPage()">&#8249; Prev</button>
           <span>Page 1 of 10</span>
           <button class="pagination-button" onclick="nextPage()">Next &#8250;</button>
       </div>
   </div>
</div>

  </div>
</div>

<!-- Modal for Update Booking -->
<div class="modal fade" id="updateBookingModal" tabindex="-1" aria-labelledby="updateBookingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="updateBookingModalLabel">Update Booking</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

               <div class="mb-4 d-flex align-items-center w-100">
                  <h6 class="mb-0">Transaction ID:</h6>
                  <span id="transactionId" class="ms-2"></span>
              </div>



                <!-- Form for updating booking details -->
                <form id="updateBookingForm" method="POST">
                 <input type="hidden" name="transaction_number" value="">

                 <h6 class="fw-bold">Personal Information:</h6>

                 <div class="row mt-2">
                   <div class="col-md-3 mb-3">
                     <label for="contactName" class="form-label">First Name</label>
                     <input type="text" class="form-control" id="contactName" name="fName" required>
                   </div>
                   <div class="col-md-3 mb-3">
                     <label for="contactLName" class="form-label">Last Name</label>
                     <input type="text" class="form-control" id="contactIName" name="IName">
                   </div>
                   <div class="col-md-3 mb-3">
                     <label for="contactMName" class="form-label">Middle Name</label>
                     <input type="text" class="form-control" id="contactMName" name="mName">
                   </div>
                   <div class="col-md-3 mb-3">
                    <label for="contactSuffix" class="form-label">Suffix</label>
                    <select class="form-control" id="contactSuffix" name="suffix">
                        <option value="Jr.">Jr.</option>
                        <option value="Sr.">Sr.</option>
                        <option value="III">III</option>
                        <option value="IV">IV</option>
                        <option value="V">V</option>
                        <option value="N/A">N/A</option>
                        <!-- Add more options as needed -->
                    </select>
                </div>
                 </div>

                 <div class="row">
                   
                 </div>

                 <div class="row">
                   <div class="col-md-2 mb-3">
                     <label for="countryCode" class="form-label">Country Code</label>
                     <input type="text" class="form-control" id="countryCode" name="countryCode">
                   </div>
                   <div class="col-md-4 mb-3">
                     <label for="contactPhone" class="form-label">Contact Phone</label>
                     <input type="text" class="form-control" id="contactPhone" name="contactNo" required>
                   </div>
                   <div class="col-md-6 mb-3">
                     <label for="contactEmail" class="form-label">Contact Email</label>
                     <input type="email" class="form-control" id="contactEmail" name="email" required>
                   </div>
                 </div>

                 <h6 class="fw-bold my-2">Booking Information:</h6>

                 <div class="row">
                   <div class="col-md-2 mb-2">
                     <label for="totalPax" class="form-label">Total Pax</label>
                     <input type="number" class="form-control" id="totalPax" name="pax" required>
                     
                   </div>
                   <div class="col-md-5 mb-3">
                      <label for="flightDetails" class="form-label">Flight Details</label>
                      <input type="text" class="form-control" id="flightDetails" name="flightDetails" required>
                  </div>
                  <div class="col-md-5 mb-3">
                    <label for="package" class="form-label">Package</label>
                    <select class="form-control" id="package" name="package">
                        <option value="Summer">Summer</option>
                        <option value="Autumn">Autumn</option>
                        <option value="Winter">Winter</option>
                        <option value="Spring">Spring</option>
                        <option value="Cherry Blossom">Cherry Blossom</option>
                        <!-- Add more options as needed -->
                    </select>
                </div>


                 </div>

                 <div class="row">
                   <div class="col-md-6 mb-3">
                     <label for="totalPrice" class="form-label">Total Price</label>
                     <input type="text" class="form-control" id="totalPrice" name="totalPrice" readonly>

                   </div>
                 </div>
               </form>

            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="updateBooking()">Update</button>
            </div>
        </div>
    </div>
</div>

<?php require "../Agent Section/includes/scripts.php"; ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Add Guest Function -->
<script>
function fetchTransaction(transactionNumber) {
    console.log("Transaction Number: ", transactionNumber); // Debug line
    // Use AJAX to send the transaction number to the server
    $.ajax({
        url: '../Agent Section/functions/addGuests.php', // The PHP file that will handle the session setting
        type: 'POST',
        data: { transaction_number: transactionNumber },
        success: function(response) {
            console.log("Response: ", response); // Debug line
            // Redirect to the next page after setting the session
            window.location.href = '../Agent Section/agent-addGuest.php'; // Redirect to your next page
        },
        error: function(xhr, status, error) {
            console.error("AJAX Error: " + status + " " + error); // Enhanced error logging
        }
    });
}
</script>


<script>
    document.addEventListener('DOMContentLoaded', function () {
    const updateBookingModal = document.getElementById('updateBookingModal');

    updateBookingModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget; // Button that triggered the modal
        const transactionId = button.getAttribute('data-transaction-id'); // Fetch transaction ID

        // Populate the hidden input field with the transaction ID
        document.querySelector('#updateBookingForm input[name="transaction_number"]').value = transactionId;

        // Display the transaction ID in the modal
        document.getElementById('transactionId').textContent = transactionId;

        // Fetch booking details based on the transaction ID
        fetchBookingDetails(transactionId);
    });
});

function fetchBookingDetails(transactionId) {
    // Use Fetch API to get booking details
    fetch('../Agent Section/functions/getBookingDetails.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ transaction_id: transactionId }),
    })

    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Populate fields with the fetched data
            document.getElementById('contactName').value = data.booking.fName;
            document.getElementById('contactIName').value = data.booking.lName;
            document.getElementById('contactMName').value = data.booking.mName;
            document.getElementById('contactSuffix').value = data.booking.suffix;
            document.getElementById('countryCode').value = data.booking.countryCode;
            document.getElementById('contactPhone').value = data.booking.contactNo;
            document.getElementById('contactEmail').value = data.booking.email;
            document.getElementById('totalPax').value = data.booking.pax;

            document.getElementById('flightDetails').value = "";
            document.getElementById('totalPrice').value = data.booking.totalPrice;

            document.getElementById('package').value = "";
        } else {
            console.error('Error fetching booking details:', data.message);
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
    });
}

function updateBooking() {
    const form = document.getElementById('updateBookingForm');
    const formData = new FormData(form);
    // Implement AJAX call to update booking...
    console.log("Updating booking with data:", formData);
}

</script>


   
</body>
</html>