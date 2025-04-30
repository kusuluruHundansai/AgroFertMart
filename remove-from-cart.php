<!-- <?php
session_start();
require 'db.php';
require 'cart-functions.php';

$data = json_decode(file_get_contents("php://input"), true);
$productId = (int)($data['product_id'] ?? 0);

if (removeFromCart($productId)) {
    echo json_encode([
        'success' => true,
        'total' => $_SESSION['cart']['total'],
        'count' => getCartCount()
    ]);
} else {
    echo json_encode(['success' => false]);
}
?> -->
