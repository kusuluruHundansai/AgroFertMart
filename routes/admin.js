const express = require('express');
const router = express.Router();
const Product = require('../models/Product');
const Order = require('../models/Order');
const { isAdmin } = require('../middleware/auth');
const multer = require('multer');
const path = require('path');

// Multer Storage
const storage = multer.diskStorage({
  destination: (req, file, cb) => cb(null, 'uploads/'),
  filename: (req, file, cb) => cb(null, Date.now() + path.extname(file.originalname))
});
const upload = multer({ storage });

// All admin routes protected
router.use(isAdmin);

// ── Dashboard ──────────────────────────────────────────────────────────
router.get('/dashboard', async (req, res) => {
  try {
    const totalProducts  = await Product.countDocuments();
    const totalOrders    = await Order.countDocuments();
    const pendingOrders  = await Order.countDocuments({ status: 'pending' });
    const recentOrders   = await Order.find().sort({ createdAt: -1 }).limit(8).populate('user');

    // Revenue: sum of all delivered order totals
    const revenueResult = await Order.aggregate([
      { $match: { status: 'delivered' } },
      { $group: { _id: null, total: { $sum: '$total' } } }
    ]);
    const revenue = revenueResult.length ? revenueResult[0].total : 0;

    res.render('admin/dashboard', {
      title: 'Admin Dashboard',
      stats: { totalProducts, totalOrders, pendingOrders, revenue },
      recentOrders
    });
  } catch (err) {
    console.error(err);
    res.redirect('/');
  }
});

// ── Products List ──────────────────────────────────────────────────────
router.get('/products', async (req, res) => {
  try {
    const products = await Product.find().sort({ createdAt: -1 });
    res.render('admin/products', { title: 'Manage Products', products });
  } catch (err) {
    req.session.error = 'Failed to load products';
    res.redirect('/admin/dashboard');
  }
});

// ── Add Product ────────────────────────────────────────────────────────
router.post('/products/add', upload.single('image'), async (req, res) => {
  try {
    const { name, price, category, description, badge } = req.body;
    let image_url = req.file ? `/uploads/${req.file.filename}` : '/images/bg-agro.jpg';
    
    const product = new Product({
      name, price: parseFloat(price), category, description,
      image_url,
      badge: badge || null
    });
    await product.save();
    req.session.success = `✅ "${name}" added successfully!`;
    res.redirect('/admin/products');
  } catch (err) {
    req.session.error = 'Failed to add product: ' + err.message;
    res.redirect('/admin/products');
  }
});

// ── Edit Product ─── GET (not needed, modal is pre-filled via JS) ─────

// ── Edit Product ─── POST ─────────────────────────────────────────────
router.post('/products/:id/edit', async (req, res) => {
  try {
    const { name, price, category, description, badge } = req.body;
    await Product.findByIdAndUpdate(req.params.id, {
      name, price: parseFloat(price), category, description,
      badge: badge || null
    });
    req.session.success = `✅ "${name}" updated successfully!`;
    res.redirect('/admin/products');
  } catch (err) {
    req.session.error = 'Failed to update product: ' + err.message;
    res.redirect('/admin/products');
  }
});

// ── Delete Product ─────────────────────────────────────────────────────
router.post('/products/:id/delete', async (req, res) => {
  try {
    const product = await Product.findByIdAndDelete(req.params.id);
    req.session.success = product ? `🗑️ "${product.name}" deleted.` : 'Product deleted.';
    res.redirect('/admin/products');
  } catch (err) {
    req.session.error = 'Failed to delete product';
    res.redirect('/admin/products');
  }
});

// ── Orders List ────────────────────────────────────────────────────────
router.get('/orders', async (req, res) => {
  try {
    const orders = await Order.find().sort({ createdAt: -1 }).populate('user');
    res.render('admin/orders', { title: 'Manage Orders', orders });
  } catch (err) {
    res.redirect('/admin/dashboard');
  }
});

// ── Update Order Status ────────────────────────────────────────────────
router.post('/orders/:id/status', async (req, res) => {
  try {
    const { status } = req.body;
    await Order.findByIdAndUpdate(req.params.id, { status });
    req.session.success = `Order status updated to "${status}"`;
    res.redirect('/admin/orders');
  } catch (err) {
    req.session.error = 'Failed to update status';
    res.redirect('/admin/orders');
  }
});

module.exports = router;
