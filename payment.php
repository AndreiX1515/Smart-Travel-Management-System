<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Selection</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

  <div class="container mt-5">
    <h1>Select Payment Method</h1>
    <div class="form-group">
      <label for="paymentMethod">Choose a payment method:</label>
      <select id="paymentMethod" class="form-control" onchange="showModal(this.value)">
        <option selected disabled>Select Payment Method</option>
        <option value="bank">Bank Transfer</option>
        <option value="gcash">GCash</option>
      </select>
    </div>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="paymentModalLabel">Payment Details</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="paymentForm">
            <div class="form-group">
              <label for="accountName">Account Name</label>
              <input type="text" class="form-control" id="accountName" required>
            </div>
            <div class="form-group">
              <label for="accountNumber">Account Number</label>
              <input type="text" class="form-control" id="accountNumber" required>
            </div>
            <div class="form-group">
              <label for="amount">Amount</label>
              <input type="number" class="form-control" id="amount" required>
            </div>
            <div class="form-group">
              <label for="paymentImage">Upload Payment Image</label>
              <input type="file" class="form-control-file" id="paymentImage" accept="image/*" required>
              <small class="form-text text-muted">Please upload an image of your payment receipt.</small>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" onclick="submitPayment()">Submit Payment</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS and dependencies -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

  <script>
    function showModal(paymentMethod) 
    {
      if (paymentMethod) 
      {
        $('#paymentModal').modal('show');
        // Optional: Change the modal title based on payment method
        $('#paymentModalLabel').text(paymentMethod === 'bank' ? 'Bank Transfer Details' : 'GCash Details');
      }
    }

    function submitPayment() 
    {
      const form = document.getElementById('paymentForm');
      if (form.checkValidity()) 
      {
        // Handle the form submission
        alert('Payment details submitted successfully!');
        $('#paymentModal').modal('hide');
        form.reset();
      } 
      else 
      {
        form.reportValidity();
      }
    }
  </script>

</body>
</html>
