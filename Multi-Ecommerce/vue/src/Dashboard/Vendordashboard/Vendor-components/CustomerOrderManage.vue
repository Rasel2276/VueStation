<template>
  <div class="page">
    <div class="card">
      <h2 class="title">Customer Orders List</h2>

      <div class="table-wrapper">
        <input
          type="text"
          v-model="search"
          placeholder="Search by Order ID or Name..."
          class="search-input"
        />

        <div class="table-responsive">
          <table class="custom-category-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Order ID</th>
                <th>Image</th>
                <th>Product Name</th>
                <th>Customer Name</th>
                <th>Phone Number</th>
                <th>Status</th> 
                <th>Actions</th>
              </tr>
            </thead>

            <tbody>
              <tr v-for="order in filteredOrders" :key="order.id">
                <td data-label="ID">#{{ order.id }}</td>
                <td data-label="Order ID" style="font-weight:700; color:#2563eb">{{ order.order_id }}</td>
                <td data-label="Image">
                  <img :src="getImageUrl(order.image)" class="category-image" />
                </td>
                <td data-label="Product Name" style="font-weight:600">{{ order.product_name }}</td>
                <td data-label="Customer Name">{{ order.customer_name }}</td>
                <td data-label="Phone Number">{{ order.phone }}</td>
                <td data-label="Status">
                  <span :class="['status-badge', order.status.toLowerCase()]">
                    {{ order.status }}
                  </span>
                </td>
                <td data-label="Actions">
                  <button class="dropdown-btn" @click="toggleDropdown(order.id, $event)">
                    Actions ▾
                  </button>

                  <teleport to="body">
                    <transition name="fade">
                      <div v-if="dropdownOpen === order.id" class="dropdown-menu-absolute" :style="dropdownPosition">
                        <button class="view-btn" @click="openViewModal(order)">
                          <i class="fa-solid fa-eye"></i> View Details
                        </button>
                        
                        <button @click="updateStatus(order.id, 'Confirmed')" style="color: #2563eb;">
                          <i class="fa-solid fa-check"></i> Confirm Order
                        </button>
                        <button @click="updateStatus(order.id, 'Shipped')" style="color: #7c3aed;">
                          <i class="fa-solid fa-truck"></i> Mark Shipped
                        </button>
                        <button @click="updateStatus(order.id, 'Delivered')" style="color: #16a34a;">
                          <i class="fa-solid fa-house"></i> Mark Delivered
                        </button>
                        
                        <button @click="updateStatus(order.id, 'Cancelled')" style="color: #64748b;">
                          <i class="fa-solid fa-ban"></i> Cancel Order
                        </button>

                        <button class="delete-btn" @click="deleteOrder(order.id)">
                          <i class="fa-solid fa-trash"></i> Delete Order
                        </button>
                      </div>
                    </transition>
                  </teleport>
                </td>
              </tr>
              <tr v-if="filteredOrders.length === 0">
                <td colspan="8" class="no-data">No orders found.</td>
              </tr>
            </tbody>
          </table>
        </div>
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
                  <p><strong>Price (Per Unit):</strong> ৳ {{ selectedOrder.price / selectedOrder.qty }}</p>
                  <p><strong>Quantity:</strong> {{ selectedOrder.qty }} pcs</p>
                  <p><strong>Total Price:</strong> <span class="price-text">৳ {{ selectedOrder.price }}</span></p>
                </div>
              </div>
            </div>

            <div class="detail-section">
              <h4 class="section-title">Customer & Shipping</h4>
              <div class="info-list">
                <p><strong>Name:</strong> {{ selectedOrder.customer_name }}</p>
                <p><strong>Phone:</strong> {{ selectedOrder.phone }}</p>
                <p><strong>Thana/Upazila:</strong> {{ selectedOrder.thana }}</p>
                <p><strong>Area/Village:</strong> {{ selectedOrder.area }}</p>
                <p><strong>Address:</strong> {{ selectedOrder.address }}</p>
              </div>
            </div>

            <div class="detail-section full-width payment-status-section">
              <h4 class="section-title">Payment & Status</h4>
              <div class="badge-container">
                <div class="payment-badge">
                  <strong>Method:</strong> {{ selectedOrder.payment_method }}
                </div>
                <div class="payment-badge status-indicator">
                  <strong>Status:</strong> {{ selectedOrder.status }}
                </div>
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
import api, { BASE_URL } from '../../../axios';

const search = ref('')
const dropdownOpen = ref(null)
const dropdownPosition = ref({})
const showViewModal = ref(false)
const selectedOrder = ref({})
const orders = ref([])

const getImageUrl = (img) => {
  if (!img) return 'https://via.placeholder.com/150';
  if (img.startsWith('http')) return img;
  return `${BASE_URL}/ui_product_images/${img}`;
}

const fetchOrders = async () => {
  try {
    const token = localStorage.getItem('token');
    const response = await api.get("/vendor/orders", {
      headers: { Authorization: `Bearer ${token}` }
    });
    orders.value = response.data;
  } catch (error) {
    console.error("Error fetching orders:", error);
  }
}

onMounted(() => {
  fetchOrders();
});

const updateStatus = async (id, newStatus) => {
  try {
    const token = localStorage.getItem('token');
    const response = await api.post(`/vendor/order-status/${id}`, 
    { status: newStatus },
    { headers: { Authorization: `Bearer ${token}` } });
    
    const order = orders.value.find(o => o.id === id)
    if (order) order.status = newStatus
    dropdownOpen.value = null
    alert(response.data.message || "Status updated successfully!");
  } catch (error) {
    alert(error.response?.data?.message || "Failed to update status.");
  }
}

const toggleDropdown = async (id, event) => {
  if (dropdownOpen.value === id) { dropdownOpen.value = null; return; }
  dropdownOpen.value = id
  await nextTick()
  const rect = event.target.getBoundingClientRect()
  
  let leftPos = rect.left + window.scrollX - 80;
  if (leftPos + 160 > window.innerWidth) leftPos = window.innerWidth - 170;
  if (leftPos < 10) leftPos = 10;

  dropdownPosition.value = { 
    position: 'absolute', 
    top: `${rect.bottom + window.scrollY}px`, 
    left: `${leftPos}px`, 
    zIndex: 9999 
  }
}

const openViewModal = (order) => {
  selectedOrder.value = order
  showViewModal.value = true
  dropdownOpen.value = null
}

const deleteOrder = async (id) => {
  if(confirm('Are you sure you want to delete this order?')) {
    try {
      const token = localStorage.getItem('token');
      await api.delete(`/vendor/order-delete/${id}`, {
        headers: { Authorization: `Bearer ${token}` }
      });
      orders.value = orders.value.filter(o => o.id !== id)
      dropdownOpen.value = null
      alert("Order deleted.");
    } catch (error) {
      alert("Failed to delete order.");
    }
  }
}

const printOrder = () => {
  window.print()
}

const filteredOrders = computed(() => {
  if (!search.value.trim()) return orders.value
  const s = search.value.toLowerCase()
  return orders.value.filter(o => 
    o.order_id.toLowerCase().includes(s) || 
    o.customer_name.toLowerCase().includes(s)
  )
})
</script>

<style scoped>
/* 🔒 DESKTOP DESIGN - 100% UNCHANGED */
.page { min-height: 100vh; display:flex; justify-content:center; align-items:flex-start; padding:40px 0; font-family:"Segoe UI", sans-serif; box-sizing: border-box;}
.card { width:95%; max-width:1200px; background:#fff; border-radius:12px; padding:2rem; box-shadow:0 4px 20px rgba(0,0,0,0.05); box-sizing: border-box; }
.title { text-align:center; font-size:26px; font-weight:600; margin-bottom:30px; color:#1e293b; }
.search-input { width:100%; max-width:350px; padding:0.7rem 1rem; margin-bottom:1.5rem; border:1px solid #e2e8f0; border-radius:8px; outline:none; box-sizing: border-box; }
.search-input:focus { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1); }
.table-wrapper { width: 100%; }
.table-responsive { width:100%; border-radius:8px; }
.custom-category-table { width:100%; border-collapse:collapse; }
.custom-category-table th, .custom-category-table td { padding:14px 15px; text-align:left; border-bottom:1px solid #f1f5f9; font-size:14px; }
.custom-category-table th { background-color:#f8fafc; font-weight:600; color: #64748b; text-transform: uppercase; font-size: 12px; }
.status-badge { padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 700; text-transform: capitalize; display: inline-block;}
.pending { background: #fef9c3; color: #854d0e; }
.confirmed { background: #dbeafe; color: #1e40af; }
.shipped { background: #e0e7ff; color: #3730a3; }
.delivered { background: #dcfce7; color: #166534; }
.cancelled { background: #fee2e2; color: #b91c1c; }
.category-image { width:50px; height:50px; object-fit:cover; border-radius:6px; border: 1px solid #e2e8f0; }
.dropdown-btn { padding:7px 14px; border:none; border-radius:6px; background:#2563eb; color:white; cursor:pointer; font-weight:600; font-size: 13px; }
.dropdown-menu-absolute { background:white; box-shadow:0 10px 25px rgba(0,0,0,0.15); border-radius:8px; overflow:hidden; min-width:160px; border: 1px solid #f1f5f9; }
.dropdown-menu-absolute button { display:block; width:100%; border:none; background:none; padding:12px 15px; cursor:pointer; text-align:left; font-size:14px; transition: 0.2s; }
.dropdown-menu-absolute button:hover { background: #f8fafc; }
.view-btn { color:#2563eb; font-weight: 500; }
.delete-btn { color:#dc2626; font-weight: 500; }
.no-data { text-align:center; padding:20px; color:#64748b; }

/* Modal */
.modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.6); display: flex; align-items: center; justify-content: center; z-index: 10000; backdrop-filter: blur(4px); padding: 15px; box-sizing: border-box; }
.modal-content { background: white; padding: 30px; border-radius: 16px; width: 100%; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); max-height: 90vh; overflow-y: auto; box-sizing: border-box; }
.order-modal { max-width: 700px; }
.modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
.close-modal { background: #f1f5f9; border: none; font-size: 20px; cursor: pointer; width: 32px; height: 32px; border-radius: 50%; }
.order-details-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 10px; }
.full-width { grid-column: span 2; }
.detail-section { background: #f8fafc; padding: 15px; border-radius: 10px; border: 1px solid #f1f5f9; }
.section-title { font-size: 14px; color: #64748b; margin-bottom: 12px; border-bottom: 1px solid #e2e8f0; padding-bottom: 5px; text-transform: uppercase; font-weight: 700; }
.product-preview { display: flex; gap: 15px; align-items: center; }
.large-preview-img { width: 80px; height: 80px; border-radius: 8px; object-fit: cover; border: 1px solid #ddd; }
.price-text { color: #2563eb; font-weight: 800; font-size: 16px; }
.info-list p { margin-bottom: 8px; font-size: 14px; color: #334155; }
.badge-container { display: flex; gap: 10px; flex-wrap: wrap; }
.payment-badge { background: #dcfce7; color: #166534; padding: 8px 12px; border-radius: 6px; font-size: 14px; }
.status-indicator { background: #e0e7ff; color: #3730a3; }
.modal-footer { display: flex; justify-content: flex-end; gap: 12px; margin-top: 25px; border-top: 1px solid #f1f5f9; padding-top: 20px; }
.cancel-btn { background: #f1f5f9; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer; font-weight: 600; color: #475569; }
.save-btn { background: #2563eb; color: white; border: none; padding: 10px 25px; border-radius: 8px; cursor: pointer; font-weight: 600; }

.fade-enter-active, .fade-leave-active { transition: opacity 0.3s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }

/* 📱 MOBILE RESPONSIVE - ADDED */
@media (max-width: 900px) {
  .page { padding: 15px 10px; }
  .card { padding: 1.5rem; border-radius: 12px; width: 100%; }
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

  .order-details-grid { grid-template-columns: 1fr; }
  .full-width { grid-column: span 1; }
  .modal-content { padding: 20px; }
  .modal-footer { flex-direction: column; }
  .modal-footer button { width: 100%; }
  .product-preview { flex-direction: column; align-items: flex-start; }
}

@media print {
  .page *, .dropdown-btn, .search-input, .close-modal, .cancel-btn { display: none !important; }
  .modal-overlay { position: absolute; background: white; }
  .modal-content { box-shadow: none; width: 100%; max-width: 100%; }
  .save-btn { display: none; }
}
</style>