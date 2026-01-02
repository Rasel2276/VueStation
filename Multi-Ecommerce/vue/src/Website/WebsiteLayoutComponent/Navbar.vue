<template>
  <header class="navbar">
    <div class="left">
      <div class="logo">
        <span class="logo-red">Z</span>aptro
      </div>
    </div>

    <nav class="center-menu">
      <router-link
        v-for="(link, index) in links"
        :key="index"
        :to="link.path"
        class="nav-link"
      >
        {{ link.name }}
      </router-link>
    </nav>

    <div class="right">
      <div class="search-container">
        <input type="text" placeholder="Search..." v-model="search" />
        <i class="fa-solid fa-magnifying-glass"></i>
      </div>

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

    <div :class="['mobile-menu', { open: menuOpen }]">
      <div class="mobile-header">
        <div class="logo">
          <span class="logo-red">Z</span>aptro
        </div>
        <div class="close" @click="toggleMenu">
          <i class="fa-solid fa-xmark"></i>
        </div>
      </div>

      <nav class="mobile-links">
        <router-link
          v-for="(link, index) in links"
          :key="index"
          :to="link.path"
          class="nav-link"
          @click="toggleMenu"
        >
          {{ link.name }}
        </router-link>
      </nav>

      <div class="mobile-search">
        <input type="text" placeholder="Search..." v-model="search" />
        <i class="fa-solid fa-magnifying-glass"></i>
      </div>

      <router-link to="/login" class="signin" @click="toggleMenu">
        Sign in
      </router-link>

      <div class="cart" style="position: relative; width: fit-content; margin-top: 10px;">
        <i class="fa-solid fa-cart-shopping"></i>
        <span class="cart-count">{{ cartItems.length }}</span>
      </div>
    </div>

    <div v-if="menuOpen" class="overlay" @click="toggleMenu"></div>
  </header>
</template>

<script>
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
    },
    handleGlobalAddToCart(event) {
      const product = event.detail;
      const existingItem = this.cartItems.find(item => item.id === product.id);
      
      if (existingItem) {
        existingItem.qty++;
      } else {
        this.cartItems.push({
          id: product.id,
          name: product.name,
          price: product.price,
          image: product.image,
          qty: 1
        });
      }
    }
  },
  mounted() {
    window.addEventListener('add-to-cart', this.handleGlobalAddToCart);
  },
  unmounted() {
    window.removeEventListener('add-to-cart', this.handleGlobalAddToCart);
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
      unmounted(el) {
        document.body.removeEventListener('click', el.clickOutsideEvent);
      },
    },
  }
};
</script>

<style scoped>

.navbar {
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 12px 40px;
  background: #050e3c;
  box-sizing: border-box;
  position: sticky;
  top: 0;
  z-index: 999;
}

.left .logo {
  font-size: 28px;
  font-weight: 700;
  color: white;
}

.logo-red {
  color: #e4002b;
}

.center-menu {
  display: flex;
  gap: 35px;
  flex: 1;
  justify-content: center;
}

.nav-link {
  position: relative;
  color: white;
  text-decoration: none;
  font-size: 16px;
  padding: 5px 0;
  transition: color 0.3s;
}

.nav-link::after {
  content: "";
  position: absolute;
  left: 0;
  bottom: -6px;
  width: 100%;
  height: 2px;
  background: #e4002b;
  transform: scaleX(0);
  transform-origin: left;
  transition: transform 0.3s ease;
}

.nav-link:hover::after {
  transform: scaleX(1);
}

.router-link-exact-active::after {
  transform: scaleX(1);
}

.right {
  display: flex;
  align-items: center;
  gap: 20px;
}


.search-container {
  position: relative;
}

.search-container input {
  padding: 6px 35px 6px 12px;
  border-radius: 20px;
  border: none;
  outline: none;
  font-size: 14px;
  background: white;
}

.search-container i {
  position: absolute;
  right: 10px;
  top: 50%;
  transform: translateY(-50%);
  color: #050e3c;
}

/* Sign In Button */
.signin {
  background: #e4002b;
  color: white;
  padding: 6px 15px;
  border-radius: 6px;
  text-decoration: none;
  font-weight: 500;
  transition: 0.3s;
}


.cart-wrapper {
  position: relative;
}

.cart {
  font-size: 22px;
  color: white;
  cursor: pointer;
  position: relative;
  transition: color 0.3s;
}

.cart:hover {
  color: #e4002b;
}

.cart-count {
  position: absolute;
  top: -8px;
  right: -10px;
  background: #e4002b;
  color: white;
  font-size: 11px;
  width: 18px;
  height: 18px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  font-weight: bold;
}

.cart-dropdown {
  position: absolute;
  top: 45px;
  right: -10px;
  width: 300px;
  background: white;
  border-radius: 12px;
  box-shadow: 0 15px 35px rgba(0, 0, 0, 0.25);
  display: none;
  flex-direction: column;
  overflow: hidden;
  z-index: 1000;
  border: 1px solid #eee;
}

.cart-dropdown.show {
  display: flex;
  animation: fadeIn 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}

.cart-header {
  padding: 15px;
  background: #fdfdfd;
  color: #050e3c;
  font-weight: 800;
  border-bottom: 1px solid #eee;
  text-align: center;
}

.cart-items-list {
  max-height: 280px;
  overflow-y: auto;
}

.custom-scroll::-webkit-scrollbar {
  width: 4px;
}

.custom-scroll::-webkit-scrollbar-thumb {
  background: #e4002b;
  border-radius: 10px;
}

.cart-item {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 12px;
  border-bottom: 1px solid #f5f5f5;
  transition: background 0.2s;
}

.cart-item:hover {
  background: #fafafa;
}

.cart-item img {
  width: 45px;
  height: 45px;
  object-fit: cover;
  border-radius: 6px;
  background: #eee;
}

.item-info {
  flex: 1;
}

.item-name {
  font-size: 14px;
  color: #333;
  font-weight: 600;
  margin: 0;
}

.item-price {
  font-size: 13px;
  color: #e4002b;
  margin: 2px 0 0 0;
  font-weight: 500;
}

.remove-item-btn {
  background: none;
  border: none;
  color: #ccc;
  cursor: pointer;
  padding: 5px;
  font-size: 16px;
  transition: 0.3s;
}

.remove-item-btn:hover {
  color: #e4002b;
  transform: scale(1.1);
}

.empty-msg {
  padding: 40px 20px;
  text-align: center;
  color: #999;
  font-size: 14px;
}

.cart-footer {
  padding: 15px;
  background: #fff;
  border-top: 1px solid #eee;
}

.total-summary {
  display: flex;
  justify-content: space-between;
  margin-bottom: 12px;
  font-weight: 700;
  color: #050e3c;
}

.view-cart-btn {
  display: block;
  background: #050e3c;
  color: white;
  text-align: center;
  padding: 12px;
  border-radius: 8px;
  text-decoration: none;
  font-size: 14px;
  font-weight: 600;
  transition: background 0.3s;
}

.view-cart-btn:hover {
  background: #e4002b;
}


.hamburger {
  display: none;
  font-size: 24px;
  color: white;
  cursor: pointer;
}

.mobile-menu {
  position: fixed;
  top: 0;
  left: -100%;
  width: 260px;
  height: 100vh;
  background: #050e3c;
  padding: 20px;
  display: flex;
  flex-direction: column;
  gap: 20px;
  transition: left 0.3s ease;
  z-index: 200;
  color: white;
}

.mobile-menu.open {
  left: 0;
}

.mobile-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.mobile-links {
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.mobile-links a {
  color: white;
  font-size: 18px;
  text-decoration: none;
}

.mobile-search {
  position: relative;
}

.mobile-search input {
  width: 100%;
  padding: 8px 12px;
  border-radius: 20px;
  border: none;
  box-sizing: border-box;
}

.overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  z-index: 150;
}


@media (max-width: 768px) {
  .center-menu,
  .right {
    display: none;
  }
  .hamburger {
    display: block;
  }
  .navbar {
    padding: 12px 20px;
  }
}
</style>