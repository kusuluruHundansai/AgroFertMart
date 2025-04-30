<?php
session_start();
require 'db.php';
require 'cart-functions.php';

$cartItems = getCartItems();
$cartTotal = $_SESSION['cart']['total'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Cart | AgroFertMart</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans">

<nav class="bg-green-700 p-4 shadow">
    <div class="max-w-7xl mx-auto flex justify-between items-center">
        <a href="index.php" class="text-white text-2xl font-bold">🌾 AgroFertMart</a>
        <div class="space-x-6">
            <a href="index.php" class="text-white hover:text-yellow-300">Home</a>
            <a href="products.php" class="text-white hover:text-yellow-300">Products</a>
        </div>
    </div>
</nav>

<div class="max-w-6xl mx-auto p-6">
    <h1 class="text-4xl font-bold text-green-800 mb-6">🛒 Shopping Cart</h1>

    <?php if (empty($cartItems)): ?>
        <p class="text-xl text-red-500 mt-8">Your cart is empty.</p>
    <?php else: ?>
        <div class="flex justify-end mt-6">
            <a href="checkout.php" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-xl shadow text-lg font-semibold">
                Proceed to Checkout →
            </a>
        </div>

        <div class="overflow-x-auto mt-4">
            <table class="min-w-full bg-white shadow rounded-lg overflow-hidden">
                <thead class="bg-green-600 text-white">
                    <tr>
                        <th class="p-3 text-left">Product</th>
                        <th class="p-3">Price</th>
                        <th class="p-3">Quantity</th>
                        <th class="p-3">Subtotal</th>
                        <th class="p-3">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cartItems as $item): ?>
                    <tr class="border-b hover:bg-green-50">
                        <td class="p-3"><?= htmlspecialchars($item['name'] ?? 'Unnamed') ?></td>
                        <td class="text-center">₹<?= number_format($item['price'] ?? 0, 2) ?></td>
                        <td class="text-center">
                            <input type="number" min="1" value="<?= htmlspecialchars($item['quantity'] ?? 1) ?>" 
                                class="w-16 border rounded text-center px-2 py-1"
                                onchange="updateQuantity(<?= htmlspecialchars($item['id'] ?? 0) ?>, this.value)">
                        </td>
                        <td class="text-center" id="subtotal-<?= htmlspecialchars($item['id'] ?? 0) ?>">
                            ₹<?= number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 1), 2) ?>
                        </td>
                        <td class="text-center">
                            <button onclick="removeFromCart(<?= htmlspecialchars($item['id'] ?? 0) ?>)" class="text-red-500 hover:text-red-700">Remove</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
               
            </table>
        </div>
    <?php endif; ?>
</div>

<script>
function updateQuantity(productId, quantity) {
    fetch('update-cart.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ product_id: productId, quantity: quantity })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            document.getElementById('subtotal-' + productId).textContent = '₹' + data.subtotal.toFixed(2);
            document.getElementById('cart-total').textContent = '₹' + data.total.toFixed(2);
        } else {
            alert('❌ Failed to update quantity!');
        }
    })
    .catch(err => alert('❌ Something went wrong!'));
}

function removeFromCart(productId) {
    fetch('remove-from-cart.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ product_id: productId })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('❌ Failed to remove item!');
        }
    })
    .catch(err => alert('❌ Something went wrong!'));
}
</script>

</body>
</html>
