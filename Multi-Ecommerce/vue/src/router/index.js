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
import CategoryCreate from '../Dashboard/Admindashboard/Admincomponents/CategoryCreate.vue'
import CategoryManage from '../Dashboard/Admindashboard/Admincomponents/CategoryManage.vue'
import SubCategoryCreate from '../Dashboard/Admindashboard/Admincomponents/SubCategoryCreate.vue'
import SubcategoryManage from '../Dashboard/Admindashboard/Admincomponents/SubcategoryManage.vue'
import SupplierAdd from '../Dashboard/Admindashboard/Admincomponents/SupplierAdd.vue'
import SupplierManage from '../Dashboard/Admindashboard/Admincomponents/SupplierManage.vue'
import SupplierProductAdd from '../Dashboard/Admindashboard/Admincomponents/SupplierProductAdd.vue'
import SupplierProductManage from '../Dashboard/Admindashboard/Admincomponents/SupplierProductManage.vue'
import AdminPurchase from '../Dashboard/Admindashboard/Admincomponents/AdminPurchase.vue'
import AdminPurchasePayment from '../Dashboard/Admindashboard/Admincomponents/AdminPurchasePayment.vue'
import AdminPurchaseManage from '../Dashboard/Admindashboard/Admincomponents/AdminPurchaseManage.vue'
import AdminStockManage from '../Dashboard/Admindashboard/Admincomponents/AdminStockManage.vue'
import SupplierPurchaseReturn from '../Dashboard/Admindashboard/Admincomponents/SupplierPurchaseReturn.vue'

// ===== WEBSITE PAGES =====
import ProductPage from '../Website/WebsitePages/ProductPage.vue'
import AboutPage from '../Website/WebsitePages/AboutPage.vue'
import ContactPage from '../Website/WebsitePages/ContactPage.vue'
import HomePage from '../Website/WebsitePages/HomePage.vue'
















const routes = [
  // ===== AUTH =====
  { path: '/login', name: 'login', component: Login },
  { path: '/register', name: 'register', component: Registration },

  // ===== ADMIN =====
  {
    path: '/AdminDefaultLayout',
    component: AdminDefaultLayout,
    children: [
      { path: '', component: AdminDashboard },  
      { path: '/category/add_category', component: CategoryCreate }, 
      { path: '/category/manage_category', component: CategoryManage },
      { path: '/sub-category/add_category', component: SubCategoryCreate },
      { path: '/sub-category/manage_category', component: SubcategoryManage },
      { path: '/supplier/add-supplier', component: SupplierAdd },
      { path: '/supplier/manage-supplier', component: SupplierManage },
      { path: '/supplier/supplier-product', component: SupplierProductAdd },
      { path: '/supplier/manage-product', component: SupplierProductManage },
      { path: '/inventory/Purchase', component: AdminPurchase },
      { path: '/inventory/purchase-payment', component: AdminPurchasePayment },
      { path: '/inventory/purchase-record', component: AdminPurchaseManage },
      { path: '/inventory/my-stock', component: AdminStockManage },
      { path: '/inventory/purchase-return', component: SupplierPurchaseReturn },
    ],
  },

  // ===== CUSTOMER =====
  {
    path: '/CustomerDefaultLayout',
    component: CustomerDefaultLayout,
    children: [
      { path: '', component: CustomerDashboard },
    ],
  },

  // ===== VENDOR =====
  {
    path: '/VendorDefaultLayout',
    component: VendorDefaultLayout,
    children: [
      { path: '', component: VendorDashboard },
    ],
  },

  // ===== WEBSITE =====
  {
    path: '/',
    component: WebsiteDefaultLayout,
    children: [
      { path: '', component: HomePage }, 
      { path: 'products', component: ProductPage },   
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

  if (!role) return next('/')

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
