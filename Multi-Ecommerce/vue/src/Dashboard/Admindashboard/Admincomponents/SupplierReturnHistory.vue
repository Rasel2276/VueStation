<template>
  <div class="page">
    <div class="card">
      <h2 class="title">Supplier Product Return History</h2>

      <div class="table-responsive">
        <input
          type="text"
          v-model="search"
          placeholder="Search"
          class="search-input"
        />

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
              <td>{{ item.id }}</td>
              <td>{{ item.product_name }}</td>
              <td>{{ item.supplier_name }}</td>
              <td>{{ item.quantity }}</td>
              <td>{{ item.reason || 'N/A' }}</td>
              <td class="action-cell">
                <button @click="toggleDropdown(item.id)" class="dropdown-btn">Actions ▾</button>
                <transition name="fade">
                  <div v-if="dropdownOpen === item.id" class="dropdown-menu-row">
                    <button @click="viewReason(item)" class="edit-btn">View Reason</button>
                    <button @click="deleteReturn(item.id)" class="delete-btn">Delete</button>
                  </div>
                </transition>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'

const search = ref('')
const returns = ref([])
const dropdownOpen = ref(null)
const token = localStorage.getItem('token')

// Fetch return history
const fetchReturns = async () => {
  try {
    const res = await axios.get('http://127.0.0.1:8000/api/admin/purchase/return-history', {
      headers: { Authorization: `Bearer ${token}` }
    })
    returns.value = res.data.map(r => ({
      id: r.id,
      product_id: r.product_id,
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

// Search filter
const filteredReturns = computed(() => {
  if (!search.value.trim()) return returns.value
  const s = search.value.toLowerCase()
  return returns.value.filter(r =>
    r.product_name.toLowerCase().includes(s) ||
    r.supplier_name.toLowerCase().includes(s) ||
    r.reason.toLowerCase().includes(s)
  )
})

// Dropdown toggle
const toggleDropdown = (id) => {
  dropdownOpen.value = dropdownOpen.value === id ? null : id
}

// View reason
const viewReason = (item) => {
  alert(`Return Reason: ${item.reason || 'No reason provided'}`)
}

// Delete return
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
    console.error(err.response?.data || err)
    alert('Failed to delete return')
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

/* Make row relative for dropdown */
.relative-row { position: relative; }

/* Action cell relative for dropdown */
.action-cell { position: relative; }

/* Dropdown below button */
.dropdown-menu-row {
  position: absolute;
  top: 100%;
  right: 0;  /* aligns to button right side */
  background:#f9fafb;
  box-shadow:0 4px 8px rgba(0,0,0,0.15);
  border-radius:6px;
  overflow:hidden;
  min-width:140px;
  z-index: 999;
}
.dropdown-menu-row button {
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
.edit-btn { color:#065f46; }
.delete-btn { color:#b91c1c; }
.dropdown-menu-row button:hover { background:#e0e7ff; }

/* Transition */
.fade-enter-active, .fade-leave-active {
  transition: all 0.2s ease;
}
.fade-enter-from, .fade-leave-to {
  opacity:0;
  transform: translateY(-5px);
}

/* Dropdown button */
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
</style>
