<?php
include('config.php');
session_start();

// Redirect to login if user is not logged in
if (!isset($_SESSION['user_role'])) {
    header("Location: login.php");
    exit;
}

$product_id = $_GET['id'];

// Fetch product details
$product_query = $conn->prepare("SELECT * FROM products WHERE id = ?");
$product_query->bind_param('i', $product_id);
$product_query->execute();
$product = $product_query->get_result()->fetch_assoc();

// Fetch reviews
$review_query = $conn->prepare("SELECT r.rating, r.comment, u.name 
                                FROM reviews r 
                                JOIN users u ON r.user_id = u.id 
                                WHERE r.product_id = ?");
$review_query->bind_param('i', $product_id);
$review_query->execute();
$reviews = $review_query->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <!-- Navbar -->
    <nav class="bg-green-600 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
        <a href="index.php" class="text-xl font-bold">Farmer's Digital Marketplace</a>
            <h1 class="text-xl font-bold">
                Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?> 
                <?php echo ($_SESSION['user_role'] == 'buyer') ? '(buyer)' : '(farmer)'; ?>
            </h1>
            <a href="logout.php" class="hover:underline">Logout</a>
        </div>
    </nav>

    <!-- Content Section -->
    <div class="container mx-auto mt-8">
        <!-- Product Card -->
        <div class="bg-white shadow-lg rounded-lg p-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-4 text-center">
                <?php echo htmlspecialchars($product['name']); ?>
            </h1>
            <p class="text-center text-gray-600 text-lg font-semibold mb-2">
                $<?php echo htmlspecialchars($product['price']); ?>
            </p>
            <p class="text-gray-700 leading-relaxed mb-6 text-center">
                <?php echo nl2br(htmlspecialchars($product['description'])); ?>
            </p>
        </div>

        <!-- Reviews Section -->
        <div class="mt-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Customer Reviews</h2>
            <div class="space-y-4">
                <?php while ($review = $reviews->fetch_assoc()): ?>
                    <div class="bg-gray-50 p-4 rounded-lg shadow-md">
                        <p class="text-gray-800 font-semibold">
                            <?php echo htmlspecialchars($review['name']); ?>
                            <span class="text-green-600">rated <?php echo $review['rating']; ?>/5</span>
                        </p>
                        <p class="text-gray-600 mt-2">
                            <?php echo nl2br(htmlspecialchars($review['comment'])); ?>
                        </p>
                    </div>
                <?php endwhile; ?>
                <?php if ($reviews->num_rows === 0): ?>
                    <p class="text-gray-500 text-center">No reviews yet. Be the first to add a review!</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Add Review Form -->
        <div class="mt-8">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Add Your Review</h3>
            <form action="review-handler.php" method="POST" class="bg-white shadow-md rounded-lg p-6">
                <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product_id); ?>">

                <!-- Rating -->
                <div class="mb-4">
                    <label for="rating" class="block text-gray-700 font-medium">Rating (1-5)</label>
                    <input type="number" id="rating" name="rating" min="1" max="5" required
                        class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-green-500 focus:outline-none"
                        placeholder="Enter your rating">
                </div>

                <!-- Comment -->
                <div class="mb-4">
                    <label for="comment" class="block text-gray-700 font-medium">Comment</label>
                    <textarea id="comment" name="comment" rows="4" required
                        class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-green-500 focus:outline-none"
                        placeholder="Write your comment here"></textarea>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full bg-green-600 text-white py-2 px-4 rounded-lg font-semibold hover:bg-green-700 transition">
                    Submit Review
                </button>
            </form>
        </div>

        <!-- Back Button -->
        <div class="mt-6 text-center">
            <?php if ($_SESSION['user_role'] == 'buyer'): ?>
                <a href="products.php" class="text-green-600 hover:underline">Back to Products</a>
            <?php else: ?>
                <a href="add-product.php" class="text-green-600 hover:underline">Add Another Product</a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
