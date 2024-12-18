<?php
include('config.php');
session_start();

$query = "SELECT * FROM products";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
     <!-- Navbar -->
     <nav class="bg-green-600 text-white p-4" data-aos="fade-down">
        <div class="container mx-auto flex justify-between items-center">
            <a href="index.php" class="text-xl font-bold">Farmer's Digital Marketplace</a>
            <div>
                <a href="index.php" class="hover:underline px-4">Home</a>
                <a href="products.php" class="hover:underline px-4">Products</a>
                <a href="cart.php" class="hover:underline px-4">Cart</a>
                <?php if(isset($_SESSION['user_role'])): ?>
                    <a href="logout.php" class="hover:underline px-4">Logout</a>
                <?php else: ?>
                    <a href="signup.php" class="hover:underline px-4">SignUp</a>
                    <a href="login.php" class="hover:underline px-4">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    <div class="container mx-auto mt-8">
        <h1 class="text-3xl font-bold text-center text-gray-700 mb-6">Products</h1>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php while ($product = $result->fetch_assoc()): ?>
                <div class="bg-white shadow-md rounded-lg overflow-hidden">
                    <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="h-40 w-full object-cover">
                    <div class="p-4">
                        <h2 class="text-lg font-bold text-gray-800"><?php echo htmlspecialchars($product['name']); ?></h2>
                        <p class="text-gray-600 mt-2"><?php echo htmlspecialchars($product['description']); ?></p>
                        <p class="text-green-600 font-bold mt-2">$<?php echo htmlspecialchars($product['price']); ?></p>
                        <form action="add_to_cart.php" method="POST" class="mt-4">
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                            <input type="number" name="quantity" value="1" min="1" class="border border-gray-300 rounded p-2 w-full">
                            <button type="submit" class="bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700 mt-2 w-full">
                                Add to Cart
                            </button>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>
