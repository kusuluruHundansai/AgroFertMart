<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgriMart</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Custom Stylesheet -->
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body class="bg-gray-50">
<link rel="stylesheet" href="style.css" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

<script>
  // Grab the menu toggle button and the mobile menu
  const menuToggle = document.getElementById('menu-toggle');
  const mobileMenu = document.getElementById('mobile-menu');

  // Toggle the visibility of the mobile menu on click
  menuToggle.addEventListener('click', () => {
    mobileMenu.classList.toggle('hidden');
  });
</script>


    <!-- Navigation Bar -->
    <nav class="bg-green-600 text-white p-4 shadow-md">
        <div class="container mx-auto flex justify-between items-center">
            <!-- Brand/Logo -->
            <a href="index.php" class="font-bold text-xl hover:text-gray-200 transition duration-300">AgriMart</a>

            <!-- Hamburger Button for Mobile -->
            <div class="md:hidden flex items-center">
                <button id="menu-toggle" class="text-white p-2 hover:bg-green-500 rounded">
                    <i class="fas fa-bars"></i> <!-- Font Awesome Bars Icon -->
                </button>
            </div>

            <!-- Desktop Menu -->
            <div class="hidden md:flex space-x-4">
                <a href="products.php" class="px-3 py-2 rounded-lg hover:bg-green-500 transition duration-300">Products</a>
                <a href="cart.php" class="px-3 py-2 rounded-lg hover:bg-green-500 transition duration-300">Cart</a>

                <?php if (!empty($_SESSION['user'])): ?>
                    <!-- User Logged In -->
                    <a href="account.php" class="px-3 py-2 rounded-lg hover:bg-green-500 transition duration-300">Account</a>
                    <a href="logout.php" class="px-3 py-2 rounded-lg hover:bg-red-500 transition duration-300">Logout</a>
                <?php else: ?>
                    <!-- User Not Logged In -->
                    <a href="login.php" class="px-3 py-2 rounded-lg hover:bg-blue-500 transition duration-300">Login</a>
                    <a href="signup.php" class="px-3 py-2 rounded-lg hover:bg-blue-500 transition duration-300">Sign Up</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Mobile Menu Items (Hidden by default) -->
    <div id="mobile-menu" class="md:hidden fixed top-0 left-0 w-full bg-green-600 text-white p-4 space-y-4 hidden">
        <a href="products.php" class="block py-2">Products</a>
        <a href="cart.php" class="block py-2">Cart</a>
        <?php if (!empty($_SESSION['user'])): ?>
            <!-- User Logged In -->
            <a href="account.php" class="block py-2">Account</a>
            <a href="logout.php" class="block py-2">Logout</a>
        <?php else: ?>
            <!-- User Not Logged In -->
            <a href="login.php" class="block py-2">Login</a>
            <a href="signup.php" class="block py-2">Sign Up</a>
        <?php endif; ?>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto py-10">
