<?php
include "../config/db.php";

$id = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

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

        $stmt->bind_param("ssdiis",
            $name, $desc, $price, $stock, $category, $id
        );
    }

    $stmt->execute();
    header("Location: dashboard.php#manage-products");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM Products WHERE product_id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();
?>

<form method="POST" enctype="multipart/form-data">
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
