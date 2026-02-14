<template>
  <div class="page">
    <div class="card">
      <h2 class="title">Add Customer Product</h2>

      <form @submit.prevent="submitForm">
        <div v-for="(item, index) in productList" :key="index" class="product-wrapper">
          
          <div class="field-row">
            <div class="field flex-2">
              <label>Select Product</label>
              <select v-model="item.selectedStockId" @change="onStockSelect(index)" required>
                <option value="">-- Choose from stock --</option>
                <option v-for="stock in vendorStocks" :key="stock.id" :value="stock.id">
                  {{ stock.admin_stock?.product?.product_name || stock.admin_stock?.product?.name }} 
                  (Stock: {{ stock.quantity }})
                </option>
              </select>
            </div>

            <div class="field flex-1">
              <label>Quantity</label>
              <input type="number" v-model.number="item.form.quantity" :max="item.maxQty" min="1" required />
            </div>

            <div class="field flex-1">
              <label>Buy Price</label>
              <input type="text" :value="item.purchasePrice ? '৳ ' + item.purchasePrice : '৳ 0'" readonly class="readonly-input" />
            </div>
            
            <button v-if="productList.length > 1" type="button" @click="productList.splice(index, 1)" class="remove-row-btn">×</button>
          </div>

          <div v-if="item.selectedStockId" class="detail-section">
            <hr />
            
            <div class="field-row">
              <div class="field flex-2">
                <label>Product Name (Customer Display)</label>
                <input type="text" v-model="item.form.name" required />
              </div>
              <div class="field flex-1">
                <label>Brand</label>
                <input type="text" v-model="item.form.brand" />
              </div>
              <div class="field flex-1">
                <label>Category</label>
                <input type="text" v-model="item.form.category" required placeholder="Enter category" />
              </div>
            </div>

            <div class="field-row">
              <div class="field">
                <label>Sale Price (৳)</label>
                <input type="number" step="0.01" v-model.number="item.form.price" @input="calculateOldPrice(index)" required />
              </div>
              <div class="field">
                <label>Old Price (৳)</label>
                <input type="number" step="0.01" v-model.number="item.form.old_price" />
              </div>
              
              <div class="field flex-1">
                <label>Theme Color</label>
                <div class="color-palette">
                  <button 
                    v-for="color in presetColors" 
                    :key="color" 
                    type="button"
                    class="color-circle" 
                    :style="{ backgroundColor: color }"
                    :class="{ active: item.form.theme_color === color }"
                    @click="item.form.theme_color = color"
                  ></button>
                  <input type="color" v-model="item.form.theme_color" class="custom-color-input" />
                </div>
              </div>
            </div>

            <div class="field-row">
              <div class="field flex-2">
                <label>Product Details</label>
                <textarea v-model="item.form.details" rows="3" placeholder="Description here..."></textarea>
              </div>
              <div class="field flex-1">
                <label>Product Image</label>
                <div class="image-upload" @click="triggerFileInput(index)">
                  <input type="file" :ref="el => fileInputs[index] = el" @change="handleImage($event, index)" accept="image/*" hidden />
                  <img v-if="item.imagePreview" :src="item.imagePreview" class="preview-img" />
                  <span v-else>+ Upload Image</span>
                </div>
              </div>
            </div>
          </div>
          <div class="row-divider" v-if="index !== productList.length - 1"></div>
        </div>

        <div class="add-more-container">
          <button type="button" @click="addNewProduct" class="add-row-btn">+ Add Another Product</button>
        </div>

        <div class="action-buttons">
          <button type="button" class="reset-btn" @click="resetForm">Reset All</button>
          <button class="submit-btn" type="submit" :disabled="loading">
            {{ loading ? 'Saving...' : 'Add All to Marketplace' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import api, { BASE_URL } from '../../../axios';

const vendorStocks = ref([]);
const fileInputs = ref([]);
const loading = ref(false);

const presetColors = ['#1abc9c', '#ff4d4d', '#f39c12', '#000000'];
const token = localStorage.getItem('vendortoken') || localStorage.getItem('token');

const createNewItem = () => ({
  selectedStockId: "",
  maxQty: 0,
  purchasePrice: 0,
  imagePreview: null,
  form: {
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
    theme_color: "#1abc9c"
  }
});

const productList = ref([createNewItem()]);

const addNewProduct = () => {
  productList.value.push(createNewItem());
};

const getStocks = async () => {
  try {
    const res = await api.get("/vendor/my-stocks", {
      headers: { Authorization: `Bearer ${token}` }
    });
    vendorStocks.value = res.data;
  } catch (err) { console.error(err); }
};

const onStockSelect = (index) => {
  const item = productList.value[index];
  const isDuplicate = productList.value.some((p, i) => i !== index && p.selectedStockId === item.selectedStockId);
  
  if (isDuplicate && item.selectedStockId !== "") {
    alert("This product is already selected!");
    item.selectedStockId = "";
    return;
  }

  const stock = vendorStocks.value.find(s => s.id === item.selectedStockId);
  if (stock && stock.admin_stock) {
    const p = stock.admin_stock.product;
    item.form.vendor_stock_id = stock.id;
    item.form.product_id = p?.id;
    item.form.category = p?.category || ""; 
    item.form.name = p?.product_name || p?.name || "";
    item.form.brand = p?.brand || "";
    item.maxQty = stock.quantity;
    item.purchasePrice = stock.admin_stock.vendor_sale_price || 0;
  }
};

const triggerFileInput = (index) => {
  if (fileInputs.value[index]) fileInputs.value[index].click();
};

const handleImage = (e, index) => {
  const file = e.target.files[0];
  if (file) {
    productList.value[index].form.image = file;
    productList.value[index].imagePreview = URL.createObjectURL(file);
  }
};

const calculateOldPrice = (index) => {
  const item = productList.value[index];
  if (item.form.price) item.form.old_price = Math.round(item.form.price * 1.10);
};

const submitForm = async () => {
  loading.value = true;
  try {
    for (const item of productList.value) {
      if (!item.selectedStockId) continue;
      
      const formData = new FormData();
      Object.keys(item.form).forEach(key => {
        if (item.form[key] !== null && item.form[key] !== undefined) {
          formData.append(key, item.form[key]);
        }
      });

      await api.post("/vendor/customer-products", formData, {
        headers: { 
          'Content-Type': 'multipart/form-data',
          'Authorization': `Bearer ${token}` 
        }
      });
    }
    
    alert("All products added successfully!");
    resetForm();
    getStocks();
  } catch (err) { 
    alert("Error saving products!"); 
    console.error(err);
  } finally { 
    loading.value = false; 
  }
};

const resetForm = () => {
  productList.value = [createNewItem()];
};

onMounted(getStocks);
</script>

<style scoped>
/* 🔒 DESKTOP DESIGN - 100% UNCHANGED */
.page { padding: 40px; min-height: 100vh; font-family: sans-serif; background: #f8f9fa; box-sizing: border-box; }
.card { background: #fff; padding: 30px; border-radius: 8px; max-width: 950px; margin: auto; box-shadow: 0 2px 10px rgba(0,0,0,0.1); box-sizing: border-box; }
.title { font-size: 22px; font-weight: 600; margin-bottom: 25px; color: #333; text-align: center; }
.product-wrapper { margin-bottom: 30px; position: relative; border: 1px solid #f0f0f0; padding: 20px; border-radius: 8px; box-sizing: border-box; }
.row-divider { height: 2px; background: #2563eb; margin: 40px 0; border-radius: 2px; }
.field-row { display: flex; gap: 20px; margin-bottom: 20px; align-items: flex-end; }
.field { flex: 1; display: flex; flex-direction: column; }
.field label { font-weight: bold; margin-bottom: 8px; font-size: 13px; color: #555; }
.field input, .field select, .field textarea { padding: 10px; border: 1px solid #ddd; border-radius: 4px; outline: none; transition: 0.3s; width: 100%; box-sizing: border-box; }
.field input:focus { border-color: #2563eb; }
.flex-1 { flex: 1; } .flex-2 { flex: 2; }
.readonly-input { background: #f9f9f9; color: #777; font-weight: bold; }
.color-palette { display: flex; gap: 8px; align-items: center; height: 40px; }
.color-circle { width: 28px; height: 28px; border-radius: 50%; border: 2px solid transparent; cursor: pointer; transition: 0.2s; padding: 0; }
.color-circle.active { border-color: #000; transform: scale(1.1); }
.custom-color-input { width: 30px; height: 30px; padding: 0; border: none; background: none; cursor: pointer; }
.image-upload { border: 2px dashed #ddd; height: 100px; border-radius: 6px; display: flex; justify-content: center; align-items: center; cursor: pointer; background: #fafafa; color: #999; font-size: 13px; overflow: hidden; position: relative; box-sizing: border-box; }
.preview-img { width: 100%; height: 100%; object-fit: contain; }
.add-row-btn { background: #10b981; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; font-weight: bold; }
.remove-row-btn { background: #ff4d4d; color: white; border: none; width: 35px; height: 35px; border-radius: 4px; cursor: pointer; font-size: 20px; flex-shrink: 0; }
.action-buttons { display: flex; justify-content: space-between; border-top: 1px solid #eee; padding-top: 20px; margin-top: 20px; gap: 10px; }
.submit-btn { background: #2563eb; color: white; border: none; padding: 10px 30px; border-radius: 4px; cursor: pointer; font-weight: bold; }
.reset-btn { background: #6b7280; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; }
hr { border: none; border-top: 1px solid #eee; margin-bottom: 25px; }

/* 📱 MOBILE RESPONSIVE - ADDED FOR PERFECT FIT */
@media (max-width: 768px) {
  .page { padding: 15px; }
  .card { padding: 20px; }
  .field-row { flex-direction: column; gap: 15px; align-items: stretch; margin-bottom: 15px; }
  .field { width: 100%; }
  .remove-row-btn { align-self: flex-end; margin-top: -10px; }
  .action-buttons { flex-direction: column; gap: 15px; }
  .submit-btn, .reset-btn, .add-row-btn { width: 100%; }
  .title { font-size: 18px; }
  .product-wrapper { padding: 15px; }
}

/* iPhone SE specific */
@media (max-width: 380px) {
  .card { padding: 15px; }
  .color-palette { gap: 5px; overflow-x: auto; padding-bottom: 5px; }
  .color-circle { width: 24px; height: 24px; }
}
</style>