<?php
session_start();
require_once "db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    try {
        $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$product_id]);

        $_SESSION['message'] = "Product deleted successfully!";
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            $_SESSION['message'] = "Cannot delete this product. It is associated with existing orders.";
        } else {
            $_SESSION['message'] = "An error occurred: " . $e->getMessage();
        }
    }
    header("Location: admin-products.php");
    exit();
} else {
    $_SESSION['message'] = "Invalid product ID.";
    header("Location: admin-products.php");
    exit();
}
