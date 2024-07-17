document.addEventListener('DOMContentLoaded', function() {
    // Get the form element
    var loginForm = document.getElementById('loginForm');

    // Add an event listener to the form submit event
    loginForm.addEventListener('submit', function(event) {
        // Prevent the default form submission
        event.preventDefault();

        // Get the form data
        var formData = new FormData(loginForm);

        // Create an XMLHttpRequest object
        var xhr = new XMLHttpRequest();

        // Define what happens on successful data submission
        xhr.onload = function() {
            if (xhr.status === 200) {
                // Parse the JSON response
                var response = JSON.parse(xhr.responseText);

                // Check if login was successful
                if (response.success) {
                    // Redirect to the admin dashboard
                    window.location.href = 'ngo.php';
                } else {
                    // Display an error message
                    document.getElementById('errorMessage').innerText = response.message;
                }
            } else {
                // Display a generic error message
                document.getElementById('errorMessage').innerText = 'An error occurred during the login process. Please try again.';
            }
        };

        // Define what happens in case of an error
        xhr.onerror = function() {
            document.getElementById('errorMessage').innerText = 'Request failed. Please check your network connection and try again.';
        };

        // Set up the request
        xhr.open('POST', 'login.php', true);

        // Send the form data
        xhr.send(formData);
    });
});