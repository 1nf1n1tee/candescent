<?php
session_start();
include "config/db.php";

$product_id = $_GET['id'];

if (!isset($_SESSION['session_id'])) {
    $_SESSION['session_id'] = session_id();
}

$session_id = $_SESSION['session_id'];
$cart = $conn->query("SELECT * FROM Cart WHERE session_id='$session_id'");
if ($cart->num_rows == 0) {
    $conn->query("INSERT INTO Cart (session_id) VALUES ('$session_id')");
    $cart = $conn->query("SELECT * FROM Cart WHERE session_id='$session_id'");
}
$cart_id = $cart->fetch_assoc()['cart_id'];

// Check if product already exists
$existing = $conn->query("SELECT * FROM CartItems WHERE cart_id='$cart_id' AND product_id='$product_id'");
if ($existing->num_rows > 0) {
    $conn->query("UPDATE CartItems SET quantity = quantity + 1 WHERE cart_id='$cart_id' AND product_id='$product_id'");
} else {
    $conn->query("INSERT INTO CartItems (cart_id, product_id, quantity) VALUES ('$cart_id', '$product_id', 1)");
}

header("Location: cart.php");
