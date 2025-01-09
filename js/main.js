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







function navigateTo(url) {
    window.location.href = url; // Replace with your desired URLs
}

let valueDisplay = document.querySelectorAll(".num");
let interval = 1000;

valueDisplay.forEach((visualDisplay) => {
    let startValue = 0;
    let endValue = parseInt(visualDisplay.getAttribute("date-val")); // Correct variable and attribute
    let duration = Math.floor(interval / endValue);

    let counter = setInterval(function () {
        startValue += 1;
        visualDisplay.textContent = startValue; // Update textContent for the correct element
        if (startValue == endValue) {
            clearInterval(counter);
        }
    }, duration);
});