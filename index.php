<?php
include "config/db.php";
include "header.php";

$result = $conn->query("SELECT p.*, pi.image_url
FROM Products p
LEFT JOIN ProductImages pi ON p.product_id = pi.product_id
GROUP BY p.product_id;
");
?>

<section class="products">
  <h2>Featured Collection</h2>

  <div class="product-grid">
    <?php while ($row = $result->fetch_assoc()): ?>
      <div class="product-card">
        <img src="<?php echo $row['image_url']; ?>">
        <h3><?php echo $row['name']; ?></h3>
        <p>$<?php echo $row['price']; ?></p>
        <button onclick="location.href='add_to_cart.php?id=<?php echo $row['product_id']; ?>'">Add to Cart</button>
      </div>
    <?php endwhile; ?>
  </div>
</section>

<?php include "footer.php"; ?>
