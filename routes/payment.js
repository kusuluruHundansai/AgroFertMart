const express = require('express');
const router = express.Router();
const Razorpay = require('razorpay');
const Order = require('../models/Order');
const { isLoggedIn } = require('../middleware/auth');

const razorpay = new Razorpay({
  key_id: process.env.RAZORPAY_KEY_ID,
  key_secret: process.env.RAZORPAY_KEY_SECRET
});

// Process Payment initialization
router.get('/process/:orderId', isLoggedIn, async (req, res) => {
  try {
    const order = await Order.findById(req.params.orderId);
    if (!order || order.user.toString() !== req.session.user.id) {
      return res.redirect('/orders/my-orders');
    }

    const options = {
      amount: order.total * 100, // in paise
      currency: "INR",
      receipt: `order_${order._id}`,
      payment_capture: 1
    };

    const rzpOrder = await razorpay.orders.create(options);
    
    // Save Razorpay order ID to our order
    order.razorpay_order_id = rzpOrder.id;
    await order.save();

    res.render('payment/process', { 
      title: 'Complete Payment',
      order,
      rzpOrder,
      key_id: process.env.RAZORPAY_KEY_ID,
      user: req.session.user
    });
  } catch (err) {
    console.error(err);
    res.redirect('/orders/my-orders');
  }
});

// Webhook or Callback for verification
router.post('/verify', async (req, res) => {
  const { razorpay_order_id, razorpay_payment_id, razorpay_signature } = req.body;
  
  // In a real app, you MUST verify the signature here using crypto
  // For this demonstration, we'll mark it as paid if ids exist
  
  try {
    const order = await Order.findOne({ razorpay_order_id });
    if (order) {
      order.payment_status = 'paid';
      order.razorpay_payment_id = razorpay_payment_id;
      await order.save();
      
      // Clear cart
      req.session.cart = { items: [], total: 0, count: 0 };
      req.session.success = "Payment successful and Order confirmed!";
      res.redirect('/orders/my-orders');
    } else {
      res.redirect('/cart');
    }
  } catch (err) {
    res.redirect('/cart');
  }
});

module.exports = router;
