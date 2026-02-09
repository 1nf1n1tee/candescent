let currentSlide = 0;
const slides = document.querySelectorAll(".carousel-slide");
const dots = document.querySelectorAll(".dot");

function showSlide(index) {
  slides.forEach((slide, i) => {
    slide.classList.toggle("active", i === index);
    dots[i].classList.toggle("active", i === index);
  });
  currentSlide = index;
}

function goToSlide(index) {
  showSlide(index);
}

function nextSlide() {
  let next = (currentSlide + 1) % slides.length;
  showSlide(next);
}

// Auto-play (every 4 seconds)
setInterval(nextSlide, 4000);

/* Sidebar toggle stays the same */
function toggleSidebar() {
  const sidebar = document.getElementById("sidebar");
  sidebar.style.left = sidebar.style.left === "0px" ? "-260px" : "0px";
}
