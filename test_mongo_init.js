const { MongoStore } = require('connect-mongo');
console.log('MongoStore is function?', typeof MongoStore === 'function');
console.log('MongoStore.create is function?', typeof MongoStore.create === 'function');

try {
    const store = new MongoStore({
        mongoUrl: 'mongodb://localhost:27017/agrofertmart'
    });
    console.log('Successfully created instance with "new"');
} catch (e) {
    console.error('Failed with "new":', e.message);
}

try {
    const store = MongoStore.create({
        mongoUrl: 'mongodb://localhost:27017/agrofertmart'
    });
    console.log('Successfully created instance with ".create"');
} catch (e) {
    console.error('Failed with ".create":', e.message);
}
process.exit(0);
