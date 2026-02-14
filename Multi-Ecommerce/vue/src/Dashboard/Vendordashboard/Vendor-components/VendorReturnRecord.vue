<template>
  <div class="page">
    <div class="card">
      <h2 class="title">My Return Records</h2>

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
                <th>Status</th>
                <th>Image</th>
                <th>Reason</th>
                <th>Return Date</th>
                <th>Actions</th>
              </tr>
            </thead>

            <tbody>
              <tr v-for="returnData in filteredReturns" :key="returnData.id">
                <td data-label="ID">#{{ returnData.id }}</td>
                <td data-label="Product Name" style="font-weight:600">{{ returnData.product_name }}</td>
                <td data-label="Quantity">{{ returnData.quantity }}</td>
                <td data-label="Status">
                  <span :class="statusClass(returnData.status)">{{ returnData.status }}</span>
                </td>
                <td data-label="Image">
                  <img
                    v-if="returnData.product_image"
                    :src="imageUrl(returnData.product_image)"
                    class="category-image"
                  />
                  <span v-else>No Image</span>
                </td>
                <td data-label="Reason">{{ returnData.reason || 'No reason' }}</td>
                <td data-label="Return Date">{{ formatDate(returnData.return_date) }}</td>
                <td data-label="Actions">
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
                <td colspan="8" class="no-data">
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
import api, { BASE_URL } from '../../../axios';

const search = ref('')
const returns = ref([])
const dropdownOpen = ref(null)
const dropdownPosition = ref({})

const token = localStorage.getItem('vendortoken') || localStorage.getItem('token')

const fetchReturns = async () => {
  try {
    const res = await api.get(
      '/vendor/returns',
      {
        headers: {
          Authorization: `Bearer ${token}`
        }
      }
    )

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

const filteredReturns = computed(() => {
  if (!search.value.trim()) return returns.value
  return returns.value.filter(r =>
    r.product_name.toLowerCase().includes(search.value.toLowerCase())
  )
})

const statusClass = (status) => {
  if (status === 'Completed') return 'status-completed';
  if (status === 'Pending') return 'status-pending';
  return '';
}

const formatDate = date => {
  if (!date) return 'N/A'
  const d = new Date(date)
  return (
    d.toLocaleDateString() +
    ' ' +
    d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
  )
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

const viewReturn = data => {
  alert(
    `Product: ${data.product_name}\nQuantity: ${data.quantity}\nReason: ${data.reason}\nStatus: ${data.status}`
  )
  dropdownOpen.value = null
}

const deleteReturn = async id => {
  if (!confirm('Are you sure? This will only delete the record from history.'))
    return

  try {
    await api.delete(
      `/vendor/returns/${id}`,
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
.custom-category-table tbody tr:hover {
  background-color: #f9fafb;
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
.status-completed { color: #10b981; font-weight: bold; }
.status-pending { color: #f59e0b; font-weight: bold; }

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

/* iPhone SE Specific Fix */
@media (max-width: 380px) {
  .custom-category-table td { font-size: 12px; padding: 8px 5px; }
  .custom-category-table td::before { font-size: 9px; }
}
</style>