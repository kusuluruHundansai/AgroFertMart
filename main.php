<?php

include 'includes/header.php';
?>

<!-- Hero Section -->
<section class="bg-green-100 py-16">
  <div class="container mx-auto px-4 text-center">
    <h1 class="text-5xl font-bold text-green-800 mb-4">Welcome to <span class="text-green-700">AgriMart</span></h1>
    <p class="text-lg text-green-700 mb-6">Your one-stop shop for quality fertilizers and pesticides.</p>
    <a href="products.php" class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg text-lg shadow-lg transition-all duration-300">
      Shop Now
    </a>
  </div>
</section>

<!-- Why Choose Us Section -->
<section class="py-16 bg-gray-50">
  <h2 class="text-3xl font-bold text-center mb-12">Why Choose Us?</h2>
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 px-4 max-w-7xl mx-auto">
    
    <!-- Card 1 -->
    <div class="bg-white p-6 rounded-lg shadow-md text-center transform transition duration-300 hover:scale-105 hover:shadow-xl">
      <h3 class="text-xl font-semibold mb-3 text-green-700">Organic Products</h3>
      <p class="text-gray-600">We offer high-quality organic fertilizers that are eco-friendly and effective.</p>
    </div>

    <!-- Card 2 -->
    <div class="bg-white p-6 rounded-lg shadow-md text-center transform transition duration-300 hover:scale-105 hover:shadow-xl">
      <h3 class="text-xl font-semibold mb-3 text-green-700">Trusted by Farmers</h3>
      <p class="text-gray-600">Thousands of farmers trust AgriMart for their agriculture needs.</p>
    </div>

    <!-- Card 3 -->
    <div class="bg-white p-6 rounded-lg shadow-md text-center transform transition duration-300 hover:scale-105 hover:shadow-xl">
      <h3 class="text-xl font-semibold mb-3 text-green-700">Fast Delivery</h3>
      <p class="text-gray-600">Get your supplies delivered to your doorstep quickly and reliably.</p>
    </div>

  </div>
</section>

<?php include 'includes/footer.php'; ?>
