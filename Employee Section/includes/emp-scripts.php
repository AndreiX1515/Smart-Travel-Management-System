


<!-- <script>
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
</script> -->

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>