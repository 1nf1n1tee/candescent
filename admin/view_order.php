<?php
include "../config/db.php";

$id = intval($_GET['id']);

$orderStmt = $conn->prepare("SELECT * FROM Orders WHERE order_id=?");
$orderStmt->bind_param("i", $id);
$orderStmt->execute();
$order = $orderStmt->get_result()->fetch_assoc();
$subtotal = 0;

$itemsStmt = $conn->prepare("
    SELECT oi.*, p.name 
    FROM OrderItems oi
    JOIN Products p ON oi.product_id = p.product_id
    WHERE oi.order_id=?
");
$itemsStmt->bind_param("i", $id);
$itemsStmt->execute();
$items = $itemsStmt->get_result();

while($row = $items->fetch_assoc()){
    $subtotal += $row['price'] * $row['quantity'];
    $itemsArray[] = $row;
}

$deliveryCharge = 0;

if($order['delivery_type'] === 'inside_dhaka'){
    $deliveryCharge = 60;
} elseif($order['delivery_type'] === 'outside_dhaka'){
    $deliveryCharge = 120;
}

$itemsStmt = $conn->prepare("
    SELECT oi.*, p.name 
    FROM OrderItems oi
    JOIN Products p ON oi.product_id = p.product_id
    WHERE oi.order_id=?
");
$itemsStmt->bind_param("i", $id);
$itemsStmt->execute();
$items = $itemsStmt->get_result();
?>

<h2>Invoice #<?php echo $order['order_id']; ?></h2>

<p><strong>Customer:</strong> <?php echo htmlspecialchars($order['customer_name']); ?></p>
<p><strong>Phone:</strong> <?php echo $order['phone_number']; ?></p>
<p><strong>Delivery:</strong> <?php echo $order['delivery_type']; ?></p>
<p><strong>Payment:</strong> <?php echo $order['payment_method']; ?></p>

<hr>

<table style="width:100%; border-collapse: collapse;">
<tr>
  <th align="left">Product</th>
  <th align="center">Qty</th>
  <th align="right">Price</th>
</tr>

<?php while($item = $items->fetch_assoc()): ?>
<tr>
  <td><?php echo $item['name']; ?></td>
  <td align="center"><?php echo $item['quantity']; ?></td>
  <td align="right">৳<?php echo $item['price']; ?></td>
</tr>
<?php endwhile; ?>

</table>

<hr>

<h4>Subtotal: ৳<?php echo $subtotal; ?></h4>
<h4>Delivery: ৳<?php echo $deliveryCharge; ?></h4>

<hr>

<h3>Total: ৳<?php echo $subtotal + $deliveryCharge; ?></h3>


<br>

<button class="invoice-btn" onclick="window.open('download_invoice.php?id=<?php echo $order['order_id']; ?>')">
Download Invoice (PDF)
</button>

<button onclick="document.getElementById('orderModal').style.display='none'">
Close
</button>
