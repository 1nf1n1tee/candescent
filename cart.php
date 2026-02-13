<?php
include "config/db.php";
if (session_status() == PHP_SESSION_NONE) session_start();

$cart = $_SESSION['cart'] ?? [];
$total = 0;
foreach($cart as $item){
    $total += $item['price'] * $item['quantity'];
}
?>

<?php include "header.php"; ?>

<section class="cart">
  <h2>Your Cart</h2>
  <?php if(empty($cart)): ?>
      <p>Your cart is empty.</p>
  <?php else: ?>
      <table>
        <thead>
          <tr>
            <th>Product</th>
            <th>Qty</th>
            <th>Price</th>
            <th>Subtotal</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($cart as $index => $item): ?>
            <tr>
              <td><?php echo htmlspecialchars($item['name']); ?></td>
              <td><?php echo $item['quantity']; ?></td>
              <td>$<?php echo $item['price']; ?></td>
              <td>$<?php echo $item['price'] * $item['quantity']; ?></td>
              <td>
                <a href="remove_from_cart.php?index=<?php echo $index; ?>">Remove</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <h3>Total: $<?php echo $total; ?></h3>
        <button onclick="openCheckout()" class="checkout-btn">Proceed to Checkout</button>
      </form>
  <?php endif; ?>
</section>

<!-- Checkout Modal -->
<div id="checkoutModal" class="modal">
  <div class="modal-content">

    <span class="close-modal" onclick="closeCheckout()">&times;</span>

    <h2>Checkout</h2>

    <form action="place_order.php" method="POST">

      <input type="text" name="name" placeholder="Full Name" required>
      <input type="tel" name="phone" placeholder="Phone Number" required>
      <input type="email" name="email" placeholder="Email (Optional)">

      <select name="delivery" id="deliverySelect" required>
        <option value="">Delivery Type</option>
        <option value="Inside Dhaka">Inside Dhaka</option>
        <option value="Outside Dhaka">Outside Dhaka</option>
      </select>

      <textarea name="address" placeholder="Delivery Address" required></textarea>

      <select name="payment" required>
        <option value="">Payment Method</option>
        <option value="Cash on Delivery">Cash on Delivery</option>
        <option value="Bkash">Bkash</option>
      </select>

      <div class="order-summary">
        <p>Subtotal: ৳<?php echo $total; ?></p>
        <p id="deliveryCharge">Delivery: ৳0</p>
        <h3 id="grandTotal">Total: ৳<?php echo $total; ?></h3>
      </div>

      <button type="submit" class="confirm-btn">Confirm Order</button>

    </form>

  </div>
</div>
<script>
function openCheckout(){
  document.getElementById("checkoutModal").style.display = "flex";
}

function closeCheckout(){
  document.getElementById("checkoutModal").style.display = "none";
}
</script>

<script>
const subtotal = <?php echo $total; ?>;

document.getElementById("deliverySelect").addEventListener("change", function(){

  let delivery = this.value;
  let charge = 0;

  if(delivery === "Inside Dhaka") charge = 60;
  if(delivery === "Outside Dhaka") charge = 120;

  document.getElementById("deliveryCharge").innerText = "Delivery: ৳" + charge;
  document.getElementById("grandTotal").innerText = "Total: ৳" + (subtotal + charge);
});
</script>

<?php include "footer.php"; ?>
