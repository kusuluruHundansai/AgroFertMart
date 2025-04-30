<?php
session_start();
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['product_id'])) {
    echo json_encode(['success' => false, 'message' => 'No product ID provided.']);
    exit;
}

$productId = (int)$data['product_id'];

$conn = new mysqli("localhost", "root", "", "agri");
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'DB connection failed.']);
    exit;
}

// Fetch product details
$stmt = $conn->prepare("SELECT id, name, price FROM products WHERE id = ?");
$stmt->bind_param("i", $productId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Product not found.']);
    exit;
}

$product = $result->fetch_assoc();

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = ['items' => [], 'total' => 0];
}

// Add or update product in cart
if (isset($_SESSION['cart']['items'][$productId])) {
    $_SESSION['cart']['items'][$productId]['quantity'] += 1;
} else {
    $_SESSION['cart']['items'][$productId] = [
        'name' => $product['name'],
        'price' => $product['price'],
        'quantity' => 1
    ];
}

// Update total
$totalQuantity = 0;
foreach ($_SESSION['cart']['items'] as $item) {
    $totalQuantity += $item['quantity'];
}
$_SESSION['cart']['total'] = $totalQuantity;

echo json_encode(['success' => true, 'count' => $totalQuantity]);
?>
