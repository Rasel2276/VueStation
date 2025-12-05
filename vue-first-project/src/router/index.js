import { createRouter, createWebHistory } from "vue-router";
import AdminDashboard from "../components/AdminDashboard.vue";
import DashboardPage from "../components/pages/DashboardPage.vue";
import TasksPage from "../components/pages/TasksPage.vue";
import UsersPage from "../components/pages/UsersPage.vue";

const routes = [
  {
    path: "/",
    component: AdminDashboard,
    children: [
      { path: "", component: DashboardPage },
      { path: "tasks", component: TasksPage },
      { path: "users", component: UsersPage },
    ],
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
