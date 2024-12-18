<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get product_id and quantity from the form
    $product_id = $_POST['product_id'] ?? null;
    $quantity = $_POST['quantity'] ?? 1;

    // Validate product_id and quantity
    if (!$product_id || !is_numeric($product_id) || $product_id <= 0) {
        die("Error: Invalid product ID.");
    }

    if (!is_numeric($quantity) || $quantity <= 0) {
        die("Error: Invalid quantity. Quantity must be a positive number.");
    }

    // Cast quantity to an integer to avoid any unintended values
    $quantity = (int)$quantity;

    // Initialize cart if not already done
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Add product to cart or update quantity
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }

    // Redirect back to the cart page
    header("Location: cart.php");
    exit();
}
