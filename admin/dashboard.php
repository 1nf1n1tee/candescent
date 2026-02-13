<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['admin'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard | Candescént</title>
  <link rel="stylesheet" href="../assets/css/admin.css">
</head>

<body>

<div class="container">

  <div class="logout">
  <a href="logout.php" class="logout-btn">Logout</a>
</div>

  <!-- Welcome -->
  <div class="welcome">
    <h1>Welcome, <?php echo htmlspecialchars($username); ?> ✨</h1>
    <p>Manage your jewellery store with elegance and ease.</p>
  </div>

  <!-- Feature Cards -->
  <div class="cards">
    <div class="card" onclick="location.href='#add-product'">
      <h3>➕ Add Product</h3>
      <p>Add new jewellery items to your store.</p>
    </div>

    <div class="card" onclick="location.href='#carousel'">
      <h3>🖼 Manage Carousel</h3>
      <p>Update homepage banners and promotions.</p>
    </div>

    <div class="card" onclick="location.href='#orders'">
      <h3>📦 Orders</h3>
      <p>View all customer checkouts.</p>
    </div>
  </div>

  <!-- Add Product -->
  <section class="section" id="add-product">
    <h3>Add New Product</h3>

    <form action="add_product.php" method="POST" enctype="multipart/form-data">

      <div class="row">
        <input type="text" name="name" placeholder="Product Name" required>
        <input type="number" name="price" placeholder="Price" required>
      </div>

      <textarea name="description" placeholder="Product Description"></textarea>

      <div class="row">
        <input type="number" name="stock" placeholder="Stock Quantity" required>
        <input type="text" name="category" placeholder="Category">
      </div>

      <input type="file" name="image" required>
      <button type="submit">Add Product</button>

    </form>
  </section>

  <!-- Carousel -->
  <section class="section" id="carousel">
    <h3>Manage Carousel</h3>

    <form action="add_carousel.php" method="POST" enctype="multipart/form-data">
      <input type="file" name="image" required>
      <input type="text" name="caption" placeholder="Caption (optional)">
      <button type="submit">Add Carousel Image</button>
    </form>
  </section>

  <!-- Orders -->
  <section class="section" id="orders">
  <h3>Orders</h3>

  <table>
  <thead>
  <tr>
    <th>ID</th>
    <th>Customer</th>
    <th>Phone</th>
    <th>Delivery</th>
    <th>Payment</th>
    <th>Total</th>
    <th>Status</th>
    <th>Actions</th>
  </tr>
  </thead>

  <tbody>

  <?php
  $orders = $conn->query("SELECT * FROM Orders ORDER BY created_at DESC");

  while($order = $orders->fetch_assoc()):
  ?>

  <tr>
  <td>#<?php echo $order['order_id']; ?></td>
  <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
  <td><?php echo $order['phone_number']; ?></td>
  <td><?php echo $order['delivery_type']; ?></td>
  <td><?php echo $order['payment_method']; ?></td>
  <td>৳<?php echo $order['total_amount']; ?></td>

  <td>
  <form action="update_order_status.php" method="POST">
  <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
  <select name="status" onchange="this.form.submit()">
    <option value="pending" <?php if($order['status']=='pending') echo 'selected'; ?>>Pending</option>
    <option value="processing" <?php if($order['status']=='processing') echo 'selected'; ?>>Processing</option>
    <option value="delivered" <?php if($order['status']=='delivered') echo 'selected'; ?>>Delivered</option>
  </select>
  </form>
  </td>

  <td>
  <button class="view-btn" onclick="openOrderModal(<?php echo $order['order_id']; ?>)">
View
</button>
  <!-- <a href="delete_order.php?id=<?php echo $order['order_id']; ?>" onclick="return confirm('Delete order?')">Delete</a> -->
  <button class="delete-btn" onclick="if(confirm('Delete order?')) location.href='delete_order.php?id=<?php echo $order['order_id']; ?>'">Delete</button>
  </td>

  </tr>

  <?php endwhile; ?>

  </tbody>
  </table>
  </section>

</div>
<div id="orderModal" class="modal">
  <div class="modal-content large">
    <span class="close-modal" onclick="closeOrderModal()">&times;</span>
    <div id="orderDetails"></div>
  </div>
</div>

<script>
function openOrderModal(id){
  fetch("get_order_details.php?id=" + id)
  .then(res => res.text())
  .then(data => {
    document.getElementById("orderDetails").innerHTML = data;
    document.getElementById("orderModal").style.display = "flex";
  });
}

function closeOrderModal(){
  document.getElementById("orderModal").style.display = "none";
}
</script>

</body>
</html>