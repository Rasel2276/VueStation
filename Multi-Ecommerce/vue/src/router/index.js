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
import WebsiteDefaultLayout from '../Website/WebsiteLayout/WebsiteDefaultLayout.vue'
import ProductPage from '../Website/WebsitePages/ProductPage.vue'




const routes = [
   

  {
    path: "/login",
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

       {
        path: "/",
        component: WebsiteDefaultLayout,
        children: [
           { path: "", component: ProductPage },
           ],
   },

]

const router = createRouter({
    history: createWebHistory(),
    routes
})


router.beforeEach((to, from, next) => {
  const role = localStorage.getItem("role");

  // Public routes (no login required)
  if (
    to.path === "/login" ||
    to.path === "/register" ||
    to.path === "/"       // website home is public
  ){
    return next();
  }

  // If no role found -> redirect to login
  if (!role) return next("/login");

  // Role based protections
  if (to.path.startsWith("/AdminDefaultLayout") && role !== "admin") {
    return next("/login");
  }

  if (to.path.startsWith("/VendorDefaultLayout") && role !== "vendor") {
    return next("/login");
  }

  if (to.path.startsWith("/CustomerDefaultLayout") && role !== "customer") {
    return next("/login");
  }

  next();
});


// router.beforeEach((to, from, next) => {
//   const role = localStorage.getItem("role");


//   if (to.path === "/" || to.path === "/register") {
//     return next();
//   }


//   if (!role) return next("/");

 
//   if (to.path.startsWith("/AdminDefaultLayout") && role !== "admin") {
//     return next("/");
//   }


//   if (to.path.startsWith("/VendorDefaultLayout") && role !== "vendor") {
//     return next("/");
//   }


//   if (to.path.startsWith("/CustomerDefaultLayout") && role !== "customer") {
//     return next("/");
//   }

//   next();
// });

// Update document title based on route meta title
router.afterEach((to) => {
    const nearestWithTitle = to.matched.slice().reverse().find(r => r.meta?.title)
    document.title = nearestWithTitle ? nearestWithTitle.meta.title : 'StarCode Kh'
})

export default router
