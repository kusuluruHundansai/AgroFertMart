<?php
session_start();
$conn = new mysqli("localhost", "root", "", "agri");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM products 
        ORDER BY FIELD(category, 'Fertilizer', 'Pesticide', 'Equipment'), name";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Products | AgroFertMart</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('images/bg-agro.jpg');
            background-size: cover;
            background-attachment: fixed;
            background-position: center;
        }
        .bg-overlay {
            background-color: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(3px);
        }
    </style>
</head>
<body class="font-sans leading-relaxed">

<!-- Navbar -->
<nav class="bg-green-800 bg-opacity-95 p-4 shadow-lg sticky top-0 z-50">
    <div class="max-w-7xl mx-auto flex justify-between items-center">
        <a href="index.php" class="text-white text-2xl font-bold flex items-center gap-2">🌾 AgroFertMart</a>
        <div class="space-x-6 text-white font-medium">
            <a href="index.php" class="hover:text-yellow-300">Home</a>
            <a href="products.php" class="hover:text-yellow-300">Products</a>
            <a href="cart.php" class="hover:text-yellow-300 relative">
                🛒 Cart 
                <span id="cart-count" class="ml-1 bg-yellow-400 text-green-900 text-xs font-bold px-2 py-1 rounded-full">
                    <?= $_SESSION['cart']['total'] ?? 0 ?>
                </span>
            </a>
        </div>
    </div>
</nav>

<!-- Main Content -->
<div class="max-w-7xl mx-auto p-6 bg-overlay rounded-lg mt-6 mb-10 shadow-2xl">
    <h1 class="text-4xl font-extrabold text-center text-green-900 mb-12">🌿 Explore Our Premium Agro Products</h1>

    <?php
    $currentCategory = null;
    if ($result->num_rows > 0):
    ?>
    <div class="space-y-20">
        <?php while ($row = $result->fetch_assoc()): ?>
            <?php
            if ($currentCategory !== $row['category']) {
                if ($currentCategory !== null) echo '</div>';
                $currentCategory = $row['category'];
                echo "<h2 class='text-3xl font-bold text-green-800 mb-4 border-b-4 border-green-400 pb-2'>{$currentCategory}s</h2>
                      <div class='grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8'>";
            }

            $image = (!empty($row['image_url']) && file_exists($row['image_url'])) ? $row['image_url'] : 'images/default.jpg';
            ?>
            <div class="bg-white bg-opacity-95 rounded-2xl shadow-lg hover:shadow-2xl transform transition duration-300 hover:scale-105 p-4 flex flex-col items-center text-center">
                <img src="<?= htmlspecialchars($image) ?>" alt="<?= htmlspecialchars($row['name']) ?>" class="w-full h-44 object-cover rounded-xl mb-3">
                <h3 class="text-lg font-semibold text-gray-800"><?= htmlspecialchars($row['name']) ?></h3>
                <p class="text-green-700 font-bold text-lg mt-1">₹<?= number_format($row['price'], 2) ?></p>
                <button onclick="addToCart(<?= $row['id'] ?>)" class="mt-4 bg-green-600 hover:bg-green-700 transition text-white px-5 py-2 rounded-lg shadow w-full font-medium">
                    ➕ Add to Cart
                </button>
            </div>
        <?php endwhile; ?>
        </div>
    </div>
    <?php else: ?>
        <p class="text-center text-red-600 text-lg font-semibold mt-10">No products available at the moment. Please check back later.</p>
    <?php endif; ?>
</div>

<!-- JS for AJAX Cart -->
<script>
function addToCart(productId) {
    fetch('add-to-cart.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ product_id: productId })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            document.getElementById('cart-count').textContent = data.count;
            alert('✅ Added to cart!');
        } else {
            alert('❌ Failed to add product!');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('❌ Something went wrong!');
    });
}
</script>

</body>
</html>
