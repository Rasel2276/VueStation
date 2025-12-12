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
      <a href="#" class="nav-link" v-for="(link, index) in links" :key="index">{{ link }}</a>
    </nav>

    <!-- RIGHT (DESKTOP) -->
    <div class="right">
      <div class="search-container">
        <input type="text" placeholder="Search..." v-model="search" />
        <i class="fa-solid fa-magnifying-glass"></i>
      </div>
      <button class="signin">Sign in</button>
      <div class="cart">
        <i class="fa-solid fa-cart-shopping"></i>
      </div>
    </div>

    <!-- HAMBURGER (MOBILE) -->
    <div class="hamburger" @click="toggleMenu">
      <i class="fa-solid fa-bars"></i>
    </div>

    <!-- MOBILE SIDE MENU -->
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
        <a href="#" class="nav-link" v-for="(link, index) in links" :key="index">{{ link }}</a>
      </nav>

      <div class="mobile-search">
        <input type="text" placeholder="Search..." v-model="search" />
        <i class="fa-solid fa-magnifying-glass"></i>
      </div>

      <button class="signin">Sign in</button>
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
      links: ["Home", "Products", "About", "Contact"],
      search: "",
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
/* MAIN NAVBAR */
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

/* LEFT LOGO */
.left .logo {
  font-size: 28px;
  font-weight: 700;
  color: white;
}
.logo-red { color: #e4002b; }

/* CENTER MENU (DESKTOP) */
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
.nav-link.active::after {
  content: "";
  width: 100%;
  height: 2px;
  background: #e4002b;
  position: absolute;
  bottom: -4px;
  left: 0;
}

/* RIGHT SECTION (DESKTOP) */
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
  cursor: pointer;
}
.signin {
  background: #e4002b;
  color: white;
  border: none;
  padding: 8px 18px;
  border-radius: 6px;
  cursor: pointer;
  transition: background 0.3s;
}
.signin:hover { background: #ff1a3c; }
.cart {
  font-size: 20px;
  color: white;
  cursor: pointer;
}
.cart:hover { color: #e4002b; }

/* HAMBURGER */
.hamburger {
  display: none;
  font-size: 24px;
  color: white;
  cursor: pointer;
}

/* MOBILE SIDE MENU */
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
.mobile-links { display: flex; flex-direction: column; gap: 15px; }
.mobile-links a { color: white; font-size: 18px; text-decoration: none; }
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
/* OVERLAY */
.overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0,0,0,0.5);
  z-index: 150;
}

/* RESPONSIVE */
@media (max-width: 1024px) { .center-menu { gap: 20px; } }
@media (max-width: 768px) {
  .center-menu, .right { display: none; }
  .hamburger { display: block; }
  .navbar { padding: 12px 20px; }
}
</style>
