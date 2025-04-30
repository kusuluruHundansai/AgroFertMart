<?php
require 'php/db.php';
require 'php/cart-functions.php';

if (!isset($_GET['payment_id'])) {
    echo "Payment failed or cancelled.";
    exit();
}

$payment_id = $_GET['payment_id'];
$cart = $_SESSION['cart'] ?? [];

if (empty($cart)) {
    echo "Cart is empty.";
    exit();
}

// Calculate total amount
$total = 0;
foreach ($cart as $id => $qty) {
    $stmt = $pdo->prepare("SELECT price, name FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $product = $stmt->fetch();
    $subtotal = $product['price'] * $qty;
    $total += $subtotal;
}

// Store the order
$stmt = $pdo->prepare("INSERT INTO orders (name, address, payment_method, total, status) VALUES (?, ?, ?, ?, ?)");
$stmt->execute(["Razorpay Buyer", "N/A", "Razorpay-UPI", $total, 'paid']);
$order_id = $pdo->lastInsertId();

// Store order items
$orderItemStmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
foreach ($cart as $id => $qty) {
    $stmt = $pdo->prepare("SELECT price, name FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $product = $stmt->fetch();
    $orderItemStmt->execute([$order_id, $id, $qty, $product['price']]);
}

// Clear the cart
unset($_SESSION['cart']);
?>

<div class="container mx-auto py-10 text-center">
  <h2 class="text-3xl font-bold text-green-600 mb-4">Payment Successful!</h2>
  <p>Your payment ID: <strong><?= htmlspecialchars($payment_id) ?></strong></p>
  <p>Your order has been placed successfully. We’ll begin processing it shortly.</p>
  <p>Order ID: <strong><?= $order_id ?></strong></p>
  <p>Thank you for shopping with us! You will receive an email confirmation soon.</p>
</div>
