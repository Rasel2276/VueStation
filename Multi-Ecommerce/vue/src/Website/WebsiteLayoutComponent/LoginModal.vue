<template>
  <transition name="fade">
    <div class="modal-overlay" @click.self="$emit('close')">
      <div class="modal-box">
        <button class="close-btn" @click="$emit('close')">&times;</button>
        <h2 class="title">Login</h2>

        <div class="input-group">
          <input v-model="email" type="email" placeholder="Email" class="input" />
          <input v-model="password" type="password" placeholder="Password" class="input" />
        </div>

        <button @click="handleLogin" class="button" :disabled="loading">
          {{ loading ? 'Logging in...' : 'Login' }}
        </button>

        <p class="registerText">
          Don't have an account?
          <button @click="$emit('openRegister')" class="registerButton">
            Register
          </button>
        </p>
      </div>
    </div>
  </transition>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'

const emit = defineEmits(['close', 'openRegister'])
const email = ref('')
const password = ref('')
const loading = ref(false)
const router = useRouter()

const handleLogin = async () => {
  loading.value = true
  try {
    const res = await axios.post('http://127.0.0.1:8000/api/login', {
      email: email.value,
      password: password.value
    })

    const data = res.data
    localStorage.setItem("token", data.token)
    localStorage.setItem("role", data.role)
    localStorage.setItem("user", JSON.stringify(data.user))

    emit('close') 

    if(data.role === 'admin') router.push("/AdminDefaultLayout")
    else if(data.role === 'vendor') router.push("/VendorDefaultLayout")
    else router.push("/CustomerDefaultLayout")
  } catch (err) {
    alert(err.response?.data?.message || "Login failed")
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
/* Modal Background */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.75);
  display: flex;
  justify-content: center; /* হরাইজন্টাল সেন্টার */
  align-items: center;     /* ভার্টিকাল সেন্টার */
  z-index: 9999;
  padding: 15px;           /* মোবাইল স্ক্রিনে চারপাশ থেকে সমান গ্যাপ নিশ্চিত করবে */
  box-sizing: border-box;
}

/* 🔵 Modal Box - Dark Blue Theme */
.modal-box {
  width: 100%;
  max-width: 380px; 
  padding: 40px 30px;
  border-radius: 16px;
  background: #050e3c; 
  position: relative;
  text-align: center;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
  border: 1px solid rgba(255, 255, 255, 0.1);
  
  /* মার্জিন অটো দিলে ব্রাউজার নিজে থেকে দুই পাশে সমান জায়গা রাখবে */
  margin-left: auto;
  margin-right: auto;
  box-sizing: border-box;
}

.close-btn {
  position: absolute; 
  top: 15px; 
  right: 15px;
  background: none; 
  border: none; 
  font-size: 26px; 
  cursor: pointer; 
  color: #ffffff; 
  opacity: 0.7;
  line-height: 1;
}

.close-btn:hover { opacity: 1; }

.title { 
  margin-bottom: 25px; 
  color: #ffffff; 
  font-size: 24px;
  font-weight: 600;
}

.input-group {
  margin-bottom: 20px;
}

.input {
  width: 100%; 
  padding: 14px; 
  margin-bottom: 15px;
  border-radius: 8px; 
  border: 1px solid rgba(255, 255, 255, 0.2); 
  box-sizing: border-box; /* এটি প্যাডিংকে বক্সের ভেতরে রাখবে */
  background: rgba(255, 255, 255, 0.05);
  color: #fff;
  font-size: 15px;
  outline: none;
  display: block; /* ফুল উইডথ নিশ্চিত করতে */
}

.input:focus {
  border-color: #e4002b;
}

.button {
  width: 100%; 
  padding: 14px; 
  border-radius: 8px; 
  border: none;
  background-color: #e4002b; 
  color: #fff; 
  font-size: 16px; 
  font-weight: 600;
  cursor: pointer;
  box-sizing: border-box;
}

.registerText { 
  margin-top: 20px; 
  font-size: 14px; 
  color: #cbd5e1; 
}

.registerButton { 
  background: none; 
  border: none; 
  color: #e4002b; 
  cursor: pointer; 
  text-decoration: underline; 
  font-weight: 600;
  padding: 0;
}

/* --- Responsive Adjustments (iPhone SE Fix) --- */
@media (max-width: 480px) {
  .modal-overlay {
    padding: 10px; /* মোবাইল স্ক্রিনের জন্য সামান্য প্যাডিং */
  }
  .modal-box {
    padding: 30px 20px;
    max-width: 300px; /* iPhone SE এর জন্য উইডথ একটু কমানো হলো যাতে দুই পাশে গ্যাপ থাকে */
  }
  .title {
    font-size: 20px;
  }
  .input, .button {
    padding: 12px;
    font-size: 14px;
  }
}
</style>