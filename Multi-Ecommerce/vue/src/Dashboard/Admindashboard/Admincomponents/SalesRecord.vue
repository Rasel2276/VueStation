<template>
  <div class="page">
    <div class="card">
      <h2 class="title">Vendor Sales Report</h2>

      <div class="table-wrapper">
        <div class="search-container">
          <input 
            type="text" 
            v-model="search" 
            placeholder="Search vendor..." 
            class="search-input" 
          />
        </div>

        <div class="table-responsive">
          <table class="custom-category-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Vendor Name</th>
                <th>Vendor Email</th>
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
                
                <td data-label="Vendor Name" style="font-weight:600">
                  {{ sale.vendor?.name || 'Unknown' }}
                </td>

                <td data-label="Vendor Email" style="color: #64748b;">
                  {{ sale.vendor?.email || 'N/A' }}
                </td>

                <td data-label="Qty">{{ sale.quantity }}</td>
                <td data-label="Price">{{ sale.price }}</td>
                <td data-label="Total" style="font-weight: 700; color: #10b981;">{{ sale.total }}</td>
                <td data-label="Status">
                  <span :class="['status-badge', sale.status?.toLowerCase()]">
                    {{ sale.status }}
                  </span>
                </td>
                <td data-label="Date">{{ formatDate(sale.purchase_date) }}</td>
                <td data-label="Actions">
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
                        <button @click="viewInvoice(sale)" class="invoice-btn">
                          📄 View Invoice
                        </button>
                        <button @click="deleteRecord(sale.id)" class="delete-btn">
                          🗑️ Delete Record
                        </button>
                      </div>
                    </transition>
                  </teleport>
                </td>
              </tr>

              <tr v-if="filteredSales.length === 0">
                <td colspan="9" class="no-data" style="text-align: center; padding: 40px; color: #94a3b8;">
                  No sales records found.
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
const sales = ref([])
const dropdownOpen = ref(null)
const dropdownPosition = ref({})
const token = localStorage.getItem('token')

const fetchSales = async () => {
  try {
    const res = await api.get('/sales-report', {
      headers: { Authorization: `Bearer ${token}` }
    })
    sales.value = res.data
  } catch (err) { console.error(err) }
}

onMounted(fetchSales)

const filteredSales = computed(() => {
  if (!search.value.trim()) return sales.value
  const s = search.value.toLowerCase()
  return sales.value.filter(sale =>
    sale.vendor?.name?.toLowerCase().includes(s) ||
    sale.vendor?.email?.toLowerCase().includes(s) ||
    sale.status?.toLowerCase().includes(s) ||
    sale.id.toString().includes(s)
  )
})

const formatDate = (dateStr) => {
  if (!dateStr) return '—'
  const d = new Date(dateStr)
  return d.toLocaleDateString()
}

const toggleDropdown = async (id, event) => {
  if (dropdownOpen.value === id) {
    dropdownOpen.value = null
    return
  }
  dropdownOpen.value = id
  await nextTick()
  const rect = event.target.getBoundingClientRect()
  let leftPos = rect.left + window.scrollX - 80;
  if (leftPos + 150 > window.innerWidth) leftPos = window.innerWidth - 160;
  dropdownPosition.value = {
    position: 'absolute',
    top: `${rect.bottom + window.scrollY + 5}px`,
    left: `${leftPos}px`,
    zIndex: 9999
  }
}

const viewInvoice = (sale) => {
  alert(`Opening Invoice for Order #${sale.id}`);
  dropdownOpen.value = null;
}

const deleteRecord = async (id) => {
  if (!confirm('Are you sure you want to delete this record?')) return
  try {
    await api.delete(`/sales-report/${id}`, {
      headers: { Authorization: `Bearer ${token}` }
    })
    dropdownOpen.value = null
    fetchSales()
  } catch (err) { alert('Delete failed!') }
}

window.addEventListener('click', (e) => {
  if (!e.target.classList.contains('dropdown-btn')) {
    dropdownOpen.value = null;
  }
});
</script>

<style scoped>
/* 🔒 DESKTOP STYLES */
.page { min-height: 100vh; display: flex; justify-content: center; align-items: flex-start; padding: 40px 15px; background-color: #f8fafc; box-sizing: border-box; }
.card { width: 100%; max-width: 1100px; background: #fff; border-radius: 8px; padding: 1.5rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); box-sizing: border-box; }
.title { text-align: center; font-size: 24px; font-weight: 600; margin-bottom: 25px; color: #1e293b; }

/* Desktop search input width */
.search-input { width: 100%; max-width: 300px; padding: 0.6rem 1rem; margin-bottom: 1.2rem; border: 1px solid #e2e8f0; border-radius: .375rem; outline: none; box-sizing: border-box; }

.table-responsive { width: 100%; border-radius: 8px; }
.custom-category-table { width: 100%; border-collapse: collapse; }
.custom-category-table th, .custom-category-table td { padding: 14px 15px; text-align: left; border-bottom: 1px solid #f1f5f9; font-size: 14px; }
.custom-category-table th { background-color: #f8fafc; font-weight: 600; color: #475569; }

.status-badge { padding: 4px 10px; border-radius: 4px; font-size: 12px; font-weight: 600; text-transform: uppercase; }
.status-badge.pending { background: #fef3c7; color: #92400e; }
.status-badge.completed { background: #ecfdf5; color: #059669; }
.status-badge.cancelled { background: #fef2f2; color: #dc2626; }

.dropdown-btn { padding: 6px 14px; border: none; border-radius: 4px; background: #1e293b; color: white; cursor: pointer; font-weight: 600; font-size: 13px; }
.dropdown-menu-absolute { background: #ffffff; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); border: 1px solid #e2e8f0; border-radius: 6px; overflow: hidden; min-width: 160px; }
.dropdown-menu-absolute button { display: block; width: 100%; border: none; background: none; padding: 12px 15px; cursor: pointer; text-align: left; font-size: 13px; }
.dropdown-menu-absolute button:hover { background: #f8fafc; }
.invoice-btn { color: #2563eb; border-bottom: 1px solid #f1f5f9; }
.delete-btn { color: #dc2626; }

/* 📱 MOBILE RESPONSIVE FIXES */
@media (max-width: 850px) {
  .page { padding: 10px 5px; }
  .card { padding: 1rem; border-radius: 12px; }
  
  /* Search input fits 100% on mobile */
  .search-input {
    max-width: 100% !important; 
    width: 100%; 
    margin-bottom: 1.5rem; 
  }
  
  .custom-category-table thead { display: none; }
  .custom-category-table tr { display: block; border: 1px solid #e2e8f0; margin-bottom: 15px; border-radius: 12px; padding: 8px; background: #fff; }
  .custom-category-table td { display: flex; justify-content: flex-start; align-items: flex-start; padding: 10px 8px; border-bottom: 1px solid #f8fafc; }
  .custom-category-table td::before { content: attr(data-label); font-weight: 700; color: #64748b; font-size: 11px; text-transform: uppercase; width: 35%; flex-shrink: 0; }
  .custom-category-table td { flex: 1; font-size: 13px; }
}

.fade-enter-active, .fade-leave-active { transition: opacity 0.2s, transform 0.2s; }
.fade-enter-from, .fade-leave-to { opacity: 0; transform: translateY(-5px); }
</style>