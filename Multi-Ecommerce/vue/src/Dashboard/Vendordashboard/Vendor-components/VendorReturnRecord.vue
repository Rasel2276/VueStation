<template>
  <div class="page">
    <div class="card">
      <h2 class="title">My Return Records</h2>

      <div class="table-responsive">
        <input
          type="text"
          v-model="search"
          placeholder="Search by product name..."
          class="search-input"
        />

        <table class="custom-category-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Product Name</th>
              <th>Quantity</th>
              <th>Status</th>
              <th>Image</th>
              <th>Reason</th>
              <th>Return Date</th>
              <th>Actions</th>
            </tr>
          </thead>

          <tbody>
            <tr v-for="returnData in filteredReturns" :key="returnData.id">
              <td>#{{ returnData.id }}</td>
              <td style="font-weight:600">{{ returnData.product_name }}</td>
              <td>{{ returnData.quantity }}</td>
              <td>
                <span :class="statusClass(returnData.status)">{{ returnData.status }}</span>
              </td>
              <td>
                <img
                  v-if="returnData.product_image"
                  :src="imageUrl(returnData.product_image)"
                  class="category-image"
                />
                <span v-else>No Image</span>
              </td>
              <td>{{ returnData.reason || 'No reason' }}</td>
              <td>{{ formatDate(returnData.return_date) }}</td>
              <td>
                <button
                  class="dropdown-btn"
                  @click="toggleDropdown(returnData.id, $event)"
                >
                  Actions ▾
                </button>

                <teleport to="body">
                  <transition name="fade">
                    <div
                      v-if="dropdownOpen === returnData.id"
                      class="dropdown-menu-absolute"
                      :style="dropdownPosition"
                    >
                      <button
                        class="edit-btn"
                        @click="viewReturn(returnData)"
                      >
                        View Details
                      </button>

                      <button
                        class="delete-btn"
                        @click="deleteReturn(returnData.id)"
                      >
                        Delete Record
                      </button>
                    </div>
                  </transition>
                </teleport>
              </td>
            </tr>

            <tr v-if="filteredReturns.length === 0">
              <td colspan="8" style="text-align:center;padding:20px">
                No return records found.
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
const returns = ref([])
const dropdownOpen = ref(null)
const dropdownPosition = ref({})

const token = localStorage.getItem('vendortoken') || localStorage.getItem('token')

// ✅ Fetch Return Records
const fetchReturns = async () => {
  try {
    const res = await axios.get(
      'http://127.0.0.1:8000/api/vendor/returns',
      {
        headers: {
          Authorization: `Bearer ${token}`
        }
      }
    )

    // ম্যাপিং ডাটা ফ্রম এপিআই
    returns.value = res.data.map(r => ({
      id: r.id,
      product_name: r.product?.product_name || 'Unknown',
      product_image: r.product?.product_image || null,
      quantity: r.quantity,
      status: r.status,
      reason: r.reason,
      return_date: r.created_at
    }))
  } catch (err) {
    console.error("Fetch Error:", err)
  }
}

onMounted(fetchReturns)

// ✅ Search filter
const filteredReturns = computed(() => {
  if (!search.value.trim()) return returns.value
  return returns.value.filter(r =>
    r.product_name.toLowerCase().includes(search.value.toLowerCase())
  )
})

// ✅ Status Class (নিজেস্ব স্টাইল যোগ করা হয়েছে)
const statusClass = (status) => {
  if (status === 'Completed') return 'status-completed';
  if (status === 'Pending') return 'status-pending';
  return '';
}

// ✅ Helpers
const formatDate = date => {
  const d = new Date(date)
  return (
    d.toLocaleDateString() +
    ' ' +
    d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
  )
}

const imageUrl = img => `http://127.0.0.1:8000/product_images/${img}`

// ✅ Dropdown logic
const toggleDropdown = async (id, event) => {
  if (dropdownOpen.value === id) {
    dropdownOpen.value = null
    return
  }

  dropdownOpen.value = id
  await nextTick()

  const rect = event.target.getBoundingClientRect()
  dropdownPosition.value = {
    position: 'absolute',
    top: `${rect.bottom + window.scrollY}px`,
    left: `${rect.left + window.scrollX - 50}px`,
    zIndex: 9999
  }
}

const viewReturn = data => {
  alert(
    `Product: ${data.product_name}\nQuantity: ${data.quantity}\nReason: ${data.reason}\nStatus: ${data.status}`
  )
  dropdownOpen.value = null
}

// ✅ DELETE Return Record
const deleteReturn = async id => {
  if (!confirm('Are you sure? This will only delete the record from history.'))
    return

  try {
    await axios.delete(
      `http://127.0.0.1:8000/api/vendor/returns/${id}`,
      {
        headers: {
          Authorization: `Bearer ${token}`
        }
      }
    )

    returns.value = returns.value.filter(r => r.id !== id)
    dropdownOpen.value = null
  } catch (err) {
    alert('Delete failed')
  }
}
</script>

<style scoped>
/* 🔒 EXACT SAME DESIGN - NO CHANGE */
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

/* Status Colors */
.status-completed { color: #10b981; font-weight: bold; }
.status-pending { color: #f59e0b; font-weight: bold; }

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
}
.edit-btn { color:#065f46; }
.delete-btn { color:#b91c1c; }
.dropdown-menu-absolute button:hover {
  background:#e0e7ff;
}
.fade-enter-active,.fade-leave-active {
  transition: all 0.2s ease;
}
.fade-enter-from,.fade-leave-to {
  opacity:0;
  transform: translateY(-5px);
}
</style>