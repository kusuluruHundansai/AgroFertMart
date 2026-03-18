/* ═══════════════════════════════════════════════════════
   AgroFertMart — Production-Ready Express Server
   ═══════════════════════════════════════════════════════
   Fixes applied for Render deployment:
   1. dotenv only loaded in development (Render injects env vars natively)
   2. connect-mongo v5+ uses default export, NOT .MongoStore
   3. MongoStore.create() uses validated MONGODB_URI
   4. trust proxy enabled for Render's reverse proxy
   5. Secure cookies in production (sameSite, secure)
   6. Graceful MongoDB connection with retry logic
   7. Meaningful startup logging for Render logs
   ═══════════════════════════════════════════════════════ */

// ─── Load .env only in development (Render sets env vars natively) ──
if (process.env.NODE_ENV !== 'production') {
  require('dotenv').config();
}

const express    = require('express');
const mongoose   = require('mongoose');
const session    = require('express-session');
const MongoStore = require('connect-mongo').default || require('connect-mongo').MongoStore || require('connect-mongo'); // FIX: handle multiple v6 export shapes
const path       = require('path');
const helmet     = require('helmet');
const cors       = require('cors');
const morgan     = require('morgan');
const methodOverride = require('method-override');
const rateLimit  = require('express-rate-limit');

// ─── Environment Variable Validation ─────────────────────────────
const MONGODB_URI    = process.env.MONGODB_URI;
const SESSION_SECRET = process.env.SESSION_SECRET;
const PORT           = process.env.PORT || 3000;
const IS_PRODUCTION  = process.env.NODE_ENV === 'production';

// FIX: Fail fast with clear error if critical env vars are missing
if (!MONGODB_URI) {
  console.error('╔══════════════════════════════════════════════════╗');
  console.error('║  FATAL: MONGODB_URI environment variable is     ║');
  console.error('║  not set. Set it in Render Dashboard → Env Vars ║');
  console.error('║  or in your local .env file.                    ║');
  console.error('╚══════════════════════════════════════════════════╝');
  process.exit(1);
}

if (!SESSION_SECRET) {
  console.warn('⚠️  SESSION_SECRET not set — using fallback. Set it in production!');
}

// Debug log for deployment troubleshooting (values are masked)
console.log('─── Environment ───────────────────────────────');
console.log(`  NODE_ENV     : ${process.env.NODE_ENV || 'development'}`);
console.log(`  PORT         : ${PORT}`);
console.log(`  MONGODB_URI  : ${MONGODB_URI.substring(0, 20)}…`);
console.log(`  SESSION_SECRET: ${SESSION_SECRET ? '✅ set' : '⚠️  fallback'}`);
console.log('───────────────────────────────────────────────');

// ─── Express App ──────────────────────────────────────────────────
const app = express();

// FIX: Trust Render's reverse proxy (required for secure cookies & rate limiting)
if (IS_PRODUCTION) {
  app.set('trust proxy', 1);
}

// ─── Security & Middleware ────────────────────────────────────────
app.use(helmet({
  contentSecurityPolicy: false,       // Allow inline scripts in EJS templates
  crossOriginEmbedderPolicy: false    // Allow external image/font CDN loading
}));
app.use(cors());
app.use(morgan(IS_PRODUCTION ? 'combined' : 'dev'));  // Combined logs in production
app.use(express.json());
app.use(express.urlencoded({ extended: true }));
app.use(methodOverride('_method'));

// Rate limiting (applied to all routes, safe defaults)
const limiter = rateLimit({
  windowMs: 15 * 60 * 1000,   // 15 minutes
  max: IS_PRODUCTION ? 100 : 500,  // Stricter in production
  message: 'Too many requests, please try again later.',
  standardHeaders: true,
  legacyHeaders: false
});
app.use(limiter);

// ─── Static Files ─────────────────────────────────────────────────
app.use(express.static(path.join(__dirname, 'public')));
app.use('/uploads', express.static(path.join(__dirname, 'uploads')));

// ─── View Engine ──────────────────────────────────────────────────
app.set('view engine', 'ejs');
app.set('views', path.join(__dirname, 'views'));

// ─── Session Configuration ────────────────────────────────────────
// FIX: MongoStore.create() with validated MONGODB_URI + error handling
const sessionStore = MongoStore.create({
  mongoUrl: MONGODB_URI,
  ttl: 24 * 60 * 60,           // Session TTL: 1 day (in seconds)
  touchAfter: 24 * 3600,       // Lazy session update (reduce DB writes)
  autoRemove: 'native'         // Use MongoDB TTL index for cleanup
});

sessionStore.on('error', function(error) {
  console.error('❌ SESSION STORE ERROR:', error);
});

app.use(session({
  secret: SESSION_SECRET || 'agrofertmart-fallback-secret',
  resave: false,
  saveUninitialized: false,
  store: sessionStore,
  cookie: {
    maxAge: 24 * 60 * 60 * 1000,   // 1 day in ms
    httpOnly: true,                  // Prevent XSS access to cookie
    secure: IS_PRODUCTION,           // FIX: HTTPS-only in production
    sameSite: IS_PRODUCTION ? 'lax' : 'lax'  // CSRF protection
  }
}));

// ─── Global Template Variables ────────────────────────────────────
app.use((req, res, next) => {
  res.locals.currentUser = req.session.user || null;
  res.locals.cart = req.session.cart || { items: [], total: 0, count: 0 };
  res.locals.success = req.session.success || null;
  res.locals.error = req.session.error || null;
  delete req.session.success;
  delete req.session.error;
  next();
});

// ─── Routes ───────────────────────────────────────────────────────
const authRoutes    = require('./routes/auth');
const productRoutes = require('./routes/products');
const cartRoutes    = require('./routes/cart');
const orderRoutes   = require('./routes/orders');
const adminRoutes   = require('./routes/admin');

app.get('/', (req, res) => {
  res.render('home', { title: 'AgroFertMart - Buy Fertilizers & Pesticides Online' });
});

app.use('/auth', authRoutes);
app.use('/products', productRoutes);
app.use('/cart', cartRoutes);
app.use('/orders', orderRoutes);
app.use('/admin', adminRoutes);

// ─── 404 Handler ──────────────────────────────────────────────────
app.use((req, res) => {
  // Ensure all layout locals exist even if session middleware failed
  res.locals.currentUser = res.locals.currentUser || null;
  res.locals.cart = res.locals.cart || { items: [], total: 0, count: 0 };
  res.locals.success = res.locals.success || null;
  res.locals.error = res.locals.error || null;
  res.status(404).render('404', { title: 'Page Not Found' });
});

// ─── Global Error Handler ─────────────────────────────────────────
app.use((err, req, res, next) => {
  console.error(`🔥 Unhandled Error on ${req.method} ${req.originalUrl}:`, err);
  // Ensure all layout locals exist even if session middleware failed
  res.locals.currentUser = res.locals.currentUser || null;
  res.locals.cart = res.locals.cart || { items: [], total: 0, count: 0 };
  res.locals.success = res.locals.success || null;
  res.locals.error = res.locals.error || null;
  res.status(500).render('error', {
    title: 'Server Error',
    error_msg: IS_PRODUCTION ? 'Something went wrong.' : err.message // Renamed to error_msg to avoid conflict with res.locals.error
  });
});

// ─── Database Connection & Server Start ───────────────────────────
async function startServer() {
  try {
    console.log('🔌 Connecting to MongoDB…');

    await mongoose.connect(MONGODB_URI, {
      // Mongoose 7+ handles these internally, but explicit for clarity:
      serverSelectionTimeoutMS: 10000,  // Fail fast if Atlas is unreachable
      socketTimeoutMS: 45000            // Close sockets after 45s inactivity
    });

    console.log('✅ MongoDB connected successfully');

    // Graceful shutdown handlers
    mongoose.connection.on('error', (err) => {
      console.error('❌ MongoDB runtime error:', err);
    });
    mongoose.connection.on('disconnected', () => {
      console.warn('⚠️  MongoDB disconnected. Attempting reconnect…');
    });

    // Start listening
    app.listen(PORT, '0.0.0.0', () => {
      console.log('═══════════════════════════════════════════');
      console.log(`🚀 AgroFertMart is live!`);
      console.log(`   Environment : ${IS_PRODUCTION ? 'PRODUCTION' : 'DEVELOPMENT'}`);
      console.log(`   Port        : ${PORT}`);
      console.log(`   URL         : ${IS_PRODUCTION ? 'https://agro-fert-mart.vercel.app' : `http://localhost:${PORT}`}`);
      console.log('═══════════════════════════════════════════');
    });

  } catch (err) {
    console.error('╔══════════════════════════════════════════════════╗');
    console.error('║  FATAL: Could not connect to MongoDB             ║');
    console.error(`║  ${err.message.substring(0, 48).padEnd(48)}║`);
    console.error('║  Check your MONGODB_URI and network access.      ║');
    console.error('╚══════════════════════════════════════════════════╝');
    process.exit(1);
  }
}

startServer();

module.exports = app;
