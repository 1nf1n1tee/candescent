<?php
session_start();
include "config/db.php";

if (!isset($_SESSION['session_id'])) {
    $_SESSION['session_id'] = session_id();
}

// Fetch or create cart
$session_id = $_SESSION['session_id'];

$cart = $conn->query("SELECT * FROM Cart WHERE session_id='$session_id'");
if ($cart->num_rows == 0) {
    $conn->query("INSERT INTO Cart (session_id) VALUES ('$session_id')");
    $cart = $conn->query("SELECT * FROM Cart WHERE session_id='$session_id'");
}

$cart_id = $cart->fetch_assoc()['cart_id'];

// Fetch cart items
$items = $conn->query("
    SELECT ci.cart_item_id, ci.quantity, p.name, p.price, p.image_url 
    FROM CartItems ci
    JOIN Products p ON ci.product_id = p.product_id
    WHERE ci.cart_id = $cart_id
");
?>

<?php include "header.php"; ?>

<section class="cart">
    <h2>Your Cart</h2>
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php $grand_total = 0; ?>
            <?php while ($row = $items->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['quantity']; ?></td>
                <td>$<?php echo $row['price']; ?></td>
                <td>$<?php echo $row['price'] * $row['quantity']; ?></td>
                <td>
                    <a href="remove_from_cart.php?id=<?php echo $row['cart_item_id']; ?>">Remove</a>
                </td>
            </tr>
            <?php $grand_total += $row['price'] * $row['quantity']; ?>
            <?php endwhile; ?>
        </tbody>
    </table>
    <h3>Grand Total: $<?php echo $grand_total; ?></h3>
    <a href="checkout.php"><button>Checkout</button></a>
</section>

<?php include "footer.php"; ?>
