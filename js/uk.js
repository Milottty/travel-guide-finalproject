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


const cart = [];

// Function to add items to the cart and show an alert
function addToCart(bookName, price) {
  // Add the book to the cart array
  cart.push({ name: bookName, price: price });

  // Update the cart counter
  const cartCount = document.getElementById('cart-count');
  cartCount.textContent = cart.length;

  // Show an alert with the book's name and price
  alert(`You added "${bookName}" to your cart for $${price.toFixed(2)}!`);

  // Log the cart's contents (optional for debugging)
  console.log('Current cart:', cart);
}