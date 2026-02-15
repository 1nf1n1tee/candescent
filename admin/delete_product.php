<?php
include "../config/db.php";

if (!isset($_GET['id'])) {
    die("Invalid ID");
}

$id = intval($_GET['id']);

// 1️⃣ Remove FK dependency by nulling product_id in orderitems
$stmt = $conn->prepare("UPDATE orderitems SET product_id=NULL WHERE product_id=?");
$stmt->bind_param("i", $id);
$stmt->execute();

// 2️⃣ Get image first
$stmt = $conn->prepare("SELECT image_url FROM products WHERE product_id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if ($product) {
    $imagePath = "../assets/images/products/" . $product['image_url'];
    if (file_exists($imagePath)) {
        unlink($imagePath);
    }
}

// 3️⃣ Delete product
$stmt = $conn->prepare("DELETE FROM products WHERE product_id=?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: dashboard.php#manage-products");
exit;
?>
