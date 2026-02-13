<?php
include "config/db.php";
include "header.php";

$result = $conn->query("
    SELECT * FROM Products 
    ORDER BY created_at DESC
");

?>

<section class="products">
  <h2>Featured Collection</h2>

  <div class="product-grid">
  <?php while ($row = $result->fetch_assoc()): ?>
    <div class="product-card"
     onclick="window.location.href='product.php?id=<?php echo urlencode($row['product_id']); ?>'">
      <img src="assets/images/products/<?php echo $row['image_url']; ?>" 
          alt="<?php echo $row['name']; ?>">
      <h3><?php echo $row['name']; ?></h3>
      <p>$<?php echo $row['price']; ?></p>
      <button 
  onclick="event.stopPropagation(); addToCart({
    id: <?php echo $row['product_id']; ?>,
    name: '<?php echo htmlspecialchars($row['name'], ENT_QUOTES); ?>',
    price: <?php echo $row['price']; ?>,
    image: '<?php echo $row['image_url']; ?>'
  })">
  Add to Cart
</button>
    </div>
  <?php endwhile; ?>
  </div>
</section>

<?php include "footer.php"; ?>
