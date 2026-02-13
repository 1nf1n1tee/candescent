<?php
include "config/db.php";
if (session_status() == PHP_SESSION_NONE) session_start();

if(!isset($_GET['id'])){
    echo "Product not found.";
    exit;
}

$product_id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT * FROM Products WHERE product_id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows == 0){
    echo "Product not found.";
    exit;
}

$product = $result->fetch_assoc();
?>

<?php include "header.php"; ?>

<section class="product-page">

  <div class="product-container">

    <div class="product-image">
      <img src="assets/images/products/<?php echo htmlspecialchars($product['image_url']); ?>">
    </div>

    <div class="product-info">
      <h1><?php echo htmlspecialchars($product['name']); ?></h1>

      <p class="product-price">৳<?php echo $product['price']; ?></p>

      <p class="product-description">
        <?php echo nl2br(htmlspecialchars($product['description'])); ?>
      </p>

      <div class="product-actions">
      <button 
      class="add-cart-btn"
      onclick="addToCart('<?php echo $product['product_id']; ?>')">
      Add to Cart
    </button>

      </div>

    </div>

  </div>

</section>

<script>
function addToCart(productId) {

  if (!productId) {
    alert("Invalid product ID");
    return;
  }

  fetch("add_to_cart.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded"
    },
    body: "id=" + encodeURIComponent(productId) + "&quantity=1"
  })
  .then(response => response.text())
  .then(data => {

    if (data.includes("Invalid") || data.includes("not found")) {
      alert(data);
      return;
    }

    // Update cart badge if exists
    const cartBadge = document.getElementById("cart-count");
    if (cartBadge) {
      cartBadge.innerText = data;
    }

    alert("✨ Product added to cart!");
  })
  .catch(error => {
    console.error("Error:", error);
    alert("Something went wrong.");
  });
}
</script>



<?php include "footer.php"; ?>
