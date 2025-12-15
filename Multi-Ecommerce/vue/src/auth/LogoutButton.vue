<template>
  <button @click="logout" class="logout-button">
    Logout
  </button>
</template>

<script setup>
import { useRouter } from 'vue-router'

const router = useRouter()

const logout = () => {
  // Clear all login info from LocalStorage
  localStorage.removeItem('token')
  localStorage.removeItem('role')
  localStorage.removeItem('user')

  // Redirect to website home page
  router.replace('/')   // 🔥 replace() avoids going back to protected pages
}
</script>

<style scoped>
.logout-button {
  padding: 8px 16px;
  background-color: #f44336;
  color: white;
  border: none;
  border-radius: 6px;
  cursor: pointer;
}
.logout-button:hover {
  background-color: #d32f2f;
}
</style>
