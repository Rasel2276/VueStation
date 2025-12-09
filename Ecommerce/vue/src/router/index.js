import { createRouter, createWebHistory } from "vue-router";
import AdminDashboardLayout from "../Dashboard/Admindashboard/Adminlayout/AdminDashboardLayout.vue";
import AdminDashboard from "../Dashboard/Admindashboard/Admincomponents/AdminDashboard.vue";
import Ashik from "@/Dashboard/Admindashboard/Admincomponents/ashik.vue";


const routes = [
  {
    path: "/",
    component: AdminDashboardLayout,
    children: [
      { path: "", component: AdminDashboard },
      { path: "/ashik", component: Ashik },
    ],
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
