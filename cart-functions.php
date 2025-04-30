<?php
require 'db.php';

// Initialize cart if not already done
function initCart() {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [
            'items' => [],
            'total' => 0
        ];
    }
}

// Get all items in cart
function getCartItems() {
    initCart();
    return $_SESSION['cart']['items'];
}

// Add product to cart
function addToCart($productId, $quantity = 1) {
    initCart();
    $productId = (int)$productId;
    $quantity = max(1, (int)$quantity);

    global $pdo;
    $stmt = $pdo->prepare("SELECT id, name, price FROM products WHERE id = ?");
    $stmt->execute([$productId]);
    $product = $stmt->fetch();

    if ($product) {
        if (isset($_SESSION['cart']['items'][$productId])) {
            $_SESSION['cart']['items'][$productId]['quantity'] += $quantity;
        } else {
            $_SESSION['cart']['items'][$productId] = [
                'id' => $productId,
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => $quantity
            ];
        }
        updateCartTotal();
        return true;
    }
    return false;
}

// Remove a product from cart
function removeFromCart($productId) {
    initCart();
    $productId = (int)$productId;
    if (isset($_SESSION['cart']['items'][$productId])) {
        unset($_SESSION['cart']['items'][$productId]);
        updateCartTotal();
        return true;
    }
    return false;
}

// Update product quantity
function updateCartQuantity($productId, $quantity) {
    initCart();
    $productId = (int)$productId;
    $quantity = max(0, (int)$quantity);

    if ($quantity === 0) {
        return removeFromCart($productId);
    }

    if (isset($_SESSION['cart']['items'][$productId])) {
        $_SESSION['cart']['items'][$productId]['quantity'] = $quantity;
        updateCartTotal();
        return true;
    }
    return false;
}

// Recalculate cart total
function updateCartTotal() {
    initCart();
    $total = 0;
    foreach ($_SESSION['cart']['items'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    $_SESSION['cart']['total'] = $total;
}

// Return total quantity of all items
function getCartCount() {
    initCart();
    return array_sum(array_column($_SESSION['cart']['items'], 'quantity'));
}

// Empty the cart
function clearCart() {
    $_SESSION['cart'] = [
        'items' => [],
        'total' => 0
    ];
}
?>
