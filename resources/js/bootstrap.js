import axios from 'axios';

window.axios = axios;

// Set default headers
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// ✅ Add this to fix HTTPS network errors
// ✅ Use relative URLs (like /login)
window.axios.defaults.baseURL = '/';

