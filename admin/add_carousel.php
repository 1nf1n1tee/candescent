<?php
include "../config/db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $caption = $_POST['caption'];

    // 🔥 Auto-generate next sort_order
    $result = $conn->query("SELECT MAX(sort_order) AS max_order FROM carouselimages");
    $row = $result->fetch_assoc();
    $nextOrder = ($row['max_order'] !== null) ? $row['max_order'] + 1 : 0;

    $imageName = time() . "_" . basename($_FILES['image']['name']);
    $target = "../assets/images/carousel/" . $imageName;

    move_uploaded_file($_FILES['image']['tmp_name'], $target);

    $stmt = $conn->prepare("
        INSERT INTO carouselimages (image_url, caption, sort_order)
        VALUES (?, ?, ?)
    ");

    $stmt->bind_param("ssi", $imageName, $caption, $nextOrder);
    $stmt->execute();

    header("Location: dashboard.php#carousel");
    exit;
}
