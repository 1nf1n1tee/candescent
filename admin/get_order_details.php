<?php
include "../config/db.php";

$order_id = intval($_GET['id']);

$order = $conn->query("SELECT * FROM Orders WHERE order_id = $order_id")->fetch_assoc();

echo "<h2>Order #".$order['order_id']."</h2>";
echo "<p><strong>Customer:</strong> ".$order['customer_name']."</p>";
echo "<p><strong>Phone:</strong> ".$order['phone_number']."</p>";
echo "<p><strong>Address:</strong> ".$order['shipping_address']."</p>";
echo "<p><strong>Delivery:</strong> ".$order['delivery_type']."</p>";
echo "<p><strong>Payment:</strong> ".$order['payment_method']."</p>";
echo "<p><strong>Status:</strong> ".$order['status']."</p>";
echo "<p><strong>Total:</strong> ৳".$order['total_amount']."</p>";

echo "<hr><h3>Items</h3>";

$items = $conn->query("
  SELECT OI.*, P.name 
  FROM OrderItems OI
  JOIN Products P ON OI.product_id = P.product_id
  WHERE order_id = $order_id
");

while($item = $items->fetch_assoc()){
  echo "<p>".$item['name']." × ".$item['quantity']." (৳".$item['price'].")</p>";
}

echo "<br><a href='generate_invoice.php?id=".$order_id."' target='_blank'>Download Invoice (PDF)</a>";
