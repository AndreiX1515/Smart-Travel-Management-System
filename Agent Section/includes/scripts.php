<!-- Bootstrap 5.3.0 Bundle (Includes Popper.js for Bootstrap components) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables (Core JS) -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
    // Check if Bootstrap and DataTables are loaded correctly
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof bootstrap !== 'undefined') {
            console.log('Bootstrap 5 is working!');
        } else {
            console.log('Bootstrap 5 failed to load.');
        }

        if (typeof $.fn.dataTable !== 'undefined') {
            console.log('DataTables is working!');
        } else {
            console.log('DataTables failed to load.');
        }
    });
</script>



<script>
 sidebar.classList.remove('hidden');
 mainContent.classList.add('active');
</script>