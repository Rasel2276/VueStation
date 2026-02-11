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
import SupplierReturnHistory from '../Dashboard/Admindashboard/Admincomponents/SupplierReturnHistory.vue'

// ===== WEBSITE PAGES =====
import ProductPage from '../Website/WebsitePages/ProductPage.vue'
import AboutPage from '../Website/WebsitePages/AboutPage.vue'
import ContactPage from '../Website/WebsitePages/ContactPage.vue'
import HomePage from '../Website/WebsitePages/HomePage.vue'
import VendorProductsList from '../Dashboard/Vendordashboard/Vendor-components/VendorProductsList.vue'
import VendorPurchase from '../Dashboard/Vendordashboard/Vendor-components/VendorPurchase.vue'
import VendorPurchaseManage from '../Dashboard/Vendordashboard/Vendor-components/VendorPurchaseManage.vue'
import VendorStockList from '../Dashboard/Vendordashboard/Vendor-components/VendorStockList.vue'
import VendorPurchaseReturn from '../Dashboard/Vendordashboard/Vendor-components/VendorPurchaseReturn.vue'
import VendorPurchasePayment from '../Dashboard/Vendordashboard/Vendor-components/VendorPurchasePayment.vue'
import VendorReturnRecord from '../Dashboard/Vendordashboard/Vendor-components/VendorReturnRecord.vue'
import ProductDetails from '../Website/WebsitePages/ProductDetails.vue'
import CheckoutPage from '../Website/WebsitePages/CheckoutPage.vue'
import AddCustomerProduct from '../Dashboard/Vendordashboard/Vendor-components/AddCustomerProduct.vue'
import HomeProducts from '../Website/WebsitePages/HomeProducts.vue'
import CustomerReviews from '../Website/WebsitePages/CustomerReviews.vue'
import CustomerProductManage from '../Dashboard/Vendordashboard/Vendor-components/CustomerProductManage.vue'
import CustomerOrderManage from '../Dashboard/Vendordashboard/Vendor-components/CustomerOrderManage.vue'
import OrderTrackingPage from '../Website/WebsitePages/OrderTrackingPage.vue'
import MyOrderManage from '../Dashboard/Customerdashboard/Customer-components/MyOrderManage.vue'
import ReturnRequest from '../Dashboard/Customerdashboard/Customer-components/ReturnRequest.vue'
import CustomerReturnRequestManage from '../Dashboard/Vendordashboard/Vendor-components/CustomerReturnRequestManage.vue'
import ReturnHistory from '../Dashboard/Vendordashboard/Vendor-components/ReturnHistory.vue'
import MyReturnList from '../Dashboard/Customerdashboard/Customer-components/MyReturnList.vue'
import ProfileSettings from '../Dashboard/Vendordashboard/Vendor-components/VendorProfileSettings.vue'
import CustomerProfileSettings from '../Dashboard/Customerdashboard/Customer-components/CustomerProfileSettings.vue'
import CustomerUserProfileView from '../Dashboard/Customerdashboard/Customer-components/CustomerUserProfileView.vue'
import VendorProfileSettings from '../Dashboard/Vendordashboard/Vendor-components/VendorProfileSettings.vue'
import VendorUserProfileView from '../Dashboard/Vendordashboard/Vendor-components/VendorUserProfileView.vue'
import VendorList from '../Dashboard/Admindashboard/Admincomponents/VendorList.vue'
import CustomerList from '../Dashboard/Admindashboard/Admincomponents/CustomerList.vue'
import SalesRecord from '../Dashboard/Admindashboard/Admincomponents/SalesRecord.vue'
import SalesReturn from '../Dashboard/Admindashboard/Admincomponents/SalesReturn.vue'
import AdminUserProfileView from '../Dashboard/Admindashboard/Admincomponents/AdminUserProfileView.vue'
import AdminProfileSettings from '../Dashboard/Admindashboard/Admincomponents/AdminProfileSettings.vue'




const routes = [
  // ===== AUTH =====
  { path: '/login', name: 'login', component: Login },
  { path: '/register', name: 'register', component: Registration },

  // ===== ADMIN ===== //
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
      { path: '/inventory/return-record', component: SupplierReturnHistory },
      { path: '/users/vendor-list', component: VendorList },
      { path: '/users/customer-list', component: CustomerList },
      { path: '/vendor-hub/sales', component: SalesRecord },
      { path: '/vendor-hub/sales-return', component: SalesReturn },
      { path: '/admin/profile/my-profile', component: AdminUserProfileView },
      { path: '/admin/profile/edit-profile', component: AdminProfileSettings },
    ],
  },

  // ===== CUSTOMER =====
  {
    path: '/CustomerDefaultLayout',
    component: CustomerDefaultLayout,
    children: [
      { path: '', component: CustomerDashboard },
      { path: '/my-order/order', component: MyOrderManage },
      { path: '/my-order/order_return', component: ReturnRequest },
      { path: '/my-order/order_return_record', component: MyReturnList },
      { path: '/account-info/my-profile', component: CustomerUserProfileView },
      { path: '/account-info/manage-profile', component: CustomerProfileSettings },
    ],
  },

  // ===== VENDOR =====
  {
    path: '/VendorDefaultLayout',
    component: VendorDefaultLayout,
    children: [
      { path: '', component: VendorDashboard },
      { path: '/admin-products-list/admin-product-list', component: VendorProductsList },
      { path: '/vendor-inventory/vendor_purchase', component: VendorPurchase },
      { path: '/vendor-inventory/vendor_purchase-record', component: VendorPurchaseManage },
      { path: '/ui-product/mystock', component: VendorStockList },
      { path: '/vendor-inventory/vendor_purchase-return', component: VendorPurchaseReturn },
      { path: '/vendor-inventory/vendor_return-record', component: VendorReturnRecord },
      { path: '/vendor-inventory/vendor_purchase-payment', component: VendorPurchasePayment },
      { path: '/ui-product/add-product', component: AddCustomerProduct },
      { path: '/ui-product/manage-product', component: CustomerProductManage },
      { path: '/customer-hub/customer-order', component: CustomerOrderManage },
      { path: '/customer-hub/customer-return', component: CustomerReturnRequestManage },
      { path: '/customer-hub/return-record', component: ReturnHistory },
      { path: '/profile/edit-profile', component: VendorProfileSettings },
      { path: '/profile/my-profile', component: VendorUserProfileView },
    ],
  },

  // ===== WEBSITE ===== //
  {
    path: '/',
    component: WebsiteDefaultLayout,
    children: [
      { path: '', component: HomePage }, 
      { path: 'products', component: ProductPage },   
      { path: 'about', component: AboutPage },
      { path: 'contact', component: ContactPage },
      { path: 'product_details', component: ProductDetails },
      { path: 'checkoutpage', component: CheckoutPage },
      { path: 'customerreviews', component: CustomerReviews },
      { path: 'ordertracking', component: OrderTrackingPage },
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
    to.path === '/homeproduct'||
    to.path === '/contact'||
    to.path === '/product_details'||
    to.path === '/customerreviews'||
    to.path === '/checkoutpage'||
    to.path === '/ordertracking'
  ) {
    return next()
  }

  if (!role) return next('/')

  if (to.path.startsWith('/AdminDefaultLayout') && role !== 'admin') return next('/login')
  if (to.path.startsWith('/VendorDefaultLayout') && role !== 'vendor') return next('/login')
  if (to.path.startsWith('/CustomerDefaultLayout') && role !== 'customer') return next('/login')

  next()
})

// ===== DOCUMENT TITLE =====
router.afterEach((to) => {
  const nearestWithTitle = to.matched.slice().reverse().find(r => r.meta?.title)
  document.title = nearestWithTitle ? nearestWithTitle.meta.title : 'StarCode Kh'
})

export default router
