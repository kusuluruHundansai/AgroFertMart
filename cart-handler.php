<?php
session_start();
require_once "db.php";
require_once "cart-functions.php";

// Check if user is logged in; if not, set default user ID
$userId = $_SESSION['user_id'] ?? 1;

// Handle POST requests
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitize the product ID
    $productId = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;

    if ($productId > 0) {
        // Check for 'remove_from_cart' action
        if (isset($_POST['remove_from_cart'])) {
            $isRemoved = removeFromCart($productId); // Call the function to remove the product

            if ($isRemoved) {
                $_SESSION['message'] = 'Product removed from cart successfully!';
            } else {
                $_SESSION['message'] = 'Failed to remove product from cart.';
            }
        }
    } else {
        $_SESSION['message'] = 'Invalid product ID.';
    }

    // Redirect back to the cart page
    header("Location: cart.php");
    exit;
}
?>
