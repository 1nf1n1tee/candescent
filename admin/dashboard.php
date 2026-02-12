<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="../assets/css/admin.css">
</head>

<body>

<div class="container">

  <div class="welcome">
    <h1>Welcome, Admin ✨</h1>
    <p>Manage your jewellery store with elegance.</p>
  </div>

  <div class="cards">
    <div class="card" onclick="location.href='#add'">
      <h3>Add Product</h3>
    </div>
    <div class="card">
      <h3>Manage Carousel</h3>
    </div>
    <div class="card">
      <h3>Orders</h3>
    </div>
  </div>

  <section id="add">
    <h3>Add Product</h3>

    <form action="add_product.php" method="POST" enctype="multipart/form-data">
      <input type="text" name="name" placeholder="Product Name" required>
      <input type="number" name="price" placeholder="Price" required>
      <textarea name="description" placeholder="Description"></textarea>
      <input type="number" name="stock" placeholder="Stock Quantity">
      <input type="text" name="category" placeholder="Category">
      <input type="file" name="image" required>
      <button type="submit">Add Product</button>
    </form>
  </section>

</div>

</body>
</html>
