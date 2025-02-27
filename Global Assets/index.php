<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bootstrap Toast Example</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

    <!-- Include Global Toast -->
    <?php include '../Global Assets/toast/script/toast.php'; ?>

    <button onclick="showToast('🎉 This is a success message!', 'success')">Show Success Toast</button>
    <button onclick="showToast('⚠️ Warning message!', 'warning')">Show Warning Toast</button>
    <button onclick="showToast('❌ Error occurred!', 'danger')">Show Error Toast</button>

    <!-- Bootstrap & Custom Script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../Global Assets/toast/script/toast.php"></script>

    <script src="../Global Assets/toast/js/toast.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            showToast("🎉 Welcome! This toast appears on page load.", "success"); // Auto show on load
        });
    </script>

</body>
</html>
