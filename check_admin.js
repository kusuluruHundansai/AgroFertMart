const mongoose = require('mongoose');
const User = require('./models/User');
require('dotenv').config();

const checkAdmin = async () => {
    try {
        await mongoose.connect(process.env.MONGODB_URI);
        const admin = await User.findOne({ username: 'admin' });
        if (admin) {
            console.log('Admin found:');
            console.log('Username:', admin.username);
            console.log('Password Hash:', admin.password);
            console.log('Role:', admin.role);
            
            // Test compare
            const bcrypt = require('bcryptjs');
            const isMatch = await bcrypt.compare('adminpassword', admin.password);
            console.log('Does "adminpassword" match?', isMatch);
        } else {
            console.log('Admin user not found!');
        }
        process.exit(0);
    } catch (err) {
        console.error(err);
        process.exit(1);
    }
};

checkAdmin();
