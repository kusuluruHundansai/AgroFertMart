<?php
session_start();
require 'db.php';
require 'cart-functions.php';

header('Content-Type: application/json');

$input = json_decode(file_get_contents("php://input"), true);

// Validate input
if (!isset($input['action'], $input['product_id'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$productId = (int)$input['product_id'];
$action = $input['action'];

switch ($action) {
    case 'add':
        $quantity = isset($input['quantity']) ? (int)$input['quantity'] : 1;
        if (addToCart($productId, $quantity)) {
            echo json_encode([
                'success' => true,
                'message' => 'Product added to cart',
                'count' => getCartCount(),
                'total' => $_SESSION['cart']['total']
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to add product']);
        }
        break;

    case 'update':
        $quantity = isset($input['quantity']) ? (int)$input['quantity'] : 1;
        if ($quantity < 1) {
            echo json_encode(['success' => false, 'message' => 'Invalid quantity']);
            exit;
        }

        if (updateCartQuantity($productId, $quantity)) {
            echo json_encode([
                'success' => true,
                'message' => 'Cart updated',
                'subtotal' => $_SESSION['cart']['items'][$productId]['price'] * $_SESSION['cart']['items'][$productId]['quantity'],
                'total' => $_SESSION['cart']['total']
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update quantity']);
        }
        break;

    case 'remove':
        if (removeFromCart($productId)) {
            echo json_encode([
                'success' => true,
                'message' => 'Product removed from cart',
                'count' => getCartCount(),
                'total' => $_SESSION['cart']['total']
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to remove product']);
        }
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Unknown action']);
        break;
}
?>
