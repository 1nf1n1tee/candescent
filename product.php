<?php
include "config/db.php";
include "header.php";

$id = intval($_GET['id']);
$product = $conn->query("SELECT * FROM Products WHERE product_id = $id")->fetch_assoc();
?>

<section class="product-detail">
  <div class="product-detail-container">
    <img src="assets/images/products/<?php echo $product['image_url']; ?>">
    <div class="product-info">
      <h2><?php echo $product['name']; ?></h2>
      <p class="price">$<?php echo $product['price']; ?></p>
      <p><?php echo $product['description']; ?></p>
      <button>Add to Cart</button>
    </div>
  </div>
</section>

<?php include "footer.php"; ?>
