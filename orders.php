<?php
session_start();
require_once "db.php";

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];

// Handle order cancellation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_order_id'])) {
    $cancelOrderId = $_POST['cancel_order_id'];

    // Check if order belongs to user and is still pending
    $checkSql = "SELECT * FROM orders WHERE id = ? AND user_id = ? AND status = 'pending'";
    $stmt = $pdo->prepare($checkSql);
    $stmt->execute([$cancelOrderId, $userId]);
    $order = $stmt->fetch();

    if ($order) {
        // Cancel the order
        $cancelSql = "UPDATE orders SET status = 'cancelled' WHERE id = ?";
        $cancelStmt = $pdo->prepare($cancelSql);
        $cancelStmt->execute([$cancelOrderId]);
        $message = "Order #$cancelOrderId has been cancelled.";
    } else {
        $error = "Order not found or cannot be cancelled.";
    }
}

// Fetch orders with their items
$sql = "SELECT o.id AS order_id, o.total, o.status, o.created_at,
               oi.product_id, p.name AS product_name, oi.quantity, oi.price
        FROM orders o
        JOIN order_items oi ON o.id = oi.order_id
        JOIN products p ON oi.product_id = p.id
        WHERE o.user_id = ?
        ORDER BY o.created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute([$userId]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'includes/header.php';
?>

<div class="container">
    <h2>Your Orders</h2>

    <?php if (isset($message)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
    <?php elseif (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if (empty($orders)): ?>
        <p>You have no orders yet. <a href="products.php">Shop Now</a></p>
    <?php else: ?>
        <?php 
        $currentOrderId = null;
        foreach ($orders as $index => $order):
            if ($order['order_id'] !== $currentOrderId):
                if ($currentOrderId !== null) echo "</tbody></table><br>";
                $currentOrderId = $order['order_id'];
        ?>
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <strong>Order #<?= $order['order_id'] ?></strong> |
                        Total: ₹<?= number_format($order['total'], 2) ?> |
                        Status: <span class="badge bg-<?= $order['status'] === 'pending' ? 'warning' : ($order['status'] === 'cancelled' ? 'danger' : 'success') ?>">
                            <?= ucfirst($order['status']) ?>
                        </span> |
                        Date: <?= $order['created_at'] ?>
                    </div>
                    <div>
                        <?php if ($order['status'] === 'pending'): ?>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="cancel_order_id" value="<?= $order['order_id'] ?>">
                                <button type="submit" class="btn btn-sm btn-danger">Cancel Order</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Qty</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
            <?php endif; ?>
                            <tr>
                                <td><?= htmlspecialchars($order['product_name']) ?></td>
                                <td>₹<?= number_format($order['price'], 2) ?></td>
                                <td><?= $order['quantity'] ?></td>
                                <td>₹<?= number_format($order['price'] * $order['quantity'], 2) ?></td>
                            </tr>
        <?php endforeach; echo "</tbody></table></div></div>"; ?>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
