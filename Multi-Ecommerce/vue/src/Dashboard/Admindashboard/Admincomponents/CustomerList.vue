<template>
  <div class="page">
    <div class="card">
      <h2 class="title">Customer Directory</h2>

      <div class="table-wrapper">
        <input
          type="text"
          v-model="search"
          placeholder="Search by Customer Name or Email..."
          class="search-input"
        />

        <div class="table-responsive">
          <table class="custom-category-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Customer Name</th>
                <th>Email Address</th>
                <th>Account Status</th>
                <th>Registration Date</th>
                <th>Actions</th>
              </tr>
            </thead>

            <tbody>
              <tr v-for="customer in filteredCustomers" :key="customer.id">
                <td data-label="ID">#{{ customer.id }}</td>
                <td data-label="Customer Name" style="font-weight:600">
                  {{ customer.name }}
                </td>
                <td data-label="Email Address">{{ customer.email }}</td>
                <td data-label="Account Status">
                  <span class="status-customer">{{ customer.role }}</span>
                </td>
                <td data-label="Registration Date">{{ formatDate(customer.created_at) }}</td>
                <td data-label="Actions">
                  <button class="dropdown-btn" @click="toggleDropdown(customer.id, $event)">
                    Actions ▾
                  </button>

                  <teleport to="body">
                    <transition name="fade">
                      <div
                        v-if="dropdownOpen === customer.id"
                        class="dropdown-menu-absolute"
                        :style="dropdownPosition"
                      >
                        <button class="view-btn" @click="viewCustomer(customer)">
                          👁️ View Profile
                        </button>
                        <button class="edit-btn" @click="editCustomer(customer.id)">
                          📝 Edit User
                        </button>
                        <button class="delete-btn" @click="deleteCustomer(customer.id)">
                          🗑️ Remove Customer
                        </button>
                      </div>
                    </transition>
                  </teleport>
                </td>
              </tr>

              <tr v-if="filteredCustomers.length === 0">
                <td colspan="6" class="no-data">
                  No customers found in the system.
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

const search = ref('')
const customers = ref([])
const dropdownOpen = ref(null)
const dropdownPosition = ref({})
const token = localStorage.getItem('token') // Admin Token

const fetchCustomers = async () => {
  try {
    // আপনার এডমিন ভেন্ডর এপিআই থেকেই ডাটা আনা হচ্ছে
    const res = await axios.get('http://127.0.0.1:8000/api/admin/customers', {
      headers: { Authorization: `Bearer ${token}` }
    })
    // এখানে শুধুমাত্র 'customer' রোল ফিল্টার করা হচ্ছে
    // যদি আপনার এপিআই আলাদা হয় তবে ইউআরএল পরিবর্তন করে নিতে পারেন
    customers.value = res.data.filter(user => user.role === 'customer')
  } catch (err) {
    console.error('Error fetching customers:', err)
  }
}

onMounted(fetchCustomers)

const filteredCustomers = computed(() => {
  if (!search.value.trim()) return customers.value
  return customers.value.filter(c => 
    c.name.toLowerCase().includes(search.value.toLowerCase()) ||
    c.email.toLowerCase().includes(search.value.toLowerCase())
  )
})

const formatDate = d => new Date(d).toLocaleDateString()

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

// Action Functions
const viewCustomer = (c) => { alert(`Customer: ${c.name}\nEmail: ${c.email}`); dropdownOpen.value = null; }
const editCustomer = (id) => { console.log('Edit customer:', id); dropdownOpen.value = null; }
const deleteCustomer = (id) => { 
  if(confirm('Are you sure you want to remove this customer?')) {
    console.log('Delete customer:', id);
    dropdownOpen.value = null;
  }
}
</script>

<style scoped>
/* 🔒 DESKTOP DESIGN - 100% SAME AS VENDOR LIST */
.page { min-height: 100vh; display:flex; justify-content:center; align-items:flex-start; padding:40px 0; font-family:"Segoe UI", sans-serif; background-color: #f8fafc; box-sizing: border-box; }
.card { width:100%; max-width:1100px; background:#fff; border-radius:8px; padding:1.5rem; box-shadow:0 4px 6px -1px rgba(0,0,0,0.1); box-sizing: border-box; }
.title { text-align:center; font-size:24px; font-weight:600; margin-bottom:25px; color:#1e293b; }
.search-input { width:100%; max-width:300px; padding:0.6rem 1rem; margin-bottom:1.2rem; border:1px solid #e2e8f0; border-radius:.375rem; outline: none; box-sizing: border-box; }
.table-wrapper { width: 100%; }
.table-responsive { width:100%; border-radius:8px; background:white; }
.custom-category-table { width:100%; border-collapse:collapse; }
.custom-category-table th, .custom-category-table td { padding:14px 15px; text-align:left; border-bottom:1px solid #f1f5f9; font-size:14px; }
.custom-category-table th { background-color:#f8fafc; font-weight:600; color: #475569; }

/* কাস্টমার স্ট্যাটাস কালার - একটু আলাদা নীল দেওয়া হয়েছে */
.status-customer { background: #ecfdf5; color: #059669; padding: 4px 10px; border-radius: 4px; font-size: 12px; font-weight: 600; text-transform: uppercase; }

.dropdown-btn { padding:6px 14px; border:none; border-radius:4px; background:#1e293b; color:white; cursor:pointer; font-weight:600; font-size: 13px; }
.dropdown-menu-absolute { background:#ffffff; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); border: 1px solid #e2e8f0; border-radius:6px; overflow:hidden; min-width:180px; }
.dropdown-menu-absolute button { display:block; width:100%; border:none; background:none; padding:12px 15px; cursor:pointer; text-align:left; font-size:13px; transition: 0.2s; }
.dropdown-menu-absolute button:hover { background: #f8fafc; }

.view-btn { color: #64748b; border-bottom: 1px solid #f1f5f9; }
.edit-btn { color: #059669; border-bottom: 1px solid #f1f5f9; }
.delete-btn { color: #dc2626; }
.no-data { text-align:center; padding:20px; color: #64748b; }

.fade-enter-active, .fade-leave-active { transition: opacity 0.2s; }
.fade-enter-from, .fade-leave-to { opacity: 0; }

/* 📱 MOBILE RESPONSIVE - CARD STYLE */
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