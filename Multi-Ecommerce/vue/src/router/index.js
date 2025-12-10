import { createRouter, createWebHistory } from 'vue-router'


import AdminDefaultLayout from '../Dashboard/Admindashboard/Adminlayout/AdminDefaultLayout.vue'
import CustomerDefaultLayout from '../Dashboard/Customerdashboard/Customer-layout/CustomerDefaultLayout.vue'
import VendorDefaultLayout from '../Dashboard/Vendordashboard/Vendor-layout/VendorDefaultLayout.vue'


import Login from '../auth/Login.vue'
import Registration from '../auth/Registration.vue'


// components//
import AdminDashboard from '../Dashboard/Admindashboard/Admincomponents/AdminDashboard.vue'
import VendorDashboard from '../Dashboard/Vendordashboard/Vendor-components/VendorDashboard.vue'
import CustomerDashboard from '../Dashboard/Customerdashboard/Customer-components/CustomerDashboard.vue'
import Recent from '../Dashboard/Admindashboard/Admincomponents/Recent.vue'



const routes = [
   

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

    {
        path: "/AdminDefaultLayout",
        component: AdminDefaultLayout,
        children: [
           { path: "", component: AdminDashboard},
           { path: "/folder/recent", component: Recent},
           ],
   },

    {
        path: "/CustomerDefaultLayout",
        component: CustomerDefaultLayout,
        children: [
           { path: "", component: CustomerDashboard },
           ],
   },

    {
        path: "/VendorDefaultLayout",
        component: VendorDefaultLayout,
        children: [
           { path: "", component:  VendorDashboard},
           ],
   },


]

const router = createRouter({
    history: createWebHistory(),
    routes
})

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
  if (to.path.startsWith("/AdminDefaultLayout") && role !== "admin") {
    return next("/");
  }

  // Vendor route protection
  if (to.path.startsWith("/VendorDefaultLayout") && role !== "vendor") {
    return next("/");
  }

  // Customer route protection
  if (to.path.startsWith("/CustomerDefaultLayout") && role !== "customer") {
    return next("/");
  }

  next();
});

// Update document title based on route meta title
router.afterEach((to) => {
    const nearestWithTitle = to.matched.slice().reverse().find(r => r.meta?.title)
    document.title = nearestWithTitle ? nearestWithTitle.meta.title : 'StarCode Kh'
})

export default router
