



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<!-- Bootstrap 5.3 tooltip initialization -->
<script>
 // Enable tooltips for all elements with the 'data-bs-toggle="tooltip"' attribute
 document.addEventListener('DOMContentLoaded', function () {
     var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
     var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
         return new bootstrap.Tooltip(tooltipTriggerEl);
     });
 });
</script>