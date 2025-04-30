<?php
session_start();
require_once "db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['status'])) {
    $orderId = intval($_POST['order_id']);
    $newStatus = $_POST['status'];

    $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->execute([$newStatus, $orderId]);

    $message = "✅ Order #$orderId status updated to '$newStatus'.";
}

$stmt = $pdo->prepare("SELECT o.id AS order_id, o.total, o.status, o.created_at, u.username AS user_name, u.email
                       FROM orders o
                       JOIN users u ON o.user_id = u.id
                       ORDER BY o.created_at DESC");
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Orders | AgroFertMart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f8;
            font-family: 'Segoe UI', sans-serif;
        }
        .navbar {
            background-color: #14532d;
        }
        .navbar-brand, .nav-link, .nav-link:hover {
            color: #fff !important;
            font-weight: 500;
        }
        .nav-link.active {
            text-decoration: underline;
        }
        .table thead {
            background-color: #14532d;
            color: white;
        }
    </style>
</head>
<body>

<!-- Admin Navigation Bar -->
<nav class="navbar navbar-expand-lg shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand" href="admin-dashboard.php">🌿 AgroFertMart Admin</a>
        <button class="navbar-toggler text-white" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="adminNavbar">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="admin-dashboard.php"><i class="fas fa-chart-line"></i> Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="admin-orders.php"><i class="fas fa-box"></i> Orders</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="admin-products.php"><i class="fas fa-tractor"></i> Products</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-danger" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Main Content -->
<div class="container mt-5">
    <div class="card shadow-sm p-4">
        <h2 class="mb-4 text-center text-success">📦 Manage Orders</h2>

        <?php if (isset($message)): ?>
            <div class="alert alert-success text-center"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <div class="text-end mb-3">
            <a href="export-orders.php" class="btn btn-outline-success">
                <i class="fas fa-file-excel"></i> Export to Excel
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>User</th>
                        <th>Email</th>
                        <th>Total (₹)</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Update</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td>#<?= $order['order_id'] ?></td>
                        <td class="fw-semibold"><?= htmlspecialchars($order['user_name']) ?></td>
                        <td><?= htmlspecialchars($order['email']) ?></td>
                        <td class="text-success fw-bold"><?= number_format($order['total'], 2) ?></td>
                        <td>
                            <span class="badge bg-<?= 
                                $order['status'] === 'pending' ? 'warning text-dark' :
                                ($order['status'] === 'cancelled' ? 'danger' : 'success') ?>">
                                <?= ucfirst($order['status']) ?>
                            </span>
                        </td>
                        <td><?= date("d M Y, h:i A", strtotime($order['created_at'])) ?></td>
                        <td>
                            <form method="POST" class="d-flex">
                                <input type="hidden" name="order_id" value="<?= $order['order_id'] ?>">
                                <select name="status" class="form-select form-select-sm me-2">
                                    <?php
                                    $statuses = ['pending', 'shipped', 'delivered', 'cancelled'];
                                    foreach ($statuses as $status):
                                    ?>
                                    <option value="<?= $status ?>" <?= $order['status'] === $status ? 'selected' : '' ?>>
                                        <?= ucfirst($status) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                                <button type="submit" class="btn btn-sm btn-outline-primary">Update</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
