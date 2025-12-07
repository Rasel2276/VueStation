import { createRouter, createWebHistory } from "vue-router";
import AdminDashboardLayout from "../Dashboard/Admindashboard/Adminlayout/AdminDashboardLayout.vue";
import AdminDashboard from "../Dashboard/Admindashboard/Admincomponents/AdminDashboard.vue";


const routes = [
  {
    path: "/",
    component: AdminDashboardLayout,
    children: [
      { path: "", component: AdminDashboard },
    ],
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
