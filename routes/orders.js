const express = require('express');
const router = express.Router();
const Order = require('../models/Order');
const Product = require('../models/Product');
const { isLoggedIn } = require('../middleware/auth');

// Protect all order routes
router.use(isLoggedIn);

// GET Checkout Page
router.get('/checkout', (req, res) => {
  const cart = req.session.cart;
  if (!cart || cart.items.length === 0) {
    req.session.error = "Your cart is empty!";
    return res.redirect('/products');
  }
  res.render('orders/checkout', { title: 'Checkout | AgroFertMart', cart });
});

// POST Place Order
router.post('/place', async (req, res) => {
  const cart = req.session.cart;
  if (!cart || cart.items.length === 0) return res.redirect('/products');

  const { payment_method, address, phone } = req.body;
  if (!address || !phone) {
    req.session.error = "Delivery address and phone are required!";
    return res.redirect('/orders/checkout');
  }

  try {
    const order = new Order({
      user: req.session.user.id,
      items: cart.items.map(item => ({
        product: item.productId,
        name: item.name,
        price: item.price,
        quantity: item.quantity
      })),
      total: cart.total,
      address,
      phone,
      payment_method,
      status: 'pending'
    });

    await order.save();

    // Clear cart for Pay on Delivery (Default)
    req.session.cart = { items: [], total: 0, count: 0 };
    req.session.success = "Order placed successfully! We will contact you soon.";
    res.redirect('/orders/my-orders');
  } catch (err) {
    console.error(err);
    req.session.error = "Failed to place order. Please try again.";
    res.redirect('/cart');
  }
});

// POST Cancel Order
router.post('/cancel/:id', async (req, res) => {
  try {
    const order = await Order.findOne({ _id: req.params.id, user: req.session.user.id });
    if (!order) {
      req.session.error = "Order not found.";
      return res.redirect('/orders/my-orders');
    }
    if (order.status !== 'pending') {
      req.session.error = "Only pending orders can be cancelled.";
      return res.redirect('/orders/my-orders');
    }
    
    order.status = 'cancelled';
    await order.save();
    
    req.session.success = "Order cancelled successfully.";
    res.redirect('/orders/my-orders');
  } catch (err) {
    console.error(err);
    res.redirect('/orders/my-orders');
  }
});

// GET My Orders
router.get('/my-orders', async (req, res) => {
  try {
    const orders = await Order.find({ user: req.session.user.id }).sort({ createdAt: -1 });
    res.render('orders/list', { title: 'My Orders | AgroFertMart', orders });
  } catch (err) {
    res.redirect('/');
  }
});

module.exports = router;
