<template>
  <div class="page">
    <div class="card">
      <h2 class="title">My Return Requests</h2>

      <div class="search-container">
        <input
          type="text"
          v-model="search"
          placeholder="Search by Order ID..."
          class="search-input"
        />
      </div>

      <div class="table-responsive">
        <table class="custom-category-table">
          <thead>
            <tr>
              <th>Order ID</th>
              <th>Product ID</th>
              <th>Reason</th>
              <th>Status</th>
              <th>Date</th>
              <th>Actions</th>
            </tr>
          </thead>

          <tbody>
            <tr v-for="item in filteredReturns" :key="item.id">
              <td data-label="Order ID" style="font-weight:700; color:#2563eb">#{{ item.order_id }}</td>
              <td data-label="Product ID">{{ item.product_id }}</td>
              <td data-label="Reason">
                <span :title="item.reason">
                  {{ item.reason.length > 20 ? item.reason.substring(0, 20) + '...' : item.reason }}
                </span>
              </td>
              <td data-label="Status">
                <span :class="['status-badge', statusClass(item.status)]">{{ item.status }}</span>
              </td>
              <td data-label="Date">{{ formatDate(item.created_at) }}</td>
              <td data-label="Actions">
                <button 
                  v-if="item.status === 'Pending'" 
                  class="cancel-btn" 
                  @click="cancelRequest(item.id)"
                >
                  Cancel Request
                </button>
                <span v-else class="locked-text">Locked</span>
              </td>
            </tr>

            <tr v-if="filteredReturns.length === 0">
              <td colspan="6" class="no-data">
                No return requests found.
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
import api, { BASE_URL } from '../../../axios';

const search = ref('')
const returns = ref([])
const token = localStorage.getItem('token')

const fetchMyReturns = async () => {
  try {
    const res = await api.get('/customer/my-returns', {
      headers: { Authorization: `Bearer ${token}` }
    })
    returns.value = res.data
  } catch (err) {
    console.error("Error fetching returns:", err)
  }
}

onMounted(fetchMyReturns)

const cancelRequest = async (id) => {
  if (!confirm('আপনি কি এই রিটার্ন রিকোয়েস্টটি ক্যান্সেল করতে চান?')) return

  try {
    const res = await api.delete(`/customer/return-cancel/${id}`, {
      headers: { Authorization: `Bearer ${token}` }
    })
    alert(res.data.message)
    fetchMyReturns()
  } catch (err) {
    alert(err.response?.data?.message || 'Cancel failed')
  }
}

const filteredReturns = computed(() => {
  if (!search.value.trim()) return returns.value
  return returns.value.filter(r => r.order_id.toString().toLowerCase().includes(search.value.toLowerCase()))
})

const formatDate = date => new Date(date).toLocaleDateString()

const statusClass = (status) => {
  if (status === 'Pending') return 'pending'
  if (status === 'Approved') return 'approved'
  return 'rejected'
}
</script>

<style scoped>
/* Base Layout */
.page { min-height: 100vh; padding: 20px 10px; background: #f8fafc; font-family: "Segoe UI", sans-serif; box-sizing: border-box; display: flex; justify-content: center; align-items: flex-start;}
.card { width: 100%; max-width: 1000px; background: #fff; border-radius: 12px; padding: 1.5rem; box-shadow: 0 4px 20px rgba(0,0,0,0.05); overflow: hidden; }
.title { text-align: center; margin-bottom: 25px; color: #1e293b; font-size: 24px; font-weight: 600; }

/* Search Box */
.search-container { width: 100%; display: flex; justify-content: flex-start; }
.search-input { width: 100%; max-width: 350px; padding: 0.7rem 1rem; margin-bottom: 1.5rem; border: 1px solid #e2e8f0; border-radius: 8px; outline: none; transition: 0.3s; font-size: 14px; }
.search-input:focus { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1); }

/* Table Styles */
.table-responsive { width: 100%; overflow-x: auto; }
.custom-category-table { width: 100%; border-collapse: collapse; }
.custom-category-table th, .custom-category-table td { padding: 14px 15px; text-align: left; border-bottom: 1px solid #f1f5f9; font-size: 14px; }
.custom-category-table th { background: #f8fafc; color: #64748b; font-weight: 600; text-transform: uppercase; font-size: 12px; white-space: nowrap; }

/* Status Badges */
.status-badge { padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 700; text-transform: capitalize; white-space: nowrap; display: inline-block; }
.pending { color: #d97706; background: #fffbeb; }
.approved { color: #059669; background: #ecfdf5; }
.rejected { color: #dc2626; background: #fef2f2; }

/* Actions */
.cancel-btn { background: #dc2626; color: white; border: none; padding: 7px 14px; border-radius: 6px; cursor: pointer; font-size: 12px; font-weight: 600; transition: 0.2s; white-space: nowrap; }
.cancel-btn:hover { background: #b91c1c; }
.locked-text { color: #94a3b8; font-style: italic; font-size: 13px; font-weight: 500; }
.no-data { text-align: center; padding: 30px; color: #64748b; }

/* Mobile Responsive Design (Pixel-Perfect) */
@media (max-width: 850px) {
  .page { padding: 15px 10px; }
  .title { font-size: 20px; }
  
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
    border-bottom: 1px solid #f1f5f9; 
    padding: 12px 5px; 
  }
  .custom-category-table td:last-child { border-bottom: none; }
  
  /* Label Insertion for Mobile */
  .custom-category-table td::before { 
    content: attr(data-label); 
    font-weight: 700; 
    color: #64748b; 
    text-transform: uppercase; 
    font-size: 11px; 
    text-align: left;
    margin-right: 15px;
  }
  
  .search-input { max-width: 100%; }
}

@media (max-width: 480px) {
  .card { padding: 1rem; }
  .status-badge { font-size: 10px; }
  .cancel-btn { padding: 6px 10px; font-size: 11px; }
}
</style>