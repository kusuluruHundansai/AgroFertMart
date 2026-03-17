const mongoose = require('mongoose');
const Product = require('./models/Product');
require('dotenv').config();

async function checkSeeds() {
    try {
        await mongoose.connect(process.env.MONGODB_URI);
        const seeds = await Product.find({ category: 'Seed' });
        console.log('--- SEED DATA IN DB ---');
        seeds.forEach(s => {
            console.log(`Name: ${s.name}`);
            console.log(`Image URL: ${s.image_url}`);
            console.log('---');
        });
        process.exit(0);
    } catch (err) {
        console.error(err);
        process.exit(1);
    }
}

checkSeeds();
