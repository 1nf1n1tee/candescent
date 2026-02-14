<?php
include "../config/db.php";

$id = $_GET['id'];

// Get image first
$stmt = $conn->prepare("SELECT image_url FROM Products WHERE product_id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if ($data) {
    unlink("../assets/images/products/" . $data['image_url']);
}

// Delete product
$stmt = $conn->prepare("DELETE FROM Products WHERE product_id=?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: dashboard.php#manage-products");
exit;
