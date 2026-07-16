import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import './echo';

window.axios.interceptors.request.use((config) => {
    if (window.Echo?.socketId()) {
        config.headers['X-Socket-Id'] = window.Echo.socketId();
    }
    return config;
});
