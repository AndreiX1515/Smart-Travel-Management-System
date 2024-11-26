<?php
  // Check if 'id' is passed in the URL
  if (isset($_GET['id'])) 
  {
    $transactionNumber = htmlspecialchars($_GET['id']);
  } 
?>

<!-- Request Table -->
<div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab" tabindex="0">
    <div class="d-flex justify-content-end align-items-center p-3">
      <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#requestModal" 
      data-transaction-id="<?= $transactionNumber ?>">Add Request</button>
    </div>
    <div class="table-container">
      <table class="product-table">
        <thead>
          <tr>
            <th>REQUEST ID</th>
            <th>REQUEST TITLE</th>
            <th>REQUEST DETAILS</th>
            <th>REQUEST DATE</th>
            <th>STATUS</th>
          </tr>
        </thead>
        <tbody>
          <?php
            $sql1 = "SELECT request.requestId, concern.concernTitle, concerndetails.details, 
                        DATE_FORMAT(request.requestDate, '%M %d, %Y %h:%i %p') AS formattedRequestDate, 
                        request.requestStatus
                      FROM request
                      JOIN concern ON request.concernId = concern.concernId
                      JOIN concerndetails ON request.concernDetailsId = concerndetails.concernDetailsId
                      WHERE request.transactNo = '$transactionNumber'";

            $res1 = $conn->query($sql1);

            if ($res1->num_rows > 0) 
            {
              while ($row = $res1->fetch_assoc()) 
              {
               
                 // Fetch the status from the database
                 $status = $row['requestStatus']; // Ensure 'requestStatus' exists in the database row

                 // Assign a corresponding Bootstrap badge class based on the status
                 $badgeClass = '';

                 switch ($status) {
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
                         $badgeClass = 'text-bg-info'; // Blue for any other status
                         break;
                 }
    
                echo "<tr>
                        <td>{$row['requestId']}</td>
                        <td>{$row['concernTitle']}</td>
                        <td>{$row['details']}</td>
                        <td>{$row['formattedRequestDate']}</td>
                        <td>
                          <span class='badge rounded-pill {$badgeClass}'>{$status}</span>
                        </td>
                      </tr>";
              }
            }
            else 
            {
              echo "<tr><td colspan='100' style='text-align: center;'>No Payment Found</td></tr>";
            }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

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
  document.addEventListener('DOMContentLoaded', function() 
  {
    const requestModal = document.getElementById('requestModal');
    if (requestModal) 
    {
      requestModal.addEventListener('show.bs.modal', function (event) 
      {
        requestModal.setAttribute('aria-hidden', 'false'); // Remove hidden status
        const button = event.relatedTarget;
        if (button) 
        {
          const transactionId = button.getAttribute('data-transaction-id');

          // Reset the form and hide additional selects
          const form = document.getElementById('requestForm');
          if (form) form.reset();
          const additionalSelectContainer = document.getElementById('additionalSelectContainer');
          if (additionalSelectContainer) additionalSelectContainer.style.display = 'none';

          // Set transaction number in the form and modal display
          const transactionInput = document.querySelector('#requestForm input[name="transaction_number"]');
          if (transactionInput) transactionInput.value = transactionId;
          const transactionIdDisplay = document.getElementById('requestTransactionId');
          if (transactionIdDisplay) transactionIdDisplay.textContent = transactionId;

          // Fetch pax for the given transaction ID
          $.ajax(
          {
            url: '../Agent Section/functions/fetchPaxPerBooking.php', // Replace with the correct server-side script URL
            type: 'POST',
            data: { transactNo: transactionId },
            success: function (response) {
              try {
                const data = JSON.parse(response);
                if (data && data.pax) 
                {
                  const maxPax = parseInt(data.pax, 10);
                  const paxRequestInput = document.getElementById('paxRequest');
                  if (paxRequestInput) {
                    paxRequestInput.setAttribute('max', maxPax); // Set max attribute
                    paxRequestInput.setAttribute('placeholder', `Enter pax (max ${maxPax})`); // Update placeholder
                  }
                } else {
                  console.error('No pax data received.');
                }
              } catch (error) {
                console.error('Error parsing pax response:', error);
              }
            },
            error: function (xhr, status, error) {
              console.error('Error fetching pax data:', error);
            }
          });

          // Optional: Fetch other related data (if needed)
          if (typeof fetchPaxForRequestModal === 'function') {
            fetchPaxForRequestModal(transactionId);
          }
        }
      });

      requestModal.addEventListener('hide.bs.modal', function () 
      {
        requestModal.setAttribute('aria-hidden', 'true'); // Reapply hidden status
      });
    }


    $(document).ready(function() 
    {
      $('#concern').on('change', function() 
      {
        var concernId = $(this).val();
        $('#requestDetails').html('<option selected disabled>Select Specific Detail</option>');
        $('#price').val('');
        $('#additionalSelectContainer').hide();
        $('#additionalDetails').html('<option selected disabled>Select Additional Detail</option>');
        if (concernId) 
        {
          $.ajax(
          {
            url: '../Agent Section/functions/fetchConcernDetails.php',
            type: 'POST',
            data: { concernId: concernId },
            success: function(response) 
            {
              try 
              {
                var data = JSON.parse(response);
                $('#additionalSelectContainer').show();
                if (Array.isArray(data.detailsData)) {
                  data.detailsData.forEach(function(item) 
                  {
                    var option = $('<option>').val(item.id).text(item.title).data('price', item.price);
                    $('#requestDetails').append(option);
                  });
                }
              } 
              catch (e) 
              {
                console.error("Error parsing JSON response: ", e);
              }
            },
            error: function(xhr, status, error) 
            {
              console.error("Error fetching additional details:", error);
            }
          });
        } 
        else 
        {
          $('#additionalSelectContainer').hide();
          $('#additionalDetails').html('<option selected disabled>Select Additional Detail</option>');
        }
      });

      $('#requestDetails').on('change', function() 
      {
        var price = $(this).find('option:selected').data('price');
        $('#price').val(price);
        calculateTotalPrice();
      });

      $('#paxRequest').on('input', function() 
      {
        calculateTotalPrice();
      });

      function calculateTotalPrice() 
      {
        var price = parseFloat($('#price').val().replace(/,/g, '')) || 0;
        var pax = parseInt($('#paxRequest').val()) || 0;
        var totalPrice = pax * price;
        $('#displayTotalPrice').text(formatNumberWithCommas(totalPrice.toFixed(2)));
        $('#TotalPrice').val(totalPrice.toFixed(2));
      }

      document.getElementById('paxRequest').addEventListener('input', function() 
      {
        validateMaxValue(this);
      });

      function formatNumberWithCommas(num) 
      {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
      }

      function fetchPaxForRequestModal(transactionId) 
      {
        fetch('../Agent Section/functions/getBookingDetails.php', 
        {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ transaction_id: transactionId })
        })
        .then(response => response.json())
        .then(data => 
        {
          if (data.success) 
          {
            const paxInput = document.querySelector('input[name="pax"]');
            paxInput.setAttribute('max', data.booking.pax);
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

      function validateMaxValue(input) 
      {
        const max = parseInt(input.getAttribute("max"));
        const currentValue = parseInt(input.value);
        if (currentValue > max) input.value = max;
      }
    });
  });
</script>
