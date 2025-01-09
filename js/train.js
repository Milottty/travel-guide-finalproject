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


const journeys = [
    {
      name: "The Glacier Express",
      region: "europe",
      description: "A scenic journey through the Swiss Alps.",
      image: "glacier-express.jpg"
    },
    {
      name: "The Trans-Siberian Railway",
      region: "asia",
      description: "The world's longest railway journey.",
      image: "trans-siberian.jpg"
    },
    // Add 22 more journeys
  ];
  
  const journeysContainer = document.getElementById("journeys-container");
  const regionFilter = document.getElementById("region");
  
  function displayJourneys(filter = "all") {
    journeysContainer.innerHTML = ""; // Clear the container
  
    const filteredJourneys = journeys.filter(journey => 
      filter === "all" || journey.region === filter
    );
  
    filteredJourneys.forEach(journey => {
      const journeyCard = document.createElement("div");
      journeyCard.className = "journey-card";
  
      journeyCard.innerHTML = `
        <img src="${journey.image}" alt="${journey.name}">
        <h3>${journey.name}</h3>
        <p>${journey.description}</p>
      `;
  
      journeysContainer.appendChild(journeyCard);
    });
  }
  
  // Initialize the display
  displayJourneys();
  
  // Add filter functionality
  regionFilter.addEventListener("change", (e) => {
    displayJourneys(e.target.value);
  });