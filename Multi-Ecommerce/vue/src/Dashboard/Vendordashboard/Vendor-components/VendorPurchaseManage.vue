<template>
  <div class="page">
    <div class="card">
      <h2 class="title">My Purchases</h2>

      <div class="table-responsive" ref="tableWrapper">
        <input type="text" v-model="search" placeholder="Search" class="search-input" />

        <table class="custom-category-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Product Name</th>
              <th>Quantity</th>
              <th>Price</th>
              <th>Total</th>
              <th>Image</th>
              <th>Purchase Date</th>
              <th>Actions</th>
            </tr>
          </thead>

          <tbody>
            <tr v-for="purchase in filteredPurchases" :key="purchase.id">
              <td>{{ purchase.id }}</td>
              <td>{{ purchase.product_name }}</td>
              <td>{{ purchase.quantity }}</td>
              <td>{{ purchase.price }}</td>
              <td>{{ purchase.total }}</td>
              <td>
                <img v-if="purchase.product_image" :src="imageUrl(purchase.product_image)" class="category-image" />
              </td>
              <td>{{ formatDate(purchase.purchase_date) }}</td>
              <td>
                <button @click="toggleDropdown(purchase.id)" class="dropdown-btn">Actions ▾</button>
                <teleport to="body">
                  <transition name="fade">
                    <div
                      v-if="dropdownOpen === purchase.id"
                      class="dropdown-menu-absolute"
                      :style="dropdownPosition"
                    >
                      <button @click="viewPurchase(purchase)" class="edit-btn">View</button>
                      <button @click="deletePurchase(purchase.id)" class="delete-btn">Delete</button>
                    </div>
                  </transition>
                </teleport>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, nextTick } from 'vue'
import axios from 'axios'

const search = ref('')
const purchases = ref([])
const dropdownOpen = ref(null)
const dropdownPosition = ref({})
const token = localStorage.getItem('token')
const tableWrapper = ref(null)

// Fetch vendor purchases
const fetchPurchases = async () => {
  try {
    const res = await axios.get(`http://127.0.0.1:8000/api/vendor/purchases`, {
      headers: { Authorization: `Bearer ${token}` }
    })
    purchases.value = res.data.map(p => ({
      id: p.id,
      product_name: p.adminStock.product_name,
      product_image: p.adminStock.product_image,
      quantity: p.quantity,
      price: p.price,
      total: p.quantity * p.price,
      purchase_date: p.created_at
    }))
  } catch (err) {
    console.error(err.response?.data || err)
    alert('Failed to fetch purchases')
  }
}

onMounted(fetchPurchases)

// Filtered purchases
const filteredPurchases = computed(() => {
  if (!search.value.trim()) return purchases.value
  const s = search.value.toLowerCase()
  return purchases.value.filter(p =>
    p.product_name.toLowerCase().includes(s)
  )
})

// Format date
const formatDate = (date) => {
  const d = new Date(date)
  return d.toLocaleString()
}

// Image URL
const imageUrl = (filename) => `http://127.0.0.1:8000/product_images/${filename}`

// Dropdown toggle
const toggleDropdown = async (id, event) => {
  if (dropdownOpen.value === id) {
    dropdownOpen.value = null
    return
  }

  dropdownOpen.value = id
  await nextTick()

  const button = event?.target || document.querySelector('.dropdown-btn')
  if (!button) return

  const rect = button.getBoundingClientRect()
  dropdownPosition.value = {
    position: 'absolute',
    top: `${rect.bottom + window.scrollY}px`,
    left: `${rect.left + window.scrollX}px`,
    zIndex: 9999
  }
}

// View purchase (placeholder)
const viewPurchase = (purchase) => {
  alert(`Viewing details for "${purchase.product_name}"`)
}

// Delete purchase
const deletePurchase = async (id) => {
  if (!confirm('Are you sure you want to delete this purchase?')) return
  try {
    await axios.delete(`http://127.0.0.1:8000/api/vendor/purchases/${id}`, {
      headers: { Authorization: `Bearer ${token}` }
    })
    purchases.value = purchases.value.filter(p => p.id !== id)
    alert('Purchase deleted successfully')
    dropdownOpen.value = null
  } catch (err) {
    console.error(err.response?.data || err)
    alert('Failed to delete purchase')
  }
}
</script>

<style scoped>
.page {
  min-height: 100vh;
  display:flex;
  justify-content:center;
  align-items:flex-start;
  padding:40px 0;
  font-family:"Segoe UI", sans-serif;
}
.card {
  width:100%;
  max-width:1000px;
  background:#fff;
  border-radius:8px;
  padding:2rem;
  box-shadow:0 2px 4px rgba(0,0,0,0.1);
}
.title {
  text-align:center;
  font-size:26px;
  font-weight:500;
  margin-bottom:30px;
  color:#222;
}
.search-input {
  width:100%;
  max-width:220px;
  padding:0.5rem 1rem;
  margin-bottom:1rem;
  border:1px solid #d2d6da;
  border-radius:.375rem;
}
.table-responsive {
  width:100%;
  overflow-x:auto;
  border-radius:8px;
  box-shadow:0 2px 4px rgba(0,0,0,0.05);
  background:white;
  padding:0.5rem;
}
.custom-category-table {
  width:100%;
  border-collapse:collapse;
  min-width:600px;
}
.custom-category-table th,
.custom-category-table td {
  padding:12px 15px;
  text-align:left;
  border-bottom:1px solid #e5e7eb;
  font-size:14px;
}
.custom-category-table th {
  background-color:#f3f4f6;
  font-weight:600;
}
.custom-category-table tbody tr:hover {
  background-color:#d0e2ff;
}
.category-image {
  width:50px;
  height:50px;
  object-fit:cover;
  border-radius:4px;
}
.dropdown-btn {
  padding:6px 12px;
  border:none;
  border-radius:4px;
  background:#2563eb;
  color:white;
  cursor:pointer;
  font-weight:600;
}
.dropdown-btn:hover {
  background:#1e40af;
}
.dropdown-menu-absolute {
  background:#f9fafb;
  box-shadow:0 4px 8px rgba(0,0,0,0.15);
  border-radius:6px;
  overflow:hidden;
  min-width:140px;
}
.dropdown-menu-absolute button {
  display:block;
  width:100%;
  border:none;
  background:none;
  padding:10px;
  cursor:pointer;
  text-align:left;
  font-size:14px;
  transition:0.2s;
}
.edit-btn {
  color:#065f46;
}
.delete-btn {
  color:#b91c1c;
}
.dropdown-menu-absolute button:hover {
  background:#e0e7ff;
}
.fade-enter-active, .fade-leave-active {
  transition: all 0.2s ease;
}
.fade-enter-from, .fade-leave-to {
  opacity:0;
  transform: translateY(-5px);
}
</style>
