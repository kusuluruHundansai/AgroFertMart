<?php
session_start();
require_once "db.php";

// Check if admin is logged in
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$sql = "SELECT id, name, price, category, image_url FROM products";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Products | AgroFertMart</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f5f6fa;
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
        }

        /* Navbar Styling */
        .navbar {
            background-color: #2e7d32;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            font-weight: bold;
            color: white !important;
            font-size: 1.5rem;
        }

        .navbar-nav .nav-link {
            font-weight: 600;
            color: #ffffff !important;
            font-size: 1.1rem;
        }

        .navbar-nav .nav-link:hover {
            color: #f1f1f1 !important;
            text-decoration: underline;
        }

        .navbar-nav .nav-item {
            margin-left: 20px;
        }

        /* Container Styling */
        .container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
            margin-top: 40px;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #2e7d32;
            font-weight: 600;
        }

        .table {
            width: 100%;
            margin-top: 30px;
            background-color: #f9f9f9;
        }

        th {
            background-color: #343a40;
            color: white;
            text-align: center;
            padding: 12px;
            border-radius: 8px 8px 0 0;
        }

        td {
            text-align: center;
            vertical-align: middle;
            padding: 12px;
            color: #555;
        }

        .btn {
            margin: 0 5px;
            border-radius: 12px;
            padding: 8px 16px;
            font-size: 1rem;
            transition: transform 0.2s ease;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background-color: #c82333;
            transform: scale(1.05);
        }

        .btn-warning {
            background-color: #ffc107;
            border-color: #ffc107;
            color: white;
        }

        .btn-warning:hover {
            background-color: #e0a800;
            transform: scale(1.05);
        }

        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
            color: white;
        }

        .btn-success:hover {
            background-color: #218838;
            transform: scale(1.05);
        }

        img {
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        .table-responsive {
            margin-top: 20px;
        }

        .alert {
            margin-bottom: 20px;
            border-radius: 8px;
            padding: 15px;
            font-size: 1.1rem;
            background-color: #d4edda;
            color: #155724;
        }

        /* Add Product Button */
        .btn-add-product {
            background-color: #007bff;
            color: white;
            border-radius: 12px;
            font-size: 1.1rem;
            padding: 12px 20px;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.3s ease;
        }

        .btn-add-product:hover {
            background-color: #0056b3;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .navbar-brand {
                font-size: 1.2rem;
            }

            .navbar-nav .nav-link {
                font-size: 1rem;
            }

            .container {
                padding: 20px;
            }

            .table th, .table td {
                font-size: 0.9rem;
            }

            .btn {
                font-size: 0.9rem;
                padding: 6px 12px;
            }
        }

        /* Hover effect for table rows */
        tbody tr:hover {
            background-color: #f1f1f1;
            cursor: pointer;
        }

    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light sticky-top px-4">
    <a class="navbar-brand" href="#">🌾 AgroFertMart Admin</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
            <li class="nav-item">
                <a class="nav-link" href="admin-dashboard.php">Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="admin-orders.php">Orders</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="admin-products.php">Products</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php">Logout</a>
            </li>
        </ul>
    </div>
</nav>

<!-- Products Table -->
<div class="container">
    <h2>All Products</h2>

    <div class="mb-4">
        <a href="add-product.php" class="btn-add-product">Add New Product</a>
    </div>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert"><?= $_SESSION['message']; unset($_SESSION['message']); ?></div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Product ID</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Category</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?= htmlspecialchars($product['id']) ?></td>
                        <td><?= htmlspecialchars($product['name']) ?></td>
                        <td>₹<?= number_format($product['price'], 2) ?></td>
                        <td><?= htmlspecialchars($product['category']) ?></td>
                        <td>
                            <?php if (!empty($product['image_url'])): ?>
                                <img src="uploads/<?= htmlspecialchars($product['image_url']) ?>" width="50" alt="Product Image">
                            <?php else: ?>
                                <span>No image</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="edit-product.php?product_id=<?= $product['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="delete-product.php?product_id=<?= $product['id'] ?>" class="btn btn-danger btn-sm">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
