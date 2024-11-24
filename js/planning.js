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







const articles = document.querySelectorAll('.article-card');
const filterInput = document.getElementById('filter');

// filterInput.addEventListener('input', function () {
//     const filterValue = filterInput.value.toLowerCase();
//     articles.forEach(article => {
//         const title = article.querySelector('.article-title').textContent.toLowerCase();
//         if (title.includes(filterValue)) {
//             article.style.display = 'block';
//         } else {
//             article.style.display = 'none';
//         }
//     });
// });