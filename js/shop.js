// document.querySelectorAll(".shop-link").forEach((button, index) => {
//     button.addEventListener("click", function() {
//         alert(`Thank you for your purchase.`);
//     });
// });
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




function initializePopups(buttonSelector, popupSelector, activeClass) {
    const buttons = document.querySelectorAll(".shopNowButton"); 
    const popups = document.querySelectorAll(".popup"); 

    buttons.forEach((button, index) => {
        button.addEventListener("click", function () {
            popups[index].classList.add(activeClass); 
        });
    });

    popups.forEach((popup) => {
        const closeButton = popup.querySelector(".btn-close"); 
        closeButton.addEventListener("click", function () {
            popup.classList.remove(activeClass); 
        });
    });
}




initializePopups(".shopNowButton", ".popup-container", "open-popup", ".okButton");