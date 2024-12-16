window.addEventListener('DOMContentLoaded', function() {
    // Functionality for filtering issues
    const buttons = document.querySelectorAll('.btn');
    const allButton = document.querySelector('#all'); // Default filter button

    buttons.forEach(function(button) {
        button.addEventListener('click', function() {
            // Remove 'active' class from all buttons and add it to the clicked button
            buttons.forEach(function(btn) { btn.classList.remove('active'); });
            this.classList.add('active');

            let filter = this.textContent.toUpperCase();
            if (this.id === 'open') {
                filter = 'OPEN';
            } else if (this.id === 'my_tickets') {
                const sessionData = sessionStorage.getItem('session');
                const session = sessionData ? JSON.parse(sessionData) : null; // Safely parse session data
                if (session && session.id) {
                    filter = `MY_TICKETS&assigned_to=${encodeURIComponent(session.id)}`;
                }
            }

            // AJAX request to fetch filtered issues
            const xhr = new XMLHttpRequest();
            xhr.open('GET', `dashboard.php?filter=${encodeURIComponent(filter)}`, true);
            xhr.onload = function() {
                if (this.status === 200) {
                    document.querySelector('tbody').innerHTML = this.responseText;

                    // Add appropriate class to status elements for styling
                    const statusElements = document.querySelectorAll('#status');
                    statusElements.forEach(function(statusElement) {
                        const statusText = statusElement.textContent.toLowerCase();
                        statusElement.className = ''; // Clear existing classes
                        if (statusText === 'open') {
                            statusElement.classList.add('open');
                        } else if (statusText === 'closed') {
                            statusElement.classList.add('closed');
                        } else if (statusText === 'in progress') {
                            statusElement.classList.add('inProgress');
                        }
                    });
                }
            };
            xhr.send();
        });
    });

    // Trigger click event on the "All" button to load all issues by default
    if (allButton) {
        allButton.click();
    }

    // Dynamically populate "Assigned To" dropdown in Add New Issue form
    const assignedToSelect = document.getElementById('assigned_to');
    if (assignedToSelect) {
        const xhr = new XMLHttpRequest();
        xhr.open('GET', 'getUsers.php', true); // Fetch user list from backend
        xhr.onload = function() {
            if (this.status === 200) {
                assignedToSelect.innerHTML = this.responseText; // Populate dropdown with user options
            }
        };
        xhr.send();
    }

    // Event handler for login form submission
    const loginForm = document.getElementById('login');
    if (loginForm) {
        loginForm.addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent default form submission

            const formData = new FormData(loginForm);
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'login.php', true);
            xhr.onload = function() {
                if (this.status === 200) {
                    try {
                        const response = JSON.parse(this.responseText);
                        if (response.success) {
                            // Store user information in session storage
                            const session = {
                                id: response.user.id,
                                email: response.user.email,
                                firstname: response.user.firstname,
                                lastname: response.user.lastname
                            };
                            sessionStorage.setItem('session', JSON.stringify(session));
                            console.log('Session:', session);
                            // Redirect to dashboard or another page
                            window.location.href = 'dashboard.html';
                        } else {
                            alert('Login failed: ' + response.message);
                        }
                    } catch (e) {
                        console.error('Error parsing JSON response:', e);
                    }
                }
            };
            xhr.send(formData);
        });
    }
});
