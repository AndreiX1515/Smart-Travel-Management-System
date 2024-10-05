$(document).ready(function() {
    $('#sendOtpLink').on('click', function(e) {
        e.preventDefault(); // Prevent the default anchor behavior
        let emailField = $('#floatingEmail');
        let usernameField = $('#floatingUsername');
        let passwordField = $('#floatingPassword');
        let cpasswordField = $('#floatingPassword2');
        let fnameField = $('#floatingFirstName');
        let lnameField = $('#floatingLastName');
        let mnameField = $('#floatingMiddleName');
        let email = emailField.val();
        let username = usernameField.val();
        let password = passwordField.val();
        let cpassword = cpasswordField.val();
        let fname = fnameField.val();
        let lname = lnameField.val();
        let mname = mnameField.val();
        console.log(email);

        // Clear any previous message
        $('#message-1').text('');

        // Email format validation
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // Simple email regex

        // Check if email is empty
        // && username === '' && password === '' && cpassword === ''
        if (email === '' ) {
            $('#message-1').text('Fields are empty'); // Show message below the email field
            emailField.css('border', '1px solid lightcoral'); // Change border to light red
            usernameField.css('border', '1px solid lightcoral'); // Change border to light red
            passwordField.css('border', '1px solid lightcoral'); // Change border to light red
            cpasswordField.css('border', '1px solid lightcoral'); // Change border to light red
            fnameField.css('border', '1px solid lightcoral'); // Change border to light red
            lnameField.css('border', '1px solid lightcoral'); // Change border to light red
            mnameField.css('border', '1px solid lightcoral'); // Change border to light red
            return; // Stop further execution
        } 

        
        else if (!emailRegex.test(email)) {
            $('#message-1').text('Please enter a valid email address.'); // Show message for invalid email
            emailField.css('border', '1px solid lightred'); // Change border to light red
            return; // Stop further execution
        } 
        
        else {
            emailField.css('border', ''); // Reset border color if email is valid
        }


        $.ajax({
            url: 'send-otp.php',
            method: 'POST',
            data: { email: email },
            dataType: 'json', // Expect a JSON response
            success: function(response) {
                console.log(response.message); // Log the message from the server
                $('#message-1').text(response.message).addClass('show'); // Display the message in #message-1

                // Automatically hide the message after 5 seconds
                setTimeout(function() {
                    $('#message-1').css('opacity', 0); // Start fading out
                    $('#message-1').removeClass('show'); // Remove show class to hide
                }, 5000);  // You can adjust the delay if needed

                // Optional: Reset the opacity back to 1 when shown again
                setTimeout(function() {
                    $('#message-1').css('opacity', 1); // Reset opacity for the next message
                }, 0);
            },
            error: function() {
                console.log('Failed to send OTP');
                $('#message-1').text('Failed to send OTP. Please try again.').addClass('show'); // Handle error display
            }
        });

    });

    // Function to start the OTP countdown
    function startOtpCountdown(linkElement) { // Use linkElement as the parameter
        // Disable the link and grey it out
        $(linkElement).addClass('disabled'); // Add the disabled class to grey it out
        let countdownTime = 10; // Countdown time in seconds
        const countdownElement = document.getElementById('otpCountdown');

        // Show the countdown
        countdownElement.textContent = `(${countdownTime})`;

        const countdownInterval = setInterval(() => {
            countdownTime--;
            countdownElement.textContent = `(${countdownTime})`;

            // When countdown reaches 0
            if (countdownTime <= 0) {
                clearInterval(countdownInterval);
                countdownElement.textContent = ''; // Change text when countdown ends
                $(linkElement).removeClass('disabled'); // Re-enable the link
                $(linkElement).css('pointer-events', 'auto'); // Allow clicking again
            }
        }, 1000);

        // Disable clicking the link until countdown is finished
        $(linkElement).css('pointer-events', 'none');
    }