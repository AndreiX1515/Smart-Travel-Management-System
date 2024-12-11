<!-- Font Awesome for Icons -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">

<!-- Bootstrap Bundle (requires Popper.js if needed) -->
<script src="https://unpkg.com/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- jQuery Core (needed for other libraries like DataTables) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- jQuery UI (depends on jQuery) -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>




<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/fixedcolumns/3.3.0/css/fixedColumns.dataTables.min.css"> 

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>

<!-- DataTables FixedColumns JS -->
<script src="https://cdn.datatables.net/fixedcolumns/3.3.0/js/dataTables.fixedColumns.min.js"></script>

<!-- DataTables FixedHeader JS -->
<script src="https://cdn.datatables.net/fixedheader/3.2.0/js/dataTables.fixedHeader.min.js"></script>




<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Enable tooltips for all elements with the 'data-bs-toggle="tooltip"' attribute
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>


<script>
document.addEventListener("DOMContentLoaded", function () {
  const rows = document.querySelectorAll(".table-wrapper tbody tr");

  rows.forEach((row) => {
    row.addEventListener("click", () => {
      const targetUrl = row.getAttribute("data-href");
      if (targetUrl) {
        window.location.href = targetUrl; // Redirects to the target URL
      }
    });
  });
});
</script>