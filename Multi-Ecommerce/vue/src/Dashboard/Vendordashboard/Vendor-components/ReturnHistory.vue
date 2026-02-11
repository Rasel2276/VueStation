<template>
  <div class="page">
    <div class="card">
      <h2 class="title">Return Request History</h2>

      <div class="table-wrapper">
        <input
          type="text"
          v-model="search"
          placeholder="Search by Order ID..."
          class="search-input"
        />

        <div class="table-responsive">
          <table class="custom-category-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Order ID</th>
                <th>Customer Phone</th>
                <th>Reason</th>
                <th>Final Status</th>
                <th>Resolved Date</th>
                <th>Actions</th>
              </tr>
            </thead>

            <tbody>
              <tr v-for="item in filteredHistory" :key="item.id">
                <td data-label="ID">#{{ item.id }}</td>
                <td data-label="Order ID" style="font-weight:600">{{ item.order_id }}</td>
                <td data-label="Customer Phone">{{ item.phone }}</td>
                <td data-label="Reason">
                  <span>{{ item.reason.substring(0, 25) }}...</span>
                </td>
                <td data-label="Final Status">
                  <span :class="item.status === 'Approved' ? 'status-approved' : 'status-rejected'">{{ item.status }}</span>
                </td>
                <td data-label="Resolved Date">{{ formatDate(item.updated_at) }}</td>
                <td data-label="Actions">
                  <button class="dropdown-btn" @click="toggleDropdown(item.id, $event)">Actions ▾</button>
                  <teleport to="body">
                    <transition name="fade">
                      <div v-if="dropdownOpen === item.id" class="dropdown-menu-absolute" :style="dropdownPosition">
                        <button class="view-btn" @click="alertReason(item.reason)">👁️ View Details</button>
                      </div>
                    </transition>
                  </teleport>
                </td>
              </tr>
              <tr v-if="filteredHistory.length === 0">
                <td colspan="7" class="no-data">No history found.</td>
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
const history = ref([])
const dropdownOpen = ref(null)
const dropdownPosition = ref({})
const token = localStorage.getItem('vendortoken') || localStorage.getItem('token')

const fetchHistory = async () => {
  try {
    const res = await axios.get('http://127.0.0.1:8000/api/vendor/customer-returns', {
      headers: { Authorization: `Bearer ${token}` }
    })
    history.value = res.data
  } catch (err) { console.error(err) }
}

onMounted(fetchHistory)

const filteredHistory = computed(() => {
  let list = history.value.filter(item => item.status !== 'Pending')
  if (!search.value.trim()) return list
  return list.filter(r => r.order_id.toLowerCase().includes(search.value.toLowerCase()))
})

const formatDate = d => new Date(d).toLocaleDateString() + ' ' + new Date(d).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })

const alertReason = (reason) => {
  alert('Reason: ' + reason);
  dropdownOpen.value = null;
}

const toggleDropdown = async (id, event) => {
  if (dropdownOpen.value === id) { dropdownOpen.value = null; return; }
  dropdownOpen.value = id; await nextTick();
  const rect = event.target.getBoundingClientRect()
  
  let leftPos = rect.left + window.scrollX - 80;
  if (leftPos + 180 > window.innerWidth) leftPos = window.innerWidth - 190;
  if (leftPos < 10) leftPos = 10;

  dropdownPosition.value = { 
    position: 'absolute', 
    top: `${rect.bottom + window.scrollY}px`, 
    left: `${leftPos}px`, 
    zIndex: 9999 
  }
}
</script>

<style scoped>
/* 🔒 DESKTOP DESIGN - 100% UNCHANGED */
.page { min-height: 100vh; display:flex; justify-content:center; align-items:flex-start; padding:40px 0; font-family:"Segoe UI", sans-serif; background-color: #f8fafc; box-sizing: border-box;}
.card { width:100%; max-width:1100px; background:#fff; border-radius:8px; padding:1.5rem; box-shadow:0 4px 6px -1px rgba(0,0,0,0.1); box-sizing: border-box;}
.title { text-align:center; font-size:24px; font-weight:600; margin-bottom:25px; color:#1e293b; }
.search-input { width:100%; max-width:250px; padding:0.6rem 1rem; margin-bottom:1.2rem; border:1px solid #e2e8f0; border-radius:.375rem; outline: none; box-sizing: border-box;}
.table-wrapper { width: 100%; }
.table-responsive { width:100%; border-radius:8px; background:white; }
.custom-category-table { width:100%; border-collapse:collapse; }
.custom-category-table th, .custom-category-table td { padding:14px 15px; text-align:left; border-bottom:1px solid #f1f5f9; font-size:14px; }
.custom-category-table th { background-color:#f8fafc; font-weight:600; color: #475569; }
.status-approved { color: #059669; font-weight: 600; }
.status-rejected { color: #dc2626; font-weight: 600; }
.dropdown-btn { padding:6px 14px; border:none; border-radius:4px; background:#1e293b; color:white; cursor:pointer; font-weight:600; font-size: 13px; }
.dropdown-menu-absolute { background:#ffffff; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); border: 1px solid #e2e8f0; border-radius:6px; overflow:hidden; min-width:180px; }
.dropdown-menu-absolute button { display:block; width:100%; border:none; background:none; padding:12px 15px; cursor:pointer; text-align:left; font-size:13px; transition: 0.2s; }
.dropdown-menu-absolute button:hover { background: #f8fafc; }
.view-btn { color: #64748b; }
.no-data { text-align:center; padding:20px; color: #64748b; }

.fade-enter-active, .fade-leave-active { transition: opacity 0.2s; }
.fade-enter-from, .fade-leave-to { opacity: 0; }

/* 📱 MOBILE RESPONSIVE - ADDED */
@media (max-width: 850px) {
  .page { padding: 15px 10px; }
  .card { padding: 1.2rem; border-radius: 12px; }
  .title { font-size: 20px; }
  .search-input { max-width: 100%; }

  .custom-category-table thead { display: none; }
  .custom-category-table tr { 
    display: block; 
    border: 1px solid #e2e8f0; 
    margin-bottom: 15px; 
    border-radius: 10px; 
    padding: 10px; 
    background: #fff;
  }
  .custom-category-table td { 
    display: flex; 
    justify-content: space-between; 
    align-items: center; 
    text-align: right; 
    padding: 10px 5px; 
    border-bottom: 1px solid #f8fafc;
  }
  .custom-category-table td:last-child { border-bottom: none; }
  .custom-category-table td::before { 
    content: attr(data-label); 
    font-weight: 700; 
    color: #64748b; 
    font-size: 11px; 
    text-transform: uppercase; 
    flex: 1; 
    text-align: left; 
    margin-right: 10px;
  }
}
</style>