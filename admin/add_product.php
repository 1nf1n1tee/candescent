<?php
include "../config/db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name     = $_POST['name'];
    $price    = $_POST['price'];
    $desc     = $_POST['description'];
    $stock    = $_POST['stock'];
    $category = $_POST['category'];

    $imageName = time() . "_" . basename($_FILES['image']['name']);
    $target = "../assets/images/products/" . $imageName;

    move_uploaded_file($_FILES['image']['tmp_name'], $target);

    $stmt = $conn->prepare("
        INSERT INTO Products 
        (name, description, price, stock_quantity, category, image_url)
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param("ssdiss",
        $name,
        $desc,
        $price,
        $stock,
        $category,
        $imageName
    );

    $stmt->execute();
    if(!$stmt->execute()){
    die("SQL Error: " . $stmt->error);
}

    header("Location: dashboard.php#manage-products");
    exit;
}
