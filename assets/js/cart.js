const cartKey = "candescent_cart";
let cart = JSON.parse(localStorage.getItem(cartKey)) || [];

function renderCart() {
  const container = document.getElementById("cart-items");
  container.innerHTML = "";

  let total = 0;

  cart.forEach((item, index) => {
    total += item.price * item.quantity;

    container.innerHTML += `
      <div class="cart-item">
        <img src="${item.image}">
        <div>
          <h3>${item.name}</h3>
          <p>$${item.price}</p>
          <input type="number" min="1" value="${item.quantity}"
            onchange="updateQuantity(${index}, this.value)">
          <button onclick="removeItem(${index})">Remove</button>
        </div>
      </div>
    `;
  });

  document.getElementById("cart-total").innerText = total.toFixed(2);
}

function updateQuantity(index, qty) {
  cart[index].quantity = parseInt(qty);
  saveCart();
}

function removeItem(index) {
  cart.splice(index, 1);
  saveCart();
}

function saveCart() {
  localStorage.setItem(cartKey, JSON.stringify(cart));
  renderCart();
}

renderCart();
