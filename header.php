<!-- cart item number -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Candescént</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

<header class="site-header">
  <button class="menu-btn" onclick="toggleSidebar()">☰</button>

  <div class="logo">
    <img src="assets/images/logo.png" alt="Candescént">
    <span>Candescént</span>
  </div>

  <nav class="desktop-nav">
    <a href="index.php">Home</a>
    <a href="#">Collections</a>
    <a href="#">Contact</a>
  </nav>
  <div class="header-right">
      <a href="cart.php" class="cart-link">
        🛒 Cart (<span id="cart-count"><?php echo $cart_count; ?></span>)
      </a>

    <a href="admin/login.php" class="admin-link">Admin</a>
  </div>
</header>

<?php
$categoryResult = $conn->query("SELECT DISTINCT category FROM Products ORDER BY category ASC");
$categories = [];
while($row = $categoryResult->fetch_assoc()) {
    $categories[] = $row['category'];
}
?>

<aside id="sidebar">
  <div id="overlay" onclick="toggleSidebar()"></div>
  <button class="close-btn" onclick="toggleSidebar()">×</button>

  <h3>Categories</h3>
    <nav>
      <?php foreach($categories as $cat): ?>
        <a href="index.php?category=<?php echo urlencode($cat); ?>"><?php echo htmlspecialchars($cat); ?></a>
      <?php endforeach; ?>
    </nav>
  <hr>

  <h3>Contact</h3>
  <p>Email: info@candescent.com</p>
  <p>Phone: +880-XXXXXXX</p>
</aside>
