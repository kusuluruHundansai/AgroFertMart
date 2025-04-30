<?php
session_start();
require_once "db.php";

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if ($password !== $confirm_password) {
        $error = "Passwords don't match";
    } else {
        try {
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $email]);

            if ($stmt->rowCount() > 0) {
                $error = "Username or email already exists";
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
                $stmt->execute([$username, $email, $hashed_password]);

                $_SESSION['user_id'] = $pdo->lastInsertId();
                $_SESSION['username'] = $username;
                header("Location: login.php");
                exit();
            }
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up - AgroFertMart</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-green-50 min-h-screen flex items-center justify-center px-4 py-8">

    <div class="w-full max-w-md p-8 bg-white rounded-3xl shadow-2xl border border-green-200 animate-fade-in">
        <div class="text-center mb-6">
            <h1 class="text-4xl font-extrabold text-green-700 mb-2">🌱 AgroFertMart</h1>
            <p class="text-gray-500">Create your account below.</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="bg-red-100 text-red-700 px-4 py-2 rounded-lg mb-4 text-sm font-medium">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-5">
            <div>
                <label for="username" class="block mb-1 text-sm font-semibold text-gray-700">Username</label>
                <input type="text" id="username" name="username" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400">
            </div>

            <div>
                <label for="email" class="block mb-1 text-sm font-semibold text-gray-700">Email</label>
                <input type="email" id="email" name="email" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400">
            </div>

            <div>
                <label for="password" class="block mb-1 text-sm font-semibold text-gray-700">Password</label>
                <input type="password" id="password" name="password" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400">
            </div>

            <div>
                <label for="confirm_password" class="block mb-1 text-sm font-semibold text-gray-700">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400">
            </div>

            <button type="submit"
                class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded-lg text-lg font-semibold transition duration-200">
                Sign Up
            </button>
        </form>

        <div class="text-center mt-6 text-sm">
            Already have an account? 
            <a href="login.php" class="text-green-600 font-semibold hover:underline">Login here</a>
        </div>
    </div>

    <style>
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fade-in 0.6s ease-out;
        }
    </style>
</body>
</html>
