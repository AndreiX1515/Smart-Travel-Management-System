<?php session_start(); ?>
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
    <?php 
      include '../Agent Section/includes/navbar.php'; 
      
     
      
      
      // Check if 'transaction_number' exists in the session
      if (isset($_SESSION['transaction_number'])) {
          $transactionNumber = $_SESSION['transaction_number'];
      } else {
          echo "No transaction number found in the session.<br>";
      }

      // Check if 'id' is passed in the URL
      if (isset($_GET['id'])) {
       $transactionNumber = htmlspecialchars($_GET['id']);
      } 
      
      ?>
    

    <?php if(isset($_SESSION['status'])): ?>
      <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong>Hey!</strong> <?= $_SESSION['status']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>

    <?php 
      unset($_SESSION['status']);
      endif;
    ?>

    <div class="content-wrapper">
     <div class="w-100 border-1 ">
        <div class="row g-3 mb-3">
          <label for="" class="fw-bold ">Transaction Information: </label>

          <div class="col-md-2">
             <label for="transactNo" class="form-label">Transaction No</label>
             <input type="text" class="form-control" id="transactNo" name="transactNo" value="<?php echo $transactionNumber ?>" readonly>
         </div>

         <div class="col-md-2">
             <label for="bookingId" class="form-label">Booking ID</label>
             <input type="text" class="form-control" id="bookingId" name="bookingId" readonly>
         </div>

         <div class="col-md-2">
             <label for="accountId" class="form-label">Account ID</label>
             <input type="text" class="form-control" id="accountId" name="accountId" readonly>
         </div>
       
         <div class="col-md-2">
             <label for="agentId" class="form-label">Agent ID</label>
             <input type="text" class="form-control" id="agentId" name="agentId">
         </div>

         <div class="col-md-2">
             <label for="flightId" class="form-label">Flight ID</label>
             <input type="text" class="form-control" id="flightId" name="flightId">
         </div>

         <div class="col-md-2">
             <label for="packageId" class="form-label">Package ID</label>
             <input type="text" class="form-control" id="packageId" name="packageId">
         </div>

         <div class="col-md-2">
            <label for="pax" class="form-label">Pax</label>
            <input type="number" class="form-control" id="pax" name="pax">
        </div>
        
        <div class="col-md-2">
            <label for="bookingDate" class="form-label">Booking Date</label>
            <input type="text" class="form-control" id="bookingDate" name="bookingDate">
        </div>

        <div class="col-md-2">
                <label for="totalPrice" class="form-label">Total Price</label>
                <input type="number" step="0.01" class="form-control" id="totalPrice" name="totalPrice">
            </div>

        <div class="col-md-2">
            <label for="status" class="form-label">Status</label>
            <input type="number" step="0.01" class="form-control" id="totalPrice" name="Status">
        </div>

        </div>

        <div class="row g-3">
            <label for="" class="fw-bold ">Contact Person Information: </label>
             
            <div class="col-md-4">
                <label for="fName" class="form-label">Name</label>
                <input type="text" class="form-control" id="fName" name="fName">
            </div>

            <div class="col-md-3">
                <label for="contactNo" class="form-label">Contact No</label>
                <input type="text" class="form-control" id="contactNo" name="contactNo">
            </div>

            <div class="col-md-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email">
            </div>
            

            
        </div>
       </div>
       
    

     <ul class="nav nav-tabs mt-3" id="myTab" role="tablist">
      <li class="nav-item" role="presentation">
        <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">Guest Information</button>
      </li>

      <li class="nav-item" role="presentation">
        <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">Request History</button>
      </li>

      <li class="nav-item" role="presentation">
        <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact-tab-pane" type="button" role="tab" aria-controls="contact-tab-pane" aria-selected="false">Payment History</button>
      </li>

      <!-- <li class="nav-item" role="presentation">
        <button class="nav-link" id="disabled-tab" data-bs-toggle="tab" data-bs-target="#disabled-tab-pane" type="button" role="tab" aria-controls="disabled-tab-pane" aria-selected="false" disabled>Disabled</button>
      </li> -->
    </ul>

    <div class="tab-content" id="myTabContent">
      <!-- Guest Table -->
      <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">

      <div class="tab-wrapper">
         <div class="d-flex justify-content-end align-items-center p-3 mt-2">
              <div class="d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-primary">
                  View Guest Files
                </button>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#visaModal">
                  Attach Visa Requirements
                </button>
              </div>
          </div>

          <div class="table-container p-3">
              <table class="product-table">
                <thead>
                  <tr>
                    <th>GUEST ID</th>
                    <th>FIRST NAME</th>
                    <th>LAST NAME</th>
                    <th>MIDDLE NAME</th>
                    <th>SUFFIX</th>
                    <th>BIRTHDATE</th>
                    <th>AGE</th>
                    <th>SEX</th>
                    <th>NATIONALITY</th>
                    <th>CONTACT NO.</th>
                    <th>OTHER CONTACT NO.</th>
                    <th>EMAIL</th>
                    <th>ADDRESS</th>
                    <th>PASSPORT NO.</th>
                    <th>PASSPORT EXP.</th>
                    <th>VISA STATUS</th>
                    
                  </tr>
                </thead>

                <tbody>
                  <?php
                    $sql1= "SELECT *, DATE_FORMAT(birthdate, '%M %d, %Y') AS birthdate, CONCAT(countryCode, contactNo) AS contactNo,
                            CASE 
                              WHEN countryCode2 IS NULL OR contactNo2 IS NULL THEN 'N/A'
                              ELSE CONCAT(countryCode2, contactNo2)
                            END AS contactNo2, CONCAT(addressLine1, ', ', 
                            CASE 
                              WHEN addressLine2 IS NOT NULL AND addressLine2 != '' THEN CONCAT(addressLine2, ', ') 
                              ELSE '' 
                            END, city, ', ', state, ', ', zipcode, ', ', country) AS address
                            FROM guest 
                            WHERE transactNo = '$transactionNumber'";

                    $res1 = $conn->query($sql1);

                    if ($res1->num_rows > 0) {
                      while ($row = $res1->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['guestId']}</td>
                                <td>{$row['fName']}</td>
                                <td>{$row['lName']}</td>
                                <td>{$row['mName']}</td>
                                <td>{$row['suffix']}</td>
                                <td>{$row['birthdate']}</td>
                                <td>{$row['age']}</td>
                                <td>{$row['sex']}</td>
                                <td>{$row['nationality']}</td>
                                <td>{$row['contactNo']}</td>
                                <td>{$row['contactNo2']}</td>
                                <td>{$row['emailAdd']}</td>
                                <td>{$row['address']}</td>
                                <td>{$row['passportNo']}</td>
                                <td>{$row['passportExp']}</td>
                                <td>{$row['visaStatus']}</td>
                              </tr>";
                      }
                    } else {
                     echo "<tr><td colspan='100' style='text-align: center;'>No Guest found</td></tr>";

                    }
                  ?>
                </tbody>
             </table>
           </div>
        </div>
      </div>


      <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
       <!-- Request Table -->
       <div class="tab-wrapper">
        <div class="d-flex justify-content-end align-items-center p-3 mt-2">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#requestModal" 
        data-transaction-id="<?= $transactionNumber ?>">Add Request</button>
        
        </div>
        <div class="table-container p-3">
       <table class="product-table">
         <thead>
          <tr>
            <th>Request Id</th>
            <th>Request Title</th>
            <th>Request Details</th>
            <th>Request Date</th>
            <th>Status</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php
            $sql1= "SELECT *, DATE_FORMAT(requestDate, '%M %d, %Y %h:%i %p') AS requestDate
                    FROM request WHERE transactNo = '$transactionNumber'";

            $res1 = $conn->query($sql1);

            if ($res1->num_rows > 0) 
            {
              while ($row = $res1->fetch_assoc()) 
              {
                echo "<tr>
                        <td>{$row['requestId']}</td>
                        <td>{$row['concern']}</td>
                        <td>{$row['details']}</td>
                        <td>{$row['requestDate']}</td>
                        <td>{$row['requestStatus']}</td>
                      </tr>";
              }
            } 
            else 
            {
              echo "<tr><td colspan='10'>No Payment Found</td></tr>";
            }
          ?>
        </tbody>
      </table>
      </div>

       </div>

      </div>


       <!-- Payment History Table -->
      <div class="tab-pane fade" id="contact-tab-pane" role="tabpanel" aria-labelledby="contact-tab" tabindex="0">
        <div class="tab-wrapper">
          <div div class="d-flex justify-content-end align-items-center p-3 mt-2">
          <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#paymentModal<?= $transactionNumber ?>"
          data-transact-no="<?= $transactionNumber ?>" data-account-id="<?= $accountId ?>">Add Payment</button>

          </div>

          <div class="table-container p-3">
      <table class="product-table">
        <thead>
          <tr>
            <th>Payment Id</th>
            <th>Payment Title</th>
            <th>Payment Type</th>
            <th>Amount</th>
            <th>Proof of Payment</th>
            <th>Payment Date</th>
            <th>Payment Status</th>
            <th></th>
          </tr>
        </thead>
        <tbody>

        <?php
           $sql1 = "SELECT *, FORMAT(amount, 2) AS amount, DATE_FORMAT(paymentDate, '%M %d, %Y %h:%i %p') AS paymentDate 
                    FROM payment 
                    WHERE transactNo = '$transactionNumber'";

           $res1 = $conn->query($sql1);

           if ($res1->num_rows > 0) {
               while ($row = $res1->fetch_assoc()) {
                   echo "<tr>
                           <td>{$row['paymentId']}</td>
                           <td>{$row['paymentTitle']}</td>
                           <td>{$row['paymentType']}</td>
                           <td>₱ {$row['amount']}</td>
                           <td>
                               <a href='functions/view-file.php?file=" . urlencode($row['filePath']) . "' target='_blank'>View File</a> 
                               <a href='functions/download.php?file=" . urlencode($row['filePath']) . "' target='_blank'>Download File</a> 
                           </td>
                           <td>{$row['paymentDate']}</td>
                           <td>{$row['paymentStatus']}</td>
                         </tr>";
               }
           } else {
               echo "<tr><td colspan='7'>No Payment Found</td></tr>";
           }
           ?>

        </tbody>
      </table>
     </div>
    </div>
   </div>

  </div>
 </div>
</div>


  <?php require "../Agent Section/includes/scripts.php"; ?>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <!-- Attach Visa Requirements Modal -->
<div class="modal fade" id="visaModal" tabindex="-1" aria-labelledby="visaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="visaModalLabel">
                    Visa Requirements for Transaction No: <?php echo htmlspecialchars($_SESSION['transaction_number'] ?? ''); ?>
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="../Agent Section/functions/agent-addVisaRequirements-code.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <!-- Hidden input for transaction number -->
                    <input type="hidden" name="transaction_number" value="<?php echo htmlspecialchars($_SESSION['transaction_number'] ?? ''); ?>">

                    <?php
                    // Assuming you have a database connection established
                    $transactionNumber = $_SESSION['transaction_number'] ?? '';
                    $query1 = "SELECT guestId, CONCAT(
                        lName, ', ', fName, ' ', 
                        CASE WHEN mName = 'N/A' THEN '' ELSE CONCAT(SUBSTRING(mName, 1, 1), '.') END, ' ',
                        CASE WHEN suffix = 'N/A' THEN '' ELSE suffix END
                    ) AS `FULLNAME` FROM guest WHERE transactNo = '$transactionNumber'";

                    // Execute the query
                    $res1 = mysqli_query($conn, $query1);

                    if ($res1) {
                        // Count the number of guests
                        $guestCount = mysqli_num_rows($res1);

                        // Display the name and guestId for each guest inside input fields
                        while ($row = mysqli_fetch_assoc($res1)) {
                            $guestId = $row['guestId'];
                            $name = $row['FULLNAME'];

                            // Create input fields for each guest
                            echo "<div class='mb-3'>";
                            echo "<label for='guest-$guestId' class='form-label'>Guest ID: $guestId</label>";
                            echo "<input type='text' class='form-control' id='guest-$guestId' name='guestIds[]' value='$guestId' readonly>";

                            echo "<label for='name-$guestId' class='form-label'>Name</label>";
                            echo "<input type='text' class='form-control' id='name-$guestId' name='guestNames[]' value='$name' readonly>";

                            echo "<h6 class='form-label'>Visa Requirements</h6>";

                            echo "<label for='passport-$guestId' class='form-label'>Passport</label>";
                            echo "<input type='file' class='form-control' id='passport-$guestId' name='passports[]' />";

                            echo "<label for='permit-$guestId' class='form-label'>Permit</label>";

                            echo "<input type='file' class='form-control' id='permit-$guestId' name='permits[]' />";

                            echo "<label for='validId-$guestId' class='form-label'>Valid Id</label>";

                            echo "<input type='file' class='form-control' id='validId-$guestId' name='validIds[]' />";

                            echo "<label for='certificate-$guestId' class='form-label'>Certificate</label>";
                            echo "<input type='file' class='form-control' id='certificate-$guestId' name='certificates[]' />";

                            echo "<label for='guaranteedLetter-$guestId' class='form-label'>Guaranteed Letter</label>";
                            echo "<input type='file' class='form-control' id='guaranteedLetter-$guestId' name='guaranteedLetters[]' />";
                            echo "</div>";
                        }
                    } else {
                        echo "<p>Error: " . mysqli_error($conn) . "</p>";
                    }
                    ?>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="attachVisaRequirements" class="btn btn-primary">Submit</button>
                </div>

            </form>
        </div>
    </div>
</div>


<!-- FOR REQUEST SECTION -->

<!-- Modal for Request -->
<div class="modal fade" id="requestModal" tabindex="-1" aria-labelledby="requestModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="requestModalLabel">Request for Transaction</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="../Agent Section/functions/agent-transactionRequest-code.php" method="POST" id="requestForm">
          <div class="modal-body">
            <!-- Transaction Number Display -->
            <p><strong>Transaction No:</strong> <span id="requestTransactionId"></span></p>

            <!-- Hidden Input Fields -->
            <input type="hidden" name="transaction_number" id="transactionNumberInput">
            <input type="hidden" name="agentId" value="<?php echo $agentId; ?>">
            <input type="hidden" name="accountId" value="<?php echo $accountId; ?>">

            <!-- Request Type Selection -->
            <div class="mb-3">
              <select class="form-select mt-2" name="concern" id="concern" required>
                <option selected disabled>Select Request</option>
                <?php
                  $sql1 = mysqli_query($conn, "SELECT DISTINCT concernId, concernTitle FROM concern ORDER BY concernTitle ASC");
                  while($res1 = mysqli_fetch_array($sql1)) 
                  {
                    echo "<option value='{$res1['concernId']}'>{$res1['concernTitle']}</option>";
                  }
                ?>
              </select>
            </div>

            <!-- Request Details Selection -->
            <div class="mb-3" id="additionalSelectContainer" style="display: none;">
              <select class="form-select mt-2" name="requestDetails" id="requestDetails" required>
                <option selected disabled>Select Specific Detail</option>
              </select>
              <label value="0.00">₱ <input type="text" id="price" name="price" value="0.00" style="border: none; background: transparent; padding: 5px 10px; font-size: 14px; display: inline-block; width: auto;" readonly></label>
            </div>

            <!-- Pax Input -->
            <div class="mb-3">
              <label class="form-label">Pax</label>
              <input type="number" class="form-control" id="paxRequest" name="pax" placeholder="Enter pax" min="1" required>
            </div>

            <!-- Details Input -->
            <div class="mb-3">
              <label class="form-label">Details</label>
              <textarea class="form-control" name="details" placeholder="Enter Specific Message" rows="4"></textarea>
            </div>

            <label>₱ <span id="displayTotalPrice">0.00</span></label>
            <input type="hidden" name="totalPrice" id="TotalPrice" value="0.00" style="border: none; background: transparent; padding: 5px 10px; font-size: 14px; display: inline-block; width: auto;" readonly>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" name="request" class="btn btn-primary">Send Request</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
document.addEventListener('DOMContentLoaded', function () {
  // Get the modal element
  const requestModal = document.getElementById('requestModal');

  if (requestModal) {
    // Add event listener for when the modal is shown
    requestModal.addEventListener('show.bs.modal', function (event) {
      const button = event.relatedTarget; // Button that triggered the modal

      if (button) {
        const transactionId = button.getAttribute('data-transaction-id'); // Fetch transaction ID

        // Reset the form to clear any previous data
        const form = document.getElementById('requestForm');
        if (form) form.reset();

        // Hide the 'additionalSelectContainer' if it exists
        const additionalSelectContainer = document.getElementById('additionalSelectContainer');
        if (additionalSelectContainer) additionalSelectContainer.style.display = 'none';

        // Populate the hidden input field specific to the request form
        const transactionInput = document.querySelector('#requestForm input[name="transaction_number"]');
        if (transactionInput) transactionInput.value = transactionId;

        // Display the transaction ID in the modal
        const transactionIdDisplay = document.getElementById('requestTransactionId');
        if (transactionIdDisplay) transactionIdDisplay.textContent = transactionId;

        // Call the function to fetch Pax for the transaction ID
        if (typeof fetchPaxForRequestModal === 'function') {
          fetchPaxForRequestModal(transactionId);
        }
      }
    });
  }
});

// Function to fetch pax for the request modal
function fetchPaxForRequestModal(transactionId) 
    {
      fetch('../Agent Section/functions/getBookingDetails.php', 
      {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ transaction_id: transactionId }),
      })
      .then(response => response.json())
      .then(data => 
      {
        if (data.success) 
        {
          // Populate only the pax field with the fetched data
          const paxInput = document.querySelector('input[name="pax"]');
          paxInput.setAttribute('max', data.booking.pax);
          
          // Ensure the current value is valid in case it exceeds the max
          validateMaxValue(paxInput);
        } 
        else 
        {
          console.error('Error fetching booking details:', data.message);
        }
      })
      .catch(error => 
      {
        console.error('Fetch error:', error);
      });
    }

    // Function to validate the max value of the input
    function validateMaxValue(input) 
    {
      const max = parseInt(input.getAttribute("max"));
      const currentValue = parseInt(input.value);
      
      if (currentValue > max) 
      {
        input.value = max; // Set the value to the max if it exceeds
      }
    }

  </script>








<!-- FOR PAYMENT SECTION -->

<!-- Modal -->
<div class="modal fade" id="paymentModal<?= $transactionNumber ?>" tabindex="-1" aria-labelledby="paymentModalLabel<?= $transactionNumber ?>" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalLabel<?= $transactionNumber ?>">Payment for Transaction #<?= $transactionNumber ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="../Agent Section/functions/agent-transactionPayment-code.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="transactionNumber" value="<?= $transactionNumber ?>">
                    <input type="hidden" name="accountId" value="<?= $accountId ?>">

                    <div class="mb-3">
                        <label class="form-label">Payment for:</label>
                        <select class="form-select" name="paymentTitle" required>
                            <option selected disabled>Select Payment Title</option>
                            <option value="Package Payment">Package Payment</option>
                            <option value="Request Payment">Request Payment</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Payment Type</label>
                        <select class="form-select" name="paymentType" required>
                            <option selected disabled>Select Payment Type</option>
                            <option value="Downpayment">Downpayment</option>
                            <option value="Partial Payment">Partial Payment</option>
                            <option value="Full Payment">Full Payment</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Payment Amount</label>
                        <input type="number" class="form-control" name="amount" placeholder="Enter payment Amount" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Proof of Payment</label>
                        <div class="mb-3">
                            <input type="file" class="form-control" name="proofs[]" accept="image/*,application/pdf" multiple>
                        </div>
                        <!-- List of file names -->
                        <ul id="fileList<?= $transactionNumber ?>" class="list-unstyled mt-2"></ul>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="payment" class="btn btn-primary">Submit payment</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
  document.addEventListener('DOMContentLoaded', function () {
    // Target all buttons that trigger a modal
    const paymentModals = document.querySelectorAll('[data-bs-toggle="modal"]');
    
    paymentModals.forEach(button => {
        button.addEventListener('click', function () {
            const transactionNumber = button.getAttribute('data-transact-no');
            const accountId = button.getAttribute('data-account-id');

            // Target the modal associated with this transaction
            const modal = document.getElementById(`paymentModal${transactionNumber}`);

            // Set the hidden input fields with the correct transaction data
            modal.querySelector('[name="transactionNumber"]').value = transactionNumber;
            modal.querySelector('[name="accountId"]').value = accountId;

            // Show the modal
            const bootstrapModal = new bootstrap.Modal(modal);
            bootstrapModal.show();
        });
    });
});
</script>

<script>
   const maxFiles = 5;
   const maxFileSize = 4 * 1024 * 1024; // 4MB
   let selectedFiles = {};

   document.querySelectorAll('.drop-zone').forEach(dropZone => 
   {
     dropZone.addEventListener("click", function() 
     {
       const transactNo = this.id.replace('dropZone', ''); // Extract transactNo
       document.getElementById('fileInput' + transactNo).click();
     });
   });

   function handleDrop(event, transactNo) 
   {
     event.preventDefault();
     handleFiles(event.dataTransfer.files, transactNo);
   }

   function handleFiles(files, transactNo) 
   {
     const fileList = document.getElementById("fileList" + transactNo);
     selectedFiles[transactNo] = selectedFiles[transactNo] || [];

     if (selectedFiles[transactNo].length + files.length > maxFiles) 
     {
       alert(`You can upload a maximum of ${maxFiles} files.`);
       return;
     }

     Array.from(files).forEach(file => 
     {
       if (file.size > maxFileSize) 
       {
         alert(`File ${file.name} exceeds the 4MB limit and won't be added.`);
       } 
       else 
       {
         selectedFiles[transactNo].push(file);

         // Debugging: Log the file and the selectedFiles array
         console.log(`File added: ${file.name}, Size: ${(file.size / 1024 / 1024).toFixed(2)} MB`);
         console.log(selectedFiles[transactNo]);

         // Create a list item for the file
         const listItem = document.createElement("li");
         listItem.classList.add("file-item");
         listItem.textContent = `${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)`;

         // Add remove button
         const removeButton = document.createElement("button");
         removeButton.textContent = "Remove";
         removeButton.classList.add("btn", "btn-danger", "btn-sm", "ml-2");
         removeButton.onclick = () => removeFile(file, transactNo);

         listItem.appendChild(removeButton);
         fileList.appendChild(listItem);
       }
     });

     updateFileInput(transactNo);
   }

   function removeFile(file, transactNo) 
   {
     const index = selectedFiles[transactNo].indexOf(file);
     if (index > -1) 
     {
       selectedFiles[transactNo].splice(index, 1); // Remove file from selectedFiles
     }

     // Remove the list item from the DOM
     const fileList = document.getElementById("fileList" + transactNo);
     const listItem = fileList.querySelector(`li:contains('${file.name}')`);
     if (listItem) 
     {
       fileList.removeChild(listItem);
     }

     updateFileInput(transactNo);
   }

   function updateFileInput(transactNo) 
   {
     const dataTransfer = new DataTransfer();
     selectedFiles[transactNo].forEach(file => dataTransfer.items.add(file));

     const fileInput = document.getElementById('fileInput' + transactNo);
     fileInput.files = dataTransfer.files;

     // Debugging: Log updated file input
     console.log(fileInput.files);
   }

 </script>

 <style>
   .drop-zone {
     cursor: pointer;
     background-color: #f8f9fa;
     min-height: 100px;
     display: flex;
     align-items: center;
     justify-content: center;
   }
 </style>














 </body>
</html>
