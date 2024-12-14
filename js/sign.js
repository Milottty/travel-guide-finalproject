



const menuBtn = document.getElementById("menu-btn");
const navLinks = document.getElementById("nav-links");
const menuBtnIcon = document.getElementById("i");

const dots = document.querySelectorAll('.dot');

menuBtn.addEventListener("click", function() {
    navLinks.classList.toggle("open")

    const isOpen = navLinks.classList.contains("open");
    menuBtnIcon.setAttribute("class", isOpen?"ri-close-line" : "ri-menu-list")
});

navLinks.addEventListener("click", function(){
    navLinks.classList.remove("open")
    menuBtnIcon.setAttribute("class", "ri-menu-line")
})
// document.querySelector('.view-all').addEventListener('click', () => {
//     alert('View all destinations clicked!');
// });



function checkPassword() {
    var password = document.getElementById("password").value;
    var numberRequirement = document.getElementById("numberRequirement");
    var symbolRequirement = document.getElementById("symbolRequirement");
    var passwordInput = document.getElementById("password");

  
    if (/\d/.test(password)) {
        numberRequirement.classList.remove("invalid");
        numberRequirement.classList.add("valid");
    } else {
        numberRequirement.classList.remove("valid");
        numberRequirement.classList.add("invalid");
    }

   
    if (/[@#%^:$;&/]/.test(password)) {
        symbolRequirement.classList.remove("valid");
        symbolRequirement.classList.add("invalid");
        passwordInput.classList.add("invalid-input"); 
    } else {
        symbolRequirement.classList.remove("invalid");
        symbolRequirement.classList.add("valid");
        passwordInput.classList.remove("invalid-input"); 
    }
}

function validatePassword() {
    var password = document.getElementById("password").value;
    if (/[@#%^:$;&/]/.test(password)) {
        alert("Password contains invalid symbols!");
        return false;
    }
    return true;
}






document.addEventListener("DOMContentLoaded", function() {
    const togglePasswordButton = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');

    // Add event listener to toggle password visibility
    togglePasswordButton.addEventListener('click', function () {
        // Check the current input type
        const inputType = passwordInput.type;

        // Toggle the type between 'password' and 'text'
        if (inputType === 'password') {
            passwordInput.type = 'text';
            togglePasswordButton.classList.remove('fa-eye');
            togglePasswordButton.classList.add('fa-eye-slash');  // Change icon to 'eye-slash'
        } else {
            passwordInput.type = 'password';
            togglePasswordButton.classList.remove('fa-eye-slash');
            togglePasswordButton.classList.add('fa-eye');  // Change icon to 'eye'
        }
    });
});
