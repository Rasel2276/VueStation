<template>
  <nav class="navbar">
    <div class="logo">Dot.Store</div>

    <ul :class="['nav-links', { open: menuOpen }]">
      <li><a href="#">Home</a></li>
      <li><a href="#">Products</a></li>
      <li><a href="#">Services</a></li>
      <li><a href="#">Contact</a></li>
    </ul>

    <div class="hamburger" @click="menuOpen = !menuOpen">
      <div></div>
      <div></div>
      <div></div>
    </div>
  </nav>
</template>

<script>
export default {
  name: "Navbar",
  data() {
    return {
      menuOpen: false
    };
  }
};
</script>

<style>
body{
  margin:0px;
  padding:0px;
}
.navbar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background: #4a00e0;
  padding:5px 25px;
  color: white;
}

.logo {
  font-size: 22px;
  font-weight: bold;
}

.nav-links {
  list-style: none;
  display: flex;
  gap: 25px;
}

.nav-links a {
  text-decoration: none;
  color: white;
  font-size: 16px;
  transition: 0.3s;
}

.nav-links a:hover {
  color: #ffc107;
}

.hamburger {
  display: none;
  cursor: pointer;
  flex-direction: column;
  gap: 5px;
}

.hamburger div {
  width: 27px;
  height: 3px;
  background: white;
}

/* Responsive */
@media (max-width: 768px) {
  .hamburger {
    display: flex;
  }

  .nav-links {
    position: absolute;
    top: 65px;
    left: 0;
    background: #4a00e0;
    width: 100%;
    flex-direction: column;
    text-align: center;
    display: none;
    padding: 15px 0;
  }

  .nav-links.open {
    display: flex;
  }
}
</style>
