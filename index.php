<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>AgroFertMart - Buy Fertilizers and Pesticides Online</title>
  <link rel="stylesheet" href="style.css"/>
</head>
<body>

  <!-- Header -->
  <header>
    <div class="logo">
      <h1>AgroFertMart</h1>
    </div>
    <nav>
      <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="products.php">Products</a></li>
        <li><a href="cart.php">Cart</a></li>
        <li><a href="login.php">Login</a></li>
        <li><a href="signup.php">Signup</a></li>
        <li><a href="orders.php">My Orders</a></li>
        <li><a href="logout.php">Log-Out</a></li>
      </ul>
    </nav>
  </header>

  <!-- Hero Section with Background Video -->
  <section class="hero">
    <video autoplay muted loop playsinline class="background-video">
      <source src="videos/farm.mp4" type="video/mp4">
      Your browser does not support HTML5 video.
    </video>
    <div class="video-overlay"></div>

    <div class="hero-content">
      <h1 class="welcome-heading">Welcome to AgroFertMart</h1>
      <h2 class="hero-subheading">Your One-Stop Shop for Fertilizers, Pesticides & Equipment</h2>
      <p class="hero-description">We bring you quality products directly from trusted sources to boost your agricultural productivity.</p>
      <a href="products.php" class="btn">Shop Now</a>
    </div>
  </section>

  <footer>
    <p>&copy; 2025 AgroFertMart. All rights reserved.</p>
  </footer>

  <script src="script.js"></script>
</body>
</html>
