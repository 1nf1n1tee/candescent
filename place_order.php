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


    $stmt = $conn->prepare("
        INSERT INTO Orders 
        (customer_name, phone_number, customer_email, delivery_type, shipping_address, payment_method, total_amount, created_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
    ");

    $stmt->bind_param("ssssssd", 
        $name, 
        $phone, 
        $email, 
        $delivery, 
        $address, 
        $payment, 
        $total
    );

    $stmt->execute();
    $order_id = $stmt->insert_id;

    // Insert order items
    $stmt2 = $conn->prepare("
        INSERT INTO OrderItems (order_id, product_id, quantity, price)
        VALUES (?, ?, ?, ?)
    ");

    foreach($cart as $item){

        // Insert order item
        $stmt2->bind_param("iiid", 
            $order_id, 
            $item['id'], 
            $item['quantity'], 
            $item['price']
        );
        $stmt2->execute();

        // Reduce stock
        $updateStock = $conn->prepare("UPDATE Products SET stock_quantity = stock_quantity - ? WHERE product_id = ?");
        $updateStock->bind_param("ii", $item['quantity'], $item['id']);
        $updateStock->execute();
    }

    unset($_SESSION['cart']);

    header("Location: cart.php?id=$order_id");
    exit;
}
?>
