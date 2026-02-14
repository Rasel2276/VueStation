<template>
  <div class="page">
    <div class="card">
      <h2 class="title">Vendor Multi Product Purchase</h2>

      <form class="form" @submit.prevent="goToPayment">
        <div v-for="(purchase, index) in purchases" :key="index" class="purchase-section">
          <div class="field-row">
            <div class="field">
              <label>Product</label>
              <select v-model.number="purchase.admin_stock_id" @change="setPrice(index)" required>
                <option value="">Select Product</option>
                <option v-for="stock in adminStocks" :key="stock.id" :value="stock.id">
                  {{ stock.product_name }} (Available: {{ stock.available_stock }})
                </option>
              </select>
            </div>

            <div class="field">
              <label>Quantity</label>
              <input type="number" v-model.number="purchase.quantity" min="1" required />
            </div>

            <div class="field">
              <label>Price</label>
              <input type="number" :value="purchase.price" readonly />
            </div>

            <div class="field">
              <label>Total</label>
              <input type="number" :value="purchaseTotal(purchase)" readonly />
            </div>

            <button v-if="purchases.length > 1" type="button" @click="purchases.splice(index, 1)" class="remove-btn">
              ×
            </button>
          </div>
          <hr />
        </div>

        <div class="action-buttons">
          <button type="button" class="add-btn" @click="addRow">
            + Add Another Product
          </button>
          <button class="submit-btn" type="submit">Proceed to Payment</button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";
import axios from "axios";
import api, { BASE_URL } from '../../../axios';

const router = useRouter();
const purchases = ref([{ admin_stock_id: "", product_name: "", quantity: 1, price: 0 }]);
const adminStocks = ref([]);
const token = localStorage.getItem("vendortoken") || localStorage.getItem("token");

const addRow = () => {
  purchases.value.push({ admin_stock_id: "", product_name: "", quantity: 1, price: 0 });
};

const purchaseTotal = (p) => (Number(p.quantity) || 0) * (Number(p.price) || 0);

const fetchAdminStocks = async () => {
  try {
    const res = await api.get("/vendor/admin-stocks", {
      headers: { Authorization: `Bearer ${token}` },
    });
    adminStocks.value = res.data;
  } catch (err) {
    console.error("Fetch Error:", err);
  }
};

const setPrice = (index) => {
  const selectedId = purchases.value[index].admin_stock_id;
  const stock = adminStocks.value.find((s) => s.id === selectedId);
  if (stock) {
    purchases.value[index].price = stock.price;
    purchases.value[index].product_name = stock.product_name;
  }
};

const goToPayment = () => {
  const isValid = purchases.value.every((p) => p.admin_stock_id && p.quantity > 0);
  if (!isValid) return alert("সবগুলো ঘর ঠিকভাবে পূরণ করুন");

  router.push({
    path: '/vendor-inventory/vendor_purchase-payment',
    state: { purchaseItems: JSON.parse(JSON.stringify(purchases.value)) }
  });
};

onMounted(fetchAdminStocks);
</script>

<style scoped>
.page { padding: 40px; min-height: 100vh; font-family: "Segoe UI", sans-serif; }
.card { background: #fff; padding: 30px; border-radius: 12px; max-width: 1000px; margin: auto; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); }
.title { text-align: center; font-size: 22px; font-weight: 600; margin-bottom: 25px; }
.field-row { display: flex; gap: 15px; align-items: flex-end; margin-bottom: 10px; }
.field { flex: 1; display: flex; flex-direction: column; }
.field label { font-weight: bold; margin-bottom: 5px; font-size: 13px; }
.field input, .field select { padding: 10px; border: 1px solid #ccc; border-radius: 4px; width: 100%; box-sizing: border-box; }
.action-buttons { display: flex; justify-content: space-between; margin-top: 20px; gap: 10px; }
.add-btn { background: #10b981; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; }
.submit-btn { background: #3b82f6; color: white; border: none; padding: 10px 25px; border-radius: 5px; cursor: pointer; }
.remove-btn { background: #ef4444; color: white; border: none; width: 30px; height: 38px; border-radius: 4px; cursor: pointer; }
hr { border: none; border-top: 1px solid #eee; margin: 10px 0; }

/* Mobile Responsiveness for iPhone SE and others */
@media (max-width: 768px) {
  .page { padding: 15px; }
  .card { padding: 20px; }
  .field-row { 
    flex-direction: column; 
    align-items: stretch; 
    gap: 12px; 
    position: relative; 
    padding-top: 30px; /* স্পেস রাখা হয়েছে যাতে রিমুভ বাটন ইনপুটের উপর না পড়ে */
  }
  .remove-btn { 
    position: absolute; 
    top: 0; 
    right: 0; 
    width: 30px; 
    height: 30px; 
    border-radius: 50%; /* গোল করা হয়েছে দেখতে সুন্দর লাগার জন্য */
    display: flex; 
    align-items: center; 
    justify-content: center; 
    z-index: 10;
  }
  .action-buttons { flex-direction: column; }
  .add-btn, .submit-btn { width: 100%; }
}
</style>