<?php
session_start();

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
    <a href="logout.php">Logout</a>
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
          <th>Order ID</th>
          <th>Customer</th>
          <th>Total</th>
          <th>Date</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td colspan="4" style="text-align:center; color:#777;">
            Orders will appear here
          </td>
        </tr>
      </tbody>
    </table>
  </section>

</div>

</body>
</html>
