<template>
  <div class="page">
    <div class="card">
      <div class="card-header">
        <h2 class="title">Order Return Request</h2>
        <p class="subtitle">Please fill out the form below</p>
      </div>

      <form class="form" @submit.prevent="submitForm">
        <div class="field-row">
          <div class="field">
            <label>Order ID</label>
            <input 
              type="text" 
              v-model="form.order_id" 
              placeholder="Order ID" 
              required 
            />
          </div>

          <div class="field">
            <label>Phone Number</label>
            <input 
              type="text" 
              v-model="form.phone" 
              placeholder="Phone Number" 
              required 
            />
          </div>
        </div>

        <div class="field">
          <label>Reason</label>
          <textarea 
            rows="3" 
            placeholder="Reason for return" 
            v-model="form.reason" 
            required
          ></textarea>
        </div>

        <transition name="fade">
          <div v-if="message" :class="['alert-box', isError ? 'alert-error' : 'alert-success']">
            {{ message }}
          </div>
        </transition>

        <div class="btn-wrapper">
          <button class="btn" type="submit" :disabled="loading">
            <span v-if="!loading">Submit Request</span>
            <span v-else class="loader-spinner"></span>
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import api, { BASE_URL } from '../../../axios';

export default {
  data() {
    return {
      loading: false,
      message: '',
      isError: false,
      form: {
        order_id: "",
        phone: "",
        reason: ""
      },
      token: localStorage.getItem("token") || localStorage.getItem("customertoken")
    }
  },
  methods: {
    async submitForm() {
      this.loading = true;
      this.message = '';
      
      try {
        const res = await api.post("/customer/order-return", this.form, {
          headers: { 
            Authorization: `Bearer ${this.token}`,
            Accept: 'application/json'
          }
        });
        
        this.isError = false;
        this.message = res.data.message;
        this.form = { order_id: "", phone: "", reason: "" };
        
      } catch (err) {
        this.isError = true;
        this.message = err.response?.data?.message || "Something went wrong! Please try again.";
      } finally {
        this.loading = false;
      }
    }
  }
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap');

.page {
  min-height: 60vh;
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 40px 15px;
  background-color: #f8fafc;
  font-family: 'Outfit', sans-serif;
}

.card {
  width: 100%;
  max-width: 600px;
  background: #ffffff;
  padding: 40px;
  border-radius: 20px;
  box-shadow: 0 15px 30px rgba(0, 0, 0, 0.05);
  border: 1px solid #f1f5f9;
}

.card-header {
  text-align: center;
  margin-bottom: 30px;
}

.title {
  font-size: 22px;
  font-weight: 700;
  color: #0f172a;
  margin: 0;
}

.subtitle {
  color: #64748b;
  font-size: 14px;
  margin-top: 5px;
}

.form {
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.field-row {
  display: flex;
  gap: 15px;
}

.field {
  flex: 1;
  display: flex;
  flex-direction: column;
}

.field label {
  font-size: 14px;
  font-weight: 600;
  color: #475569;
  margin-bottom: 6px;
}

.field input,
.field textarea {
  padding: 12px;
  border-radius: 10px;
  border: 1px solid #e2e8f0;
  font-size: 14px;
  transition: all 0.3s ease;
  background: #fcfdfe;
}

.field input:focus,
.field textarea:focus {
  border-color: #3b82f6;
  background: #fff;
  outline: none;
}

.alert-box {
  padding: 12px;
  border-radius: 10px;
  font-size: 14px;
  text-align: center;
}

.alert-success { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
.alert-error { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }

.btn {
  width: 100%;
  background: #0f172a;
  color: #fff;
  padding: 14px;
  border: none;
  border-radius: 10px;
  font-size: 16px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
}

.btn:hover:not(:disabled) {
  background: #3b82f6;
}

.btn:disabled {
  opacity: 0.7;
}

.loader-spinner {
  width: 18px;
  height: 18px;
  border: 2px solid rgba(255, 255, 255, 0.3);
  border-radius: 50%;
  border-top-color: #fff;
  animation: spin 1s linear infinite;
  display: inline-block;
}

@keyframes spin { to { transform: rotate(360deg); } }

.fade-enter-active, .fade-leave-active { transition: opacity 0.3s; }
.fade-enter-from, .fade-leave-to { opacity: 0; }

/* 📱 MOBILE RESPONSIVE UPDATES */
@media (max-width: 640px) {
  .page {
    padding: 20px 10px; /* পেজের ডানে-বামে হালকা জায়গা থাকবে */
  }

  .card {
    padding: 25px 20px; /* কার্ডের ভেতরের প্যাডিং কমিয়ে দেওয়া হয়েছে */
    border-radius: 15px; /* মোবাইলে হালকা কম রেডিয়াস ভালো লাগে */
  }

  .field-row {
    flex-direction: column; /* ইনপুটগুলো নিচে নিচে আসবে */
    gap: 15px;
  }

  .title {
    font-size: 20px; /* টাইটেল একটু ছোট করা হয়েছে */
  }
}
</style>