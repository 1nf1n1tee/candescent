<?php
session_start();
include "config/db.php";

if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    echo "Invalid product ID";
    exit;
}

$product_id = intval($_POST['id']);
$quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

// Use prepared statement
$stmt = $conn->prepare("SELECT * FROM Products WHERE product_id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
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

    $newQty = $_SESSION['cart'][$product_id]['quantity'] + $quantity;

    if ($newQty > $product['stock_quantity']) {
        echo "Not enough stock available";
        exit;
    }

    $_SESSION['cart'][$product_id]['quantity'] = $newQty;

} else {

    if ($quantity > $product['stock_quantity']) {
        echo "Not enough stock available";
        exit;
    }

    $_SESSION['cart'][$product_id] = [
        'id' => $product_id,
        'name' => $product['name'],
        'price' => $product['price'],
        'image_url' => $product['image_url'],
        'quantity' => $quantity
    ];
}

echo count($_SESSION['cart']);
?>
