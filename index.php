<?php
include "config/db.php";
include "header.php";

$carouselQuery = $conn->query("SELECT * FROM carouselimages ORDER BY sort_order ASC");
$result = $conn->query("
    SELECT * FROM Products 
    ORDER BY created_at DESC
");

?>
<!-- Carousel Start -->
<div class="carousel">
    <div class="slides">
        <?php while($slide = $carouselQuery->fetch_assoc()): ?>
            <div class="slide">
                <img src="assets/images/carousel/<?php echo $slide['image_url']; ?>" 
                     alt="<?php echo htmlspecialchars($slide['caption']); ?>">
                <?php if(!empty($slide['caption'])): ?>
                    <div class="caption">
                        <?php echo htmlspecialchars($slide['caption']); ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    </div>

    <div class="dots"></div>
</div>
<!-- Carousel End -->

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
      <?php if ($row['stock_quantity'] > 0): ?>
      <button 
      onclick="event.stopPropagation(); addToCart('<?php echo $row['product_id']; ?>')">
      Add to Cart
      </button>
    <?php else: ?>
      <button disabled class="out-stock-btn">
      Out of Stock
      </button>
    <?php endif; ?>
    </div>
  <?php endwhile; ?>
  </div>
</section>
<script>
const slides = document.querySelector('.slides');
const slide = document.querySelectorAll('.slide');
const dotsContainer = document.querySelector('.dots');

let index = 0;
const totalSlides = slide.length;

// Create dots
for(let i = 0; i < totalSlides; i++){
    const dot = document.createElement('span');
    dot.addEventListener('click', () => goToSlide(i));
    dotsContainer.appendChild(dot);
}

const dots = document.querySelectorAll('.dots span');

function updateCarousel(){
    slides.style.transform = `translateX(-${index * 100}%)`;

    dots.forEach(dot => dot.classList.remove('active'));
    dots[index].classList.add('active');
}

function goToSlide(i){
    index = i;
    updateCarousel();
}

// Auto slide
function autoSlide(){
    index++;
    if(index >= totalSlides){
        index = 0;
    }
    updateCarousel();
}

updateCarousel();
setInterval(autoSlide, 4000);
</script>

<?php include "footer.php"; ?>
