<?php
session_start();
include "config/db.php";

$product_id = $_POST['id'];
$quantity = $_POST['quantity'] ?? 1;

// Fetch product details from DB
$result = $conn->query("SELECT * FROM Products WHERE product_id = $product_id");
$product = $result->fetch_assoc();

if(!$product) {
    echo "Product not found";
    exit;
}

// Initialize cart if not exists
if(!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Add or update quantity
if(isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
} else {
    $_SESSION['cart'][$product_id] = [
        'id' => $product_id,
        'name' => $product['name'],
        'price' => $product['price'],
        'image_url' => $product['image_url'],
        'quantity' => $quantity
    ];
}

echo count($_SESSION['cart']); // can be used to update cart badge
?>
