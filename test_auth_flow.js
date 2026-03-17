const mongoose = require('mongoose');
const User = require('./models/User');
require('dotenv').config();

const testFlow = async () => {
    try {
        await mongoose.connect(process.env.MONGODB_URI);
        
        // 1. Create a test user
        const testUsername = 'testuser_' + Date.now();
        const testPassword = 'testpassword123';
        console.log(`Creating test user: ${testUsername} / ${testPassword}`);
        
        const user = new User({
            username: testUsername,
            email: `${testUsername}@example.com`,
            password: testPassword
        });
        await user.save();
        console.log('User saved successfully.');
        
        // 2. Fetch it back
        const fetched = await User.findOne({ username: testUsername });
        console.log('Fetched user:', fetched.username);
        
        // 3. Test comparison
        const isMatch = await fetched.comparePassword(testPassword);
        console.log('Does password match?', isMatch);
        
        // 4. Test WRONG password
        const isWrongMatch = await fetched.comparePassword('wrongpassword');
        console.log('Does wrong password match?', isWrongMatch);
        
        process.exit(0);
    } catch (err) {
        console.error(err);
        process.exit(1);
    }
};

testFlow();
