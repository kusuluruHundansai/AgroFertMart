<?php
session_start();
require 'db.php';
require 'cart-functions.php';

// Get raw POST data
$data = json_decode(file_get_contents("php://input"), true);

// Validate product ID
$productId = isset($data['product_id']) ? (int)$data['product_id'] : 0;

header('Content-Type: application/json');

if ($productId <= 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid product ID.'
    ]);
    exit();
}

// Optional: Check if product exists in DB
$stmt = $pdo->prepare("SELECT id FROM products WHERE id = ?");
$stmt->execute([$productId]);

if ($stmt->rowCount() === 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Product not found.'
    ]);
    exit();
}

// Add to cart
if (addToCart($productId)) {
    echo json_encode([
        'success' => true,
        'message' => 'Product added to cart.',
        'count' => getCartCount()
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Failed to add product to cart.'
    ]);
}
