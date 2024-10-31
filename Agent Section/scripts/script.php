<script>
  function updateDate() {
     const options = { weekday: 'short', year: 'numeric', month: 'long', day: 'numeric' };
     const currentDate = new Date().toLocaleDateString('en-US', options);
     document.getElementById('current-date').textContent = currentDate;
 }

 // Update the date every second (1000 milliseconds)
 setInterval(updateDate, 1000);

 updateDate();
</script>


<script>
const toggleBtn = document.getElementById('toggleBtn');
const sidebar = document.getElementById('sidebar');
const mainContent = document.getElementById('mainContent');

// Initialize sidebar to be visible by default, if required
sidebar.classList.remove('hidden');
mainContent.classList.add('active');

toggleBtn.addEventListener('click', function () {
    sidebar.classList.toggle('hidden'); // Toggle the hidden class on the sidebar
    mainContent.classList.toggle('active'); // Toggle the active class on main content

    // Change the icon based on the sidebar state
    if (sidebar.classList.contains('hidden')) {
        toggleBtn.innerHTML = '<i class="fas fa-bars"></i>'; // Change to hamburger icon
    } else {
        toggleBtn.innerHTML = '<i class="fas fa-times"></i>'; // Change to X icon
    }
});


</script>


<script>
 document.getElementById('profileButton').addEventListener('click', function () {
   const dropdownMenu = document.getElementById('dropdownMenu');
   dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
 });

  // Close the dropdown if the user clicks outside of it
  window.onclick = function(event) {
    if (!event.target.matches('.dropbtn')) {
        const dropdowns = document.getElementsByClassName("dropdown-content");
        for (let i = 0; i < dropdowns.length; i++) {
            const openDropdown = dropdowns[i];
            if (openDropdown.style.display === 'block') {
                openDropdown.style.display = 'none';
            }
        }
    }
  }
</script>


<script>
  // Get all buttons
  // script.js

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
            }, 100);
        });
    });
});




</script>