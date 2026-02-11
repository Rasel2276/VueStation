<template>
  <div class="page">
    <div class="card">
      <h2 class="title">Vendor Sales Report</h2>

      <div class="table-wrapper">
        <div class="search-container">
          <input
            type="text"
            v-model="search"
            placeholder="Search by vendor, price or status..."
            class="search-input"
          />
        </div>

        <div class="table-responsive">
          <table class="custom-category-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Vendor Name</th>
                <th>Product ID</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Total</th>
                <th>Status</th>
                <th>Date</th>
                <th>Actions</th>
              </tr>
            </thead>

            <tbody>
              <tr v-for="sale in filteredSales" :key="sale.id">
                <td data-label="ID">#{{ sale.id }}</td>
                <td data-label="Vendor" class="bold-text">{{ sale.vendor?.name || 'Unknown Vendor' }}</td>
                <td data-label="Stock ID">#{{ sale.admin_stock_id }}</td>
                <td data-label="Qty">
                  <span class="qty-badge">{{ sale.quantity }}</span>
                </td>
                <td data-label="Price">{{ sale.price }}</td>
                <td data-label="Total" class="total-amount">{{ sale.total }}</td>
                <td data-label="Status">
                  <span :class="['status-badge', sale.status?.toLowerCase()]">
                    {{ sale.status }}
                  </span>
                </td>
                <td data-label="Date">{{ formatDate(sale.purchase_date) }}</td>
                
                <td data-label="Actions" class="action-cell">
                  <button @click="toggleDropdown(sale.id, $event)" class="dropdown-btn">
                    Actions ▾
                  </button>
                  
                  <teleport to="body">
                    <transition name="fade">
                      <div 
                        v-if="dropdownOpen === sale.id" 
                        class="dropdown-menu-absolute"
                        :style="dropdownPosition"
                      >
                        <button @click="updateStatus(sale.id, 'Completed')" class="edit-btn">
                          ✅ Mark Completed
                        </button>
                        <button @click="updateStatus(sale.id, 'Cancelled')" class="delete-btn">
                          ❌ Cancel Order
                        </button>
                      </div>
                    </transition>
                  </teleport>
                </td>
              </tr>
              <tr v-if="filteredSales.length === 0">
                <td colspan="9" class="no-data">No sales data found.</td>
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
const sales = ref([])
const dropdownOpen = ref(null)
const dropdownPosition = ref({})
const token = localStorage.getItem('token')

// Fetch Sales Data
const fetchSales = async () => {
  try {
    const res = await axios.get('http://127.0.0.1:8000/api/sales-report', {
      headers: { Authorization: `Bearer ${token}` }
    })
    sales.value = res.data
  } catch (err) {
    console.error(err.response?.data || err)
  }
}

onMounted(fetchSales)

// Search Filter
const filteredSales = computed(() => {
  if (!search.value.trim()) return sales.value
  const s = search.value.toLowerCase()
  return sales.value.filter(sale =>
    sale.vendor?.name?.toLowerCase().includes(s) ||
    sale.status.toLowerCase().includes(s) ||
    sale.total.toString().includes(s)
  )
})

// Date Formatting
const formatDate = (dateStr) => {
  const d = new Date(dateStr)
  return d.toLocaleDateString() + ' ' + d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
}

// Dropdown Logic
const toggleDropdown = async (id, event) => {
  if (dropdownOpen.value === id) {
    dropdownOpen.value = null
    return
  }
  dropdownOpen.value = id
  await nextTick()
  
  const rect = event.target.getBoundingClientRect()
  let leftPos = rect.left + window.scrollX - 100
  if (leftPos + 160 > window.innerWidth) leftPos = window.innerWidth - 170

  dropdownPosition.value = {
    position: "absolute",
    top: `${rect.bottom + window.scrollY + 5}px`,
    left: `${leftPos}px`,
    zIndex: 9999
  }
}

// Update Status (Example Action)
const updateStatus = async (id, newStatus) => {
  try {
    // API endpoint call logic here
    alert(`Order #${id} marked as ${newStatus}`)
    dropdownOpen.value = null
    fetchSales() // Refresh
  } catch (err) {
    alert('Failed to update status')
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
  font-family: "Segoe UI", sans-serif; 
}

.card { 
  width: 100%; 
  max-width: 1200px; 
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

.search-input { 
  width: 100%; 
  max-width: 300px; 
  padding: 0.7rem 1rem; 
  margin-bottom: 1.5rem; 
  border: 1px solid #e2e8f0; 
  border-radius: 8px; 
  outline: none;
}

.table-responsive { width: 100%; overflow-x: auto; }
.custom-category-table { width: 100%; border-collapse: collapse; }
.custom-category-table th, .custom-category-table td { 
  padding: 14px 15px; 
  text-align: left; 
  border-bottom: 1px solid #f1f5f9; 
  font-size: 14px; 
}
.custom-category-table th { background-color: #f8fafc; font-weight: 600; color: #475569; }

.qty-badge { background: #f1f5f9; padding: 4px 8px; border-radius: 4px; font-weight: 700; color: #2563eb; }
.total-amount { font-weight: 700; color: #10b981; }

/* Status Badges */
.status-badge {
  padding: 4px 10px;
  border-radius: 6px;
  font-size: 11px;
  font-weight: 700;
  text-transform: uppercase;
}
.pending { background: #fef3c7; color: #92400e; }
.completed { background: #dcfce7; color: #166534; }
.cancelled { background: #fee2e2; color: #991b1b; }

.dropdown-btn { 
  padding: 6px 14px; 
  border: none; 
  border-radius: 6px; 
  background: #2563eb; 
  color: white; 
  cursor: pointer; 
  font-weight: 600; 
  font-size: 13px; 
}

.dropdown-menu-absolute { 
  background: #ffffff; 
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); 
  border: 1px solid #e2e8f0; 
  border-radius: 8px; 
  min-width: 170px; 
}

.dropdown-menu-absolute button { 
  display: block; width: 100%; border: none; background: none; 
  padding: 12px 15px; cursor: pointer; text-align: left; font-size: 13px; 
}

.edit-btn { color: #059669; border-bottom: 1px solid #f1f5f9; }
.delete-btn { color: #dc2626; }

/* 📱 MOBILE RESPONSIVE FIX */
@media (max-width: 1024px) {
  .page { padding: 15px 10px; }
  .card { padding: 1.5rem 1rem; }
  
  .search-input { 
    max-width: 100%; 
    box-sizing: border-box; 
    margin-bottom: 1.2rem;
  }
  
  .custom-category-table thead { display: none; }
  .custom-category-table tr { 
    display: block; border: 1px solid #e2e8f0; margin-bottom: 15px; 
    border-radius: 12px; padding: 10px; background: #fff;
  }
  .custom-category-table td { 
    display: flex; justify-content: flex-start; align-items: center; 
    padding: 10px 8px; border-bottom: 1px solid #f8fafc;
  }
  .custom-category-table td:last-child { border-bottom: none; }
  .custom-category-table td::before { 
    content: attr(data-label); 
    font-weight: 700; color: #64748b; font-size: 11px; 
    text-transform: uppercase; width: 40%; flex-shrink: 0;
  }
  .bold-text { font-weight: 600; color: #1e293b; }
}

.fade-enter-active, .fade-leave-active { transition: opacity 0.2s, transform 0.2s; }
.fade-enter-from, .fade-leave-to { opacity: 0; transform: translateY(-5px); }
</style>