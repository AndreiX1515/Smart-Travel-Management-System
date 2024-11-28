<script src="https://unpkg.com/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


<!-- Font Awesome for Icons -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<!-- DataTables CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/datatables.net-dt/css/jquery.dataTables.min.css">

<!-- DataTables JS -->
<script type="text/javascript" charset="utf8" src="https://cdn.jsdelivr.net/npm/datatables.net/js/jquery.dataTables.min.js"></script>




<!-- Bootstrap 5.3 Tooltip Initialization -->
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