// main.js
import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import './assets/style.css'; // global CSS

// Optional: global axios setup
import axios from 'axios';

// Set base URL for axios (backend API URL)
axios.defaults.baseURL = 'http://localhost:8000/api'; // ekhane tomader backend URL den
axios.defaults.headers.common['Accept'] = 'application/json';

// Optional: attach token from localStorage if exists
const token = localStorage.getItem('token');
if (token) {
  axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
}

const app = createApp(App);

// Make axios globally available in all components
app.config.globalProperties.$axios = axios;

app.use(router);
app.mount('#app');

