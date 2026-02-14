<template>
  <div class="container">
    <div class="box">
      <h2 class="title">Login</h2>

      <input v-model="email" type="email" placeholder="Email" class="input" />
      <input v-model="password" type="password" placeholder="Password" class="input" />

      <button @click="handleLogin" class="button" :disabled="loading">
        {{ loading ? 'Logging in...' : 'Login' }}
      </button>

      <p class="registerText">
        Don't have an account?
        <button @click="$router.push('/register')" class="registerButton">
          Register
        </button>
      </p>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'
import api, { BASE_URL } from '../axios';

const email = ref('')
const password = ref('')
const loading = ref(false)
const router = useRouter()

const handleLogin = async () => {
  loading.value = true
  try{
    const res = await api.post('/login',{
      email: email.value,
      password: password.value
    })

    const data = res.data
    localStorage.setItem("token", data.token)
    localStorage.setItem("role", data.role)
    localStorage.setItem("user", JSON.stringify(data.user))

    if(data.role==='admin') router.push("/AdminDefaultLayout")
    else if(data.role==='vendor') router.push("/VendorDefaultLayout")
    else router.push("/CustomerDefaultLayout")
  }catch(err){
    alert(err.response?.data?.message || "Login failed")
  }finally{
    loading.value=false
  }
}
</script>



<style scoped>
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
.input {
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
.registerText {
  margin-top: 15px;
  font-size: 14px;
  color: #555;
}
.registerButton {
  background: none;
  border: none;
  color: #4CAF50;
  cursor: pointer;
  text-decoration: underline;
  padding: 0;
  font-size: 14px;
}
</style>
