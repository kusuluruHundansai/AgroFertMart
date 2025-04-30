<?php
require_once "db.php";

// Set headers to force download of a CSV file
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=orders_export.xls");
header("Pragma: no-cache");
header("Expires: 0");

// Fetch orders from database
$stmt = $pdo->prepare("
    SELECT o.id AS order_id, o.total, o.status, o.created_at, u.username AS user_name, u.email
    FROM orders o
    JOIN users u ON o.user_id = u.id
    ORDER BY o.created_at DESC
");
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Output column headers
echo "Order ID\tUsername\tEmail\tTotal\tStatus\tCreated At\n";

// Output data rows
foreach ($orders as $order) {
    echo "{$order['order_id']}\t" .
         "{$order['user_name']}\t" .
         "{$order['email']}\t" .
         number_format($order['total'], 2) . "\t" .
         ucfirst($order['status']) . "\t" .
         date("d M Y, h:i A", strtotime($order['created_at'])) . "\n";
}
exit;
