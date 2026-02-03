<template>
  <header class="navbar">
    <div class="left-section">
      <div class="logo">
        <span class="logo-red">R</span>YZE
      </div>
      <div class="search-container desktop-search">
        <input type="text" placeholder="Search..." v-model="search" />
        <i class="fa-solid fa-magnifying-glass"></i>
      </div>
    </div>

    <div class="right-section">
      <nav class="center-menu desktop-menu">
        <router-link
          v-for="(link, index) in links"
          :key="index"
          :to="link.path"
          class="nav-link"
        >
          {{ link.name }}
        </router-link>
      </nav>

      <div class="actions">
        <div class="auth-wrapper" v-click-outside="closeUserMenu">
          <button v-if="!isLoggedIn" @click="openLogin" class="signin" style="border:none; cursor:pointer;">
            Sign in
          </button>

          <div v-else class="user-profile-container desktop-menu">
            <div class="user-trigger" @click="userMenuOpen = !userMenuOpen">
              <i class="fa-solid fa-circle-user profile-icon"></i>
              <span class="user-name-text">{{ userName }}</span>
              <i class="fa-solid fa-chevron-down" :class="{ 'rotate-icon': userMenuOpen }"></i>
            </div>

            <div :class="['user-dropdown-premium', { show: userMenuOpen }]">
              <div class="dropdown-header">Hello, {{ userName }}!</div>
              <router-link :to="dashboardPath" class="u-item" @click="userMenuOpen = false">
                <i class="fa-solid fa-gauge-high"></i> Dashboard
              </router-link>
              <button @click="handleLogout" class="u-item logout-btn-premium">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
              </button>
            </div>
          </div>
        </div>

        <div class="cart-wrapper" v-click-outside="closeCart">
          <div class="cart" @click="toggleCart">
            <i class="fa-solid fa-cart-shopping"></i>
            <span class="cart-count" v-if="cartItems.length > 0">{{ cartItems.length }}</span>
          </div>

          <div :class="['cart-dropdown-premium', { show: cartOpen }]">
            <div class="cart-header-modern">
              <span>Shopping Bag ({{ cartItems.length }})</span>
              <i class="fa-solid fa-xmark close-cart-mobile" @click="closeCart"></i>
            </div>
            
            <div class="cart-body custom-scroll" v-if="cartItems.length > 0">
              <div class="cart-item-modern" v-for="(item, index) in cartItems" :key="index">
                <div class="item-img-box">
                  <img :src="getImageUrl(item.image)" :alt="item.name" />
                </div>
                <div class="item-details">
                  <p class="item-name-text">{{ item.name }}</p>
                  <div class="item-meta">
                    <span class="item-price-tag">৳{{ item.price }}</span>
                    <span class="item-qty-tag">Qty: {{ item.qty }}</span>
                  </div>
                </div>
                <button class="remove-btn-modern" @click.stop="removeItem(index)">
                  <i class="fa-solid fa-trash-can"></i>
                </button>
              </div>
            </div>

            <div class="empty-cart-ui" v-else>
              <i class="fa-solid fa-basket-shopping"></i>
              <p>Your bag is empty!</p>
            </div>

            <div class="cart-footer-modern" v-if="cartItems.length > 0">
              <div class="total-row">
                <span>Total Amount:</span>
                <span class="total-val">৳{{ totalPrice }}</span>
              </div>
              <router-link to="/checkoutpage" class="checkout-btn-modern" @click="cartOpen = false">
                Proceed to Checkout
              </router-link>
            </div>
          </div>
        </div>
      </div>

      <div class="hamburger" @click="toggleMenu">
        <i class="fa-solid fa-bars"></i>
      </div>
    </div>

    <div :class="['mobile-menu', { open: menuOpen }]">
      <div class="mobile-header">
        <div class="logo"><span class="logo-red">R</span>YZE</div>
        <div class="close" @click="toggleMenu"><i class="fa-solid fa-xmark"></i></div>
      </div>
      <div class="mobile-search-area">
        <div class="mobile-search-box">
          <input type="text" placeholder="Search products..." v-model="search" />
          <i class="fa-solid fa-magnifying-glass"></i>
        </div>
      </div>
      <nav class="mobile-links">
        <router-link v-for="(link, index) in links" :key="index" :to="link.path" class="nav-link mobile-nav-fix" @click="toggleMenu">
          {{ link.name }}
        </router-link>
        <button v-if="!isLoggedIn" class="mobile-signin-btn" @click="openLoginFromMobile" style="border:none; cursor:pointer; background: #e4002b;">Sign in</button>
        <template v-else>
          <div class="user-profile-container" style="margin-bottom: 10px;">
            <div class="user-trigger mobile-user-layout">
              <i class="fa-solid fa-circle-user profile-icon"></i>
              <span class="user-name-centered">{{ userName }}</span>
            </div>
          </div>
          <router-link :to="dashboardPath" class="mobile-signin-btn" @click="toggleMenu" style="text-decoration: none; background: #007bff; margin-bottom: 10px; display: block;">Dashboard</router-link>
          <button @click="handleLogout" class="mobile-signin-btn" style="border:none; cursor:pointer; background: #e4002b;">Logout</button>
        </template>
      </nav>
    </div>
    <div v-if="menuOpen" class="overlay" @click="toggleMenu"></div>

    <LoginModal v-if="isLoginModalOpen" @close="updateAuth" @openRegister="isLoginModalOpen = false; isRegisterModalOpen = true" />
    <RegisterModal v-if="isRegisterModalOpen" @close="isRegisterModalOpen = false" @openLogin="isRegisterModalOpen = false; isLoginModalOpen = true" />
  </header>
</template>

<script>
import LoginModal from './LoginModal.vue';
import RegisterModal from './RegisterModal.vue';

export default {
  name: "Navbar",
  components: { LoginModal, RegisterModal },
  data() {
    return {
      isLoginModalOpen: false,
      isRegisterModalOpen: false,
      menuOpen: false,
      cartOpen: false,
      userMenuOpen: false,
      isLoggedIn: false,
      userName: "",
      dashboardPath: "/CustomerDefaultLayout",
      search: "",
      links: [
        { name: "Home", path: "/" },
        { name: "Products", path: "/products" },
        { name: "About", path: "/about" },
        { name: "Contact", path: "/contact" },
      ],
      cartItems: []
    };
  },
  computed: {
    totalPrice() { return this.cartItems.reduce((acc, item) => acc + (item.price * item.qty), 0); }
  },
  methods: {
    toggleMenu() { this.menuOpen = !this.menuOpen; },
    toggleCart() { this.cartOpen = !this.cartOpen; },
    closeCart() { this.cartOpen = false; },
    closeUserMenu() { this.userMenuOpen = false; },
    openLogin() { this.isLoginModalOpen = true; },
    openLoginFromMobile() { this.menuOpen = false; this.isLoginModalOpen = true; },
    getImageUrl(img) {
      return img ? `http://127.0.0.1:8000/ui_product_images/${img}` : '/assets/no-image.jpg';
    },
    checkUserAuth() {
      const token = localStorage.getItem("token");
      const user = JSON.parse(localStorage.getItem("user"));
      const role = localStorage.getItem("role");
      if (token && user) {
        this.isLoggedIn = true;
        this.userName = user.name.split(' ')[0]; 
        if(role === 'admin') this.dashboardPath = "/AdminDefaultLayout";
        else if(role === 'vendor') this.dashboardPath = "/VendorDefaultLayout";
        else this.dashboardPath = "/CustomerDefaultLayout";
      } else {
        this.isLoggedIn = false;
      }
    },
    updateAuth() { this.isLoginModalOpen = false; this.checkUserAuth(); },
    handleLogout() {
      localStorage.clear();
      this.isLoggedIn = false;
      this.userMenuOpen = false;
      this.menuOpen = false;
      this.$router.push("/");
    },
    removeItem(index) {
      this.cartItems.splice(index, 1);
      this.saveCart();
      window.dispatchEvent(new CustomEvent('cart-updated'));
    },
    saveCart() { localStorage.setItem('shopping_cart', JSON.stringify(this.cartItems)); },
    loadCart() {
      const saved = localStorage.getItem('shopping_cart');
      if (saved) { this.cartItems = JSON.parse(saved); }
    },
    handleGlobalAddToCart(event) {
      const product = event.detail;
      const existingItem = this.cartItems.find(item => item.id === product.id);
      if (existingItem) { existingItem.qty += (product.qty || 1); } 
      else { this.cartItems.push({ id: product.id, name: product.name, price: product.price, image: product.image, qty: product.qty || 1 }); }
      this.saveCart();
      window.dispatchEvent(new CustomEvent('cart-updated'));
    }
  },
  mounted() {
    this.checkUserAuth();
    this.loadCart();
    window.addEventListener('add-to-cart', this.handleGlobalAddToCart);
    window.addEventListener('cart-updated', this.loadCart);
  },
  unmounted() {
    window.removeEventListener('add-to-cart', this.handleGlobalAddToCart);
    window.removeEventListener('cart-updated', this.loadCart);
  },
  directives: {
    clickOutside: {
      mounted(el, binding) {
        el.clickOutsideEvent = function(event) {
          if (!(el === event.target || el.contains(event.target))) { binding.value(event); }
        };
        document.body.addEventListener('click', el.clickOutsideEvent);
      },
      unmounted(el) { document.body.removeEventListener('click', el.clickOutsideEvent); },
    },
  }
};
</script>

<style scoped>
/* --- ORIGINAL BASE NAVBAR PIXELS --- */
.navbar { width: 100%; display: flex; align-items: center; justify-content: space-between; padding: 12px 40px; background: #050e3c; box-sizing: border-box; position: sticky; top: 0; z-index: 999; }
.left-section { display: flex; align-items: center; gap: 25px; }
.left-section .logo { font-size: 28px; font-weight: 700; color: white; }
.logo-red { color: #e4002b; }
.search-container input { padding: 6px 35px 6px 15px; border-radius: 20px; border: none; outline: none; font-size: 14px; background: white; width: 220px; }
.right-section { display: flex; align-items: center; gap: 35px; }
.actions { display: flex; align-items: center; gap: 20px; }
.center-menu { display: flex; gap: 25px; }
.nav-link { position: relative; color: white; text-decoration: none; font-size: 15px; padding: 5px 0; transition: color 0.3s; }
.nav-link::after { content: ""; position: absolute; left: 0; bottom: -6px; width: 100%; height: 2px; background: #e4002b; transform: scaleX(0); transform-origin: left; transition: transform 0.3s ease; }
.nav-link:hover::after, .router-link-exact-active::after { transform: scaleX(1); }
.signin { background: #e4002b; color: white; padding: 6px 18px; border-radius: 6px; text-decoration: none; font-weight: 500; transition: 0.3s; }

/* --- USER DROPDOWN (ORIGINAL) --- */
.user-profile-container { position: relative; }
.user-trigger { display: flex; align-items: center; gap: 10px; color: white; cursor: pointer; padding: 6px 14px; background: rgba(255, 255, 255, 0.1); border-radius: 20px; font-size: 14px; transition: 0.3s ease; }
.profile-icon { font-size: 18px; color: #e4002b; }
.rotate-icon { transform: rotate(180deg); }
.user-dropdown-premium { position: absolute; top: 48px; right: 0; width: 180px; background: #ffffff; border-radius: 12px; box-shadow: 0 15px 35px rgba(0,0,0,0.2); display: none; flex-direction: column; overflow: hidden; z-index: 1001; }
.user-dropdown-premium.show { display: flex; }
.dropdown-header { padding: 12px 15px; background: #f8f9fa; font-size: 11px; font-weight: 700; color: #666; border-bottom: 1px solid #eee; text-transform: uppercase; }
.u-item { padding: 12px 15px; color: #050e3c; text-decoration: none; font-size: 14px; border: none; background: none; width: 100%; cursor: pointer; display: flex; align-items: center; gap: 10px; transition: 0.2s; text-align: left; }
.u-item:hover { background: #f1f1f1; padding-left: 20px; }
.logout-btn-premium { background-color: #e4002b !important; color: #fff !important; font-weight: 600; margin: 5px; width: calc(100% - 10px); border-radius: 6px; }

/* --- PREMIUM CART DROPDOWN --- */
.cart-wrapper { position: relative; }
.cart { font-size: 22px; color: white; cursor: pointer; position: relative; }
.cart-count { position: absolute; top: -8px; right: -10px; background: #e4002b; color: white; font-size: 11px; width: 18px; height: 18px; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-weight: bold; }

.cart-dropdown-premium { 
  position: absolute; top: 45px; right: -10px; width: 340px; 
  background: white; border-radius: 16px; 
  box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15); 
  display: none; flex-direction: column; 
  z-index: 1000; border: 1px solid #f1f1f1; 
  overflow: hidden; animation: slideUp 0.3s ease-out;
}
@keyframes slideUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
.cart-dropdown-premium.show { display: flex; }

.cart-header-modern { padding: 16px 20px; background: #050e3c; color: white; font-weight: 700; font-size: 16px; display: flex; justify-content: space-between; align-items: center; }
.close-cart-mobile { display: none; cursor: pointer; font-size: 20px; }

.cart-body { max-height: 380px; overflow-y: auto; padding: 10px; }
.cart-item-modern { display: flex; align-items: center; gap: 12px; padding: 12px; border-bottom: 1px solid #f8f8f8; transition: 0.2s; }
.cart-item-modern:hover { background: #f9f9f9; }
.item-img-box { width: 60px; height: 60px; background: #fff; border-radius: 8px; border: 1px solid #eee; padding: 4px; flex-shrink: 0; }
.item-img-box img { width: 100%; height: 100%; object-fit: contain; }
.item-details { flex: 1; }
.item-name-text { font-size: 13px; font-weight: 700; color: #333; margin: 0 0 4px 0; line-height: 1.3; }
.item-meta { display: flex; gap: 10px; font-size: 12px; }
.item-price-tag { color: #e4002b; font-weight: 800; }
.item-qty-tag { color: #777; font-weight: 500; }

.remove-btn-modern { background: none; border: none; color: #ccc; cursor: pointer; transition: 0.2s; padding: 5px; }
.remove-btn-modern:hover { color: #e4002b; transform: scale(1.1); }

.empty-cart-ui { padding: 40px 20px; text-align: center; color: #bbb; }
.empty-cart-ui i { font-size: 50px; margin-bottom: 10px; opacity: 0.3; }

.cart-footer-modern { padding: 20px; background: #fcfcfc; border-top: 1px solid #eee; }
.total-row { display: flex; justify-content: space-between; margin-bottom: 15px; font-size: 15px; font-weight: 700; color: #333; }
.total-val { color: #e4002b; font-size: 18px; }

/* FIXED BUTTON BOX-SIZING */
.checkout-btn-modern { 
  display: block; width: 100%; background: #e4002b; color: white; 
  text-align: center; padding: 14px; border-radius: 10px; 
  text-decoration: none; font-weight: 700; transition: 0.3s; 
  box-shadow: 0 8px 15px rgba(228, 0, 43, 0.2);
  box-sizing: border-box; /* Fixed: keeps button within footer container */
}
.checkout-btn-modern:hover { background: #c50025; transform: translateY(-2px); }

/* --- ORIGINAL MOBILE SIDEBAR DESIGN --- */
.hamburger { display: none; font-size: 24px; color: white; cursor: pointer; }
.mobile-menu { position: fixed; top: 0; left: -100%; width: 280px; height: 100vh; background: #050e3c; padding: 25px 20px; display: flex; flex-direction: column; transition: left 0.4s ease; z-index: 1000; color: white; }
.mobile-menu.open { left: 0; }
.mobile-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
.overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.7); z-index: 999; }

@media (max-width: 850px) {
  .desktop-menu, .desktop-search { display: none !important; }
  .hamburger { display: block; }
  .navbar { padding: 12px 20px; }
  .mobile-search-area { width: 100%; margin-bottom: 25px; }
  .mobile-search-box { position: relative; }
  .mobile-search-box input { width: 100%; padding: 12px 15px; border-radius: 8px; border: none; background: #fff; color: #050e3c; box-sizing: border-box; }
  .mobile-search-box i { position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: #050e3c; }
  .mobile-links { display: flex; flex-direction: column; gap: 20px; }
  .mobile-signin-btn { display: block; width: 100%; color: white; padding: 14px; text-align: center; border-radius: 8px; text-decoration: none; font-weight: 700; font-size: 16px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); box-sizing: border-box; }
  
  .cart-dropdown-premium { position: fixed; top: 0; right: 0; width: 100%; height: 100vh; border-radius: 0; animation: slideLeft 0.3s ease; }
  @keyframes slideLeft { from { transform: translateX(100%); } to { transform: translateX(0); } }
  .close-cart-mobile { display: block; }
  .cart-body { max-height: calc(100vh - 200px); }
  .item-name-text { font-size: 15px; }

  .mobile-user-layout { position: relative; display: flex; align-items: center; background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); width: 100%; box-sizing: border-box; cursor: default; }
  .user-name-centered { flex: 1; text-align: center; margin-right: 28px; }
  .mobile-nav-fix { width: fit-content; }
}

/* Custom Scrollbar */
.custom-scroll::-webkit-scrollbar { width: 5px; }
.custom-scroll::-webkit-scrollbar-track { background: #f1f1f1; }
.custom-scroll::-webkit-scrollbar-thumb { background: #ccc; border-radius: 10px; }
</style>