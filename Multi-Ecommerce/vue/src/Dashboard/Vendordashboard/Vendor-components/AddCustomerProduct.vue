<template>
  <div class="page">
    <div class="card">
      <div class="card-header">
        <div class="icon-circle"><i class="fa-solid fa-cart-plus"></i></div>
        <div>
          <h2 class="title">Add Customer Product</h2>
          <p class="subtitle">মাইগ্রেশন অনুযায়ী আপনার কাস্টমার লিস্টিং তৈরি করুন</p>
        </div>
      </div>

      <form class="form" @submit.prevent="submitForm">
        <div class="form-section stock-box">
          <h3 class="section-title"><i class="fa-solid fa-boxes-stacked"></i> ১. আপনার স্টক ইনভেন্টরি</h3>
          <div class="field">
            <label>প্রোডাক্ট সিলেক্ট করুন</label>
            <select v-model="selectedStockId" @change="onStockSelect" class="stock-select" required>
              <option value="">-- ড্রপডাউন থেকে প্রোডাক্ট বেছে নিন --</option>
              <option v-for="stock in vendorStocks" :key="stock.id" :value="stock.id">
                {{ stock.admin_stock?.product?.product_name || stock.admin_stock?.product?.name || 'নাম পাওয়া যায়নি' }} 
                (স্টক আছে: {{ stock.quantity }} টি)
              </option>
            </select>
          </div>
          
          <div v-if="selectedStockId" class="info-grid mt-15">
             <div class="info-card">
                <span class="info-label">ক্যাটাগরি</span>
                <span class="info-value">{{ form.category || 'N/A' }}</span>
             </div>
             <div class="info-card highlight">
                <span class="info-label">আপনার ক্রয়মূল্য</span>
                <span class="info-value">৳ {{ purchasePrice }}</span>
             </div>
             <div class="info-card danger">
                <span class="info-label">এভেইলেবল স্টক</span>
                <span class="info-value">{{ maxQty }} টি</span>
             </div>
          </div>
        </div>

        <div v-if="selectedStockId" class="form-container mt-30">
          <h3 class="section-title"><i class="fa-solid fa-edit"></i> ২. বিক্রয় ও ডিসপ্লে তথ্য</h3>
          
          <div class="field-row">
            <div class="field flex-2">
              <label>প্রোডাক্টের নাম (Name)</label>
              <input type="text" v-model="form.name" required />
            </div>
            <div class="field flex-1">
              <label>ব্র্যান্ড (Brand)</label>
              <input type="text" v-model="form.brand" />
            </div>
          </div>

          <div class="field-row mt-15">
            <div class="field">
              <label>বিক্রয় মূল্য (Price)</label>
              <div class="input-with-icon">
                <span class="currency-label">৳</span>
                <input type="number" step="0.01" v-model.number="form.price" @input="calculateOldPrice" required />
              </div>
            </div>
            <div class="field">
              <label>পুরাতন মূল্য (Old Price)</label>
              <div class="input-with-icon">
                <span class="currency-label">৳</span>
                <input type="number" step="0.01" v-model.number="form.old_price" />
              </div>
            </div>
            <div class="field">
              <label>বিক্রয় পরিমাণ (Quantity)</label>
              <input type="number" v-model.number="form.quantity" :max="maxQty" min="1" required />
            </div>
          </div>

          <div class="field-row mt-15">
            <div class="field flex-2">
              <label>বিস্তারিত বিবরণ (Details)</label>
              <textarea v-model="form.details" rows="3" placeholder="প্রোডাক্ট সম্পর্কে লিখুন..."></textarea>
            </div>
            <div class="field flex-1">
              <label>থিম কালার (Theme Color)</label>
              <div class="color-picker-box">
                <input type="color" v-model="form.theme_color" class="color-input" />
                <span class="color-code">{{ form.theme_color.toUpperCase() }}</span>
              </div>
            </div>
          </div>

          <div class="field mt-15">
            <label>প্রোডাক্ট ইমেজ (Image)</label>
            <div class="upload-area" @click="$refs.fileInput.click()">
              <input type="file" ref="fileInput" @change="handleImage" accept="image/*" hidden />
              <img v-if="imagePreview" :src="imagePreview" class="preview-img" />
              <div v-else class="placeholder">
                <i class="fa-solid fa-image"></i>
                <p>ছবি আপলোড করতে ক্লিক করুন</p>
              </div>
            </div>
          </div>

          <div class="form-footer mt-40">
            <button type="button" class="btn-cancel" @click="resetForm">রিসেট</button>
            <button class="btn-save" type="submit" :disabled="loading">
              <span v-if="loading">প্রসেসিং...</span>
              <span v-else>লিস্টে যুক্ত করুন</span>
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";

const vendorStocks = ref([]);
const selectedStockId = ref("");
const maxQty = ref(0);
const purchasePrice = ref(0);
const imagePreview = ref(null);
const loading = ref(false);

// মাইগ্রেশন অনুযায়ী ইনিশিয়াল ডাটা
const form = ref({
  vendor_stock_id: null,
  product_id: null,
  name: "",
  brand: "",
  category: "",
  price: null,
  old_price: null,
  quantity: 1,
  details: "",
  image: null,
  theme_color: "#e4002b"
});

const getStocks = async () => {
  try {
    const res = await axios.get("/vendor/my-stocks");
    vendorStocks.value = res.data;
  } catch (err) {
    console.error("স্টক লোড হয়নি।");
  }
};

onMounted(getStocks);

const onStockSelect = () => {
  const stock = vendorStocks.value.find(s => s.id === selectedStockId.value);
  if (stock && stock.admin_stock?.product) {
    const p = stock.admin_stock.product;
    form.value.vendor_stock_id = stock.id;
    form.value.product_id = p.id;
    form.value.category = p.category || "General";
    form.value.name = p.product_name || p.name;
    form.value.brand = p.brand || "";
    purchasePrice.value = stock.price;
    maxQty.value = stock.quantity;
  }
};

const handleImage = (e) => {
  const file = e.target.files[0];
  if (file) {
    form.value.image = file;
    imagePreview.value = URL.createObjectURL(file);
  }
};

const calculateOldPrice = () => {
  if (form.value.price) {
    form.value.old_price = Math.round(form.value.price * 1.10);
  }
};

const submitForm = async () => {
  if (form.value.quantity > maxQty.value) {
    alert("পর্যাপ্ত স্টক নেই!");
    return;
  }

  loading.value = true;
  
  // যেহেতু ইমেজ আছে, তাই FormData ব্যবহার করতে হবে
  const formData = new FormData();
  Object.keys(form.value).forEach(key => {
    if (form.value[key] !== null) {
      formData.append(key, form.value[key]);
    }
  });

  try {
    await axios.post("/vendor/customer-products", formData, {
      headers: { 'Content-Type': 'multipart/form-data' }
    });
    alert("সফলভাবে সেভ হয়েছে!");
    resetForm();
    getStocks();
  } catch (err) {
    alert("সেভ করতে সমস্যা হয়েছে। ডাটা চেক করুন।");
  } finally {
    loading.value = false;
  }
};

const resetForm = () => {
  selectedStockId.value = "";
  form.value = { 
    quantity: 1, name: "", brand: "", category: "", 
    price: null, old_price: null, details: "", 
    image: null, theme_color: "#e4002b" 
  };
  imagePreview.value = null;
  purchasePrice.value = 0;
  maxQty.value = 0;
};
</script>

<style scoped>
/* Scoped CSS for better look */
.page { padding: 40px 20px; background: #f8fafc; min-height: 100vh; }
.card { background: #fff; padding: 35px; border-radius: 20px; max-width: 900px; margin: auto; box-shadow: 0 10px 30px rgba(0,0,0,0.04); border: 1px solid #e2e8f0; }
.card-header { display: flex; align-items: center; gap: 20px; margin-bottom: 30px; }
.icon-circle { width: 60px; height: 60px; background: #4f46e5; color: white; border-radius: 15px; display: flex; align-items: center; justify-content: center; font-size: 24px; }
.title { font-size: 26px; font-weight: 800; color: #1e293b; margin: 0; }
.subtitle { color: #64748b; font-size: 14px; margin-top: 5px; }
.stock-box { background: #f1f5f9; padding: 25px; border-radius: 15px; border: 1px solid #cbd5e1; }
.stock-select { width: 100%; padding: 14px; border-radius: 10px; border: 1.5px solid #cbd5e1; font-weight: 600; background: white; cursor: pointer; }
.info-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; }
.info-card { background: white; padding: 15px; border-radius: 12px; border: 1px solid #e2e8f0; display: flex; flex-direction: column; }
.info-label { font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase; }
.info-value { font-size: 15px; font-weight: 700; color: #334155; }
.info-card.highlight { background: #eef2ff; border-color: #c7d2fe; }
.info-card.danger { background: #fff1f2; border-color: #fecdd3; }
.field-row { display: flex; gap: 20px; flex-wrap: wrap; }
.flex-1 { flex: 1; } .flex-2 { flex: 2; }
.field { display: flex; flex-direction: column; margin-bottom: 20px; flex: 1; }
.field label { font-size: 14px; font-weight: 700; color: #475569; margin-bottom: 8px; }
.field input, .field textarea { padding: 12px 15px; border-radius: 10px; border: 1.5px solid #e2e8f0; outline: none; }
.input-with-icon { position: relative; display: flex; align-items: center; }
.currency-label { position: absolute; left: 15px; font-weight: 800; color: #94a3b8; }
.input-with-icon input { padding-left: 35px; width: 100%; }
.color-picker-box { display: flex; align-items: center; gap: 10px; padding: 10px; border: 1.5px solid #e2e8f0; border-radius: 10px; background: #fff; }
.color-input { width: 40px; height: 30px; border: none; cursor: pointer; }
.color-code { font-weight: bold; color: #475569; }
.upload-area { border: 2px dashed #cbd5e1; height: 120px; border-radius: 12px; display: flex; justify-content: center; align-items: center; cursor: pointer; overflow: hidden; background: #f8fafc; }
.preview-img { width: 100%; height: 100%; object-fit: cover; }
.placeholder { text-align: center; color: #94a3b8; }
.form-footer { display: flex; justify-content: flex-end; gap: 15px; padding-top: 30px; border-top: 2px solid #f1f5f9; }
.btn-save { background: #4f46e5; color: white; border: none; padding: 15px 35px; border-radius: 12px; font-weight: 800; cursor: pointer; }
.btn-cancel { background: #f1f5f9; color: #64748b; border: none; padding: 15px 25px; border-radius: 12px; font-weight: 700; cursor: pointer; }
</style>