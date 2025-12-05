import { createRouter, createWebHistory } from 'vue-router';
import MainLayout from '@/admindashboard/layouts/MainLayout.vue';
import Dashboard from '@/admindashboard/views/Dashboard.vue';

const routes = [
  {
    path: '/admin',
    component: MainLayout,
    children: [
      {
        path: '',
        name: 'AdminDashboard',
        component: Dashboard
      }
    ]
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
