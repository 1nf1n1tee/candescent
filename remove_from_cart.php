<?php
include "config/db.php";

$cart_item_id = $_GET['id'];
$conn->query("DELETE FROM CartItems WHERE cart_item_id='$cart_item_id'");

header("Location: cart.php");
