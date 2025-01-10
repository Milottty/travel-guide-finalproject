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
      image: "img/The Glacier Express.avif"
    },
    {
      name: "British Pullman",
      region: "europe",
      description: "A scenic journey through the Swiss Alps.",
      image: "img/Belmond-British-Pullman.webp"
    },
    {
      name: "Andean Explorer",
      region: "america",
      description: "A scenic journey through the Swiss Alps.",
      image: "img/Belmond20Explorer.webp"
    },
    {
      name: "Transcantabrico",
      region: "europe",
      description: "A scenic journey through the Swiss Alps.",
      image: "img/Transcantabrico.webp"
    },
    {
      name: "The Hokkaidō Shinkansen",
      region: "asia",
      description: "A scenic journey through the Swiss Alps.",
      image: "img/The Hokkaidō Shinkansen.avif"
    },
    {
      name: "Bangkok",
      region: "asia",
      description: "A scenic journey through the Swiss Alps.",
      image: "img/Bangkok.avif"
    },
    {
      name: "Alaska Railroad",
      region: "america",
      description: "A scenic journey through the Swiss Alps.",
      image: "img/Alaska Railroad.webp"
    },
    {
      name: "Rocky Mountaineer",
      region: "asia",
      description: "A scenic journey through the Swiss Alps.",
      image: "img/Rocky Mountaineer.webp"
    },
    {
      name: "The Bergensbanen",
      region: "america",
      description: "A scenic journey through the Swiss Alps.",
      image: "img/The Bergensbanen.webp"
    },
    {
      name: "The Blue Train",
      region: "asia",
      description: "A scenic journey through the Swiss Alps.",
      image: "img/The Blue Train.webp"
    },
    {
      name: "Serra Verde Express",
      region: "america",
      description: "A scenic journey through the Swiss Alps.",
      image: "img/Serra Verde Express.jpg"
    },
    {
      name: "Jungfrau Railway",
      region: "europe",
      description: "A scenic journey through the Swiss Alps.",
      image: "img/Switzerland8020Jungfrau20copy.webp"
    },
    {
      name: "The Trans-Siberian Railway",
      region: "asia",
      description: "The world's longest railway journey.",
      image: "img/The Trans-Siberian Railway.jpg"
    },
    {
      name: "The Canadian",
      region: "america",
      description: "The world's longest railway journey.",
      image: "img/The20Train_RS452_AF.webp"
    },
    {
      name: "Golden Eagle Danube Express",
      region: "europe",
      description: "The world's longest railway journey.",
      image: "img/Golden Eagle Danube Express.jpg"
    },
    {
      name: "Rocky Mountaineer",
      region: "america",
      description: "The world's longest railway journey.",
      image: "img/Rocky Mountaineer.avif"
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