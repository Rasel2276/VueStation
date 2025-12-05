<template>
  <div class="topbar">
    <div class="search-box">
      <input type="text" placeholder="Search" />
      <span class="search-icon">🔍</span>
    </div>

    <div class="right">
      <span class="logout">LOGOUT</span>
      <span class="menu-icon">☰</span>
    </div>
  </div>
</template>

<style scoped>
.topbar {
  background: #b7eaff;
  padding: 10px 20px;
  border-radius: 8px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.search-box {
  position: relative;
}

.search-box input {
  padding: 8px;
  padding-right: 30px;
  width: 260px;
  border: none;
  border-radius: 5px;
}

.search-icon {
  position: absolute;
  right: 8px;
  top: 8px;
}

.right {
  display: flex;
  align-items: center;
  gap: 20px;
  font-weight: bold;
}
</style>
