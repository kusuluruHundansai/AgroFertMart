<?php

require '../php/db.php';

// Admin authentication placeholder
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
  die("Access denied. Admins only.");
}

$stmt = $pdo->query("SELECT * FROM orders ORDER BY created_at DESC");
$orders = $stmt->fetchAll();

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Orders</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
  <div class="container mx-auto py-10">
    <h2 class="text-3xl font-bold mb-6">All Orders</h2>
    <div class="overflow-x-auto">
      <table class="table-auto w-full text-left border">
        <thead>
          <tr class="bg-gray-300">
            <th class="px-4 py-2">Order ID</th>
            <th class="px-4 py-2">Customer</th>
            <th class="px-4 py-2">Total</th>
            <th class="px-4 py-2">Payment</th>
            <th class="px-4 py-2">Placed On</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($orders as $order): ?>
            <tr class="border-t bg-white hover:bg-gray-100">
              <td class="px-4 py-2">#<?= $order['id'] ?></td>
              <td class="px-4 py-2"><?= htmlspecialchars($order['name']) ?></td>
              <td class="px-4 py-2">₹<?= number_format($order['total'], 2) ?></td>
              <td class="px-4 py-2"><?= htmlspecialchars($order['payment_method']) ?></td>
              <td class="px-4 py-2"><?= date('d M Y, h:i A', strtotime($order['created_at'])) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>
