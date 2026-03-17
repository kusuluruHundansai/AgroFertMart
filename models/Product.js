const mongoose = require('mongoose');

const productSchema = new mongoose.Schema({
  name: {
    type: String,
    required: true,
    trim: true
  },
  description: {
    type: String,
    trim: true
  },
  price: {
    type: Number,
    required: true,
    min: 0
  },
  category: {
    type: String,
    required: true,
    enum: ['Fertilizer', 'Pesticide', 'Equipment', 'Seed', 'Other']
  },
  image_url: {
    type: String,
    default: 'default.jpg'
  },
  badge: {
    type: String,
    enum: ['NEW', 'POPULAR', null],
    default: null
  },
  createdAt: {
    type: Date,
    default: Date.now
  }
});

module.exports = mongoose.model('Product', productSchema);
