<template>
  <div class="page">
    <div class="card">
      <h2 class="title">My Orders List</h2>

      <div class="table-responsive">
        <input
          type="text"
          v-model="search"
          placeholder="Search by Order ID or Product Name..."
          class="search-input"
        />

        <table class="custom-category-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Order ID</th>
              <th>Image</th>
              <th>Product Name</th>
              <th>Quantity</th>
              <th>Total Price</th>
              <th>Status</th> 
              <th>Actions</th>
            </tr>
          </thead>

          <tbody>
            <tr v-for="order in filteredOrders" :key="order.id">
              <td>#{{ order.id }}</td>
              <td style="font-weight:700; color:#2563eb">{{ order.order_id }}</td>
              <td>
                <img :src="getImageUrl(order.image)" class="category-image" />
              </td>
              <td style="font-weight:600">{{ order.product_name }}</td>
              <td>{{ order.qty }} pcs</td>
              <td>৳ {{ order.price }}</td>
              <td>
                <span :class="['status-badge', order.status.toLowerCase()]">
                  {{ order.status }}
                </span>
              </td>
              <td>
                <button class="dropdown-btn" @click="toggleDropdown(order.id, $event)">
                  Actions ▾
                </button>

                <teleport to="body">
                  <transition name="fade">
                    <div v-if="dropdownOpen === order.id" class="dropdown-menu-absolute" :style="dropdownPosition">
                      <button class="view-btn" @click="openViewModal(order)">
                        <i class="fa-solid fa-eye"></i> View Details
                      </button>
                      
                      <button 
                        v-if="order.status === 'Pending'" 
                        @click="cancelOrder(order.id)" 
                        style="color: #f97316;"
                      >
                        <i class="fa-solid fa-circle-xmark"></i> Cancel Order
                      </button>

                      <button @click="openViewModal(order); nextTick(() => printOrder())">
                        <i class="fa-solid fa-print"></i> Quick Print
                      </button>
                    </div>
                  </transition>
                </teleport>
              </td>
            </tr>
            <tr v-if="filteredOrders.length === 0">
              <td colspan="8" style="text-align:center;padding:20px">No orders found.</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <transition name="fade">
      <div v-if="showViewModal" class="modal-overlay" @click.self="showViewModal = false">
        <div class="modal-content order-modal">
          <div class="modal-header">
            <h3>Order Details: {{ selectedOrder.order_id }}</h3>
            <button class="close-modal" @click="showViewModal = false">&times;</button>
          </div>
          
          <div class="order-details-grid">
            <div class="detail-section">
              <h4 class="section-title">Product Information</h4>
              <div class="product-preview">
                <img :src="getImageUrl(selectedOrder.image)" class="large-preview-img" />
                <div>
                  <p><strong>Product:</strong> {{ selectedOrder.product_name }}</p>
                  <p><strong>Quantity:</strong> {{ selectedOrder.qty }} pcs</p>
                  <p><strong>Total Price:</strong> <span class="price-text">৳ {{ selectedOrder.price }}</span></p>
                </div>
              </div>
            </div>

            <div class="detail-section">
              <h4 class="section-title">Shipping Address</h4>
              <div class="info-list">
                <p><strong>Name:</strong> {{ selectedOrder.customer_name }}</p>
                <p><strong>Phone:</strong> {{ selectedOrder.phone }}</p>
                <p><strong>Address:</strong> {{ selectedOrder.address }}, {{ selectedOrder.area }}, {{ selectedOrder.thana }}</p>
              </div>
            </div>

            <div class="detail-section full-width">
              <h4 class="section-title">Current Status</h4>
              <div class="payment-badge" :class="selectedOrder.status?.toLowerCase()">
                <strong>Status:</strong> {{ selectedOrder.status }}
              </div>
            </div>
          </div>

          <div class="modal-footer">
            <button class="cancel-btn" @click="showViewModal = false">Close</button>
            <button class="save-btn" @click="printOrder">Print Invoice</button>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<script setup>
import { ref, computed, nextTick, onMounted } from 'vue'
import axios from 'axios'

const search = ref('')
const dropdownOpen = ref(null)
const dropdownPosition = ref({})
const showViewModal = ref(false)
const selectedOrder = ref({})
const orders = ref([])

const getImageUrl = (img) => {
  if (!img) return 'https://via.placeholder.com/150';
  if (img.startsWith('http')) return img;
  return `http://127.0.0.1:8000/ui_product_images/${img}`;
}

const fetchMyOrders = async () => {
  try {
    const token = localStorage.getItem('token');
    const response = await axios.get("http://127.0.0.1:8000/api/customer/my-orders", {
      headers: { Authorization: `Bearer ${token}` }
    });
    orders.value = response.data;
  } catch (error) {
    console.error("Error fetching orders:", error);
  }
}

const cancelOrder = async (id) => {
  if (!confirm("Are you sure you want to cancel this order?")) return;
  try {
    const token = localStorage.getItem('token');
    await axios.post(`http://127.0.0.1:8000/api/customer/order-cancel/${id}`, {}, {
      headers: { Authorization: `Bearer ${token}` }
    });
    const order = orders.value.find(o => o.id === id);
    if (order) order.status = 'Cancelled';
    dropdownOpen.value = null;
    alert("Order cancelled successfully!");
  } catch (error) {
    alert("Failed to cancel order.");
  }
}

const toggleDropdown = async (id, event) => {
  if (dropdownOpen.value === id) { dropdownOpen.value = null; return; }
  dropdownOpen.value = id
  await nextTick()
  const rect = event.target.getBoundingClientRect()
  dropdownPosition.value = { 
    position: 'absolute', 
    top: `${rect.bottom + window.scrollY}px`, 
    left: `${rect.left + window.scrollX - 80}px`, 
    zIndex: 9999 
  }
}

const openViewModal = (order) => {
  selectedOrder.value = order
  showViewModal.value = true
  dropdownOpen.value = null
}

const printOrder = () => {
  window.print()
}

onMounted(() => { fetchMyOrders(); });

const filteredOrders = computed(() => {
  if (!search.value.trim()) return orders.value
  const s = search.value.toLowerCase()
  return orders.value.filter(o => 
    o.order_id.toLowerCase().includes(s) || 
    o.product_name.toLowerCase().includes(s)
  )
})
</script>

<style scoped>
/* সব স্টাইল ভেন্ডর পেজের মতোই রাখা হয়েছে যেন ইন্টারফেস সেম থাকে */
.page { min-height: 100vh; display:flex; justify-content:center; align-items:flex-start; padding:40px 0; font-family:"Segoe UI", sans-serif; background: #f8fafc;}
.card { width:95%; max-width:1200px; background:#fff; border-radius:12px; padding:2rem; box-shadow:0 4px 20px rgba(0,0,0,0.05); }
.title { text-align:center; font-size:26px; font-weight:600; margin-bottom:30px; color:#1e293b; }
.search-input { width:100%; max-width:350px; padding:0.7rem 1rem; margin-bottom:1.5rem; border:1px solid #e2e8f0; border-radius:8px; outline:none; transition: 0.3s; }
.search-input:focus { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1); }

.table-responsive { width:100%; overflow-x:auto; border-radius:8px; }
.custom-category-table { width:100%; border-collapse:collapse; }
.custom-category-table th, .custom-category-table td { padding:14px 15px; text-align:left; border-bottom:1px solid #f1f5f9; font-size:14px; }
.custom-category-table th { background-color:#f8fafc; font-weight:600; color: #64748b; text-transform: uppercase; font-size: 12px; }

.status-badge { padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 700; text-transform: capitalize; }
.pending { background: #fef9c3; color: #854d0e; }
.confirmed { background: #dbeafe; color: #1e40af; }
.delivered { background: #dcfce7; color: #166534; }
.cancelled { background: #fee2e2; color: #b91c1c; }

.category-image { width:45px; height:45px; object-fit:cover; border-radius:6px; border: 1px solid #e2e8f0; }

/* Dropdown Action Buttons */
.dropdown-btn { padding:7px 14px; border:1px solid #e2e8f0; border-radius:6px; background:#fff; color:#475569; cursor:pointer; font-weight:600; font-size: 13px; transition: 0.2s; }
.dropdown-btn:hover { background: #f1f5f9; }

.dropdown-menu-absolute { background:white; box-shadow:0 10px 25px rgba(0,0,0,0.15); border-radius:8px; overflow:hidden; min-width:170px; border: 1px solid #f1f5f9; }
.dropdown-menu-absolute button { display:block; width:100%; border:none; background:none; padding:11px 15px; cursor:pointer; text-align:left; font-size:13px; transition: 0.2s; color: #334155; }
.dropdown-menu-absolute button:hover { background: #f8fafc; }
.dropdown-menu-absolute button i { margin-right: 8px; width: 14px; }
.view-btn { color:#2563eb !important; font-weight: 600; }

/* Modal & Transitions */
.modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.6); display: flex; align-items: center; justify-content: center; z-index: 10000; backdrop-filter: blur(4px); }
.modal-content { background: white; padding: 30px; border-radius: 16px; width: 95%; max-width: 700px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); }
.order-details-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 10px; }
.detail-section { background: #f8fafc; padding: 15px; border-radius: 10px; border: 1px solid #f1f5f9; }
.full-width { grid-column: span 2; }
.section-title { font-size: 13px; color: #64748b; margin-bottom: 12px; border-bottom: 1px solid #e2e8f0; padding-bottom: 5px; text-transform: uppercase; font-weight: 700; }
.product-preview { display: flex; gap: 15px; align-items: center; }
.large-preview-img { width: 70px; height: 70px; border-radius: 8px; object-fit: cover; }
.price-text { color: #2563eb; font-weight: 800; font-size: 16px; }

.modal-footer { display: flex; justify-content: flex-end; gap: 12px; margin-top: 25px; border-top: 1px solid #f1f5f9; padding-top: 20px; }
.cancel-btn { background: #f1f5f9; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer; font-weight: 600; color: #475569; }
.save-btn { background: #2563eb; color: white; border: none; padding: 10px 25px; border-radius: 8px; cursor: pointer; font-weight: 600; }

.fade-enter-active, .fade-leave-active { transition: opacity 0.3s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }

@media (max-width: 600px) { .order-details-grid { grid-template-columns: 1fr; } .full-width { grid-column: span 1; } }
</style>