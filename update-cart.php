<?php
session_start();
require 'db.php';
require 'cart-functions.php';

// Get the raw input and decode JSON data
$data = json_decode(file_get_contents("php://input"), true);

// Validate input
$productId = (int)($data['product_id'] ?? 0);
$quantity = (int)($data['quantity'] ?? 1);

// Check if quantity is a valid number
if ($quantity < 1) {
    echo json_encode(['success' => false, 'message' => 'Invalid quantity']);
    exit;
}

// Update cart quantity
if (updateCartQuantity($productId, $quantity)) {
    echo json_encode([
        'success' => true,
        'subtotal' => $_SESSION['cart']['items'][$productId]['price'] * $_SESSION['cart']['items'][$productId]['quantity'],
        'total' => $_SESSION['cart']['total']
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update quantity']);
}
?>
