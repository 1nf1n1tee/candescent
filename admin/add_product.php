<?php
include "../config/db.php";

$name = $_POST['name'];
$price = $_POST['price'];
$desc = $_POST['description'];
$stock = $_POST['stock'];
$category = $_POST['category'];

$imageName = time() . "_" . $_FILES['image']['name'];
$target = "../assets/images/products/" . $imageName;

move_uploaded_file($_FILES['image']['tmp_name'], $target);

$sql = "INSERT INTO Products 
(name, description, price, stock_quantity, category, image_url)
VALUES 
('$name', '$desc', '$price', '$stock', '$category', 'assets/images/products/$imageName')";

$conn->query($sql);

header("Location: dashboard.php");
