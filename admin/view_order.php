<?php
include "../config/db.php";

$id = intval($_GET['id']);

/* ==============================
   1️⃣ FETCH ORDER
============================== */

$orderStmt = $conn->prepare("SELECT * FROM Orders WHERE order_id = ?");
$orderStmt->bind_param("i", $id);
$orderStmt->execute();
$order = $orderStmt->get_result()->fetch_assoc();

if(!$order){
    die("Order not found.");
}

/* ==============================
   2️⃣ FETCH DELIVERY CHARGE FROM deliverycharges TABLE
============================== */

$deliveryStmt = $conn->prepare("
    SELECT charge 
    FROM deliverycharges 
    WHERE type = ?
");

$deliveryStmt->bind_param("s", $order['delivery_type']);
$deliveryStmt->execute();
$deliveryResult = $deliveryStmt->get_result()->fetch_assoc();

$deliveryCharge = $deliveryResult ? $deliveryResult['charge'] : 0;

/* ==============================
   3️⃣ FETCH ORDER ITEMS
============================== */

$itemsStmt = $conn->prepare("
    SELECT product_name, quantity, price 
    FROM OrderItems
    WHERE order_id = ?
");
$itemsStmt->bind_param("i", $id);
$itemsStmt->execute();
$items = $itemsStmt->get_result();

/* ==============================
   4️⃣ CALCULATE SUBTOTAL
============================== */

$subtotal = 0;
$itemsArray = [];

while($row = $items->fetch_assoc()){
    $subtotal += $row['price'] * $row['quantity'];
    $itemsArray[] = $row;
}

$grandTotal = $subtotal + $deliveryCharge;
?>

<h2>Invoice #<?php echo $order['order_id']; ?></h2>

<p><strong>Customer:</strong> <?php echo htmlspecialchars($order['customer_name']); ?></p>
<p><strong>Phone:</strong> <?php echo htmlspecialchars($order['phone_number']); ?></p>
<p><strong>Delivery:</strong> <?php echo htmlspecialchars($order['delivery_type']); ?></p>
<p><strong>Payment:</strong> <?php echo htmlspecialchars($order['payment_method']); ?></p>

<hr>

<table style="width:100%; border-collapse: collapse;">
<tr>
  <th align="left">Product</th>
  <th align="center">Qty</th>
  <th align="right">Price</th>
</tr>

<?php foreach($itemsArray as $item): ?>
<tr>
  <td><?php echo htmlspecialchars($item['product_name']); ?></td>
  <td align="center"><?php echo $item['quantity']; ?></td>
  <td align="right">৳<?php echo number_format($item['price'], 2); ?></td>
</tr>
<?php endforeach; ?>

</table>

<hr>

<h4>Subtotal: ৳<?php echo number_format($subtotal, 2); ?></h4>
<h4>Delivery: ৳<?php echo number_format($deliveryCharge, 2); ?></h4>

<hr>

<h3>Total: ৳<?php echo number_format($grandTotal, 2); ?></h3>

<br>

<button class="invoice-btn"
onclick="window.open('download_invoice.php?id=<?php echo $order['order_id']; ?>')">
Download Invoice (PDF)
</button>

<button onclick="document.getElementById('orderModal').style.display='none'">
Close
</button>
