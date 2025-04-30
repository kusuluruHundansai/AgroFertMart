<?php
session_start();
require_once "db.php";

// Check if admin is logged in
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $category = $_POST['category'];

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image = $_FILES['image']['name'];
        $tempPath = $_FILES['image']['tmp_name'];

        $uploadDir = "uploads/";
        $targetPath = $uploadDir . basename($image);

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true); // Create directory if it doesn't exist
        }

        if (move_uploaded_file($tempPath, $targetPath)) {
            // Insert into DB
            $sql = "INSERT INTO products (name, price, category, image_url) VALUES (?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$name, $price, $category, $image]);

            $_SESSION['message'] = "Product added successfully!";
            header("Location: admin-products.php");
            exit();
        } else {
            $_SESSION['message'] = "Image upload failed!";
        }
    } else {
        $_SESSION['message'] = "No image selected or upload error!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Product | AgroFertMart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
            padding-top: 70px;
        }

        /* Navigation Bar Styling */
        .navbar {
            background-color: #28a745;
            padding: 15px 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            color: #fff !important;
            font-weight: bold;
            font-size: 1.5rem;
        }

        .navbar-nav .nav-link {
            color: #fff !important;
            font-weight: 600;
            padding: 12px 20px;
            text-transform: uppercase;
            transition: color 0.3s, background-color 0.3s;
        }

        .navbar-nav .nav-link:hover {
            color: #28a745 !important;
            background-color: #ffffff;
        }

        /* Main container for form */
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
            max-width: 650px;
            margin-top: 30px;
        }

        h2 {
            text-align: center;
            color: #28a745;
            margin-bottom: 20px;
        }

        .form-label {
            font-weight: 600;
            color: #333;
        }

        .form-control {
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .btn {
            background-color: #007bff;
            color: white;
            font-weight: 600;
            border-radius: 8px;
            width: 100%;
            padding: 12px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .btn:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        .alert-info {
            background-color: #d4edda;
            color: #155724;
            border-radius: 8px;
            padding: 15px;
            font-size: 1.1rem;
            margin-top: 20px;
            animation: fadeIn 1s ease-out;
        }

        /* Form Input Focus Effect */
        .form-control:focus {
            border-color: #28a745;
            box-shadow: 0 0 5px rgba(40, 167, 69, 0.5);
        }

        /* Fade In Effect for Alerts */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }

            h2 {
                font-size: 1.5rem;
            }

            .btn {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-light fixed-top">
    <a class="navbar-brand" href="admin-dashboard.php">AgroFertMart Admin</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="admin-dashboard.php">Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="admin-products.php">Products</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="admin-orders.php">Orders</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php">Logout</a>
            </li>
        </ul>
    </div>
</nav>

<div class="container">
    <h2>Add New Product</h2>

    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label" for="name">Name:</label>
            <input type="text" id="name" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label" for="price">Price:</label>
            <input type="number" id="price" name="price" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label" for="category">Category:</label>
            <input type="text" id="category" name="category" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label" for="image">Image:</label>
            <input type="file" id="image" name="image" class="form-control" required>
        </div>

        <button type="submit" class="btn">Add Product</button>
    </form>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-info">
            <?= $_SESSION['message']; unset($_SESSION['message']); ?>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
