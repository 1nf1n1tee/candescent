<?php
include "../config/db.php";

$order_id = $_GET['id'];

$stmt = $conn->prepare("DELETE FROM Orders WHERE order_id=?");
$stmt->bind_param("i", $order_id);
$stmt->execute();

header("Location: dashboard.php#orders");
exit;
?>
