<?php
session_start();
include "config/db.php";
include "header.php";

$cart = $_SESSION['cart'] ?? [];
$total = 0;
?>

<section class="cart">
  <h2>Your Cart</h2>

  <?php if(!$cart): ?>
    <p>Your cart is empty.</p>
  <?php else: ?>
    <table>
      <thead>
        <tr>
          <th>Product</th>
          <th>Price</th>
          <th>Quantity</th>
          <th>Subtotal</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($cart as $item): 
          $subtotal = $item['price'] * $item['quantity'];
          $total += $subtotal;
        ?>
          <tr>
            <td>
              <img src="assets/images/products/<?php echo $item['image_url']; ?>" 
                   alt="<?php echo $item['name']; ?>" style="width:50px; height:50px; object-fit:cover;">
              <?php echo $item['name']; ?>
            </td>
            <td>$<?php echo $item['price']; ?></td>
            <td>
              <form method="POST" action="update_cart.php">
                <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1">
                <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                <button type="submit">Update</button>
              </form>
            </td>
            <td>$<?php echo $subtotal; ?></td>
            <td>
              <a href="remove_cart.php?id=<?php echo $item['id']; ?>">Remove</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <h3>Total: $<?php echo $total; ?></h3>

    <a href="place_order.php" class="btn-place-order">Place Order</a>
  <?php endif; ?>
</section>

<?php include "footer.php"; ?>
