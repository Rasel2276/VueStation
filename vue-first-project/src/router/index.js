import { createRouter, createWebHistory } from "vue-router";
import AdminDashboard from "../components/AdminDashboard.vue";
import DashboardPage from "../components/pages/DashboardPage.vue";
import TasksPage from "../components/pages/TasksPage.vue";
import UsersPage from "../components/pages/UsersPage.vue";
import Profile from "@/components/pages/Profile.vue";
import Navbar from "@/website/Navbar.vue";
import Footer from "@/website/Footer.vue";
import ProductPage from "@/website/ProductPage.vue";

const routes = [
  {
    path: "/",
    component: AdminDashboard,
    children: [
      { path: "", component: DashboardPage },
      { path: "tasks", component: TasksPage },
      { path: "users", component: UsersPage },
      { path: "profile", component: Profile },
    ],
  },

  {
    path: "/navbar",
    name: "navbar",
    component: Navbar,
  },

    {
    path: "/footer",
    name: "footer",
    component: Footer,
  },

      {
    path: "/productpage",
    name: "productpage",
    component: ProductPage,
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
