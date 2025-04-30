<?php
require 'php/db.php';

// Replace with your authentication system logic
$user_email = $_SESSION['user_email'] ?? null;
if (!$user_email) {
  header("Location: login.php");
  exit();
}

$stmt = $pdo->prepare("SELECT * FROM orders WHERE name = ? ORDER BY created_at DESC");
$stmt->execute([$user_email]); // Assuming name = email, adjust as needed
$orders = $stmt->fetchAll();

include 'includes/header.php';
?>

<div class="container mx-auto py-10">
  <h2 class="text-3xl font-bold mb-6">My Orders</h2>
  <?php if (empty($orders)): ?>
    <p>You haven't placed any orders yet.</p>
  <?php else: ?>
    <div class="overflow-x-auto">
      <table class="table-auto w-full text-left border">
        <thead>
          <tr class="bg-gray-200">
            <th class="px-4 py-2">Order ID</th>
            <th class="px-4 py-2">Date</th>
            <th class="px-4 py-2">Total</th>
            <th class="px-4 py-2">Payment</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($orders as $order): ?>
            <tr class="border-t">
              <td class="px-4 py-2">#<?= $order['id'] ?></td>
              <td class="px-4 py-2"><?= date('d M Y, h:i A', strtotime($order['created_at'])) ?></td>
              <td class="px-4 py-2">₹<?= number_format($order['total'], 2) ?></td>
              <td class="px-4 py-2"><?= htmlspecialchars($order['payment_method']) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
