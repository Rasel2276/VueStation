<template>
  <transition name="fade">
    <div class="modal-overlay" @click.self="$emit('close')">
      <div class="modal-box">
        <button class="close-btn" @click="$emit('close')">&times;</button>
        
        <h2 class="title">Register</h2>

        <div class="input-group">
          <input
            v-model="name"
            type="text"
            placeholder="Full Name"
            class="input"
          />

          <input
            v-model="email"
            type="email"
            placeholder="Email Address"
            class="input"
          />

          <input
            v-model="password"
            type="password"
            placeholder="Password"
            class="input"
          />

          <input
            v-model="password_confirmation"
            type="password"
            placeholder="Confirm Password"
            class="input"
          />

          <select v-model="role" class="select">
            <option value="customer">Customer</option>
            <option value="vendor">Vendor</option>
          </select>
        </div>

        <button
          @click="handleRegister"
          class="button"
          :disabled="loading"
        >
          {{ loading ? 'Registering...' : 'Register' }}
        </button>

        <p class="loginText">
          Already have an account?
          <button
            @click="$emit('openLogin')"
            class="loginButton"
          >
            Login
          </button>
        </p>
      </div>
    </div>
  </transition>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'

const emit = defineEmits(['close', 'openLogin'])

const name = ref('')
const email = ref('')
const password = ref('')
const password_confirmation = ref('')
const role = ref('customer')
const loading = ref(false)

const handleRegister = async () => {
  loading.value = true

  try {
    await axios.post('http://127.0.0.1:8000/api/register', {
      name: name.value,
      email: email.value,
      password: password.value,
      password_confirmation: password_confirmation.value,
      role: role.value
    })

    alert('Registration Successful')
    emit('openLogin') // রেজিস্ট্রেশন শেষে লগইন মডাল ওপেন হবে

  } catch (err) {
    alert(
      err.response?.data?.message ||
      'Registration failed'
    )
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
  padding: 15px;           /* মোবাইল স্ক্রিনে সমান গ্যাপ নিশ্চিত করবে */
  box-sizing: border-box;
}

/* 🔵 Modal Box - Dark Blue Theme (Exact match with Login) */
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

.input, .select {
  width: 100%; 
  padding: 14px; 
  margin-bottom: 12px;
  border-radius: 8px; 
  border: 1px solid rgba(255, 255, 255, 0.2); 
  box-sizing: border-box;
  background: rgba(255, 255, 255, 0.05);
  color: #fff;
  font-size: 15px;
  outline: none;
  display: block;
  transition: 0.3s;
}

/* Select option background fix */
.select option {
  background-color: #050e3c;
  color: #fff;
}

.input:focus, .select:focus {
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
  transition: 0.3s;
}
.button:hover { background-color: #c30025; }

.loginText { 
  margin-top: 20px; 
  font-size: 14px; 
  color: #cbd5e1; 
}

.loginButton { 
  background: none; 
  border: none; 
  color: #e4002b; 
  cursor: pointer; 
  text-decoration: underline; 
  font-weight: 600;
  padding: 0;
  margin-left: 5px;
}

/* --- Responsive Adjustments (iPhone SE Fix) --- */
@media (max-width: 480px) {
  .modal-overlay {
    padding: 10px;
  }
  .modal-box {
    padding: 30px 20px;
    max-width: 300px;
  }
  .title {
    font-size: 20px;
    margin-bottom: 20px;
  }
  .input, .select, .button {
    padding: 12px;
    font-size: 14px;
  }
}

/* Animations */
.fade-enter-active, .fade-leave-active { transition: opacity 0.3s; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>