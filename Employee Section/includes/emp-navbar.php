<nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container-fluid mx-3">
        <a class="navbar-brand text-white fw-bold" id="page-title" href="#"></a>

        <!-- Navbar items and functionality can be added here -->
    </div>
</nav>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    // Check if there's a saved title in local storage
    const savedTitle = localStorage.getItem('pageTitle');
    if (savedTitle) {
        document.getElementById('page-title').textContent = savedTitle;
    }

    const buttons = document.querySelectorAll('.page-button');
    buttons.forEach(button => {
        button.addEventListener('click', (event) => {
            event.preventDefault();
            const newPageName = button.getAttribute('data-page-name');
            document.getElementById('page-title').textContent = newPageName;

            // Save the title to local storage
            localStorage.setItem('pageTitle', newPageName);

            const newUrl = button.getAttribute('href');
            setTimeout(() => {
                window.location.href = newUrl;
            }, 25);
        });
    });
});
</script>