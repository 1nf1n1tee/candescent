<?php
include "../config/db.php";

/* ---------------- FETCH PRODUCT FOR MODAL ---------------- */

if ($_SERVER["REQUEST_METHOD"] === "GET") {

    if (!isset($_GET['id']) || empty($_GET['id'])) {
        echo "<p>Invalid product ID.</p>";
        exit;
    }

    $id = intval($_GET['id']);

    $stmt = $conn->prepare("SELECT * FROM Products WHERE product_id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if (!$product) {
        echo "<p>Product not found.</p>";
        exit;
    }
}

/* ---------------- UPDATE PRODUCT ---------------- */

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $id       = intval($_POST['product_id']);
    $name     = $_POST['name'];
    $price    = $_POST['price'];
    $desc     = $_POST['description'];
    $stock    = $_POST['stock'];
    $category = $_POST['category'];

    if (!empty($_FILES['image']['name'])) {

        $imageName = time() . "_" . basename($_FILES['image']['name']);
        $target = "../assets/images/products/" . $imageName;
        move_uploaded_file($_FILES['image']['tmp_name'], $target);

        $stmt = $conn->prepare("
            UPDATE Products 
            SET name=?, description=?, price=?, stock_quantity=?, category=?, image_url=? 
            WHERE product_id=?
        ");

        $stmt->bind_param("ssdiisi",
            $name, $desc, $price, $stock, $category, $imageName, $id
        );

    } else {

        $stmt = $conn->prepare("
            UPDATE Products 
            SET name=?, description=?, price=?, stock_quantity=?, category=? 
            WHERE product_id=?
        ");

        $stmt->bind_param("ssdiii",
            $name, $desc, $price, $stock, $category, $id
        );
    }

    $stmt->execute();
    if(!$stmt->execute()){
    die("Update failed: " . $stmt->error);
}


    echo "<script>window.location.reload();</script>";
    exit;
}
?>

<form method="POST" enctype="multipart/form-data">

<input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">

<h3>Edit Product</h3>

<img src="../assets/images/products/<?php echo $product['image_url']; ?>" width="150"><br><br>

<input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
<input type="number" name="price" value="<?php echo $product['price']; ?>" required>
<textarea name="description"><?php echo htmlspecialchars($product['description']); ?></textarea>
<input type="number" name="stock" value="<?php echo $product['stock_quantity']; ?>" required>
<input type="text" name="category" value="<?php echo htmlspecialchars($product['category']); ?>">

<label>Change Image (optional)</label>
<input type="file" name="image">

<button type="submit">Update Product</button>

</form>
