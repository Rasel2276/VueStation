<template>
  <div class="page">
    <div class="card">
      <h2 class="title">Customer Orders List</h2>

      <div class="table-responsive">
        <input
          type="text"
          v-model="search"
          placeholder="Search by Order ID or Name..."
          class="search-input"
        />

        <table class="custom-category-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Order ID</th>
              <th>Image</th>
              <th>Product Name</th>
              <th>Customer Name</th>
              <th>Phone Number</th>
              <th>Status</th> <th>Actions</th>
            </tr>
          </thead>

          <tbody>
            <tr v-for="order in filteredOrders" :key="order.id">
              <td>#{{ order.id }}</td>
              <td style="font-weight:700; color:#2563eb">{{ order.order_id }}</td>
              <td>
                <img :src="order.image" class="category-image" />
              </td>
              <td style="font-weight:600">{{ order.product_name }}</td>
              <td>{{ order.customer_name }}</td>
              <td>{{ order.phone }}</td>
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
                      
                      <button @click="updateStatus(order.id, 'Confirmed')" style="color: #2563eb;">
                        <i class="fa-solid fa-check"></i> Confirm Order
                      </button>
                      <button @click="updateStatus(order.id, 'Shipped')" style="color: #7c3aed;">
                        <i class="fa-solid fa-truck"></i> Mark Shipped
                      </button>
                      <button @click="updateStatus(order.id, 'Delivered')" style="color: #16a34a;">
                        <i class="fa-solid fa-house"></i> Mark Delivered
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
                <img :src="selectedOrder.image" class="large-preview-img" />
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

            <div class="detail-section full-width">
              <h4 class="section-title">Payment & Status</h4>
              <div style="display: flex; gap: 10px;">
                <div class="payment-badge">
                  <strong>Method:</strong> {{ selectedOrder.payment_method }}
                </div>
                <div class="payment-badge" style="background: #e0e7ff; color: #3730a3;">
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
import { ref, computed, nextTick } from 'vue'

const search = ref('')
const dropdownOpen = ref(null)
const dropdownPosition = ref({})
const showViewModal = ref(false)
const selectedOrder = ref({})

const orders = ref([
  {
    id: 1,
    order_id: 'ORD-5521',
    product_name: 'Premium Leather Wallet',
    image: 'https://via.placeholder.com/150',
    customer_name: 'Abir Rahman',
    phone: '01712345678',
    thana: 'Mirpur',
    area: 'DOHS',
    address: 'Road 5, House 12, Apt 4A',
    payment_method: 'Cash On Delivery',
    status: 'Pending',
    qty: 2,
    price: 1500
  },
  {
    id: 2,
    order_id: 'ORD-9902',
    product_name: 'Wireless Bluetooth Earbuds',
    image: 'https://via.placeholder.com/150',
    customer_name: 'Sarah Khan',
    phone: '01987654321',
    thana: 'Uttara',
    area: 'Sector 4',
    address: 'Road 12, House 3',
    payment_method: 'bKash',
    status: 'Confirmed',
    qty: 1,
    price: 2200
  }
])

const updateStatus = (id, newStatus) => {
  const order = orders.value.find(o => o.id === id)
  if (order) order.status = newStatus
  dropdownOpen.value = null
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

const deleteOrder = (id) => {
  if(confirm('Are you sure?')) {
    orders.value = orders.value.filter(o => o.id !== id)
    dropdownOpen.value = null
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
.page { min-height: 100vh; display:flex; justify-content:center; align-items:flex-start; padding:40px 0; font-family:"Segoe UI", sans-serif;}
.card { width:95%; max-width:1200px; background:#fff; border-radius:12px; padding:2rem; box-shadow:0 4px 20px rgba(0,0,0,0.05); }
.title { text-align:center; font-size:26px; font-weight:600; margin-bottom:30px; color:#1e293b; }
.search-input { width:100%; max-width:350px; padding:0.7rem 1rem; margin-bottom:1.5rem; border:1px solid #e2e8f0; border-radius:8px; outline:none; }
.search-input:focus { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1); }

.table-responsive { width:100%; overflow-x:auto; border-radius:8px; }
.custom-category-table { width:100%; border-collapse:collapse; }
.custom-category-table th, .custom-category-table td { padding:14px 15px; text-align:left; border-bottom:1px solid #f1f5f9; font-size:14px; }
.custom-category-table th { background-color:#f8fafc; font-weight:600; color: #64748b; text-transform: uppercase; font-size: 12px; }

.status-badge { padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 700; }
.pending { background: #fef9c3; color: #854d0e; }
.confirmed { background: #dbeafe; color: #1e40af; }
.shipped { background: #e0e7ff; color: #3730a3; }
.delivered { background: #dcfce7; color: #166534; }

.category-image { width:50px; height:50px; object-fit:cover; border-radius:6px; border: 1px solid #e2e8f0; }
.dropdown-btn { padding:7px 14px; border:none; border-radius:6px; background:#2563eb; color:white; cursor:pointer; font-weight:600; font-size: 13px; }

.dropdown-menu-absolute { background:white; box-shadow:0 10px 25px rgba(0,0,0,0.15); border-radius:8px; overflow:hidden; min-width:160px; border: 1px solid #f1f5f9; }
.dropdown-menu-absolute button { display:block; width:100%; border:none; background:none; padding:12px 15px; cursor:pointer; text-align:left; font-size:14px; transition: 0.2s; }
.dropdown-menu-absolute button:hover { background: #f8fafc; }
.view-btn { color:#2563eb; font-weight: 500; }
.delete-btn { color:#dc2626; font-weight: 500; }

.order-modal { max-width: 700px !important; }
.order-details-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 10px; }
.full-width { grid-column: span 2; }
.detail-section { background: #f8fafc; padding: 15px; border-radius: 10px; border: 1px solid #f1f5f9; }
.section-title { font-size: 14px; color: #64748b; margin-bottom: 12px; border-bottom: 1px solid #e2e8f0; padding-bottom: 5px; text-transform: uppercase; }

.product-preview { display: flex; gap: 15px; align-items: center; }
.large-preview-img { width: 80px; height: 80px; border-radius: 8px; object-fit: cover; border: 1px solid #ddd; }
.price-text { color: #2563eb; font-weight: 800; font-size: 16px; }

.info-list p { margin-bottom: 8px; font-size: 14px; color: #334155; }
.payment-badge { background: #dcfce7; color: #166534; padding: 8px 12px; border-radius: 6px; display: inline-block; font-size: 14px; }

.modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.6); display: flex; align-items: center; justify-content: center; z-index: 10000; backdrop-filter: blur(4px); }
.modal-content { background: white; padding: 30px; border-radius: 16px; width: 95%; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); }
.modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
.close-modal { background: #f1f5f9; border: none; font-size: 20px; cursor: pointer; width: 32px; height: 32px; border-radius: 50%; }

.modal-footer { display: flex; justify-content: flex-end; gap: 12px; margin-top: 25px; border-top: 1px solid #f1f5f9; padding-top: 20px; }
.cancel-btn { background: #f1f5f9; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer; font-weight: 600; color: #475569; }
.save-btn { background: #2563eb; color: white; border: none; padding: 10px 25px; border-radius: 8px; cursor: pointer; font-weight: 600; }

.fade-enter-active, .fade-leave-active { transition: opacity 0.3s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }

@media (max-width: 600px) {
  .order-details-grid { grid-template-columns: 1fr; }
  .full-width { grid-column: span 1; }
}
</style>