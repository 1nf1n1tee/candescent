<?php
session_start();
include "config/db.php";

$cart = $_SESSION['cart'] ?? [];

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'] ?? ''; // optional
    $delivery = $_POST['delivery'];
    $address = $_POST['address'];
    $payment = $_POST['payment'];
    $total = 0;

    foreach($_SESSION['cart'] as $item){
        $total += $item['price'] * $item['quantity'];
    }
    // Insert order
    $stmt = $conn->prepare("INSERT INTO Orders (customer_name, phone, delivery_type, address, payment_method, total_amount) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssd", $name, $phone, $delivery, $address, $payment, $total);
    $stmt->execute();
    $order_id = $stmt->insert_id;
    
        $stmt = $conn->prepare("
        INSERT INTO Orders 
        (customer_name, phone_number, customer_email, shipping_address, total_amount) 
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("ssssd", $name, $phone, $email, $address, $total);
    $stmt->execute();
    $order_id = $stmt->insert_id;
    
    // Insert order items
    $stmt2 = $conn->prepare("INSERT INTO OrderItems (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    foreach($cart as $item){
        $stmt2->bind_param("iiid", $order_id, $item['id'], $item['quantity'], $item['price']);
        $stmt2->execute();
    }

    // Clear cart
    unset($_SESSION['cart']);

    header("Location: order_success.php?id=$order_id");
    exit;
}
?>

<section class="place-order">
  <h2>Place Your Order</h2>
  <form method="POST">
    <input type="text" name="name" placeholder="Your Name" required>
    <input type="tel" name="phone" placeholder="Phone Number" required>
    <select name="delivery" required>
      <option value="">Select Delivery Type</option>
      <option value="Inside Dhaka">Inside Dhaka</option>
      <option value="Outside Dhaka">Outside Dhaka</option>
    </select>
    <textarea name="address" placeholder="Delivery Address" required></textarea>
    <select name="payment" required>
      <option value="">Select Payment Method</option>
      <option value="Cash on Delivery">Cash on Delivery</option>
      <option value="Bkash">Bkash Payment</option>
    </select>
    <button type="submit">Place Order</button>
  </form>
</section>
