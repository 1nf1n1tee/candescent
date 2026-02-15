<?php
include "../config/db.php";

/* =========================
   HANDLE UPDATE
========================= */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $id      = intval($_POST['carousel_id']);
    $caption = trim($_POST['caption']);
    $sort    = intval($_POST['sort_order']);

    $stmt = $conn->prepare("
        UPDATE carouselimages 
        SET caption=?, sort_order=? 
        WHERE carousel_id=?
    ");

    $stmt->bind_param("sii", $caption, $sort, $id);

    if(!$stmt->execute()){
        echo "error: " . $stmt->error;
        exit;
    }

    echo "success";
    exit;
}

/* =========================
   LOAD SLIDE
========================= */
if (!isset($_GET['id'])) {
    exit("Invalid ID");
}

$id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT * FROM carouselimages WHERE carousel_id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$slide = $stmt->get_result()->fetch_assoc();

if (!$slide) {
    exit("Slide not found");
}
?>

<form id="carouselEditForm">

<input type="hidden" name="carousel_id" value="<?php echo $slide['carousel_id']; ?>">

<h3>Edit Slide</h3>

<img src="../assets/images/carousel/<?php echo $slide['image_url']; ?>" width="200"><br><br>

<input type="text" name="caption"
       value="<?php echo htmlspecialchars($slide['caption']); ?>"
       required>

<input type="number" name="sort_order"
       value="<?php echo $slide['sort_order']; ?>"
       required>

<br><br>
<button type="submit">Update</button>

</form>
