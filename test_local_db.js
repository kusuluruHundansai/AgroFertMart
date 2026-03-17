const mongoose = require('mongoose');

const uri = 'mongodb://localhost:27017/agrofertmart';

console.log('Attempting to connect to local MongoDB...');

mongoose.connect(uri, {
    serverSelectionTimeoutMS: 5000
})
.then(() => {
    console.log('✅ Success! Connected to local MongoDB.');
    process.exit(0);
})
.catch(err => {
    console.error('❌ Local connection failed:');
    console.error(err.message);
    process.exit(1);
});
