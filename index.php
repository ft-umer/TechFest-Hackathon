<?php
include('config.php');
session_start();

// Fetch all reviews from the database
$review_query = $conn->prepare("SELECT r.rating, r.comment, u.name 
                                FROM reviews r 
                                JOIN users u ON r.user_id = u.id");
$review_query->execute();
$reviews = $review_query->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmer's Digital Marketplace</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
</head>
<body class="bg-gray-100">
    <!-- Navbar -->
    <nav class="bg-green-600 text-white p-4" data-aos="fade-down">
        <div class="container mx-auto flex justify-between items-center">
            <a href="index.php" class="text-xl font-bold">Farmer's Digital Marketplace</a>
            <div>
                <a href="index.php" class="hover:underline px-4">Home</a>
                <a href="products.php" class="hover:underline px-4">Products</a>
                <?php if(isset($_SESSION['user_role'])): ?>
                    <a href="logout.php" class="hover:underline px-4">Logout</a>
                <?php else: ?>
                    <a href="signup.php" class="hover:underline px-4">SignUp</a>
                    <a href="login.php" class="hover:underline px-4">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section 
        class="bg-cover bg-center h-96 flex items-center justify-center text-white" 
        style="background-image: url('./uploads/hero-image.jpg');" 
        data-aos="zoom-in"
        data-aos-duration="1000"
    >
        <div class="text-center bg-black bg-opacity-50 p-8 rounded">
            <h1 class="text-4xl font-bold">Welcome to Farmer's Digital Marketplace</h1>
            <p class="mt-4 text-lg">Connecting farmers and buyers for fresh, organic produce.</p>
            <?php if ($_SESSION['user_role'] == 'buyer'): ?>
                <a href="products.php" class="mt-6 inline-block bg-green-600 py-2 px-4 rounded text-white hover:bg-green-700">Browse Products</a>
            <?php elseif ($_SESSION['user_role'] == 'farmer'): ?>
                <a href="add-product.php" class="mt-6 inline-block bg-green-600 py-2 px-4 rounded text-white hover:bg-green-700">Add Products</a>
            <?php endif; ?>
        </div>
    </section>

    <!-- About Us Section -->
    <section class="container mx-auto mt-8" data-aos="fade-right" data-aos-duration="1000">
        <h2 class="text-3xl font-bold text-gray-700 text-center mb-6">About Us</h2>
        <p class="text-gray-600 text-center max-w-2xl mx-auto">
            At Farmer's Digital Marketplace, our mission is to empower local farmers and provide buyers with access to fresh, organic, and high-quality produce. 
            We aim to bridge the gap between the farm and your table, promoting sustainability and healthy living.
        </p>
    </section>

    <!-- Featured Products -->
    <section class="container mx-auto mt-8" data-aos="fade-up" data-aos-duration="1000">
        <h2 class="text-3xl font-bold text-gray-700 text-center mb-6">Featured Products</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
           <?php
           $query = "SELECT * FROM products LIMIT 4";
           $result = $conn->query($query);

           if ($result->num_rows > 0) {
               while ($product = $result->fetch_assoc()) {
                   echo '
                   <div class="bg-white shadow-md rounded-lg overflow-hidden" data-aos="flip-left">
                       <img src="' . htmlspecialchars($product['image']) . '" alt="' . htmlspecialchars($product['name']) . '" class="h-40 w-full object-cover">
                       <div class="p-4">
                           <h2 class="text-lg font-bold text-gray-800">' . htmlspecialchars($product['name']) . '</h2>
                           <p class="text-gray-600 mt-2">' . htmlspecialchars($product['description']) . '</p>
                           <p class="text-green-600 font-bold mt-2">$' . htmlspecialchars($product['price']) . '</p>
                           <a href="product-details.php?id=' . htmlspecialchars($product['id']) . '"
                               class="block mt-4 text-center bg-green-600 text-white py-2 rounded hover:bg-green-700">View Details</a>
                       </div>
                   </div>';
               }
           } else {
               echo '<p class="text-center text-gray-600 col-span-4">No products found.</p>';
           }
           ?>
        </div>
        <div class="text-center mt-6">
            <a href="products.php" class="text-green-600 hover:underline">Explore more...</a>
        </div>
    </section>


    <!-- Reviews Section -->
    <section class="container mx-auto mt-12" data-aos="fade-up" data-aos-duration="1000">
    <h2 class="text-3xl font-bold text-gray-700 text-center mb-6">What People Say About Us</h2>
    <div class="max-w-3xl mx-auto space-y-4">
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
</section>
    

    <!-- Footer -->
    <footer class="bg-gray-800 text-white mt-8 py-6">
        <div class="container mx-auto text-center">
            <p>&copy; 2024 Farmer's Digital Marketplace. All rights reserved.</p>
            <div class="mt-4">
                <a href="contact.php" class="text-gray-400 hover:text-white mx-2">Contact Us</a> | 
                <a href="terms.php" class="text-gray-400 hover:text-white mx-2">Terms of Service</a> | 
                <a href="privacy.php" class="text-gray-400 hover:text-white mx-2">Privacy Policy</a>
            </div>
        </div>
    </footer>

    <!-- Initialize AOS -->
    <script>
        AOS.init();
    </script>
</body>
</html>
