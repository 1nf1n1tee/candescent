<?php
include "../config/db.php";

$order_id = $_POST['order_id'];
$status = $_POST['status'];

$stmt = $conn->prepare("UPDATE Orders SET status=? WHERE order_id=?");
$stmt->bind_param("si", $status, $order_id);
$stmt->execute();

header("Location: dashboard.php#orders");
exit;
?>
