const MongoStore = require('connect-mongo');
console.log('MongoStore type:', typeof MongoStore);
console.log('MongoStore keys:', Object.keys(MongoStore));
if (MongoStore.default) {
    console.log('MongoStore.default exists');
    console.log('MongoStore.default keys:', Object.keys(MongoStore.default));
}
process.exit(0);
