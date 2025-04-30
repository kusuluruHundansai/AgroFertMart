<?php
session_start();
require_once "db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$adminName = "Sandeep";

// Fetch total products
$stmt = $pdo->query("SELECT COUNT(*) FROM products");
$totalProducts = $stmt->fetchColumn();

// Fetch total orders
$stmt = $pdo->query("SELECT COUNT(*) FROM orders");
$totalOrders = $stmt->fetchColumn();

// Fetch pending orders
$stmt = $pdo->prepare("SELECT COUNT(*) FROM orders WHERE status = ?");
$stmt->execute(['pending']);
$pendingOrders = $stmt->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard | AgroFertMart</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #e0f2f1, #f1f8e9);
            font-family: 'Segoe UI', sans-serif;
        }

        .navbar {
            background-color: #ffffff;
            box-shadow: 0 4px 12px rgba(0,0,0,0.06);
        }

        .navbar-brand {
            font-weight: bold;
            color: #2e7d32 !important;
        }

        .logout-btn {
            background-color: #e53935;
            color: white;
            padding: 8px 16px;
            border-radius: 10px;
            text-decoration: none;
            transition: 0.3s ease;
        }

        .logout-btn:hover {
            background-color: #c62828;
        }

        .dashboard-container {
            max-width: 1200px;
            margin: auto;
            padding: 60px 20px;
        }

        .card-glass {
            backdrop-filter: blur(10px);
            background-color: rgba(255, 255, 255, 0.75);
            border: 1px solid rgba(255,255,255,0.2);
            box-shadow: 0 8px 32px rgba(0,0,0,0.05);
            border-radius: 20px;
            padding: 40px;
            text-align: center;
        }

        .btn-lg {
            padding: 14px;
            font-size: 1.1rem;
            border-radius: 12px;
        }

        .greeting {
            font-size: 1.3rem;
            font-weight: 500;
        }

        .analytics-box {
            border-radius: 16px;
            background-color: #ffffff;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.05);
            padding: 20px 30px;
            text-align: left;
        }

        .analytics-box h4 {
            font-size: 1.1rem;
            color: #555;
        }

        .analytics-box span {
            font-size: 1.8rem;
            font-weight: bold;
            color: #2e7d32;
        }

        @media (max-width: 768px) {
            .dashboard-container {
                padding: 30px 15px;
            }

            .analytics-grid {
                grid-template-columns: 1fr !important;
            }
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light sticky-top px-4">
    <a class="navbar-brand" href="#">🌾 AgroFertMart Admin</a>
    <div class="ms-auto d-flex align-items-center gap-3">
        <span class="text-muted greeting">Welcome, <?php echo $adminName; ?> 👋</span>
        <a href="logout.php" class="logout-btn">🚪 Logout</a>
    </div>
</nav>

<!-- Dashboard -->
<div class="dashboard-container">

    <!-- Analytics Overview -->
    <div class="row mb-5 g-4">
        <div class="col-md-4">
            <div class="analytics-box">
                <h4>Total Products</h4>
                <span><?= $totalProducts ?></span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="analytics-box">
                <h4>Total Orders</h4>
                <span><?= $totalOrders ?></span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="analytics-box">
                <h4>Pending Orders</h4>
                <span><?= $pendingOrders ?></span>
            </div>
        </div>
    </div>

    <!-- Main Dashboard Actions -->
    <div class="card-glass">
        <h2 class="mb-3 text-success">Admin Control Panel</h2>
        <p class="text-muted mb-4">Manage everything from one place efficiently.</p>
        <div class="d-grid gap-3">
            <a href="admin-products.php" class="btn btn-success btn-lg">
                🛒 Manage Products
            </a>
            <a href="admin-orders.php" class="btn btn-primary btn-lg">
                📦 Manage Orders
            </a>
        </div>
    </div>
</div>

</body>
</html>
