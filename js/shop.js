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





// Popup

const shopBtn = document.getElementById("btn-popup");


const PopUp = document.getElementById("popup");

document.querySelectorAll(".popup-container").forEach((button, index) =>{
    shopBtn.addEventListener("click", function(){
       PopUp.classList.add("open-popup"); 
    })
})

document.querySelectorAll(".popup-container").forEach((button, index) =>{
    shopBtn.addEventListener("click", function(){
       PopUp.classList.remove("close-popup"); 
    })
 })

