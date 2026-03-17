const mongoose = require('mongoose');
require('dotenv').config();

const uri = process.env.MONGODB_URI;

console.log('Attempting to connect to MongoDB Atlas...');
console.log('URI:', uri.replace(/\/\/.*@/, '//<credentials>@'));

mongoose.connect(uri, {
    serverSelectionTimeoutMS: 10000,
    tlsAllowInvalidCertificates: true // Testing if it helps with handshake
})
.then(() => {
    console.log('✅ Success! Connected to MongoDB.');
    process.exit(0);
})
.catch(err => {
    console.error('❌ Connection failed:');
    console.error(err);
    process.exit(1);
});
