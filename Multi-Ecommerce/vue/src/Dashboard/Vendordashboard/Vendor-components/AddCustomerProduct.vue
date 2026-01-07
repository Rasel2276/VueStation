<template>
  <div class="page">
    <div class="card">
      <div class="card-header">
        <div class="icon-circle">
          <i class="fa-solid fa-cart-plus"></i>
        </div>
        <div>
          <h2 class="title">Add Customer Product</h2>
          <p class="subtitle">Fill in the details to list a new product in your store</p>
        </div>
      </div>

      <form class="form" @submit.prevent="submitForm">
        <div class="form-section">
          <h3 class="section-title"><i class="fa-solid fa-circle-info"></i> Basic Information</h3>
          <div class="field-row">
            <div class="field flex-2">
              <label>Product Name</label>
              <input type="text" v-model="form.name" placeholder="Enter product full name" required />
            </div>
            <div class="field flex-1">
              <label>Category</label>
              <select v-model="form.category" required>
                <option value="">Select Category</option>
                <option v-for="cat in categories" :key="cat" :value="cat">{{ cat }}</option>
              </select>
            </div>
            <div class="field flex-1">
              <label>Brand</label>
              <input type="text" v-model="form.brand" placeholder="e.g. Sony, Apple" required />
            </div>
          </div>
        </div>

        <div class="form-section mt-30">
          <h3 class="section-title"><i class="fa-solid fa-money-bill-wave"></i> Pricing & Inventory</h3>
          <div class="field-row">
            <div class="field">
              <label>Current Price (৳)</label>
              <div class="input-with-icon">
                <span class="currency-label">৳</span>
                <input type="number" v-model.number="form.price" placeholder="0.00" required />
              </div>
            </div>
            <div class="field">
              <label>Old Price (৳)</label>
              <div class="input-with-icon">
                <span class="currency-label">৳</span>
                <input type="number" v-model.number="form.old_price" placeholder="0.00" />
              </div>
            </div>
            <div class="field">
              <label>Stock Quantity</label>
              <input type="number" v-model.number="form.quantity" min="1" required />
            </div>
          </div>
        </div>

        <div class="form-section mt-30">
          <h3 class="section-title"><i class="fa-solid fa-image"></i> Media & Description</h3>
          <div class="field-row">
            <div class="field flex-2">
              <label>Product Details</label>
              <textarea v-model="form.details" rows="5" placeholder="Write a short description about the product..."></textarea>
            </div>
            <div class="field flex-1">
              <label>Product Image</label>
              <div class="upload-container" :class="{ 'has-image': imagePreview }" @click="$refs.fileInput.click()">
                <input type="file" ref="fileInput" @change="handleImageUpload" accept="image/*" hidden />
                <div v-if="!imagePreview" class="upload-hint">
                  <div class="upload-icon-box">
                    <i class="fa-solid fa-camera"></i>
                  </div>
                  <span>Upload Photo</span>
                  <small>JPG, PNG or WEBP</small>
                </div>
                <div v-else class="preview-wrapper">
                  <img :src="imagePreview" class="preview-img" />
                  <div class="change-overlay">Click to Change</div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="form-section mt-30 visual-theme-box">
          <div class="theme-header">
            <h3 class="section-title"><i class="fa-solid fa-palette"></i> Card Theme Color</h3>
            <span class="badge" :style="{ background: form.color }">{{ form.color.toUpperCase() }}</span>
          </div>
          <div class="color-picker-grid">
            <div class="preset-group">
              <button 
                type="button"
                v-for="c in colorPresets" 
                :key="c" 
                :style="{ background: c }"
                :class="['color-btn', { active: form.color === c }]"
                @click="form.color = c"
              ></button>
            </div>
            <div class="custom-pick-wrapper">
              <div class="custom-preview" :style="{ background: form.color }">
                <input type="color" v-model="form.color" class="color-input-hidden" />
                <i class="fa-solid fa-plus"></i>
              </div>
              <span class="custom-label">Custom Color</span>
            </div>
          </div>
        </div>

        <div class="form-footer mt-40">
          <button type="button" class="btn-secondary" @click="resetForm" :disabled="loading">
            <i class="fa-solid fa-arrow-rotate-left"></i> Reset Fields
          </button>
          <button class="btn-primary" type="submit" :disabled="loading">
            <span v-if="loading">Publishing...</span>
            <span v-else>Publish Product</span>
            <i v-if="!loading" class="fa-solid fa-paper-plane"></i>
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";
import axios from "axios";

const categories = ["PHONE", "AUDIO", "GAMING", "TV", "WATCH", "CLOTH"];
const colorPresets = ["#e4002b", "#1abc9c", "#3498db", "#9b59b6", "#f39c12", "#2c3e50"];

const imagePreview = ref(null);
const fileInput = ref(null);
const loading = ref(false);

const form = ref({
  name: "",
  brand: "",
  category: "",
  price: null,
  old_price: null,
  quantity: 1,
  details: "",
  image: null,
  color: "#e4002b",
  vendor_stock_id: 1, // আপনার ভেন্ডর স্টকের রিয়েল আইডি এখানে দিতে পারেন
  product_id: 1       // অরিজিনাল প্রোডাক্টের আইডি
});

const handleImageUpload = (event) => {
  const file = event.target.files[0];
  if (file) {
    form.value.image = file;
    imagePreview.value = URL.createObjectURL(file);
  }
};

const submitForm = async () => {
  try {
    loading.value = true;
    const formData = new FormData();
    
    // ডাটাবেজ কলামের নাম অনুযায়ী ডেটাগুলো পাঠানো হচ্ছে
    formData.append("vendor_stock_id", form.value.vendor_stock_id);
    formData.append("product_id", form.value.product_id);
    formData.append("name", form.value.name);
    formData.append("brand", form.value.brand || "");
    formData.append("category", form.value.category);
    formData.append("price", form.value.price);
    formData.append("old_price", form.value.old_price || "");
    formData.append("quantity", form.value.quantity);
    formData.append("details", form.value.details || "");
    formData.append("theme_color", form.value.color);

    if (form.value.image) {
      formData.append("image", form.value.image);
    }

    // 🛑 ফিক্স: /api/ যদি baseURL এ থাকে, তবে এখানে শুধু 'vendor/customer-products' দিন
    const response = await axios.post("vendor/customer-products", formData, {
      headers: { "Content-Type": "multipart/form-data" }
    });

    alert("Success! Product added.");
    resetForm();

  } catch (error) {
    console.error("Error Detail:", error.response?.data);
    alert("Error: " + (error.response?.data?.message || "Check Console"));
  } finally {
    loading.value = false;
  }
};

const resetForm = () => {
  form.value = {
    name: "", brand: "", category: "", price: null, old_price: null,
    quantity: 1, details: "", image: null, color: "#e4002b",
    vendor_stock_id: 1, product_id: 1
  };
  imagePreview.value = null;
  if (fileInput.value) fileInput.value.value = "";
};
</script>

<style scoped>
/* আপনার আগের CSS এখানে থাকবে */
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');
.page { padding: 50px 20px; min-height: 100vh; font-family: 'Plus Jakarta Sans', sans-serif; background: #f8fafc; }
.card { background: #fff; padding: 40px; border-radius: 24px; max-width: 900px; margin: auto; box-shadow: 0 20px 40px rgba(0,0,0,0.06); border: 1px solid #e2e8f0; }
.card-header { display: flex; align-items: center; gap: 20px; margin-bottom: 40px; }
.icon-circle { width: 60px; height: 60px; background: #3b82f6; color: #fff; border-radius: 18px; display: flex; align-items: center; justify-content: center; font-size: 24px; }
.title { font-size: 26px; font-weight: 700; color: #1e293b; margin: 0; }
.subtitle { color: #64748b; font-size: 14px; margin-top: 4px; }
.section-title { font-size: 15px; font-weight: 700; color: #334155; margin-bottom: 18px; display: flex; align-items: center; gap: 10px; text-transform: uppercase; }
.section-title i { color: #3b82f6; }
.field-row { display: flex; gap: 24px; flex-wrap: wrap; }
.flex-1 { flex: 1; min-width: 200px; }
.flex-2 { flex: 2; min-width: 300px; }
.field { display: flex; flex-direction: column; margin-bottom: 15px; }
.field label { font-weight: 600; margin-bottom: 8px; font-size: 13px; color: #475569; }
.field input, .field select, .field textarea { padding: 14px 16px; border: 1.5px solid #e2e8f0; border-radius: 12px; font-size: 14px; }
.input-with-icon { position: relative; display: flex; align-items: center; }
.currency-label { position: absolute; left: 16px; font-weight: 700; color: #64748b; }
.input-with-icon input { padding-left: 35px; width: 100%; }
.upload-container { height: 155px; border: 2px dashed #cbd5e1; border-radius: 16px; cursor: pointer; background: #f8fafc; position: relative; }
.upload-hint { height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; color: #64748b; }
.preview-wrapper, .preview-img { width: 100%; height: 100%; object-fit: cover; border-radius: 14px; }
.change-overlay { position: absolute; inset: 0; background: rgba(0,0,0,0.4); display: flex; align-items: center; justify-content: center; color: #fff; opacity: 0; transition: 0.3s; }
.preview-wrapper:hover .change-overlay { opacity: 1; }
.visual-theme-box { background: #f1f5f9; padding: 25px; border-radius: 20px; }
.theme-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
.badge { padding: 4px 12px; border-radius: 20px; color: #fff; font-size: 12px; font-weight: 700; }
.color-picker-grid { display: flex; align-items: center; gap: 30px; }
.preset-group { display: flex; gap: 15px; }
.color-btn { width: 38px; height: 38px; border-radius: 12px; border: 3px solid #fff; cursor: pointer; }
.color-btn.active { transform: scale(1.2); border-color: #1e293b; }
.custom-pick-wrapper { display: flex; flex-direction: column; align-items: center; gap: 5px; }
.custom-preview { width: 40px; height: 40px; border-radius: 12px; border: 2.5px solid #fff; position: relative; display: flex; align-items: center; justify-content: center; color: #fff; }
.color-input-hidden { position: absolute; opacity: 0; inset: 0; cursor: pointer; }
.form-footer { display: flex; justify-content: space-between; border-top: 1px solid #e2e8f0; padding-top: 30px; }
.btn-secondary { background: none; border: 1.5px solid #e2e8f0; padding: 12px 24px; border-radius: 12px; cursor: pointer; }
.btn-primary { background: #3b82f6; color: #fff; border: none; padding: 14px 35px; border-radius: 14px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 12px; }
@media (max-width: 768px) { .field-row { flex-direction: column; } .form-footer { flex-direction: column-reverse; gap: 15px; } .btn-primary, .btn-secondary { width: 100%; justify-content: center; } }
</style>