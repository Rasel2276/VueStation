<template>
  <div class="container">
    <div class="box">
      <h2 class="title">Register</h2>

      <input v-model="name" placeholder="Name" class="input" />
      <input v-model="email" placeholder="Email" class="input" />
      <input v-model="password" type="password" placeholder="Password" class="input" />

      <select v-model="role" class="select">
        <option value="customer">Customer</option>
        <option value="vendor">Vendor</option>
      </select>

      <button @click="handleRegister" class="button" :disabled="loading">
        {{ loading ? 'Registering...' : 'Register' }}
      </button>

      <p class="loginText">
        Already have an account?
        <button @click="$router.push('/')" class="loginButton">Login</button>
      </p>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue"
import axios from "axios"
import { useRouter } from "vue-router"

const name = ref('')
const email = ref('')
const password = ref('')
const role = ref('customer')
const loading = ref(false)
const router = useRouter()

const handleRegister = async () => {
  loading.value = true

  try {
    await axios.post("http://127.0.0.1:8000/api/register", {
      name: name.value,
      email: email.value,
      password: password.value,
      role: role.value,
    })

    alert("Registration successful!")
    router.push("/")
  } catch (err) {
    alert(err.response?.data?.message || "Registration failed")
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
/* same CSS as login */
.container {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
  background: #f0f2f5;
}
.box {
  width: 350px;
  padding: 30px;
  border-radius: 10px;
  background: #ffffff;
  box-shadow: 0 4px 15px rgba(0,0,0,0.1);
  text-align: center;
}
.title {
  margin-bottom: 25px;
  color: #333;
}
.input, .select {
  width: 100%;
  padding: 12px;
  margin-bottom: 15px;
  border-radius: 6px;
  border: 1px solid #ccc;
  font-size: 14px;
  box-sizing: border-box;
}
.button {
  width: 100%;
  padding: 12px;
  border-radius: 6px;
  border: none;
  background-color: #4CAF50;
  color: #fff;
  font-size: 16px;
  cursor: pointer;
}
.loginText {
  margin-top: 15px;
  font-size: 14px;
  color: #555;
}
.loginButton {
  background: none;
  border: none;
  color: #4CAF50;
  cursor: pointer;
  text-decoration: underline;
  padding: 0;
  font-size: 14px;
}
</style>
