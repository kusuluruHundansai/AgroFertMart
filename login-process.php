<?php
session_start();
require_once "db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $role = $_POST['login_type'] ?? 'user';

    if (empty($username) || empty($password)) {
        $_SESSION['error'] = "Please fill in all fields.";
        header("Location: login.php");
        exit();
    }

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
    $stmt->execute([':username' => $username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] === 'admin') {
            header("Location: admin-dashboard.php");
        } else {
            header("Location: index.php");
        }
        exit();
    } else {
        $_SESSION['error'] = "Invalid username or password.";
        header("Location: login.php");
        exit();
    }
}
?>
