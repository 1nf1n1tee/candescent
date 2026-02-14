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

<body class="dashboard-body">

<div class="admin-wrapper">

  <!-- SIDEBAR -->
  <aside class="sidebar">
    <div class="logo">Candescént</div>
    <nav>
      <a href="#add-product">➕ Add Product</a>
      <a href="#manage-products">📦 Manage Products</a>
      <a href="#carousel">🖼 Manage Carousel</a>
      <a href="#orders">📑 Orders</a>
    </nav>
    <div class="sidebar-footer">
      <span>Welcome, <?php echo htmlspecialchars($username); ?> ✨</span><br>
      <a href="logout.php" class="logout-btn">Logout</a>
    </div>
  </aside>

  <!-- MAIN CONTENT -->
  <main class="main-content">

    <!-- Welcome Card -->
    <section class="welcome">
      <h1>Welcome, <?php echo htmlspecialchars($username); ?> ✨</h1>
      <p>Manage your jewellery store with elegance and ease.</p>
    </section>

    <!-- Add Product -->
    <section class="card-section" id="add-product">
      <h3>Add New Product</h3>
      <form action="add_product.php" method="POST" enctype="multipart/form-data">
        <div class="grid-2">
          <input type="text" name="name" placeholder="Product Name" required>
          <input type="number" name="price" placeholder="Price" required>
        </div>
        <textarea name="description" placeholder="Product Description"></textarea>
        <div class="grid-2">
          <input type="number" name="stock" placeholder="Stock Quantity" required>
          <input type="text" name="category" placeholder="Category">
        </div>
        <input type="file" name="image" required>
        <button type="submit" class="primary-btn">Add Product</button>
      </form>
    </section>

    <!-- Manage Products -->
    <section class="card-section" id="manage-products">
      <h3>Existing Products</h3>
      <table class="elegant-table">
        <thead>
          <tr>
            <th>Image</th>
            <th>Name</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Category</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $products = $conn->query("SELECT * FROM Products ORDER BY created_at DESC");
          while($product = $products->fetch_assoc()):
          ?>
          <tr>
            <td><img src="../assets/images/products/<?php echo $product['image_url']; ?>" class="thumb"></td>
            <td><?php echo htmlspecialchars($product['name']); ?></td>
            <td>৳<?php echo $product['price']; ?></td>
            <td><?php echo $product['stock_quantity']; ?></td>
            <td><?php echo htmlspecialchars($product['category']); ?></td>
            <td>
              <button class="edit-btn" onclick="openProductModal(<?php echo $product['product_id']; ?>)">Edit</button>
              <a class="delete-link" href="delete_product.php?id=<?php echo $product['product_id']; ?>" onclick="return confirm('Delete this product?')">Delete</a>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </section>

    <!-- Manage Carousel -->
    <section class="card-section" id="carousel">
      <h3>Carousel Management</h3>

      <form action="add_carousel.php" method="POST" enctype="multipart/form-data">
        <input type="file" name="image" required>
        <input type="text" name="caption" placeholder="Caption (optional)">
        <button type="submit" class="primary-btn">Add Slide</button>
      </form>

      <hr>

      <h4>Existing Slides</h4>
      <table class="elegant-table">
        <thead>
          <tr>
            <th>Preview</th>
            <th>Caption</th>
            <th>Sort</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $slides = $conn->query("SELECT * FROM carouselimages ORDER BY sort_order ASC");
          while($slide = $slides->fetch_assoc()):
          ?>
          <tr>
            <td><img src="../assets/images/carousel/<?php echo $slide['image_url']; ?>" class="thumb"></td>
            <td><?php echo htmlspecialchars($slide['caption']); ?></td>
            <td><?php echo $slide['sort_order']; ?></td>
            <td>
              <button class="edit-btn" onclick="openCarouselModal(<?php echo $slide['carousel_id']; ?>)">Edit</button>
              <a class="delete-link" href="delete_carousel.php?id=<?php echo $slide['carousel_id']; ?>" onclick="return confirm('Delete this slide?')">Delete</a>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </section>

    <!-- Orders -->
    <section class="card-section" id="orders">
      <h3>Orders</h3>
      <table class="elegant-table">
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
              <button class="edit-btn" onclick="openOrderModal(<?php echo $order['order_id']; ?>)">View</button>
              <a class="delete-link" href="delete_order.php?id=<?php echo $order['order_id']; ?>" onclick="return confirm('Delete order?')">Delete</a>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </section>

  </main>
</div>

<!-- MODALS -->
<div id="productModal" class="modal"><div class="modal-content" id="productForm"></div></div>
<div id="carouselModal" class="modal"><div class="modal-content" id="carouselForm"></div></div>

<script>
// Modal functions
function openModal(id) { document.getElementById(id).style.display = 'flex'; }
function closeModal(id) { document.getElementById(id).style.display = 'none'; }
</script>

</body>
</html>
