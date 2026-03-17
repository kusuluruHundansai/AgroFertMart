const express = require('express');
const router = express.Router();
const Product = require('../models/Product');

// Add to Cart
router.post('/add', async (req, res) => {
  const { productId, quantity = 1 } = req.body;
  
  if (!req.session.cart) {
    req.session.cart = { items: [], total: 0, count: 0 };
  }

  try {
    const product = await Product.findById(productId);
    if (!product) return res.json({ success: false, message: 'Product not found' });

    const cart = req.session.cart;
    const existingItem = cart.items.find(item => item.productId === productId);

    if (existingItem) {
      existingItem.quantity += parseInt(quantity);
    } else {
      cart.items.push({
        productId: product._id.toString(),
        name: product.name,
        price: product.price,
        image_url: product.image_url,
        quantity: parseInt(quantity)
      });
    }

    // Update totals
    cart.count = cart.items.reduce((sum, item) => sum + item.quantity, 0);
    cart.total = cart.items.reduce((sum, item) => sum + (item.price * item.quantity), 0);

    res.json({ success: true, count: cart.count, total: cart.total });
  } catch (err) {
    res.status(500).json({ success: false, message: 'Server error' });
  }
});

// GET View Cart
router.get('/', (req, res) => {
  res.render('cart/index', { 
    title: 'Your Cart | AgroFertMart',
    cart: req.session.cart || { items: [], total: 0, count: 0 }
  });
});

// Update Quantity
router.post('/update', (req, res) => {
  const { productId, quantity } = req.body;
  if (!req.session.cart) return res.json({ success: false });

  const cart = req.session.cart;
  const item = cart.items.find(i => i.productId === productId);
  
  if (item) {
    item.quantity = Math.max(0, parseInt(quantity));
    if (item.quantity === 0) {
      cart.items = cart.items.filter(i => i.productId !== productId);
    }
    
    cart.count = cart.items.reduce((sum, item) => sum + item.quantity, 0);
    cart.total = cart.items.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    
    return res.json({ 
      success: true, 
      subtotal: item.price * item.quantity,
      total: cart.total,
      count: cart.count
    });
  }
  res.json({ success: false });
});

// Remove Item
router.post('/remove', (req, res) => {
  const { productId } = req.body;
  if (!req.session.cart) return res.json({ success: false });

  const cart = req.session.cart;
  cart.items = cart.items.filter(i => i.productId !== productId);
  
  cart.count = cart.items.reduce((sum, item) => sum + item.quantity, 0);
  cart.total = cart.items.reduce((sum, item) => sum + (item.price * item.quantity), 0);

  res.json({ success: true, total: cart.total, count: cart.count });
});

module.exports = router;
