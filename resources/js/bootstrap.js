import axios from 'axios';

window.axios = axios;

// Set default headers
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// ✅ Add this to fix HTTPS network errors
window.axios.defaults.baseURL = 'https://laravel-image-gallery.onrender.com';

