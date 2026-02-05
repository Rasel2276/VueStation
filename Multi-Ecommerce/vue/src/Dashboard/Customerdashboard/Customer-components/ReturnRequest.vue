<template>
  <div class="page">
    <div class="card">
      <h2 class="title">Customer Order Return Request</h2>

      <form class="form" @submit.prevent="submitForm">
        <div class="field-row">
          <div class="field">
            <label>Order Tracking ID</label>
            <input 
              type="text" 
              v-model="form.order_id" 
              placeholder="Ex: ORD-12345" 
              required 
            />
          </div>

          <div class="field">
            <label>Registered Phone Number</label>
            <input 
              type="text" 
              v-model="form.phone" 
              placeholder="Enter your phone number" 
              required 
            />
          </div>
        </div>

        <div class="field-row">
          <div class="field">
            <label>Reason for Return</label>
            <textarea 
              rows="4" 
              placeholder="Why do you want to return this product?" 
              v-model="form.reason" 
              required
            ></textarea>
          </div>
        </div>

        <div v-if="message" :class="['alert-box', isError ? 'alert-error' : 'alert-success']">
          {{ message }}
        </div>

        <div class="btn-wrapper">
          <button class="btn" type="submit" :disabled="loading">
            <i class="fas fa-undo"></i> {{ loading ? 'Processing...' : 'Submit Return Request' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import axios from "axios";

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
      // কাস্টমারের টোকেন নিচ্ছি
      token: localStorage.getItem("token") || localStorage.getItem("customertoken")
    }
  },
  methods: {
    async submitForm() {
      this.loading = true;
      this.message = '';
      
      try {
        const res = await axios.post("http://127.0.0.1:8000/api/customer/order-return", this.form, {
          headers: { 
            Authorization: `Bearer ${this.token}`,
            Accept: 'application/json'
          }
        });
        
        this.isError = false;
        this.message = res.data.message;
        
        // ফর্ম রিসেট
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
/* কার্ডের হাইট এবং প্যাডিং কমানো হয়েছে */
.page {
  min-height: 20vh; /* পেজের মিনিমাম হাইট কমানো হয়েছে */
  display: flex;
  justify-content: center;
  padding: 20px 15px; /* বাইরের প্যাডিং কমানো হয়েছে */
  background-color: #f9fafb;
}

.card {
  width: 100%;
  max-width: 850px;
  background: #ffffff;
  padding: 20px 30px; /* কার্ডের ভেতরের প্যাডিং কমানো হয়েছে */
  border-radius: 10px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
}

.title {
  text-align: center;
  font-size: 22px; /* টাইটেল সাইজ একটু ছোট করা হয়েছে */
  font-weight: 700;
  color: #1f2937;
  margin-bottom: 20px; /* নিচের গ্যাপ কমানো হয়েছে */
  border-bottom: 1px solid #f3f4f6;
  padding-bottom: 10px;
}

.form {
  display: flex;
  flex-direction: column;
  gap: 15px; /* প্রতিটি ফিল্ডের মাঝের গ্যাপ কমানো হয়েছে */
}

.field-row {
  display: flex;
  gap: 20px;
  flex-wrap: wrap;
}

.field {
  flex: 1;
  display: flex;
  flex-direction: column;
  min-width: 250px;
}

.field label {
  font-size: 13px; /* লেবেল সাইজ একটু ছোট */
  font-weight: 600;
  color: #4b5563;
  margin-bottom: 5px;
}

.field input,
.field textarea {
  padding: 10px; /* ইনপুটের ভেতরের প্যাডিং কমানো হয়েছে */
  border-radius: 6px;
  border: 1px solid #d1d5db;
  font-size: 14px;
  outline: none;
}

/* Alert Boxes */
.alert-box {
  padding: 10px;
  border-radius: 6px;
  font-size: 14px;
  text-align: center;
}

.btn-wrapper {
  display: flex;
  justify-content: center;
  margin-top: 5px;
}

.btn {
  background: #1e293b;
  color: #fff;
  padding: 10px 35px; /* বাটনের হাইট কমানো হয়েছে */
  border: none;
  border-radius: 6px;
  font-size: 15px;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.3s ease;
}

.btn:hover {
  background: #0f172a;
}
</style>