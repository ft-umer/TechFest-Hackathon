<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Successful</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-20 text-center">
        <h1 class="text-3xl font-bold text-green-600">Thank you for your order!</h1>
        <p class="mt-4 text-gray-700">Your order has been placed successfully.</p>
        <a href="products.php" class="bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700 mt-6 inline-block">Continue Shopping</a>
    </div>
</body>
</html>
