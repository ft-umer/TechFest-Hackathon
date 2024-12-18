<?php
session_start();

// Redirect to login if the user is not logged in or not a farmer
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'farmer') {
    header("Location: login.php");
    exit;
}

// Include database connection
include('config.php');

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = htmlspecialchars($_POST['name']);
    $price = floatval($_POST['price']);
    $description = htmlspecialchars($_POST['description']);
    $image = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];

    $target_dir = "uploads/";
    $target_file = $target_dir . basename($image);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if the file is an actual image
    $check = getimagesize($image_tmp);
    if ($check === false) {
        $error_message = "File is not a valid image.";
        $uploadOk = 0;
    }

    // Check file size (2MB limit)
    if ($_FILES['image']['size'] > 2000000) {
        $error_message = "Image is too large. Max size is 2MB.";
        $uploadOk = 0;
    }

    // Allow only certain file formats
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($imageFileType, $allowed_extensions)) {
        $error_message = "Only JPG, JPEG, PNG, and GIF files are allowed.";
        $uploadOk = 0;
    }

    // If all checks pass, attempt to upload the file
    if ($uploadOk == 1) {
        if (move_uploaded_file($image_tmp, $target_file)) {
            $user_id = $_SESSION['user_id']; // Farmer's ID
            $query = "INSERT INTO products (user_id, name, price, description, image) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("issss", $user_id, $name, $price, $description, $target_file);

            if ($stmt->execute()) {
                $success_message = "Product added successfully!";
            } else {
                $error_message = "Error adding product: " . $stmt->error;
            }
        } else {
            $error_message = "There was an error uploading your image.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex justify-center items-center min-h-screen">

    <!-- Navbar -->
    <nav class="bg-green-600 text-white p-4 w-full fixed top-0 ">
        <div class="container mx-auto flex justify-between items-center">
        <a href="index.php" class="text-xl font-bold">Farmer's Digital Marketplace</a>
            <h1 class="text-xl font-bold">
                Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?> (Farmer)
            </h1>
            <a href="logout.php" class="hover:underline">Logout</a>
        </div>
    </nav>

    <!-- Add Product Form Container -->
    <div class="bg-white shadow-lg rounded-lg p-8 mt-16 w-full max-w-md mx-auto">
        <h2 class="text-3xl font-bold text-gray-700 mb-6 text-center">Add Product</h2>

        <!-- Success/Error Messages -->
        <?php if (isset($error_message)) : ?>
            <div class="text-red-500 bg-red-100 p-3 rounded-lg mb-4 text-center">
                <?php echo $error_message; ?>
            </div>
        <?php elseif (isset($success_message)) : ?>
            <div class="text-green-500 bg-green-100 p-3 rounded-lg mb-4 text-center">
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>

        <!-- Form -->
        <form action="add-product.php" method="POST" enctype="multipart/form-data" class="space-y-4">
            <!-- Product Name -->
            <div>
                <label for="name" class="block text-gray-700">Product Name</label>
                <input type="text" name="name" id="name" required
                       class="w-full border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-green-500 focus:outline-none"
                       placeholder="Enter product name">
            </div>

            <!-- Price -->
            <div>
                <label for="price" class="block text-gray-700">Price ($)</label>
                <input type="number" name="price" id="price" step="0.01" required
                       class="w-full border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-green-500 focus:outline-none"
                       placeholder="Enter price">
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-gray-700">Description</label>
                <textarea name="description" id="description" rows="4" required
                          class="w-full border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-green-500 focus:outline-none"
                          placeholder="Enter product description"></textarea>
            </div>

            <!-- Image Upload -->
            <div>
                <label for="image" class="block text-gray-700">Upload Image</label>
                <input type="file" name="image" id="image" required
                       class="w-full border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-green-500 focus:outline-none">
            </div>

            <!-- Submit Button -->
            <button type="submit"
                    class="w-full bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 transition">
                Add Product
            </button>
        </form>

        <!-- Back Link -->
        <div class="mt-4 text-center">
            <a href="index.php" class="text-green-600 hover:underline">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
