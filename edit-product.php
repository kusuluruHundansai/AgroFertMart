<?php
session_start();
require_once "db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['product_id'])) {
    $id = $_GET['product_id'];

    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        $_SESSION['message'] = "Product not found!";
        header("Location: admin-products.php");
        exit();
    }
} else {
    $_SESSION['message'] = "Invalid product ID.";
    header("Location: admin-products.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $category = $_POST['category'];

    $stmt = $pdo->prepare("UPDATE products SET name = ?, price = ?, category = ? WHERE id = ?");
    $stmt->execute([$name, $price, $category, $id]);

    $_SESSION['message'] = "Product updated!";
    header("Location: admin-products.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2>Edit Product</h2>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Name:</label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($product['name']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Price:</label>
            <input type="number" name="price" class="form-control" value="<?= $product['price'] ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Category:</label>
            <input type="text" name="category" class="form-control" value="<?= htmlspecialchars($product['category']) ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Product</button>
        <a href="admin-products.php" class="btn btn-secondary">Cancel</a>
    </form>
</body>
</html>
