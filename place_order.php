<?php
include('config.php');
session_start();

// Retrieve user and cart information
$cart = $_SESSION['cart'] ?? []; // Cart data
$user_name = $_SESSION['user_name'] ?? null;
$user_email = $_SESSION['user_email'] ?? null;
$address = $_POST['address'] ?? 'Unknown Address'; // Address from the form
$total_price = 0;

if (!$user_name || !$user_email || empty($cart)) {
    die("Error: Missing user information or empty cart.");
}

try {
    // Enable MySQL error reporting
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    // Start transaction
    $conn->begin_transaction();

    // Step 1: Calculate total price
    foreach ($cart as $product_id => $quantity) {
        $query = "SELECT price FROM products WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();

        if (!$product) {
            throw new Exception("Product not found: ID $product_id");
        }

        $subtotal = $product['price'] * $quantity;
        $total_price += $subtotal;
    }

    // Step 2: Insert into `orders` table
    $query = "INSERT INTO orders (user_name, user_email, address, total_price, order_date) VALUES (?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sssd', $user_name, $user_email, $address, $total_price);

    if (!$stmt->execute()) {
        throw new Exception("Failed to insert into orders table: " . $stmt->error);
    }

    $order_id = $stmt->insert_id; // Get the newly created order ID

    // Step 3: Insert into `order_details` table
    foreach ($cart as $product_id => $quantity) {
        $query = "SELECT price FROM products WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();

        if (!$product) {
            throw new Exception("Product not found: ID $product_id");
        }

        $price = $product['price']; // Get product price
        $query = "INSERT INTO order_details (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('iiid', $order_id, $product_id, $quantity, $price);

        if (!$stmt->execute()) {
            throw new Exception("Failed to insert into order_details table: " . $stmt->error);
        }
    }

    // Commit transaction
    $conn->commit();

    // Clear cart after order is placed
    unset($_SESSION['cart']);

    // Redirect to confirmation page
    header("Location: order_confirmation.php");
    exit();

} catch (Exception $e) {
    // Rollback transaction if there’s an error
    $conn->rollback();
    die("Error placing order: " . $e->getMessage());
}
?>
