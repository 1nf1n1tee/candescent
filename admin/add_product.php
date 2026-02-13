<?php
include "../config/db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name     = $_POST['name'];
    $price    = $_POST['price'];
    $desc     = $_POST['description'];
    $stock    = $_POST['stock'];
    $category = $_POST['category'];

    // Image upload
    $imageName = time() . "_" . basename($_FILES['image']['name']);
    $imagePath = "/candescent/assets/images/products/" . $imageName;
    $target    = "../assets/images/products/" . $imageName;

    move_uploaded_file($_FILES['image']['tmp_name'], $target);

    // INSERT PRODUCT (NOW MATCHES DB)
    $sql = "INSERT INTO Products 
            (name, description, price, stock_quantity, category, image_url)
            VALUES 
            ('$name', '$desc', '$price', '$stock', '$category', '$imageName')";

    if ($conn->query($sql)) {
        header("Location: dashboard.php?success=1");
        exit;
    } else {
        die("DB ERROR: " . $conn->error);
    }
}
