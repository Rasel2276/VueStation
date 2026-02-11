<template>
  <div class="page">
    <div class="card">
      <h2 class="title">Supplier Product Return History</h2>

      <div class="table-wrapper">
        <div class="search-container">
          <input
            type="text"
            v-model="search"
            placeholder="Search return history..."
            class="search-input"
          />
        </div>

        <div class="table-responsive">
          <table class="custom-category-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Product</th>
                <th>Supplier</th>
                <th>Total Returned Qty</th>
                <th>Reason</th>
                <th>Actions</th>
              </tr>
            </thead>

            <tbody>
              <tr v-for="item in filteredReturns" :key="item.id" class="relative-row">
                <td data-label="ID">#{{ item.id }}</td>
                <td data-label="Product" class="bold-text">{{ item.product_name }}</td>
                <td data-label="Supplier">{{ item.supplier_name }}</td>
                <td data-label="Returned Qty">
                  <span class="qty-badge">{{ item.quantity }}</span>
                </td>
                <td data-label="Reason" class="reason-cell">{{ item.reason || 'N/A' }}</td>
                
                <td data-label="Actions" class="action-cell">
                  <button @click="toggleDropdown(item.id, $event)" class="dropdown-btn">
                    Actions ▾
                  </button>
                  
                  <teleport to="body">
                    <transition name="fade">
                      <div 
                        v-if="dropdownOpen === item.id" 
                        class="dropdown-menu-absolute"
                        :style="dropdownPosition"
                      >
                        <button @click="viewReason(item)" class="edit-btn">
                          👁️ View Reason
                        </button>
                        <button @click="deleteReturn(item.id)" class="delete-btn">
                          🗑️ Delete
                        </button>
                      </div>
                    </transition>
                  </teleport>
                </td>
              </tr>
              <tr v-if="filteredReturns.length === 0">
                <td colspan="6" class="no-data">No return records found.</td>
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

const search = ref('')
const returns = ref([])
const dropdownOpen = ref(null)
const dropdownPosition = ref({})
const token = localStorage.getItem('token')

const fetchReturns = async () => {
  try {
    const res = await axios.get('http://127.0.0.1:8000/api/admin/purchase/return-history', {
      headers: { Authorization: `Bearer ${token}` }
    })
    returns.value = res.data.map(r => ({
      id: r.id,
      product_name: r.product?.product_name || 'Unknown',
      supplier_name: r.supplier?.supplier_name || 'Unknown',
      quantity: Number(r.quantity),
      reason: r.reason || ''
    }))
  } catch (err) {
    console.error(err.response?.data || err)
  }
}

onMounted(fetchReturns)

const filteredReturns = computed(() => {
  if (!search.value.trim()) return returns.value
  const s = search.value.toLowerCase()
  return returns.value.filter(r =>
    r.product_name.toLowerCase().includes(s) ||
    r.supplier_name.toLowerCase().includes(s) ||
    r.reason.toLowerCase().includes(s)
  )
})

const toggleDropdown = async (id, event) => {
  if (dropdownOpen.value === id) {
    dropdownOpen.value = null
    return
  }
  dropdownOpen.value = id
  await nextTick()
  
  const rect = event.target.getBoundingClientRect()
  let leftPos = rect.left + window.scrollX - 100
  if (leftPos + 150 > window.innerWidth) leftPos = window.innerWidth - 160

  dropdownPosition.value = {
    position: "absolute",
    top: `${rect.bottom + window.scrollY + 5}px`,
    left: `${leftPos}px`,
    zIndex: 9999
  }
}

const viewReason = (item) => {
  alert(`Return Reason: ${item.reason || 'No reason provided'}`)
  dropdownOpen.value = null
}

const deleteReturn = async (id) => {
  if (!confirm('Are you sure you want to delete this return?')) return
  try {
    await axios.delete(`http://127.0.0.1:8000/api/admin/purchase/return/${id}`, {
      headers: { Authorization: `Bearer ${token}` }
    })
    returns.value = returns.value.filter(r => r.id !== id)
    alert('Return deleted successfully')
    dropdownOpen.value = null
  } catch (err) {
    alert('Failed to delete return')
  }
}

window.addEventListener('click', (e) => {
  if (!e.target.classList.contains('dropdown-btn')) {
    dropdownOpen.value = null
  }
})
</script>

<style scoped>
/* 🔒 DESKTOP DESIGN */
.page { 
  min-height: 100vh; 
  display: flex; 
  justify-content: center; 
  align-items: flex-start; 
  padding: 40px 15px; 
  background-color: #f8fafc; 
}

.card { 
  width: 100%; 
  max-width: 1000px; 
  background: #fff; 
  border-radius: 12px; 
  padding: 2rem; 
  box-shadow: 0 4px 15px rgba(0,0,0,0.05); 
}

.title { 
  text-align: center; 
  font-size: 26px; 
  margin-bottom: 30px; 
  font-weight: 700; 
  color: #1e293b; 
}

/* Default Desktop Search Style */
.search-input { 
  width: 100%; 
  max-width: 300px; 
  padding: 0.7rem 1rem; 
  margin-bottom: 1.5rem; 
  border: 1px solid #e2e8f0; 
  border-radius: 8px; 
  outline: none;
  font-size: 14px;
}

.table-responsive { width: 100%; overflow-x: auto; }
.custom-category-table { width: 100%; border-collapse: collapse; }
.custom-category-table th, .custom-category-table td { padding: 14px 15px; text-align: left; border-bottom: 1px solid #f1f5f9; font-size: 14px; }
.custom-category-table th { background-color: #f8fafc; font-weight: 600; color: #475569; }

.qty-badge { background: #f1f5f9; padding: 4px 8px; border-radius: 4px; font-weight: 700; color: #2563eb; }
.dropdown-btn { padding: 6px 14px; border: none; border-radius: 6px; background: #2563eb; color: white; cursor: pointer; font-weight: 600; font-size: 13px; }
.dropdown-menu-absolute { background: #ffffff; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); border: 1px solid #e2e8f0; border-radius: 8px; min-width: 160px; }
.dropdown-menu-absolute button { display: block; width: 100%; border: none; background: none; padding: 12px 15px; cursor: pointer; text-align: left; font-size: 13px; }
.edit-btn { color: #059669; border-bottom: 1px solid #f1f5f9; }
.delete-btn { color: #dc2626; }

/* 📱 MOBILE RESPONSIVE FIX */
@media (max-width: 850px) {
  .page { padding: 15px 10px; }
  .card { padding: 1.5rem 1rem; }
  
  /* Search field now fits perfectly on mobile */
  .search-input { 
    max-width: 100%; 
    box-sizing: border-box; 
    margin-bottom: 1rem;
    padding: 12px;
  }
  
  .custom-category-table thead { display: none; }
  .custom-category-table tr { display: block; border: 1px solid #e2e8f0; margin-bottom: 15px; border-radius: 12px; padding: 10px; background: #fff; }
  .custom-category-table td { display: flex; justify-content: flex-start; align-items: center; padding: 10px 8px; border-bottom: 1px solid #f8fafc; }
  .custom-category-table td:last-child { border-bottom: none; }
  .custom-category-table td::before { content: attr(data-label); font-weight: 700; color: #64748b; font-size: 11px; text-transform: uppercase; width: 40%; flex-shrink: 0; }
  .bold-text { font-weight: 600; color: #1e293b; }
}

.fade-enter-active, .fade-leave-active { transition: opacity 0.2s, transform 0.2s; }
.fade-enter-from, .fade-leave-to { opacity: 0; transform: translateY(-5px); }
</style>