const express = require('express');
const router = express.Router();
const User = require('../models/User');
const { body, validationResult } = require('express-validator');

// GET Signup
router.get('/signup', (req, res) => {
  res.render('auth/signup', { title: 'Sign Up - AgroFertMart' });
});

// POST Signup
router.post('/signup', [
  body('username').trim().isLength({ min: 3 }).withMessage('Username must be at least 3 characters'),
  body('email').isEmail().normalizeEmail().withMessage('Enter a valid email'),
  body('password').isLength({ min: 6 }).withMessage('Password must be at least 6 characters'),
  body('confirm_password').custom((value, { req }) => {
    if (value !== req.body.password) {
      throw new Error('Password confirmation does not match password');
    }
    return true;
  })
], async (req, res) => {
  const errors = validationResult(req);
  if (!errors.isEmpty()) {
    return res.render('auth/signup', { 
      title: 'Sign Up - AgroFertMart',
      error: errors.array()[0].msg,
      body: req.body
    });
  }

  try {
    const { username, email, password } = req.body;
    const existingUser = await User.findOne({ $or: [{ email }, { username }] });
    if (existingUser) {
      return res.render('auth/signup', { 
        title: 'Sign Up - AgroFertMart',
        error: 'Username or email already exists',
        body: req.body
      });
    }

    const user = new User({ username, email, password });
    await user.save();
    
    req.session.success = 'Registration successful! Please login.';
    res.redirect('/auth/login');
  } catch (err) {
    console.error('SIGNUP ERROR:', err);
    res.render('auth/signup', { 
      title: 'Sign Up - AgroFertMart',
      error: 'Something went wrong. Please try again.',
      body: req.body
    });
  }
});

// GET Login
router.get('/login', (req, res) => {
  res.render('auth/login', { title: 'Login - AgroFertMart' });
});

// POST Login
router.post('/login', async (req, res) => {
  let { username, password, loginAs } = req.body;
  username = username ? username.trim().toLowerCase() : '';
  
  try {
    const user = await User.findOne({ username });
    if (!user || !(await user.comparePassword(password))) {
      return res.render('auth/login', { 
        title: 'Login - AgroFertMart',
        error: 'Invalid username or password',
        username
      });
    }

    // Role check: block normal users from admin portal
    if (loginAs === 'admin' && user.role !== 'admin') {
      return res.render('auth/login', {
        title: 'Login - AgroFertMart',
        error: 'Access Denied: You do not have administrator privileges.',
        username
      });
    }

    req.session.user = {
      id: user._id.toString(),
      username: user.username,
      role: user.role
    };

    if (user.role === 'admin') {
      res.redirect('/admin/dashboard');
    } else {
      res.redirect('/');
    }
  } catch (err) {
    console.error(err);
    res.render('auth/login', { 
      title: 'Login - AgroFertMart',
      error: 'Something went wrong',
      username
    });
  }
});

// GET Logout
router.get('/logout', (req, res) => {
  req.session.destroy();
  res.redirect('/auth/login');
});

module.exports = router;
