<template>
  <div class="page">
    <div class="card">
      <h2 class="title">Return Request History</h2>

      <div class="table-responsive">
        <input
          type="text"
          v-model="search"
          placeholder="Search by Order ID..."
          class="search-input"
        />

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
              <td>#{{ item.id }}</td>
              <td style="font-weight:600">{{ item.order_id }}</td>
              <td>{{ item.phone }}</td>
              <td>
                <span>{{ item.reason.substring(0, 25) }}...</span>
              </td>
              <td>
                <span :class="item.status === 'Approved' ? 'status-approved' : 'status-rejected'">{{ item.status }}</span>
              </td>
              <td>{{ formatDate(item.updated_at) }}</td>
              <td>
                <button class="dropdown-btn" @click="toggleDropdown(item.id, $event)">Actions ▾</button>
                <teleport to="body">
                  <div v-if="dropdownOpen === item.id" class="dropdown-menu-absolute" :style="dropdownPosition">
                    <button class="view-btn" @click="alert('Reason: ' + item.reason)">👁️ View Details</button>
                  </div>
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
const toggleDropdown = async (id, event) => {
  if (dropdownOpen.value === id) { dropdownOpen.value = null; return; }
  dropdownOpen.value = id; await nextTick();
  const rect = event.target.getBoundingClientRect()
  dropdownPosition.value = { position: 'absolute', top: `${rect.bottom + window.scrollY}px`, left: `${rect.left + window.scrollX - 80}px`, zIndex: 9999 }
}
</script>

<style scoped>
/* হুবহু মেইন ফাইলের CSS কপি */
.page { min-height: 100vh; display:flex; justify-content:center; align-items:flex-start; padding:40px 0; font-family:"Segoe UI", sans-serif; background-color: #f8fafc; }
.card { width:100%; max-width:1100px; background:#fff; border-radius:8px; padding:1.5rem; box-shadow:0 4px 6px -1px rgba(0,0,0,0.1); }
.title { text-align:center; font-size:24px; font-weight:600; margin-bottom:25px; color:#1e293b; }
.search-input { width:100%; max-width:250px; padding:0.6rem 1rem; margin-bottom:1.2rem; border:1px solid #e2e8f0; border-radius:.375rem; outline: none; }
.table-responsive { width:100%; overflow-x:auto; border-radius:8px; background:white; }
.custom-category-table { width:100%; border-collapse:collapse; min-width:800px; }
.custom-category-table th, .custom-category-table td { padding:14px 15px; text-align:left; border-bottom:1px solid #f1f5f9; font-size:14px; }
.custom-category-table th { background-color:#f8fafc; font-weight:600; color: #475569; }
.status-approved { color: #059669; font-weight: 600; }
.status-rejected { color: #dc2626; font-weight: 600; }
.dropdown-btn { padding:6px 14px; border:none; border-radius:4px; background:#1e293b; color:white; cursor:pointer; font-weight:600; font-size: 13px; }
.dropdown-menu-absolute { background:#ffffff; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); border: 1px solid #e2e8f0; border-radius:6px; overflow:hidden; min-width:180px; }
.dropdown-menu-absolute button { display:block; width:100%; border:none; background:none; padding:12px 15px; cursor:pointer; text-align:left; font-size:13px; }
.view-btn { color: #64748b; }
</style>