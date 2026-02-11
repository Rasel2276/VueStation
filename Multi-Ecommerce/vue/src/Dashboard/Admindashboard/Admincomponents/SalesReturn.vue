<template>
  <div class="page">
    <div class="card">
      <h2 class="title">Sales Return Report</h2>

      <div class="table-wrapper">
        <div class="search-container">
          <input 
            type="text" 
            v-model="search" 
            placeholder="Search by id..." 
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
                <th>Product</th>
                <th>Qty</th>
                <th>Reason</th>
                <th>Status</th>
                <th>Date</th>
                <th>Actions</th>
              </tr>
            </thead>

            <tbody>
              <tr v-for="item in filteredReturns" :key="item.id">
                <td data-label="ID">#{{ item.id }}</td>
                
                <td data-label="Vendor Name" style="font-weight:600">
                  {{ item.vendor?.name || 'Unknown' }}
                </td>

                <td data-label="Vendor Email" style="color: #64748b;">
                  {{ item.vendor?.email || 'N/A' }}
                </td>

                <td data-label="Product">
                  {{ item.product?.product_name || 'N/A' }}
                </td>

                <td data-label="Qty">{{ item.quantity }}</td>

                <td data-label="Reason" style="font-style: italic; font-size: 13px; color: #64748b;">
                  {{ item.reason || 'No reason provided' }}
                </td>

                <td data-label="Status">
                  <span :class="['status-badge', item.status?.toLowerCase()]">
                    {{ item.status }}
                  </span>
                </td>

                <td data-label="Date">{{ formatDate(item.return_date) }}</td>

                <td data-label="Actions">
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
                        <button @click="deleteReturn(item.id)" class="delete-btn">
                          🗑️ Delete Record
                        </button>
                      </div>
                    </transition>
                  </teleport>
                </td>
              </tr>

              <tr v-if="filteredReturns.length === 0">
                <td colspan="9" class="no-data" style="text-align: center; padding: 40px; color: #94a3b8;">
                  No return records found.
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

const search = ref('')
const returns = ref([])
const dropdownOpen = ref(null)
const dropdownPosition = ref({})
const token = localStorage.getItem('token')

const fetchReturns = async () => {
  try {
    const res = await axios.get('http://127.0.0.1:8000/api/sales-returns', {
      headers: { Authorization: `Bearer ${token}` }
    })
    returns.value = res.data
  } catch (err) { console.error(err) }
}

onMounted(fetchReturns)

const filteredReturns = computed(() => {
  if (!search.value.trim()) return returns.value
  const s = search.value.toLowerCase()
  return returns.value.filter(item =>
    item.vendor?.name?.toLowerCase().includes(s) ||
    item.vendor?.email?.toLowerCase().includes(s) ||
    item.product?.product_name?.toLowerCase().includes(s) ||
    item.status?.toLowerCase().includes(s) ||
    item.id.toString().includes(s)
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

const deleteReturn = async (id) => {
  if (!confirm('Are you sure you want to delete this return record?')) return
  try {
    await axios.delete(`http://127.0.0.1:8000/api/sales-returns/${id}`, {
      headers: { Authorization: `Bearer ${token}` }
    })
    dropdownOpen.value = null
    fetchReturns()
  } catch (err) { alert('Delete failed!') }
}

window.addEventListener('click', (e) => {
  if (!e.target.classList.contains('dropdown-btn')) {
    dropdownOpen.value = null;
  }
});
</script>

<style scoped>
/* 🔒 DESKTOP STYLES (Sales Record এর সাথে হুবহু মিল) */
.page { min-height: 100vh; display: flex; justify-content: center; align-items: flex-start; padding: 40px 15px; background-color: #f8fafc; box-sizing: border-box; }
.card { width: 100%; max-width: 1100px; background: #fff; border-radius: 8px; padding: 1.5rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); box-sizing: border-box; }
.title { text-align: center; font-size: 24px; font-weight: 600; margin-bottom: 25px; color: #1e293b; }

.search-input { width: 100%; max-width: 300px; padding: 0.6rem 1rem; margin-bottom: 1.2rem; border: 1px solid #e2e8f0; border-radius: .375rem; outline: none; box-sizing: border-box; }

.table-responsive { width: 100%; border-radius: 8px; }
.custom-category-table { width: 100%; border-collapse: collapse; }
.custom-category-table th, .custom-category-table td { padding: 14px 15px; text-align: left; border-bottom: 1px solid #f1f5f9; font-size: 14px; }
.custom-category-table th { background-color: #f8fafc; font-weight: 600; color: #475569; }

/* Status Badges */
.status-badge { padding: 4px 10px; border-radius: 4px; font-size: 12px; font-weight: 600; text-transform: uppercase; }
.status-badge.pending { background: #fef3c7; color: #92400e; }
.status-badge.completed { background: #ecfdf5; color: #059669; }
.status-badge.cancelled { background: #fef2f2; color: #dc2626; }
/* Return specific statuses */
.status-badge.approved { background: #ecfdf5; color: #059669; }
.status-badge.rejected { background: #fef2f2; color: #dc2626; }

.dropdown-btn { padding: 6px 14px; border: none; border-radius: 4px; background: #1e293b; color: white; cursor: pointer; font-weight: 600; font-size: 13px; }
.dropdown-menu-absolute { background: #ffffff; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); border: 1px solid #e2e8f0; border-radius: 6px; overflow: hidden; min-width: 160px; }
.dropdown-menu-absolute button { display: block; width: 100%; border: none; background: none; padding: 12px 15px; cursor: pointer; text-align: left; font-size: 13px; }
.dropdown-menu-absolute button:hover { background: #f8fafc; }
.delete-btn { color: #dc2626; }

/* 📱 MOBILE RESPONSIVE FIXES */
@media (max-width: 850px) {
  .page { padding: 10px 5px; }
  .card { padding: 1rem; border-radius: 12px; }
  .search-input { max-width: 100% !important; width: 100%; margin-bottom: 1.5rem; }
  
  .custom-category-table thead { display: none; }
  .custom-category-table tr { display: block; border: 1px solid #e2e8f0; margin-bottom: 15px; border-radius: 12px; padding: 8px; background: #fff; }
  .custom-category-table td { display: flex; justify-content: flex-start; align-items: flex-start; padding: 10px 8px; border-bottom: 1px solid #f8fafc; }
  .custom-category-table td::before { content: attr(data-label); font-weight: 700; color: #64748b; font-size: 11px; text-transform: uppercase; width: 35%; flex-shrink: 0; }
  .custom-category-table td { flex: 1; font-size: 13px; text-align: left; }
}

.fade-enter-active, .fade-leave-active { transition: opacity 0.2s, transform 0.2s; }
.fade-enter-from, .fade-leave-to { opacity: 0; transform: translateY(-5px); }
</style>