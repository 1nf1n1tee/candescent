<?php
include "../config/db.php";

$id = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $caption = $_POST['caption'];
    $sort    = $_POST['sort_order'];

    $stmt = $conn->prepare("
        UPDATE carouselimages 
        SET caption=?, sort_order=? 
        WHERE carousel_id=?
    ");

    $stmt->bind_param("sii", $caption, $sort, $id);
    $stmt->execute();

    header("Location: dashboard.php#carousel");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM carouselimages WHERE carousel_id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$slide = $stmt->get_result()->fetch_assoc();
?>

<form method="POST">
    <h3>Edit Slide</h3>
    <img src="../assets/images/carousel/<?php echo $slide['image_url']; ?>" width="200"><br><br>

    <input type="text" name="caption" value="<?php echo htmlspecialchars($slide['caption']); ?>">
    <input type="number" name="sort_order" value="<?php echo $slide['sort_order']; ?>">
    <button type="submit">Update</button>
</form>
