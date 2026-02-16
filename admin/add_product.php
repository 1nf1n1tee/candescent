<?php
include "../config/db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name     = trim($_POST['name']);
    $price    = floatval($_POST['price']);
    $desc     = trim($_POST['description']);
    $stock    = intval($_POST['stock']);
    $category = trim($_POST['category']);

    // Validate required fields
    if(empty($name) || $price <= 0 || $stock < 0){
        die("Invalid input data.");
    }

    // Handle image
    if(empty($_FILES['image']['name'])){
        die("Image is required.");
    }

    $imageName = time() . "_" . basename($_FILES['image']['name']);
    $target = "../assets/images/products/" . $imageName;

    if(!move_uploaded_file($_FILES['image']['tmp_name'], $target)){
        die("Image upload failed.");
    }

    $stmt = $conn->prepare("
        INSERT INTO Products 
        (name, description, price, stock_quantity, category, image_url, created_at)
        VALUES (?, ?, ?, ?, ?, ?, NOW())
    ");

    $stmt->bind_param("ssdiss",
        $name,
        $desc,
        $price,
        $stock,
        $category,
        $imageName
    );

    if(!$stmt->execute()){
        die("SQL Error: " . $stmt->error);
    }

    header("Location: dashboard.php#manage-products");
    exit;
}
?>
