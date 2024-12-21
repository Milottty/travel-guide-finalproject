const generatedCode = Math.floor(100000 + Math.random() * 900000); // Generates a 6-digit number
alert("Your verification code is: " + generatedCode); // Show the code in the alert (for testing purposes)

// Handling form submission and redirecting
document.getElementById('verification-form').addEventListener('submit', function(event) {
    event.preventDefault();  // Prevent form from submitting the usual way

    // Get the value entered by the user
    const userCode = document.getElementById('verification-code').value;

    // Check if the code matches the generated one
    if (userCode == generatedCode) {
        alert("Verification successful! You can now reset your password.");
        window.location.href = "newpass.html"; // Redirect to the "new password" page
    } else {
        alert("Incorrect code. Please try again.");
    }
});
