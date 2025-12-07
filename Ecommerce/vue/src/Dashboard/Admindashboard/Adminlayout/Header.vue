<template>
  <header class="header">
    <!-- Hamburger for sidebar toggle -->
    <button class="hamburger" @click="$emit('toggle-sidebar')">☰</button>

    <!-- Left: Search Bar -->
    <div class="search-container">
      <input type="text" placeholder="Search..." v-model="searchQuery" />
      <button>🔍</button>
    </div>

    <!-- Right: Profile Dropdown -->
    <div class="profile-dropdown" @click="toggleDropdown">
      <span class="profile-name">PROFILE ⬇</span>
      <ul v-if="dropdownOpen" class="dropdown-menu">
        <li @click="goHome">Home</li>
        <li @click="logout">Logout</li>
      </ul>
    </div>
  </header>
</template>

<script>
export default {
  name: "Header",
  data() {
    return {
      searchQuery: "",
      dropdownOpen: false,
    };
  },
  methods: {
    toggleDropdown() {
      this.dropdownOpen = !this.dropdownOpen;
    },
    goHome() {
      alert("Go to Home!");
      this.dropdownOpen = false;
    },
    logout() {
      alert("Logout clicked!");
      this.dropdownOpen = false;
    },
  },
};
</script>

<style scoped>
.header {
  height: 60px;
  width: 100%;
  background: linear-gradient(to right, #0f6bbf, #0aaed8);
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0 20px;
  color: white;
  position: sticky;
  top: 0;
  z-index: 150;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
}

.hamburger {
  display: none;
  font-size: 24px;
  background: none;
  border: none;
  color: white;
  cursor: pointer;
  margin-right: 10px;
}

.search-container {
  display: flex;
  align-items: center;
}

.search-container input {
  padding: 7px 10px;
  border-radius: 20px 0 0 20px;
  border: none;
  outline: none;
  width: 200px;
}

.search-container button {
  padding: 7px 12px;
  border: none;
  background: rgba(255, 255, 255, 0.2);
  color: white;
  border-radius: 0 20px 20px 0;
  cursor: pointer;
}

.search-container button:hover {
  background: rgba(255, 255, 255, 0.35);
}

.profile-dropdown {
  position: relative;
  cursor: pointer;
}

.profile-name {
  font-weight: bold;
}

.dropdown-menu {
  position: absolute;
  right: 0;
  top: 35px;
  background: white;
  color: #0f6bbf;
  list-style: none;
  padding: 10px 0;
  margin: 0;
  border-radius: 8px;
  min-width: 100px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
}

.dropdown-menu li {
  padding: 8px 15px;
  cursor: pointer;
}

.dropdown-menu li:hover {
  background: rgba(15, 107, 191, 0.1);
}

@media screen and (max-width: 768px) {
  .hamburger {
    display: block;
  }

  .search-container input {
    width: 100px;
  }
}
</style>
