<?php
include('config.php');
session_start();

$total = 0;

// Check if user is logged in and cart is not empty
if (!isset($_SESSION['user_id'], $_SESSION['user_name'], $_SESSION['user_email'])) {
    header("Location: login.php");
    exit;
}

if (empty($_SESSION['cart'])) {
    die("Error: Your cart is empty.");
}

// Fetch user details
$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$user_email = $_SESSION['user_email'];
$cart = $_SESSION['cart'];

// Initialize variables
$total_price = 0;
$order_details = [];
$products = [];

// Fetch product details
if (!empty($cart)) {
    $product_ids = implode(',', array_keys($cart));
    $query = "SELECT id, name, price FROM products WHERE id IN ($product_ids)";
    $result = $conn->query($query);

    if (!$result) {
        die("Error fetching products: " . $conn->error);
    }

    $products = $result->fetch_all(MYSQLI_ASSOC);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $address = $_POST['address'] ?? '';

    if (empty($address)) {
        die("Error: Address is required.");
    }

    // Calculate total price and prepare order details
    foreach ($products as $product) {
        $product_id = $product['id'];
        $quantity = $cart[$product_id];
        $price = $product['price'];
        $subtotal = $quantity * $price;

        $total_price += $subtotal;

        $order_details[] = [
            'product_id' => $product_id,
            'quantity' => $quantity,
            'price' => $price,
        ];
    }

    // Insert order into `orders` table
    $order_date = date('Y-m-d H:i:s');
    $stmt = $conn->prepare("INSERT INTO orders (user_name, user_email, address, total_price, order_date) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssds", $user_name, $user_email, $address, $total_price, $order_date);

    if (!$stmt->execute()) {
        die("Error placing order: " . $stmt->error);
    }

    // Get the order ID of the newly inserted order
    $order_id = $stmt->insert_id;

    // Insert each product into `orderdetails` table
    $stmt = $conn->prepare("INSERT INTO orderdetails (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    foreach ($order_details as $detail) {
        $stmt->bind_param("iiid", $order_id, $detail['product_id'], $detail['quantity'], $detail['price']);
        if (!$stmt->execute()) {
            die("Error inserting order details: " . $stmt->error);
        }
    }

    // Send email to farmers
    foreach ($farmer_orders as $farmer_email => $farmer_products) {
        $subject = "New Order Received from $user_name";
        $message = "You have received a new order.\n\n";
        $message .= "Delivery Address: $address\n\n";
        $message .= "Order Details:\n";

        foreach ($farmer_products as $product) {
            $message .= "- " . $product['product_name'] . 
                        " (Quantity: " . $product['quantity'] . 
                        ", Price: $" . number_format($product['price'], 2) . 
                        ", Subtotal: $" . number_format($product['subtotal'], 2) . ")\n";
        }

        $message .= "\nThank you for using Farmer's Digital Marketplace.";

        // Send email
        if (!mail($farmer_email, $subject, $message)) {
            echo "Failed to send email to $farmer_email.";
        }
    }


    // Clear the cart after successful order placement
    unset($_SESSION['cart']);

    // Redirect to a success page
    header("Location: order_confirmation.php");
    exit;
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-8">
        <h1 class="text-3xl font-bold text-center text-gray-700 mb-6">Checkout</h1>
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Order Summary</h2>
            <?php if (!empty($products)): ?>
                <!-- Display Order Summary -->
                <table class="w-full border-collapse border border-gray-300">
    <thead>
        <tr class="bg-gray-200">
            <th class="text-left px-4 py-2">Product Name</th>
            <th class="text-left px-4 py-2">Price</th>
            <th class="text-left px-4 py-2">Quantity</th>
            <th class="text-left px-4 py-2">Total</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($products as $product): ?>
            <?php 
                $quantity = $cart[$product['id']];
                $subtotal = $product['price'] * $quantity;
                $total += $subtotal;
            ?>
            <tr>
                <td class="text-left border px-4 py-2"><?php echo htmlspecialchars($product['name']); ?></td>
                <td class="text-left border px-4 py-2">$<?php echo htmlspecialchars($product['price']); ?></td>
                <td class="text-left border px-4 py-2"><?php echo htmlspecialchars($quantity); ?></td>
                <td class="text-left border px-4 py-2">$<?php echo number_format($subtotal, 2); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

            <?php else: ?>
                <p>Your cart is empty. Add products to proceed with checkout.</p>
            <?php endif; ?>

            <div class="text-right mt-4">
                <p class="text-lg font-bold">Total: $<?php echo number_format($total, 2); ?></p>
            </div>
        </div>
        <form method="POST" action="place_order.php" class="mt-6">
    <label for="address" class="block text-gray-700 font-bold mb-2">Delivery Address:</label>
    <input
        type="text"
        id="address"
        name="address"
        class="w-full border rounded py-2 px-3 mb-4"
        placeholder="Enter your delivery address"
        required
    >
    <button type="submit" class="bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700">
        Place Order
    </button>
</form>

    </div>
</body>
</html>
