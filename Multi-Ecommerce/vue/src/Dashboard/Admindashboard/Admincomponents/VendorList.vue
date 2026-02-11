<template>
  <div class="page">
    <div class="card">
      <h2 class="title">Vendor Directory</h2>

      <div class="table-wrapper">
        <input
          type="text"
          v-model="search"
          placeholder="Search by Vendor Name or Email..."
          class="search-input"
        />

        <div class="table-responsive">
          <table class="custom-category-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Vendor Name</th>
                <th>Email Address</th>
                <th>Role</th>
                <th>Joined Date</th>
                <th>Actions</th>
              </tr>
            </thead>

            <tbody>
              <tr v-for="vendor in filteredVendors" :key="vendor.id">
                <td data-label="ID">#{{ vendor.id }}</td>
                <td data-label="Vendor Name" style="font-weight:600">
                  {{ vendor.name }}
                </td>
                <td data-label="Email Address">{{ vendor.email }}</td>
                <td data-label="Role">
                  <span class="status-vendor">{{ vendor.role }}</span>
                </td>
                <td data-label="Joined Date">{{ formatDate(vendor.created_at) }}</td>
                <td data-label="Actions">
                  <button class="dropdown-btn" @click="toggleDropdown(vendor.id, $event)">
                    Actions ▾
                  </button>

                  <teleport to="body">
                    <transition name="fade">
                      <div
                        v-if="dropdownOpen === vendor.id"
                        class="dropdown-menu-absolute"
                        :style="dropdownPosition"
                      >
                        <button class="view-btn" @click="viewVendor(vendor)">
                          👁️ View Profile
                        </button>
                        <button class="edit-btn" @click="editVendor(vendor.id)">
                          📝 Edit User
                        </button>
                        <button class="delete-btn" @click="deleteVendor(vendor.id)">
                          🗑️ Remove Vendor
                        </button>
                      </div>
                    </transition>
                  </teleport>
                </td>
              </tr>

              <tr v-if="filteredVendors.length === 0">
                <td colspan="6" class="no-data">
                  No vendors found in the system.
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
const vendors = ref([])
const dropdownOpen = ref(null)
const dropdownPosition = ref({})
const token = localStorage.getItem('token') // Admin Token

const fetchVendors = async () => {
  try {
    // এখানে আপনার API এন্ডপয়েন্ট দিন যা সব ইউজার আনে
    const res = await axios.get('http://127.0.0.1:8000/api/admin/vendors', {
      headers: { Authorization: `Bearer ${token}` }
    })
    // শুধুমাত্র ভেন্ডরদের ফিল্টার করে রাখা হচ্ছে
    vendors.value = res.data.filter(user => user.role === 'vendor')
  } catch (err) {
    console.error('Error fetching vendors:', err)
  }
}

onMounted(fetchVendors)

const filteredVendors = computed(() => {
  if (!search.value.trim()) return vendors.value
  return vendors.value.filter(v => 
    v.name.toLowerCase().includes(search.value.toLowerCase()) ||
    v.email.toLowerCase().includes(search.value.toLowerCase())
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
const viewVendor = (v) => { alert(`Vendor: ${v.name}\nEmail: ${v.email}`); dropdownOpen.value = null; }
const editVendor = (id) => { console.log('Edit vendor:', id); dropdownOpen.value = null; }
const deleteVendor = (id) => { 
  if(confirm('Are you sure you want to remove this vendor?')) {
    console.log('Delete vendor:', id);
    dropdownOpen.value = null;
  }
}
</script>

<style scoped>
/* 🔒 DESKTOP DESIGN - 100% MY PURCHASE STYLE */
.page { min-height: 100vh; display:flex; justify-content:center; align-items:flex-start; padding:40px 0; font-family:"Segoe UI", sans-serif; background-color: #f8fafc; box-sizing: border-box; }
.card { width:100%; max-width:1100px; background:#fff; border-radius:8px; padding:1.5rem; box-shadow:0 4px 6px -1px rgba(0,0,0,0.1); box-sizing: border-box; }
.title { text-align:center; font-size:24px; font-weight:600; margin-bottom:25px; color:#1e293b; }
.search-input { width:100%; max-width:300px; padding:0.6rem 1rem; margin-bottom:1.2rem; border:1px solid #e2e8f0; border-radius:.375rem; outline: none; box-sizing: border-box; }
.table-wrapper { width: 100%; }
.table-responsive { width:100%; border-radius:8px; background:white; }
.custom-category-table { width:100%; border-collapse:collapse; }
.custom-category-table th, .custom-category-table td { padding:14px 15px; text-align:left; border-bottom:1px solid #f1f5f9; font-size:14px; }
.custom-category-table th { background-color:#f8fafc; font-weight:600; color: #475569; }

.status-vendor { background: #e0e7ff; color: #4338ca; padding: 4px 10px; border-radius: 4px; font-size: 12px; font-weight: 600; text-transform: uppercase; }

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