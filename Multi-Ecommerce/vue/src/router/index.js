import { createRouter, createWebHistory } from 'vue-router'

// ===== LAYOUTS =====
import AdminDefaultLayout from '../Dashboard/Admindashboard/Adminlayout/AdminDefaultLayout.vue'
import CustomerDefaultLayout from '../Dashboard/Customerdashboard/Customer-layout/CustomerDefaultLayout.vue'
import VendorDefaultLayout from '../Dashboard/Vendordashboard/Vendor-layout/VendorDefaultLayout.vue'
import WebsiteDefaultLayout from '../Website/WebsiteLayout/WebsiteDefaultLayout.vue'

// ===== AUTH =====
import Login from '../auth/Login.vue'
import Registration from '../auth/Registration.vue'

// ===== DASHBOARD PAGES =====
import AdminDashboard from '../Dashboard/Admindashboard/Admincomponents/AdminDashboard.vue'
import VendorDashboard from '../Dashboard/Vendordashboard/Vendor-components/VendorDashboard.vue'
import CustomerDashboard from '../Dashboard/Customerdashboard/Customer-components/CustomerDashboard.vue'
import Recent from '../Dashboard/Admindashboard/Admincomponents/Recent.vue'

// ===== WEBSITE PAGES =====
import ProductPage from '../Website/WebsitePages/ProductPage.vue'
import HomePage from '../Website/WebsitePages/HomePage.vue'
import AboutPage from '../Website/WebsitePages/AboutPage.vue'
import ContactPage from '../Website/WebsitePages/ContactPage.vue'
// (Optional future pages)
// import About from '../Website/WebsitePages/About.vue'
// import Contact from '../Website/WebsitePages/Contact.vue'

const routes = [
  // ===== AUTH =====
  { path: '/login', name: 'login', component: Login },
  { path: '/register', name: 'register', component: Registration },

  // ===== ADMIN =====
  {
    path: '/admin',
    component: AdminDefaultLayout,
    children: [
      { path: '', component: AdminDashboard },         // /admin
      { path: 'recent', component: Recent },           // /admin/recent
    ],
  },

  // ===== CUSTOMER =====
  {
    path: '/customer',
    component: CustomerDefaultLayout,
    children: [
      { path: '', component: CustomerDashboard },      // /customer
    ],
  },

  // ===== VENDOR =====
  {
    path: '/vendor',
    component: VendorDefaultLayout,
    children: [
      { path: '', component: VendorDashboard },        // /vendor
    ],
  },

  // ===== WEBSITE =====
  {
    path: '/',
    component: WebsiteDefaultLayout,
    children: [
      { path: '', component: HomePage },            // /
      { path: 'products', component: ProductPage },    // /products
      { path: 'about', component: AboutPage },
      { path: 'contact', component: ContactPage },
    ],
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

// ===== ROUTE GUARD =====
router.beforeEach((to, from, next) => {
  const role = localStorage.getItem('role')

  // PUBLIC WEBSITE ROUTES
  if (
    to.path === '/' ||
    to.path.startsWith('/products') ||
    to.path === '/login' ||
    to.path === '/register'||
    to.path === '/about'||
    to.path === '/contact'
  ) {
    return next()
  }

  if (!role) return next('/login')

  if (to.path.startsWith('/admin') && role !== 'admin') return next('/login')
  if (to.path.startsWith('/vendor') && role !== 'vendor') return next('/login')
  if (to.path.startsWith('/customer') && role !== 'customer') return next('/login')

  next()
})

// ===== DOCUMENT TITLE =====
router.afterEach((to) => {
  const nearestWithTitle = to.matched.slice().reverse().find(r => r.meta?.title)
  document.title = nearestWithTitle ? nearestWithTitle.meta.title : 'StarCode Kh'
})

export default router
