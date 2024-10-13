// heartbeat.js
let heartbeatInterval = setInterval(() => {
    fetch('heartbeat.php', {
        method: 'POST',
        credentials: 'include'
    })
    .then(response => response.json())
    .then(data => {
        if (data.status !== 'OK') {
            // Session expired or invalid session
            alert('Your session has expired. Please log in again.');
            window.location.href = 'login.php';
        }
    })
    .catch(error => {
        console.error('Heartbeat error:', error);
    });
}, 20 * 1000); // Every 20 seconds

// Flags to track user actions
let isLeaving = false;
let isReloading = false;

// Detect when the user is reloading or navigating away
window.addEventListener("beforeunload", function (event) {
    if (isReloading || isLeaving) {
        // Allow reload or navigation without showing the confirmation dialog
        return;
    } else {
        // Prevent session destroy if it's a normal reload or navigation
        const confirmationMessage = "All unsaved data will be lost. Do you really want to leave?";
        event.returnValue = confirmationMessage; // Show confirmation dialog
        return confirmationMessage;
    }
});

// Detect when the tab is being closed
window.addEventListener("unload", function () {
    if (!isReloading && !isLeaving) {
        // Destroy the session only when the tab is being closed
        fetch('client-logout.php', {
            method: 'POST',
            credentials: 'include' // Send the session cookie
        });
    }
});

// Mark reload event when the page is being reloaded
window.addEventListener('keydown', (event) => {
    if (event.key === 'F5' || (event.ctrlKey && event.key === 'r')) {
        isReloading = true; // Set flag when reload is triggered via keyboard
    }
});

// Detect when the user clicks on the refresh button or reloads using the mouse
window.addEventListener('mousedown', (event) => {
    if (event.button === 1) { // Middle mouse button click (refresh)
        isReloading = true;
    }
});

// Detect when the user clicks on a link or button (i.e., they are navigating away)
document.querySelectorAll('a, button').forEach(item => {
    item.addEventListener('click', () => {
        isLeaving = true; // Set the flag when a link or button is clicked
    });
});














        // let isReloading = false;
        // let isLeaving = false;

        // // Detect page reload or tab close
        // window.addEventListener("beforeunload", function (event) {
        //     // Detect if page is being reloaded or navigated away
        //     if (isReloading) {
        //         return; // Do nothing for reload
        //     } else if (!isReloading && !isLeaving) {
        //         // If the session has timed out, call the client logout
        //         checkSessionTimeout().then(isTimedOut => {
        //             if (isTimedOut) {
        //                 navigator.sendBeacon('client-logout.php'); // Trigger client-logout.php to destroy session
        //             }
        //         });
        //     }
        // });

        // // Function to check if the session is timed out
        // function checkSessionTimeout() {
        //     return fetch('session_validate.php', {
        //         method: 'GET',
        //         credentials: 'include'
        //     })
        //     .then(response => response.json())
        //     .then(data => {
        //         console.log('Response data:', data); // Log the response data to the console
        //         return data.status === 'expired'; // Assuming the response indicates the session status
        //     })
        //     .catch(error => {
        //         console.error('Error checking session timeout:', error);
        //         return false; // Default to not expired on error
        //     });
        // }

        // // Detect manual logout button click or navigation
        // document.querySelectorAll('a, button').forEach(item => {
        //     item.addEventListener('click', () => {
        //         isLeaving = true; // Mark that the user is navigating away
        //     });
        // });

        // // Detect reload action
        // window.addEventListener("load", () => {
        //     isReloading = true;
        // });

