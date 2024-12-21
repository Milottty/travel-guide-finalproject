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
document.querySelector('.view-all').addEventListener('click', () => {
    alert('View all destinations clicked!');
});




 // Form submission logic
 document.getElementById('reset-password-form').addEventListener('submit', function(event) {
    event.preventDefault();  // Prevent default form submission

    // Get the values of the new password and confirm password fields
    let newPassword = document.getElementById('new-password').value;
    let confirmPassword = document.getElementById('confirm-password').value;

    // Check if the new password and confirm password match
    if (newPassword === confirmPassword) {
        alert("Your password has been successfully reset.");
        window.location.href = "index.html";  // Redirect to index.html
    } else {
        alert("Passwords do not match. Please try again.");
    }
});

const backgrounds = [
    '..img/GettyImages-1061872058.avif   ',
    '..img/San-Francisco.webp',
    '..img/Bruges.webp',
    '..img/shutterstock1636820080.avif'
];

let currentIndex = 0;

function changeBackground() {
    // Update the background image
    document.body.style.backgroundImage = `url('${backgrounds[currentIndex]}')`;

    // Move to the next image index
    currentIndex = (currentIndex + 1) % backgrounds.length;
}

// Initial background setup
changeBackground();

// Change the background every 5 seconds
setInterval(changeBackground, 5000);
