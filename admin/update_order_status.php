<?php
include "../config/db.php";

if($_SERVER["REQUEST_METHOD"] === "POST"){

    $order_id = intval($_POST['order_id']);
    $status   = $_POST['status'];

    $allowed = ['pending','processing','delivered'];

    if(!in_array($status, $allowed)){
        die("Invalid status.");
    }

    $stmt = $conn->prepare("
        UPDATE Orders 
        SET status=? 
        WHERE order_id=?
    ");

    $stmt->bind_param("si", $status, $order_id);

    if(!$stmt->execute()){
        die("Status update failed.");
    }

    header("Location: dashboard.php#orders");
    exit;
}
?>
