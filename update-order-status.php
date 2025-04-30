<?php
session_start();
require_once "db.php";

// Ensure user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Check if the action and order_id are set in the form
if (isset($_POST['action'], $_POST['order_id'])) {
    $order_id = $_POST['order_id'];
    $action = $_POST['action'];

    // Handle the update or cancel action
    if ($action == 'update') {
        try {
            // Example: Update order status to 'processed'
            $stmt = $pdo->prepare("UPDATE orders SET status = 'processed' WHERE id = :order_id");
            $stmt->bindParam(':order_id', $order_id);
            $stmt->execute();
            
            // Redirect back to the admin dashboard
            header("Location: admin-dashboard.php");
            exit();
        } catch (PDOException $e) {
            die("Database error: " . $e->getMessage());
        }
    } elseif ($action == 'cancel') {
        try {
            // Example: Cancel the order
            $stmt = $pdo->prepare("UPDATE orders SET status = 'canceled' WHERE id = :order_id");
            $stmt->bindParam(':order_id', $order_id);
            $stmt->execute();
            
            // Redirect back to the admin dashboard
            header("Location: admin-dashboard.php");
            exit();
        } catch (PDOException $e) {
            die("Database error: " . $e->getMessage());
        }
    }
} else {
    // Redirect to the admin dashboard if no valid action is passed
    header("Location: admin-dashboard.php");
    exit();
}
