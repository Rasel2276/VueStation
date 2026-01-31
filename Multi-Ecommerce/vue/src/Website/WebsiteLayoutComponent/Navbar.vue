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
        <router-link to="/login" class="signin">
          Sign in
        </router-link>

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
        
        <router-link to="/login" class="mobile-signin-btn" @click="toggleMenu">
          Sign in
        </router-link>
      </nav>
    </div>
    <div v-if="menuOpen" class="overlay" @click="toggleMenu"></div>
  </header>
</template>

<script>
// Script remains the same as your original
export default {
  name: "Navbar",
  data() {
    return {
      menuOpen: false,
      cartOpen: false,
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
    totalPrice() {
      return this.cartItems.reduce((acc, item) => acc + (item.price * item.qty), 0);
    }
  },
  methods: {
    toggleMenu() { this.menuOpen = !this.menuOpen; },
    toggleCart() { this.cartOpen = !this.cartOpen; },
    closeCart() { this.cartOpen = false; },
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
      if (existingItem) {
        existingItem.qty += (product.qty || 1);
      } else {
        this.cartItems.push({
          id: product.id, name: product.name, price: product.price,
          image: product.image, qty: product.qty || 1
        });
      }
      this.saveCart();
      window.dispatchEvent(new CustomEvent('cart-updated'));
    }
  },
  mounted() {
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
          if (!(el === event.target || el.contains(event.target))) {
            binding.value(event);
          }
        };
        document.body.addEventListener('click', el.clickOutsideEvent);
      },
      unmounted(el) { document.body.removeEventListener('click', el.clickOutsideEvent); },
    },
  }
};
</script>

<style scoped>
/* --- DESKTOP STYLES --- */
.navbar { width: 100%; display: flex; align-items: center; justify-content: space-between; padding: 12px 40px; background: #050e3c; box-sizing: border-box; position: sticky; top: 0; z-index: 999; }
.left-section { display: flex; align-items: center; gap: 25px; }
.left-section .logo { font-size: 28px; font-weight: 700; color: white; }
.logo-red { color: #e4002b; }
.search-container { position: relative; }
.search-container input { padding: 6px 35px 6px 15px; border-radius: 20px; border: none; outline: none; font-size: 14px; background: white; width: 220px; }
.search-container i { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); color: #050e3c; }
.right-section { display: flex; align-items: center; gap: 35px; }
.actions { display: flex; align-items: center; gap: 20px; }
.center-menu { display: flex; gap: 25px; }
.nav-link { position: relative; color: white; text-decoration: none; font-size: 15px; padding: 5px 0; transition: color 0.3s; }
.nav-link::after { content: ""; position: absolute; left: 0; bottom: -6px; width: 100%; height: 2px; background: #e4002b; transform: scaleX(0); transform-origin: left; transition: transform 0.3s ease; }
.nav-link:hover::after, .router-link-exact-active::after { transform: scaleX(1); }
.signin { background: #e4002b; color: white; padding: 6px 18px; border-radius: 6px; text-decoration: none; font-weight: 500; transition: 0.3s; }
.cart-wrapper { position: relative; }
.cart { font-size: 22px; color: white; cursor: pointer; position: relative; }
.cart-count { position: absolute; top: -8px; right: -10px; background: #e4002b; color: white; font-size: 11px; width: 18px; height: 18px; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-weight: bold; }
.cart-dropdown { position: absolute; top: 45px; right: -10px; width: 300px; background: white; border-radius: 12px; box-shadow: 0 15px 35px rgba(0, 0, 0, 0.25); display: none; flex-direction: column; overflow: hidden; z-index: 1000; border: 1px solid #eee; }
.cart-dropdown.show { display: flex; }
.cart-header { padding: 15px; color: #050e3c; font-weight: 800; border-bottom: 1px solid #eee; text-align: center; }
.cart-items-list { max-height: 280px; overflow-y: auto; }
.cart-item { display: flex; align-items: center; gap: 12px; padding: 12px; border-bottom: 1px solid #f5f5f5; }
.cart-item img { width: 45px; height: 45px; object-fit: cover; border-radius: 6px; }
.item-info { flex: 1; }
.item-name { font-size: 14px; color: #333; font-weight: 600; margin: 0; }
.item-price { font-size: 13px; color: #e4002b; }
.remove-item-btn { background: none; border: none; color: #ccc; cursor: pointer; }
.cart-footer { padding: 15px; background: #fff; border-top: 1px solid #eee; }
.total-summary { display: flex; justify-content: space-between; font-weight: 700; color: #050e3c; margin-bottom: 12px; }
.view-cart-btn { display: block; background: #050e3c; color: white; text-align: center; padding: 12px; border-radius: 8px; text-decoration: none; font-weight: 600; }
.empty-msg { padding: 40px; text-align: center; color: #999; }
.hamburger { display: none; font-size: 24px; color: white; cursor: pointer; }

/* --- MOBILE MENU BASE --- */
.mobile-menu {
  position: fixed;
  top: 0;
  left: -100%;
  width: 280px;
  height: 100vh;
  background: #050e3c;
  padding: 25px 20px;
  display: flex;
  flex-direction: column;
  transition: left 0.4s ease;
  z-index: 1000;
  color: white;
}
.mobile-menu.open { left: 0; }
.mobile-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
.overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.7); z-index: 999; }

/* --- MOBILE SPECIFIC FIXES (AS REQUESTED) --- */
@media (max-width: 850px) {
  .desktop-menu, .desktop-search { display: none !important; }
  .hamburger { display: block; }
  .navbar { padding: 12px 20px; }

  /* SEARCH BAR */
  .mobile-search-area { width: 100%; margin-bottom: 25px; }
  .mobile-search-box { position: relative; }
  .mobile-search-box input {
    width: 100%;
    padding: 12px 15px;
    border-radius: 8px;
    border: none;
    background: #fff;
    color: #050e3c;
    box-sizing: border-box;
  }
  .mobile-search-box i { position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: #050e3c; }

  /* LINKS & SIGN IN BUTTON (10px DISTANCE) */
  .mobile-links { 
    display: flex; 
    flex-direction: column; 
    gap: 20px; /* Space between each nav item */
  }
  
  .mobile-signin-btn {
    margin-top: -10px; /* This adjusts the 20px gap to 10px precisely after the last link */
    display: block;
    width: 100%;
    background: #e4002b;
    color: white;
    padding: 14px;
    text-align: center;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 700;
    box-shadow: 0 4px 10px rgba(228, 0, 43, 0.3);
    box-sizing: border-box;
  }

  .mobile-nav-fix { width: fit-content; }
}
</style>