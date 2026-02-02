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

          <div :class="['cart-dropdown', { show: cartOpen }]">
            <div class="cart-header">Shopping Cart</div>
            <div class="cart-items-list custom-scroll" v-if="cartItems.length > 0">
              <div class="cart-item" v-for="(item, index) in cartItems" :key="index">
                <img :src="item.image" :alt="item.name" />
                <div class="item-info">
                  <p class="item-name">{{ item.name }}</p>
                  <p class="item-price">৳{{ item.price }} x {{ item.qty }}</p>
                </div>
                <button class="remove-item-btn" @click.stop="removeItem(index)">
                  <i class="fa-solid fa-xmark"></i>
                </button>
              </div>
            </div>
            <div class="empty-msg" v-else>Your cart is empty!</div>
            <div class="cart-footer" v-if="cartItems.length > 0">
              <div class="total-summary">
                <span>Total:</span>
                <span>৳{{ totalPrice }}</span>
              </div>
              <router-link to="/checkoutpage" class="view-cart-btn" @click="cartOpen = false">
                Checkout Now
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
        <div class="logo">
          <span class="logo-red">R</span>YZE
        </div>
        <div class="close" @click="toggleMenu">
          <i class="fa-solid fa-xmark"></i>
        </div>
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
        
        <button v-if="!isLoggedIn" class="mobile-signin-btn" @click="openLoginFromMobile" style="border:none; cursor:pointer;">
          Sign in
        </button>
        <template v-else>
          <div class="user-profile-container" style="margin-bottom: 10px;">
            <div class="user-trigger mobile-trigger-style" @click="userMenuOpen = !userMenuOpen">
              <i class="fa-solid fa-circle-user profile-icon"></i>
              <span class="user-name-text">{{ userName }}</span>
              <i class="fa-solid fa-chevron-down" :class="{ 'rotate-icon': userMenuOpen }"></i>
            </div>
            <div :class="['user-dropdown-premium mobile-dropdown-adjust', { show: userMenuOpen }]">
              <router-link :to="dashboardPath" class="u-item" @click="toggleMenu(); userMenuOpen = false">
                <i class="fa-solid fa-gauge-high"></i> Dashboard
              </router-link>
            </div>
          </div>
          <button @click="handleLogout" class="mobile-signin-btn" style="border:none; cursor:pointer; background: #e4002b;">
            Logout
          </button>
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
/* সব CSS হুবহু আপনার দেওয়া কোড থেকে রাখা হয়েছে */
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

/* প্রোফাইল ড্রপডাউন */
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

/* মোবাইল স্পেশাল ড্রপডাউন ডিজাইন ফিক্স */
.mobile-trigger-style { background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); width: 100%; justify-content: space-between; box-sizing: border-box; }
.mobile-dropdown-adjust { position: relative; top: 5px; width: 100%; box-shadow: none; border: 1px solid #eee; }

/* বাকি সব স্টাইল হুবহু আপনার কোড */
.cart-wrapper { position: relative; }
.cart { font-size: 22px; color: white; cursor: pointer; position: relative; }
.cart-count { position: absolute; top: -8px; right: -10px; background: #e4002b; color: white; font-size: 11px; width: 18px; height: 18px; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-weight: bold; }
.cart-dropdown { position: absolute; top: 45px; right: -10px; width: 300px; background: white; border-radius: 12px; box-shadow: 0 15px 35px rgba(0, 0, 0, 0.25); display: none; flex-direction: column; overflow: hidden; z-index: 1000; border: 1px solid #eee; }
.cart-dropdown.show { display: flex; }
.cart-header { padding: 15px; color: #050e3c; font-weight: 800; border-bottom: 1px solid #eee; text-align: center; }
.view-cart-btn { display: block; background: #050e3c; color: white; text-align: center; padding: 12px; border-radius: 8px; text-decoration: none; font-weight: 600; }
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
  .mobile-signin-btn { margin-top: -10px; display: block; width: 100%; background: #e4002b; color: white; padding: 14px; text-align: center; border-radius: 8px; text-decoration: none; font-weight: 700; box-shadow: 0 4px 10px rgba(228, 0, 43, 0.3); box-sizing: border-box; }
  .mobile-nav-fix { width: fit-content; }
}
</style>