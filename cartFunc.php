<?php
// php/cart-functions.php


function addToCart($productId) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  if (isset($_SESSION['cart'][$productId])) {
    $_SESSION['cart'][$productId]++;
  } else {
    $_SESSION['cart'][$productId] = 1;
  }
}

function removeFromCart($productId) {
  if (isset($_SESSION['cart'][$productId])) {
    unset($_SESSION['cart'][$productId]);
  }
}

function updateCartQuantity($productId, $quantity) {
  if ($quantity <= 0) {
    removeFromCart($productId);
  } else {
    $_SESSION['cart'][$productId] = $quantity;
  }
}
