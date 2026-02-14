<?php
include "../config/db.php";

$id = $_GET['id'];

// Get image name first
$stmt = $conn->prepare("SELECT image_url FROM carouselimages WHERE carousel_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if ($data) {
    unlink("../assets/images/carousel/" . $data['image_url']);
}

// Delete from DB
$stmt = $conn->prepare("DELETE FROM carouselimages WHERE carousel_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: dashboard.php#carousel");
exit;
