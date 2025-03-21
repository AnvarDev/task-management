import axios from 'axios';
window.axios = axios;

// Define the base URL for all axios requests:
axios.defaults.baseURL = import.meta.env.BASE_URL + 'api';

// Configure the default headers for axios:
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

if (localStorage.getItem('token')) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('token')}`;
}

// Handle token expiration or invalid tokens:
axios.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error.response?.status === 401) {
            // Remove the token from local storage:
            localStorage.removeItem('token');

            // Reset the axios Authorization header:
            axios.defaults.headers.common['Authorization'] = 'Bearer';

            const loginPage = '/login';
            if (window.location.pathname != loginPage) {
                // Redirect the user to the login page:
                window.location.href = loginPage;
            }
        }

        return Promise.reject(error);
    }
);