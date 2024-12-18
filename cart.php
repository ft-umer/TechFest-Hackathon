<?php
include('config.php');
session_start();

$cart = $_SESSION['cart'] ?? [];
$total = 0;

if (!empty($cart)) {
    $product_ids = implode(',', array_keys($cart));
    $query = "SELECT * FROM products WHERE id IN ($product_ids)";
    $result = $conn->query($query);
    $products = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $products = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
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
        <h1 class="text-3xl font-bold text-center text-gray-700 mb-6">Your Cart</h1>
        <?php if (!empty($products)): ?>
            <table class="table-auto w-full bg-white shadow-md rounded-lg">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="text-left px-4 py-2">Product</th>
                        <th class="text-left px-4 py-2">Price</th>
                        <th class="text-left px-4 py-2">Quantity</th>
                        <th class="text-left px-4 py-2">Subtotal</th>
                        <th class="text-left px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
<?php foreach ($products as $product): ?>
    <?php 
        // Calculate subtotal for each product
        $subtotal = $product['price'] * $cart[$product['id']];
        // Update the total amount
        $total += $subtotal; 
    ?>
    <tr>
        <td class="border px-4 py-2"><?php echo htmlspecialchars($product['name']); ?></td>
        <td class="border px-4 py-2">$<?php echo htmlspecialchars($product['price']); ?></td>
        <td class="border px-4 py-2"><?php echo $cart[$product['id']]; ?></td>
        <td class="border px-4 py-2">$<?php echo number_format($subtotal, 2); ?></td>
        <td class="border px-4 py-2">
            <form action="remove_from_cart.php" method="POST">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                <button type="submit" class="bg-red-600 text-white py-1 px-2 rounded hover:bg-red-700">Remove</button>
            </form>
        </td>
    </tr>
<?php endforeach; ?>
</tbody>

            </table>
            <div class="text-right mt-4">
                <!-- Display the updated total -->
                <p class="text-lg font-bold">Total: $<?php echo number_format($total, 2); ?></p>
                <a href="checkout.php" class="bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700 mt-4 inline-block">Proceed to Checkout</a>
            </div>
        <?php else: ?>
            <p class="text-center text-gray-600">Your cart is empty.</p>
        <?php endif; ?>
    </div>
</body>
</html>
