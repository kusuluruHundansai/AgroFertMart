const mongoose = require('mongoose');
require('dotenv').config();

const checkSessions = async () => {
    try {
        await mongoose.connect(process.env.MONGODB_URI);
        const db = mongoose.connection.db;
        const sessions = await db.collection('sessions').find({}).toArray();
        console.log('Total sessions found:', sessions.length);
        sessions.forEach(s => {
            console.log('Session ID:', s._id);
            console.log('Session Data:', JSON.stringify(s.session));
        });
        process.exit(0);
    } catch (err) {
        console.error(err);
        process.exit(1);
    }
};

checkSessions();
