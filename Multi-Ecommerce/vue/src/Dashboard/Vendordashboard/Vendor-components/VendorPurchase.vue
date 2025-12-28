<template>
  <div class="page">
    <div class="card">
      <h2 class="title">Vendor Multi Product Purchase</h2>

      <form class="form" @submit.prevent="submitForm">
        <div v-for="(purchase, index) in purchases" :key="index" class="purchase-section">
          <div class="field-row">
            <div class="field">
              <label>Product</label>
              <select v-model.number="purchase.admin_stock_id" @change="setPrice(index)">
                <option value="">Select Product</option>
                <option v-for="stock in adminStocks" :key="stock.id" :value="stock.id">
                  {{ stock.product_name }} (Available: {{ stock.available_stock }})
                </option>
              </select>
            </div>

            <div class="field">
              <label>Quantity</label>
              <input type="number" v-model.number="purchase.quantity" min="1" />
            </div>

            <div class="field">
              <label>Price</label>
              <input type="number" :value="purchase.price" readonly />
            </div>

            <div class="field">
              <label>Total</label>
              <input type="number" :value="purchaseTotal(purchase)" readonly />
            </div>
            
            <button v-if="purchases.length > 1" type="button" @click="purchases.splice(index, 1)" class="remove-btn">×</button>
          </div>
          <hr />
        </div>

        <div class="action-buttons">
          <button type="button" class="add-btn" @click="addRow">+ Add Another Product</button>
          <button class="submit-btn" type="submit">Submit All Purchases</button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const purchases = ref([{ admin_stock_id: '', quantity: 1, price: 0 }])
const adminStocks = ref([])

// টোকেন নাম যাই হোক এটা হ্যান্ডেল করবে
const getAuthToken = () => localStorage.getItem('vendortoken') || localStorage.getItem('token');

const addRow = () => {
  purchases.value.push({ admin_stock_id: '', quantity: 1, price: 0 })
}

const purchaseTotal = (p) => (Number(p.quantity) || 0) * (Number(p.price) || 0)

const fetchAdminStocks = async () => {
  try {
    const token = getAuthToken();
    const res = await axios.get('http://127.0.0.1:8000/api/vendor/admin-stocks', {
      headers: { Authorization: `Bearer ${token}` }
    })
    adminStocks.value = res.data
  } catch (err) {
    console.error('Fetch Error:', err)
  }
}

const setPrice = (index) => {
  const selectedId = purchases.value[index].admin_stock_id
  const stock = adminStocks.value.find(s => s.id === selectedId)
  if (stock) {
    purchases.value[index].price = stock.price
  }
}

const submitForm = async () => {
  const isValid = purchases.value.every(p => p.admin_stock_id && p.quantity > 0)
  if (!isValid) return alert('সবগুলো ঘর ঠিকভাবে পূরণ করুন')

  try {
    const token = getAuthToken();
    await axios.post('http://127.0.0.1:8000/api/vendor/purchases', purchases.value, {
      headers: { Authorization: `Bearer ${token}` }
    })

    alert('সবগুলো পারচেজ সফলভাবে সম্পন্ন হয়েছে!')
    purchases.value = [{ admin_stock_id: '', quantity: 1, price: 0 }]
  } catch (err) {
    alert('এরর: ' + (err.response?.data?.message || 'সাবমিট ব্যর্থ হয়েছে'))
  }
}

onMounted(fetchAdminStocks)
</script>

<style scoped>
.page { padding: 40px; background: #f3f4f6; min-height: 100vh; }
.card { background: #fff; padding: 30px; border-radius: 12px; max-width: 1000px; margin: auto; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
.field-row { display: flex; gap: 15px; align-items: flex-end; margin-bottom: 10px; }
.field { flex: 1; display: flex; flex-direction: column; }
.field label { font-weight: bold; margin-bottom: 5px; font-size: 13px; }
.field input, .field select { padding: 10px; border: 1px solid #ccc; border-radius: 4px; }
.action-buttons { display: flex; justify-content: space-between; margin-top: 20px; }
.add-btn { background: #10b981; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; }
.submit-btn { background: #3b82f6; color: white; border: none; padding: 10px 25px; border-radius: 5px; cursor: pointer; }
.remove-btn { background: #ef4444; color: white; border: none; width: 30px; height: 38px; border-radius: 4px; cursor: pointer; }
</style>