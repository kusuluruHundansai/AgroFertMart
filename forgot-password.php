<?php
require_once 'db.php';
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';

    if (empty($username)) {
        $error = "Please enter your username";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch();

        if ($user) {
            $token = bin2hex(random_bytes(16));
            $expiry = date("Y-m-d H:i:s", time() + 3600); // 1 hour

            $update = $pdo->prepare("UPDATE users SET reset_token = :token, token_expire = :expiry WHERE id = :id");
            $update->execute([
                ':token' => $token,
                ':expiry' => $expiry,
                ':id' => $user['id']
            ]);

            $resetLink = "http://yourdomain.com/reset-password.php?token=$token";
            $success = "Password reset link: <a href='$resetLink' class='text-blue-600 underline'>$resetLink</a>";
        } else {
            $error = "Username not found";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password - AgroFertMart</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-green-50 min-h-screen flex items-center justify-center px-4 py-8">
    <div class="w-full max-w-md p-8 bg-white rounded-3xl shadow-2xl border border-green-200">
        <h2 class="text-2xl font-bold text-green-700 mb-4 text-center">🔑 Forgot Password</h2>

        <?php if ($error): ?>
            <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4"><?= htmlspecialchars($error) ?></div>
        <?php elseif ($success): ?>
            <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4"><?= $success ?></div>
        <?php endif; ?>

        <form method="POST" action="" class="space-y-4">
            <div>
                <label for="username" class="block mb-1 text-sm font-semibold text-gray-700">Username</label>
                <input type="text" id="username" name="username" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400">
            </div>
            <button type="submit"
                class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded-lg text-lg font-semibold transition">
                Send Reset Link
            </button>
            <div class="text-center mt-4">
                <a href="login.php">
                    <button type="button" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition duration-200">
                        Back to Login
                    </button>
                </a>
            </div>
        </form>
    </div>
</body>
</html>
