<template>
  <div class="page">
    <div class="card">
      <h2 class="title">My Return Requests</h2>

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
              <td style="font-weight:600">#{{ item.order_id }}</td>
              <td>{{ item.product_id }}</td>
              <td>
                <span :title="item.reason">
                  {{ item.reason.length > 20 ? item.reason.substring(0, 20) + '...' : item.reason }}
                </span>
              </td>
              <td>
                <span :class="statusClass(item.status)">{{ item.status }}</span>
              </td>
              <td>{{ formatDate(item.created_at) }}</td>
              <td>
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
              <td colspan="6" style="text-align:center;padding:20px">
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

const search = ref('')
const returns = ref([])
const token = localStorage.getItem('token') // কাস্টমার টোকেন

// ✅ কাস্টমারের নিজস্ব রিটার্ন লিস্ট নিয়ে আসা
const fetchMyReturns = async () => {
  try {
    const res = await axios.get('http://127.0.0.1:8000/api/customer/my-returns', {
      headers: { Authorization: `Bearer ${token}` }
    })
    returns.value = res.data
  } catch (err) {
    console.error("Error fetching returns:", err)
  }
}

onMounted(fetchMyReturns)

// ✅ রিটার্ন রিকোয়েস্ট ক্যান্সেল করা
const cancelRequest = async (id) => {
  if (!confirm('আপনি কি এই রিটার্ন রিকোয়েস্টটি ক্যান্সেল করতে চান?')) return

  try {
    const res = await axios.delete(`http://127.0.0.1:8000/api/customer/return-cancel/${id}`, {
      headers: { Authorization: `Bearer ${token}` }
    })
    alert(res.data.message)
    fetchMyReturns() // লিস্ট রিফ্রেশ করা
  } catch (err) {
    alert(err.response?.data?.message || 'Cancel failed')
  }
}

// ✅ ফিল্টার এবং হেল্পারস
const filteredReturns = computed(() => {
  if (!search.value.trim()) return returns.value
  return returns.value.filter(r => r.order_id.toLowerCase().includes(search.value.toLowerCase()))
})

const formatDate = date => new Date(date).toLocaleDateString()

const statusClass = (status) => {
  if (status === 'Pending') return 'status-pending'
  if (status === 'Approved') return 'status-approved'
  return 'status-rejected'
}
</script>

<style scoped>
.page { min-height: 100vh; padding: 40px 20px; background: #f8fafc; font-family: "Segoe UI", sans-serif; }
.card { max-width: 1000px; margin: 0 auto; background: #fff; border-radius: 10px; padding: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
.title { text-align: center; margin-bottom: 25px; color: #1e293b; font-size: 22px; }
.search-input { width: 100%; max-width: 300px; padding: 10px; margin-bottom: 20px; border: 1px solid #e2e8f0; border-radius: 6px; }

.table-responsive { width: 100%; overflow-x: auto; }
.custom-category-table { width: 100%; border-collapse: collapse; }
.custom-category-table th, .custom-category-table td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #f1f5f9; font-size: 14px; }
.custom-category-table th { background: #f8fafc; color: #64748b; font-weight: 600; }

.status-pending { color: #d97706; background: #fffbeb; padding: 4px 8px; border-radius: 4px; font-weight: 600; }
.status-approved { color: #059669; background: #ecfdf5; padding: 4px 8px; border-radius: 4px; font-weight: 600; }
.status-rejected { color: #dc2626; background: #fef2f2; padding: 4px 8px; border-radius: 4px; font-weight: 600; }

.cancel-btn { background: #dc2626; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-size: 12px; transition: 0.3s; }
.cancel-btn:hover { background: #b91c1c; }
.locked-text { color: #94a3b8; font-style: italic; font-size: 13px; }
</style>