<?php
include ('config.php'); // Include database connection
$info = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $role = htmlspecialchars($_POST['role']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Check if the email already exists
    $check_query = "SELECT * FROM users WHERE email = ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        // Email already exists
        $info = "<p style='color: red; text-align: center;'>Email already exists. Please use a different email.</p>";
    } else {
        // Insert the new user
        $query = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssss", $name, $email, $password, $role);

        if ($stmt->execute()) {
            header("Location: login.php?signup=success");
            exit;
        } else {
            $info = "Error: " . $conn->error;
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex justify-center items-center h-screen">
    <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-sm">
        <h1 class="text-2xl font-bold text-gray-700 mb-4 text-center">Signup</h1>
        <form action="" method="POST" class="space-y-4">
            <?php echo $info; ?>
            <div>
                <label for="name" class="block text-gray-700">Name</label>
                <input type="text" id="name" name="name" required
                    class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-green-500 focus:outline-none">
            </div>
            <div>
                <label for="email" class="block text-gray-700">Email</label>
                <input type="email" id="email" name="email" required
                    class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-green-500 focus:outline-none">
            </div>
            <div>
                <label for="password" class="block text-gray-700">Password</label>
                <input type="password" id="password" name="password" required
                    class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-green-500 focus:outline-none">
            </div>
            <div>
                <label for="role" class="block text-gray-700">Sign up as</label>
                <select id="role" name="role" required
                    class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-green-500 focus:outline-none">
                    <option value="buyer">Buyer</option>
                    <option value="farmer">Farmer</option>
                </select>
            </div>
            <button type="submit"
                class="w-full bg-green-600 text-white py-2 rounded-lg font-semibold hover:bg-green-700 transition">Signup</button>
        </form>
        <p class="text-sm text-gray-500 mt-4 text-center">
            Already have an account? <a href="login.php" class="text-green-600 hover:underline">Login</a>
        </p>
    </div>
</body>
</html>
