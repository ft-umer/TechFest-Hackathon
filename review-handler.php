<?php
include('config.php');
session_start();

// Replace with user ID from session after login
$user_id = $_SESSION['user_id'];
$product_id = $_POST['product_id'];
$rating = $_POST['rating'];
$comment = $_POST['comment'];

$query = $conn->prepare("INSERT INTO reviews (product_id, user_id, rating, comment) VALUES (?, ?, ?, ?)");
$query->bind_param('iiis', $product_id, $user_id, $rating, $comment);

if ($query->execute()) {
    header("Location: product-details.php?id=" . $product_id);
} else {
    echo "Error submitting review.";
}
?>
