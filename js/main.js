const menuBtn = document.getElementById("menu-btn");
const navLinks = document.getElementById("nav-links");
const menuBtnIcon = document.getElementById("i");
const dots = document.querySelectorAll('.dot');
const navBtn = document.querySelectorAll('.nav-btn')






menuBtn.addEventListener("click", function() {
    navLinks.classList.toggle("open")

    const isOpen = navLinks.classList.contains("open");
    // menuBtnIcon.setAttribute("class", isOpen?"ri-close-line" : "ri-menu-list")
});

navLinks.addEventListener("click", function(){
    navLinks.classList.remove("open")
    menuBtnIcon.setAttribute("class", "ri-menu-line")
})

document.addEventListener("click", () => {
  
    // Add click event listener to toggle visibility
    menuBtn.addEventListener("click", () => {
      navBtn.classList.toggle('visible');
    });
  });






function navigateTo(url) {
    window.location.href = url; 
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






// // Select all slides
// const slides = document.querySelectorAll('.section');
// let currentIndex = 0;

// // Function to show the next slide
// function showNextSlide() {
//     // Remove the 'active' class from the current slide
//     slides[currentIndex].classList.remove('active');

//     // Update the index to the next slide
//     currentIndex = (currentIndex + 1) % slides.length;

//     // Add the 'active' class to the next slide
//     slides[currentIndex].classList.add('active');
// }

// // Set the initial slide to active
// slides[currentIndex].classList.add('active');

// // Change slide every 5 seconds
// setInterval(showNextSlide, 5000);