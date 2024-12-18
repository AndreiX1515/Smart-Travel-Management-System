
<!-- Bootstrap Bundle (includes Popper.js for Bootstrap components) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
        onload="console.log('Bootstrap loaded successfully.')"
        onerror="console.error('Failed to load Bootstrap.')"></script>

<!-- DataTables JS (requires jQuery) -->
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js" 
        onload="console.log('DataTables loaded successfully.')"
        onerror="console.error('Failed to load DataTables.')"></script>

<!-- DataTables FixedColumns JS (requires DataTables) -->
<script src="https://cdn.datatables.net/fixedcolumns/3.3.0/js/dataTables.fixedColumns.min.js" 
        onload="console.log('DataTables FixedColumns loaded successfully.')"
        onerror="console.error('Failed to load DataTables FixedColumns.')"></script>

<!-- DataTables FixedHeader JS (requires DataTables) -->
<script src="https://cdn.datatables.net/fixedheader/3.2.0/js/dataTables.fixedHeader.min.js" 
        onload="console.log('DataTables FixedHeader loaded successfully.')"
        onerror="console.error('Failed to load DataTables FixedHeader.')"></script>


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