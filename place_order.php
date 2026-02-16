<?php
session_start();
include "config/db.php";

$cart = $_SESSION['cart'] ?? [];

if(empty($cart)){
    die("Cart is empty.");
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $email = $_POST['email'] ?? '';
    $delivery = $_POST['delivery'];
    $address = trim($_POST['address']);
    $payment = $_POST['payment'];

    $total = 0;

    // Calculate total safely
    foreach($cart as $item){

        $checkStock = $conn->prepare("
            SELECT price, stock_quantity 
            FROM Products 
            WHERE product_id = ?
        ");
        $checkStock->bind_param("i", $item['id']);
        $checkStock->execute();
        $product = $checkStock->get_result()->fetch_assoc();

        if (!$product || $product['stock_quantity'] < $item['quantity']) {
            die("Product unavailable or insufficient stock.");
        }

        $total += $product['price'] * $item['quantity'];
    }

    // 1️⃣ INSERT ORDER FIRST
    $stmt = $conn->prepare("
        INSERT INTO Orders 
        (customer_name, phone_number, customer_email, delivery_type, shipping_address, payment_method, total_amount, status, created_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, 'pending', NOW())
    ");

    $stmt->bind_param(
        "ssssssd",
        $name,
        $phone,
        $email,
        $delivery,
        $address,
        $payment,
        $total
    );

    if(!$stmt->execute()){
        die("Order creation failed: " . $stmt->error);
    }

    $order_id = $stmt->insert_id;

    // 2️⃣ INSERT ORDER ITEMS
    $stmt2 = $conn->prepare("
        INSERT INTO OrderItems 
        (order_id, product_id, product_name, quantity, price)
        VALUES (?, ?, ?, ?, ?)
    ");

    foreach($cart as $item){

        $productStmt = $conn->prepare("
            SELECT name, price, stock_quantity 
            FROM Products 
            WHERE product_id = ?
        ");
        $productStmt->bind_param("i", $item['id']);
        $productStmt->execute();
        $product = $productStmt->get_result()->fetch_assoc();

        $product_name  = $product['name'];
        $product_price = $product['price'];
        $quantity      = $item['quantity'];

        $stmt2->bind_param(
            "iisid",
            $order_id,
            $item['id'],
            $product_name,
            $quantity,
            $product_price
        );

        if(!$stmt2->execute()){
            die("Order item insert failed: " . $stmt2->error);
        }

        // Reduce stock
        $updateStock = $conn->prepare("
            UPDATE Products 
            SET stock_quantity = stock_quantity - ? 
            WHERE product_id = ?
        ");
        $updateStock->bind_param("ii", $quantity, $item['id']);
        $updateStock->execute();
    }

    unset($_SESSION['cart']);

    header("Location: cart.php?id=" . $order_id);
    exit;
}
?>
