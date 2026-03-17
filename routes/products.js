const express = require('express');
const router = express.Router();
const Product = require('../models/Product');

// GET all products
router.get('/', async (req, res) => {
  try {
    const products = await Product.find().sort({ category: 1, name: 1 });
    // Group products by category for the view
    const groupedProducts = products.reduce((acc, product) => {
      (acc[product.category] = acc[product.category] || []).push(product);
      return acc;
    }, {});
    
    res.render('products/index', { 
      title: 'Products | AgroFertMart',
      groupedProducts 
    });
  } catch (err) {
    console.error(err);
    req.session.error = 'Failed to load products';
    res.redirect('/');
  }
});

// GET single product detail
router.get('/:id', async (req, res) => {
  try {
    const product = await Product.findById(req.params.id);
    if (!product) return res.status(404).render('404');
    res.render('products/show', { title: product.name, product });
  } catch (err) {
    res.redirect('/products');
  }
});

module.exports = router;
