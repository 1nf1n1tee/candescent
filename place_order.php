<?php
session_start();
include "config/db.php";

$cart = $_SESSION['cart'] ?? [];

if(empty($cart)){
    die("Cart is empty.");
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'] ?? '';
    $delivery = $_POST['delivery'];
    $address = $_POST['address'];
    $payment = $_POST['payment'];

    $total = 0;

    foreach($cart as $item){

        // Fetch current stock from DB
        $checkStock = $conn->prepare("SELECT stock_quantity FROM Products WHERE product_id = ?");
        $checkStock->bind_param("i", $item['id']);
        $checkStock->execute();
        $resultStock = $checkStock->get_result();
        $productData = $resultStock->fetch_assoc();

        if (!$productData || $productData['stock_quantity'] < $item['quantity']) {
            die("One of the products is out of stock or insufficient quantity.");
        }

        $total += $item['price'] * $item['quantity'];
    }


    // Prepare once
    $stmt2 = $conn->prepare("
        INSERT INTO OrderItems 
        (order_id, product_id, product_name, quantity, price)
        VALUES (?, ?, ?, ?, ?)
    ");

    foreach($cart as $item){

        // Fetch product snapshot from DB
        $productStmt = $conn->prepare("
            SELECT name, price, stock_quantity 
            FROM Products 
            WHERE product_id = ?
        ");
        $productStmt->bind_param("i", $item['id']);
        $productStmt->execute();
        $product = $productStmt->get_result()->fetch_assoc();

        if(!$product){
            die("Product not found.");
        }

        if($product['stock_quantity'] < $item['quantity']){
            die("Insufficient stock.");
        }

        $product_name  = $product['name'];
        $product_price = $product['price'];
        $quantity      = $item['quantity'];

        // Insert order item (snapshot)
        $stmt2->bind_param(
            "iisid",
            $order_id,
            $item['id'],
            $product_name,
            $quantity,
            $product_price
        );
        $stmt2->execute();

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

    header("Location: cart.php?id=$order_id");
    exit;
}
?>
