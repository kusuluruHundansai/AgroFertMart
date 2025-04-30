<?php
require_once 'db.php';

$token = $_GET['token'] ?? '';
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'];
    $password = trim($_POST['password']);

    if (empty($password)) {
        $error = "Please enter a new password";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE reset_token = :token AND token_expire > NOW()");
        $stmt->execute([':token' => $token]);
        $user = $stmt->fetch();

        if ($user) {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $update = $pdo->prepare("UPDATE users SET password = :password, reset_token = NULL, token_expire = NULL WHERE id = :id");
            $update->execute([
                ':password' => $hashed,
                ':id' => $user['id']
            ]);
            $success = "Password successfully updated. <a href='login.php' class='text-blue-600 underline'>Login now</a>";
        } else {
            $error = "Invalid or expired token";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password - AgroFertMart</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-green-50 min-h-screen flex items-center justify-center px-4 py-8">
    <div class="w-full max-w-md p-8 bg-white rounded-3xl shadow-2xl border border-green-200">
        <h2 class="text-2xl font-bold text-green-700 mb-4 text-center">🔐 Reset Your Password</h2>

        <?php if ($error): ?>
            <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4"><?= htmlspecialchars($error) ?></div>
        <?php elseif ($success): ?>
            <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4"><?= $success ?></div>
        <?php endif; ?>

        <?php if (!$success): ?>
        <form method="POST" action="" class="space-y-4">
            <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

            <div>
                <label for="password" class="block mb-1 text-sm font-semibold text-gray-700">New Password</label>
                <input type="password" id="password" name="password" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400">
            </div>

            <button type="submit"
                class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded-lg text-lg font-semibold transition">
                Update Password
            </button>
        </form>
        <?php endif; ?>
    </div>
</body>
</html>
