const axios = require('axios');

const testLogin = async () => {
    try {
        console.log('Attempting to login as admin...');
        const response = await axios.post('http://localhost:3000/auth/login', {
            username: 'admin',
            password: 'adminpassword',
            loginAs: 'admin'
        }, {
            maxRedirects: 0,
            validateStatus: (status) => status < 500
        });

        console.log('Status:', response.status);
        console.log('Headers:', response.headers);
        
        if (response.status === 302) {
            console.log('Success! Redirected to:', response.headers.location);
        } else {
            console.log('Login failed.');
            // Check if there's an error message in HTML (this is rough)
            const html = response.data;
            if (html.includes('Invalid username or password')) {
                console.log('Error: Invalid username or password');
            } else if (html.includes('Access Denied')) {
                console.log('Error: Access Denied');
            } else {
                console.log('Unknown error. Page length:', html.length);
            }
        }
    } catch (err) {
        console.error('Error during request:', err.message);
    }
};

testLogin();
