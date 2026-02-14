<template>
  <div class="page">
    <div class="card">
      <h2 class="title">My Purchases</h2>

      <div class="table-wrapper">
        <input
          type="text"
          v-model="search"
          placeholder="Search by product name..."
          class="search-input"
        />

        <div class="table-responsive">
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
                <td data-label="ID">#{{ purchase.id }}</td>
                <td data-label="Product Name" style="font-weight:600">{{ purchase.product_name }}</td>
                <td data-label="Quantity">{{ purchase.quantity }}</td>
                <td data-label="Price">{{ purchase.price }}</td>
                <td data-label="Total">{{ (purchase.quantity * purchase.price).toFixed(2) }}</td>
                <td data-label="Image">
                  <img
                    v-if="purchase.product_image"
                    :src="imageUrl(purchase.product_image)"
                    class="category-image"
                  />
                  <span v-else>No Image</span>
                </td>
                <td data-label="Purchase Date">{{ formatDate(purchase.purchase_date) }}</td>
                <td data-label="Actions">
                  <button
                    class="dropdown-btn"
                    @click="toggleDropdown(purchase.id, $event)"
                  >
                    Actions ▾
                  </button>

                  <teleport to="body">
                    <transition name="fade">
                      <div
                        v-if="dropdownOpen === purchase.id"
                        class="dropdown-menu-absolute"
                        :style="dropdownPosition"
                      >
                        <button
                          class="edit-btn"
                          @click="viewPurchase(purchase)"
                        >
                          View Details
                        </button>

                        <button
                          class="delete-btn"
                          @click="deletePurchase(purchase.id)"
                        >
                          Delete Record
                        </button>
                      </div>
                    </transition>
                  </teleport>
                </td>
              </tr>

              <tr v-if="filteredPurchases.length === 0">
                <td colspan="8" class="no-data">
                  No purchases found.
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, nextTick } from 'vue'
import axios from 'axios'
import api, { BASE_URL } from '../../../axios';

const search = ref('')
const purchases = ref([])
const dropdownOpen = ref(null)
const dropdownPosition = ref({})

const token = localStorage.getItem('vendortoken') || localStorage.getItem('token')

const fetchPurchases = async () => {
  try {
    const res = await api.get('/vendor/purchases', {
      headers: { Authorization: `Bearer ${token}` }
    })
    purchases.value = res.data.map(p => ({
      id: p.id,
      product_name: p.admin_stock?.product?.product_name || 'Unknown',
      product_image: p.admin_stock?.product?.product_image || null,
      quantity: p.quantity,
      price: p.price,
     
      purchase_date: p.purchase_date 
    }))
  } catch (err) {
    console.error(err)
  }
}

onMounted(fetchPurchases)

const filteredPurchases = computed(() => {
  if (!search.value.trim()) return purchases.value
  return purchases.value.filter(p =>
    p.product_name.toLowerCase().includes(search.value.toLowerCase())
  )
})

const formatDate = date => {
  if (!date) return 'N/A'
  const d = new Date(date)
  if (isNaN(d.getTime())) return date
  return d.toLocaleDateString() + ' ' + d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
}

const imageUrl = img => `${BASE_URL}/product_images/${img}`

const toggleDropdown = async (id, event) => {
  if (dropdownOpen.value === id) {
    dropdownOpen.value = null
    return
  }

  dropdownOpen.value = id
  await nextTick()

  const rect = event.target.getBoundingClientRect()
  const dropdownWidth = 140
  let leftPos = rect.left + window.scrollX - 50
  
  if (leftPos + dropdownWidth > window.innerWidth) {
    leftPos = window.innerWidth - dropdownWidth - 15
  }
  if (leftPos < 15) leftPos = 15

  dropdownPosition.value = {
    position: 'absolute',
    top: `${rect.bottom + window.scrollY}px`,
    left: `${leftPos}px`,
    zIndex: 9999
  }
}

const viewPurchase = purchase => {
  alert(`Product: ${purchase.product_name}\nQuantity: ${purchase.quantity}\nPrice: ${purchase.price}`)
  dropdownOpen.value = null
}

const deletePurchase = async id => {
  if (!confirm('Are you sure?')) return
  try {
    await api.delete(`/vendor/purchases/${id}`, {
      headers: { Authorization: `Bearer ${token}` }
    })
    purchases.value = purchases.value.filter(p => p.id !== id)
    dropdownOpen.value = null
  } catch (err) {
    alert('Delete failed')
  }
}
</script>

<style scoped>
/* 🔒 DESKTOP DESIGN - 100% UNCHANGED */
.page {
  min-height: 100vh;
  display: flex;
  justify-content: center;
  align-items: flex-start;
  padding: 40px 0;
  font-family: "Segoe UI", sans-serif;
  box-sizing: border-box;
}
.card {
  width: 100%;
  max-width: 1000px;
  background: #fff;
  border-radius: 8px;
  padding: 2rem;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  box-sizing: border-box;
}
.title {
  text-align: center;
  font-size: 26px;
  font-weight: 500;
  margin-bottom: 30px;
  color: #222;
}
.search-input {
  width: 100%;
  max-width: 220px;
  padding: 0.5rem 1rem;
  margin-bottom: 1rem;
  border: 1px solid #d2d6da;
  border-radius: .375rem;
  box-sizing: border-box;
}
.table-responsive {
  width: 100%;
  border-radius: 8px;
  background: white;
  box-sizing: border-box;
}
.custom-category-table {
  width: 100%;
  border-collapse: collapse;
  min-width: 600px;
}
.custom-category-table th,
.custom-category-table td {
  padding: 12px 15px;
  text-align: left;
  border-bottom: 1px solid #e5e7eb;
  font-size: 14px;
}
.custom-category-table th {
  background-color: #f3f4f6;
  font-weight: 600;
}
.category-image {
  width: 50px;
  height: 50px;
  object-fit: cover;
  border-radius: 4px;
}
.dropdown-btn {
  padding: 6px 12px;
  border: none;
  border-radius: 4px;
  background: #2563eb;
  color: white;
  cursor: pointer;
  font-weight: 600;
}
.dropdown-menu-absolute {
  background: #f9fafb;
  box-shadow: 0 4px 8px rgba(0,0,0,0.15);
  border-radius: 6px;
  min-width: 140px;
}
.dropdown-menu-absolute button {
  display: block;
  width: 100%;
  border: none;
  background: none;
  padding: 10px;
  cursor: pointer;
  text-align: left;
  font-size: 14px;
}
.edit-btn { color: #065f46; }
.delete-btn { color: #b91c1c; }
.no-data { text-align: center; padding: 20px; }

.fade-enter-active, .fade-leave-active { transition: all 0.2s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; transform: translateY(-5px); }

/* 📱 MOBILE RESPONSIVE - PERFECT FIT */
@media (max-width: 850px) {
  .page { padding: 15px; } 
  .card { 
    padding: 15px; 
    border-radius: 12px; 
    max-width: 100%; 
    margin: 0 auto;
  }
  .title { font-size: 20px; margin-bottom: 20px; }
  .search-input { max-width: 100%; }
  
  .table-wrapper { width: 100%; overflow: hidden; }
  .table-responsive { overflow: visible; padding: 0; }

  .custom-category-table { min-width: 100%; }
  .custom-category-table thead { display: none; }
  
  .custom-category-table tr { 
    display: block; 
    border: 1px solid #e2e8f0; 
    margin-bottom: 15px; 
    border-radius: 10px; 
    padding: 8px; 
    background: #fff;
    box-shadow: 0 2px 5px rgba(0,0,0,0.02);
  }
  
  .custom-category-table td { 
    display: flex; 
    justify-content: space-between; 
    align-items: center; 
    text-align: right; 
    border-bottom: 1px solid #f1f5f9; 
    padding: 10px 8px; 
    font-size: 13px;
    word-break: break-word; 
  }
  
  .custom-category-table td:last-child { border-bottom: none; }
  
  .custom-category-table td::before { 
    content: attr(data-label); 
    font-weight: 700; 
    color: #64748b; 
    text-transform: uppercase; 
    font-size: 10px; 
    text-align: left; 
    flex: 1; 
    margin-right: 10px;
  }
  
  .category-image { width: 40px; height: 40px; }
  .dropdown-btn { width: auto; padding: 5px 12px; }
}

@media (max-width: 380px) {
  .custom-category-table td { font-size: 12px; padding: 8px 5px; }
  .custom-category-table td::before { font-size: 9px; }
}
</style>