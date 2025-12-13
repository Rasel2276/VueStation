<template>
  <header class="navbar">

    <!-- LEFT LOGO -->
    <div class="left">
      <div class="logo">
        <span class="logo-red">Z</span>aptro
      </div>
    </div>

    <!-- CENTER MENU (DESKTOP) -->
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

    <!-- RIGHT (DESKTOP) -->
    <div class="right">
      <div class="search-container">
        <input type="text" placeholder="Search..." v-model="search" />
        <i class="fa-solid fa-magnifying-glass"></i>
      </div>

      <router-link to="/login" class="signin">
        Sign in
      </router-link>

      <div class="cart">
        <i class="fa-solid fa-cart-shopping"></i>
      </div>
    </div>

    <!-- HAMBURGER -->
    <div class="hamburger" @click="toggleMenu">
      <i class="fa-solid fa-bars"></i>
    </div>

    <!-- MOBILE MENU -->
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

      <div class="cart">
        <i class="fa-solid fa-cart-shopping"></i>
      </div>
    </div>

    <!-- OVERLAY -->
    <div v-if="menuOpen" class="overlay" @click="toggleMenu"></div>

  </header>
</template>

<script>
export default {
  name: "Navbar",
  data() {
    return {
      menuOpen: false,
      search: "",
      links: [
        { name: "Home", path: "/" },
        { name: "Products", path: "/products" },
        { name: "About", path: "/about" },
        { name: "Contact", path: "/contact" },
      ],
    };
  },
  methods: {
    toggleMenu() {
      this.menuOpen = !this.menuOpen;
    },
  },
};
</script>

<style scoped>

/* === CSS একদম আগের মতোই === */

.navbar {
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 12px 40px;
  background: #050e3c;
  box-sizing: border-box;
  position: relative;
  z-index: 100;
}

.left .logo {
  font-size: 28px;
  font-weight: 700;
  color: white;
}
.logo-red { color: #e4002b; }

.center-menu {
  display: flex;
  gap: 35px;
  flex: 1;
  justify-content: center;
}
.nav-link {
  color: white;
  text-decoration: none;
  font-size: 16px;
  transition: color 0.3s;
}
.nav-link:hover { color: #e4002b; }

.right {
  display: flex;
  align-items: center;
  gap: 15px;
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
  transition: all 0.3s;
  background: white;
}
.search-container input:focus {
  box-shadow: 0 0 8px #e4002b;
}
.search-container i {
  position: absolute;
  right: 10px;
  top: 50%;
  transform: translateY(-50%);
  color: #050e3c;
}

.signin {
  background: #e4002b;
  color: white;
  border: none;
  padding: 5px 12px;
  border-radius: 6px;
  cursor: pointer;
  transition: background 0.3s;
  text-decoration: none;
}
.signin:hover { background: #ff1a3c; }

.cart {
  font-size: 20px;
  color: white;
  cursor: pointer;
}
.cart:hover { color: #e4002b; }

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
.mobile-menu.open { left: 0; }

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
.mobile-links a:hover { color: #e4002b; }

.mobile-search {
  position: relative;
}
.mobile-search input {
  width: 82%;
  padding: 6px 35px 6px 12px;
  border-radius: 20px;
  border: none;
}
.mobile-search i {
  position: absolute;
  right: 10px;
  top: 50%;
  transform: translateY(-50%);
  color: #050e3c;
}

.mobile-header .close i {
  font-size: 24px;
  color: white;
  cursor: pointer;
}

.overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0,0,0,0.5);
  z-index: 150;
}

@media (max-width: 1024px) {
  .center-menu { gap: 20px; }
}

@media (max-width: 768px) {
  .center-menu,
  .right { display: none; }
  .hamburger { display: block; }
  .navbar { padding: 12px 20px; }
}

</style>
