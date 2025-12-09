import { createRouter, createWebHistory } from "vue-router";

// ===== Auth Pages =====
import Login from "@/auth/Login.vue";
import Registration from "@/auth/Registration.vue";

// ===== Admin =====
import AdminDashboardLayout from "@/Dashboard/Admindashboard/Adminlayout/AdminDashboardLayout.vue";
import AdminDashboard from "@/Dashboard/Admindashboard/Admincomponents/AdminDashboard.vue";

// ===== Vendor =====
import VendorDashboardLayout from "@/Dashboard/Vendordashboard/Vendor-layout/VendorDashboardLayout.vue";
import VendorDashboard from "@/Dashboard/Vendordashboard/Vendor-components/VendorDashboard.vue";

// ===== Customer =====
import CustomerDashboardLayout from "@/Dashboard/Customerdashboard/Customer-layout/CustomerDashboardLayout.vue";
import CustomerDashboard from "@/Dashboard/Customerdashboard/Customer-components/CustomerDashboard.vue";


// ========================
//       ROUTES
// ========================
const routes = [
  // Public Routes
  {
    path: "/",
    name: "login",
    component: Login,
  },
  {
    path: "/register",
    name: "register",
    component: Registration,
  },

  // Admin
  {
    path: "/admindashboardlayout",
    component: AdminDashboardLayout,
    children: [
      { path: "", component: AdminDashboard },
    ],
  },

  // Vendor
  {
    path: "/vendordashboardlayout",
    component: VendorDashboardLayout,
    children: [
      { path: "", component: VendorDashboard },
    ],
  },

  // Customer
  {
    path: "/customerdashboardlayout",
    component: CustomerDashboardLayout,
    children: [
      { path: "", component: CustomerDashboard },
    ],
  },
];


// ========================
//     CREATE ROUTER
// ========================
const router = createRouter({
  history: createWebHistory(),
  routes,
});


// ================================
// 🔥 GLOBAL ROUTE GUARD (Auth)
// ================================
router.beforeEach((to, from, next) => {
  const role = localStorage.getItem("role");

  // Free Routes (Login & Register)
  if (to.path === "/" || to.path === "/register") {
    return next();
  }

  // Not logged in → Redirect to login
  if (!role) return next("/");

  // Admin route protection
  if (to.path.startsWith("/admindashboardlayout") && role !== "admin") {
    return next("/");
  }

  // Vendor route protection
  if (to.path.startsWith("/vendordashboardlayout") && role !== "vendor") {
    return next("/");
  }

  // Customer route protection
  if (to.path.startsWith("/customerdashboardlayout") && role !== "customer") {
    return next("/");
  }

  next();
});


export default router;
