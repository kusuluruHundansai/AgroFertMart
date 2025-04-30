<?php
session_start();
require_once "db.php";

$error = '';
$username = '';
$login_type = 'user'; // default role is user

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $login_type = $_POST['login_type'] ?? 'user';

    if (empty($username) || empty($password)) {
        $error = "Please fill in all fields";
    } else {
        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
            $stmt->execute([':username' => $username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role']; 

                if ($_SESSION['role'] === 'admin') {
                    header("Location: admin-dashboard.php");
                    exit();
                } else {
                    header("Location: index.php");
                    exit();
                }
            } else {
                $error = "Invalid username or password";
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
    <title>Login - AgroFertMart</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-green-50 min-h-screen flex items-center justify-center px-4 py-8">

    <div class="w-full max-w-md p-8 bg-white rounded-3xl shadow-2xl border border-green-200 animate-fade-in">
        <div class="text-center mb-6">
            <h1 class="text-4xl font-extrabold text-green-700 mb-2">🌱 AgroFertMart</h1>
            <p class="text-gray-500">Welcome back! Please login to continue.</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="bg-red-100 text-red-700 px-4 py-2 rounded-lg mb-4 text-sm font-medium">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="" class="space-y-5">
            <div>
                <label for="username" class="block mb-1 text-sm font-semibold text-gray-700">Username</label>
                <input type="text" id="username" name="username" value="<?= htmlspecialchars($username) ?>" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400">
            </div>

            <div>
                <label for="password" class="block mb-1 text-sm font-semibold text-gray-700">Password</label>
                <input type="password" id="password" name="password" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400">
                <div class="text-right mt-1">
                    <a href="forgot-password.php" class="text-sm text-green-600 hover:underline">Forgot Password?</a>
                </div>
            </div>

            <div>
                <label for="login_type" class="block mb-1 text-sm font-semibold text-gray-700">Login as</label>
                <select name="login_type" id="login_type"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-green-400">
                    <option value="user" <?= $login_type === 'user' ? 'selected' : '' ?>>User</option>
                    <option value="admin" <?= $login_type === 'admin' ? 'selected' : '' ?>>Admin</option>
                </select>
            </div>

            <button type="submit"
                class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded-lg text-lg font-semibold transition duration-200">
                Login
            </button>
        </form>

        <div class="text-center mt-6 text-sm">
            Don't have an account? 
            <a href="signup.php" class="text-green-600 font-semibold hover:underline">Sign up here</a>
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
