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

function addToCart(productId) {
  const id = parseInt(productId);
  if (!id || isNaN(id)) {
    alert("Invalid product ID");
    return;
  }

  fetch("add_to_cart.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded"
    },
    body: "id=" + encodeURIComponent(id) + "&quantity=1"
  })
  .then(response => response.text())
  .then(data => {
    if (data.includes("Invalid") || data.includes("not found")) {
      alert(data);
      return;
    }

    // Update cart badge
    const cartBadge = document.getElementById("cart-count");
    if (cartBadge) {
      cartBadge.innerText = data;
    }

    alert("✨ Product added to cart!");
  })
  .catch(error => {
    console.error("Error:", error);
    alert("Something went wrong.");
  });
}


// orde view
window.onclick = function(event) {
  const modal = document.getElementById("orderModal");
  if (event.target === modal) {
    modal.style.display = "none";
  }
}
