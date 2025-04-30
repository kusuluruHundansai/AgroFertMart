<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once "db.php";
require_once "cart-functions.php";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];
$cartItems = getCartItems();

$total = 0;
foreach ($cartItems as $item) {
    $total += $item['price'] * $item['quantity'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($cartItems)) {
    try {
        $pdo->beginTransaction();

        // Create order record
        $paymentMethod = $_POST['payment_method'];  // Get payment method from the form
        $orderSql = "INSERT INTO orders (user_id, total, status, payment_method) VALUES (?, ?, 'pending', ?)";
        $orderStmt = $pdo->prepare($orderSql);
        $orderStmt->execute([$userId, $total, $paymentMethod]);
        $orderId = $pdo->lastInsertId();

        // Add order items
        foreach ($cartItems as $productId => $item) {
            $sql = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$orderId, $productId, $item['quantity'], $item['price']]);
        }

        $pdo->commit();

        // Redirect based on payment method
        if ($paymentMethod === 'pay_now') {
            // Redirect to payment gateway (e.g., Razorpay)
            header("Location: payment.php?order_id=$orderId");
            exit();
        } else {
            clearCart();
            $success = true;
        }
    } catch (Exception $e) {
        $pdo->rollBack();
        $error = "Error: " . $e->getMessage(); // Show the actual exception
        error_log("Order Processing Error: " . $e->getMessage()); // Logs error to php_error_log
    }
}

include 'includes/header.php';
?>

<div class="container">
    <h2>Checkout</h2>

    <?php if (isset($success) && $success): ?>
        <div class="alert alert-success">
            <h3>Thank you for your order!</h3>
            <p>Your order has been placed successfully.</p>
            <a href="orders.php" class="btn">View Your Orders</a>
        </div>
    <?php elseif (!empty($cartItems)): ?>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h4>Order Summary</h4>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cartItems as $item): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($item['name']) ?></td>
                                        <td>₹<?= number_format($item['price'], 2) ?></td>
                                        <td><?= $item['quantity'] ?></td>
                                        <td>₹<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3">Total</th>
                                    <th>₹<?= number_format($total, 2) ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4>Complete Order</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="mb-3">
                                <label for="payment_method" class="form-label">Payment Method</label>
                                <select name="payment_method" class="form-select" id="payment_method" required>
                                    <option value="pay_on_delivery">Pay on Delivery</option>
                                    <option value="pay_now">Pay Now</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Place Order</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-info">
            Your cart is empty. <a href="products.php">Continue shopping</a>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
