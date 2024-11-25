<?php
  // Check if 'id' is passed in the URL
  if (isset($_GET['id'])) 
  {
    $transactionNumber = htmlspecialchars($_GET['id']);
  } 
?>

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

            if ($res1->num_rows > 0) 
            {
              while ($row = $res1->fetch_assoc())
                {
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
            } 
            else 
            {
              echo "<tr><td colspan='7'>No Payment Found</td></tr>";
            }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal" id="paymentModal<?= $transactionNumber ?>" tabindex="-1" aria-labelledby="paymentModalLabel<?= $transactionNumber ?>" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="paymentModalLabel<?= $transactionNumber ?>">Payment for Transaction #<?= $transactionNumber ?></h5>
        <button type="button" class="btn-close custom-close" aria-label="Close" data-modal-id="paymentModal<?= $transactionNumber ?>"></button>
      </div>
      <form action="../Agent Section/functions/agent-transactionPayment-code.php" method="POST" enctype="multipart/form-data">
        <div class="modal-body">
          <!-- Hidden Inputs -->
          <input type="hidden" name="transactionNumber" value="<?= $transactionNumber ?>">
          <input type="hidden" name="accountId" value="<?= $accountId ?>">

          <!-- Payment Title -->
          <div class="mb-3">
            <label class="form-label" for="paymentTitle<?= $transactionNumber ?>">Payment for:</label>
            <select class="form-select" id="paymentTitle<?= $transactionNumber ?>" name="paymentTitle" required>
              <option selected disabled>Select Payment Title</option>
              <option value="Package Payment">Package Payment</option>
              <option value="Request Payment">Request Payment</option>
            </select>
          </div>

          <!-- Payment Type -->
          <div class="mb-3">
            <label class="form-label" for="paymentType<?= $transactionNumber ?>">Payment Type</label>
            <select class="form-select" id="paymentType<?= $transactionNumber ?>" name="paymentType" required>
              <option selected disabled>Select Payment Type</option>
              <option value="Downpayment">Downpayment</option>
              <option value="Partial Payment">Partial Payment</option>
              <option value="Full Payment">Full Payment</option>
            </select>
          </div>

          <!-- Amount Display -->
          <div id="amountDisplay<?= $transactionNumber ?>">
            <p>Total Amount Left: ₱ <span id="amountValue<?= $transactionNumber ?>">0.00</span></p>
            <span id="amountStatus<?= $transactionNumber ?>"></span>
            <span id="requestAmountStatus<?= $transactionNumber ?>"></span>
          </div>

          <!-- Payment Amount -->
          <div class="mb-3">
            <label class="form-label" for="paymentAmount<?= $transactionNumber ?>">Payment Amount</label>
            <input type="number" step="0.01" class="form-control" id="paymentAmount<?= $transactionNumber ?>" name="amount" placeholder="Enter payment amount" min="1" required>
          </div>

          <!-- Proof of Payment -->
          <div class="mb-3">
            <label class="form-label" for="proofs<?= $transactionNumber ?>">Proof of Payment</label>
            <input type="file" class="form-control" id="proofs<?= $transactionNumber ?>" name="proofs[]" accept="image/*,application/pdf" multiple>
            <ul id="fileList<?= $transactionNumber ?>" class="list-unstyled mt-2"></ul>
          </div>
        </div>

        <!-- Modal Footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary custom-close" data-modal-id="paymentModal<?= $transactionNumber ?>">Close</button>
          <button type="submit" name="payment" class="btn btn-primary">Submit payment</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  // Custom JavaScript for handling modal close and removing backdrop
  $(document).ready(function () {
    // Handle close button click with custom behavior
    $(".custom-close").on("click", function () {
      const modalId = $(this).data("modal-id");
      $(`#${modalId}`).modal("hide");
    });

    // Ensure backdrops are removed when the modal is hidden
    $("#paymentModal<?= $transactionNumber ?>").on("hidden.bs.modal", function () {
      $(".modal-backdrop").remove();
    });
  });
</script>


<script>
  // Custom JavaScript to handle modal close functionality
  document.addEventListener("DOMContentLoaded", () => {
    const closeButtons = document.querySelectorAll(".custom-close");

    closeButtons.forEach(button => {
      button.addEventListener("click", function () {
        const modalId = this.getAttribute("data-modal-id");
        const modalElement = document.getElementById(modalId);

        if (modalElement) {
          // Use Bootstrap's modal('hide') to close the modal properly
          $(`#${modalId}`).modal('hide');
        }
      });
    });
  });
</script>




<script>
  document.addEventListener('DOMContentLoaded', function () 
  {
    // Target all buttons that trigger a modal
    const paymentModals = document.querySelectorAll('[data-bs-toggle="modal"]');
    
    paymentModals.forEach(button => 
    {
      button.addEventListener('click', function () 
      {
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

<script>
  $(document).ready(function() 
  {
    $('#paymentTitle').on('change', function () 
    {
      var paymentTitle = $(this).val(); // Get the selected payment title
      var transactionNumber = $('input[name="transactionNumber"]').val(); // Get the transaction number

      // Reset all fields
      $('#amountValue').text('0.00'); // Reset amount display
      $('#amountStatus').text(''); // Reset package payment status
      $('#requestAmountStatus').text(''); // Reset request payment status

      if (paymentTitle) 
      {
        // Make the AJAX request
        $.ajax(
        {
          url: '../Agent Section/functions/fetchPaymentBalance.php', // Your server-side script
          type: 'POST',
          data: {
              paymentTitle: paymentTitle,
              transactionNumber: transactionNumber
          },
          success: function (response) 
          {
            var data = null;

            // Parse the JSON response
            try {
                data = JSON.parse(response);
            } catch (e) {
                console.error("Error parsing JSON response: ", e);
                $('#amountStatus').text('Error retrieving data');
                $('#requestAmountStatus').text('Error retrieving data');
                return;
            }

            // Check if amountLeft is defined and valid
            if (data && typeof data.amountLeft !== 'undefined') 
            {
              var amountLeft = parseFloat(data.amountLeft || 0); // Default to 0
              var packageMessage = data.packageMessage || ''; // Package payment status message
              var requestMessage = data.requestMessage || ''; // Request payment status message

              // Update the amount display
              $('#amountValue').text(
                amountLeft.toLocaleString('en-US', 
                {
                  minimumFractionDigits: 2,
                  maximumFractionDigits: 2
                })
              );

              // Update status based on the payment title
              if (paymentTitle === 'Package Payment') 
              {
                $('#amountStatus').text(packageMessage); // Show package payment message
                $('#requestAmountStatus').text(''); // Clear request status
              } 
              else if (paymentTitle === 'Request Payment') 
              {
                if (requestMessage === "No confirmed requests found.") 
                {
                  // If no confirmed requests exist, show this message
                  $('#requestAmountStatus').text(requestMessage); // Show the no requests message
                  $('#amountStatus').text(''); // Clear package status
                } 
                else 
                {
                  $('#requestAmountStatus').text(requestMessage); // Show request payment message
                  $('#amountStatus').text(''); // Clear package status
                }
              }

              // Enable the payment input only if there is a balance
              if (amountLeft > 0) 
              {
                $('#paymentAmount').attr('max', amountLeft.toFixed(2)); // Set max value

                // Ensure entered amount doesn't exceed the max
                $('input[name="amount"]').on('input', function () 
                {
                  var enteredAmount = parseFloat($(this).val());
                  if (enteredAmount > amountLeft) 
                  {
                    $(this).val(amountLeft.toFixed(2)); // Cap input value
                  }
                });
              } 
            } 
            else 
            {
              // Handle missing or invalid amountLeft
              console.error("Invalid response: amountLeft is missing");
              $('#amountStatus').text('Error retrieving payment balance');
              $('#requestAmountStatus').text('Error retrieving payment balance');
            }
          },
          error: function (xhr, status, error) 
          {
            console.error("Error fetching payment details:", error);
            $('#amountValue').text('0.00'); // Reset amount display
            $('#amountStatus').text('Error retrieving payment status');
            $('#requestAmountStatus').text('Error retrieving payment status');
          }
        });
      } 
      else 
      {
        // Reset if no payment title selected
        $('#amountValue').text('0.00');
        $('#amountStatus').text('');
        $('#requestAmountStatus').text('');
      }
    });
  });
</script>

