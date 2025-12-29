<template>
  <div class="page">
    <div class="card">
      <h2 class="title">Vendor Payment</h2>

      <form class="form" @submit.prevent="submitFinalPurchase">
        <div v-for="(purchase, index) in purchases" :key="index" class="purchase-section">
          <div class="field-row">
            <div class="field">
              <label>Product</label>
              <input type="text" :value="purchase.product_name" readonly />
            </div>

            <div class="field">
              <label>Quantity</label>
              <input type="number" :value="purchase.quantity" readonly />
            </div>

            <div class="field">
              <label>Price</label>
              <input type="number" :value="purchase.price" readonly />
            </div>

            <div class="field">
              <label>Total</label>
              <input type="number" :value="purchaseTotal(purchase)" readonly />
            </div>
          </div>
          <hr />
        </div>

        <div class="field-row" style="justify-content: flex-end; margin-top: 20px;">
          <div class="field" style="flex: 0 0 300px;">
            <label>Grand Total</label>
            <input type="number" :value="grandTotal" readonly style="font-weight: bold; color: #1e40af;" />
          </div>
        </div>

        <div class="field-row" style="margin-top: 15px; justify-content: flex-end;">
          <div class="field" style="flex: 0 0 300px;">
            <label>Payment Method</label>
            <select v-model="payment_method" required>
              <option value="">Select Method</option>
              <option value="cash">Cash</option>
              <option value="bank">Bank</option>
              <option value="card">Card</option>
            </select>
          </div>
        </div>

        <div class="action-buttons" style="justify-content: flex-end; margin-top: 20px;">
          <button type="button" @click="$router.back()" class="submit-btn" style="background: #6b7280; margin-right: 10px;">Back</button>
          <button type="submit" class="submit-btn" :disabled="loading">
            {{ loading ? 'Processing...' : 'Pay Now' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { useRouter } from "vue-router";
import axios from "axios";

const router = useRouter();
const purchases = ref([]);
const payment_method = ref("");
const loading = ref(false);
const token = localStorage.getItem("vendortoken") || localStorage.getItem("token");

onMounted(() => {
  const stateData = window.history.state?.purchaseItems;
  if (!stateData) {
    router.push('/vendor-inventory/vendor_purchase');
  } else {
    purchases.value = stateData;
  }
});

const purchaseTotal = (p) => p.quantity * p.price;

const grandTotal = computed(() =>
  purchases.value.reduce((sum, p) => sum + purchaseTotal(p), 0)
);

const submitFinalPurchase = async () => {
  if (!payment_method.value) return alert("পেমেন্ট মেথড সিলেক্ট করুন");
  
  loading.value = true;
  try {
    const payload = purchases.value.map(item => ({
      admin_stock_id: item.admin_stock_id,
      quantity: item.quantity,
      price: item.price
    }));

    const res = await axios.post("http://127.0.0.1:8000/api/vendor/purchases", payload, {
      headers: { Authorization: `Bearer ${token}` }
    });

    alert(res.data.message || "Purchase Successful!");
    router.push('/vendor-inventory/vendor_purchase-record');
  } catch (err) {
    alert("Error: " + (err.response?.data?.message || "সাবমিট ব্যর্থ হয়েছে"));
  } finally {
    loading.value = false;
  }
};
</script>

<style scoped>
.page { padding: 20px; min-height: 70vh; font-family: "Segoe UI", sans-serif; }
.card { background: #fff; padding: 30px; border-radius: 12px; max-width: 1000px; margin: auto; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); }
.title { text-align: center; font-size: 22px; font-weight: 600; margin-bottom: 25px; }
.field-row { display: flex; gap: 15px; align-items: flex-end; margin-bottom: 10px; }
.field { flex: 1; display: flex; flex-direction: column; }
.field label { font-weight: bold; margin-bottom: 5px; font-size: 13px; }
.field input, .field select { padding: 10px; border: 1px solid #ccc; border-radius: 4px; }
.submit-btn { background: #3b82f6; color: white; border: none; padding: 10px 25px; border-radius: 5px; cursor: pointer; }
hr { border: none; border-top: 1px solid #eee; margin: 10px 0; }
</style>