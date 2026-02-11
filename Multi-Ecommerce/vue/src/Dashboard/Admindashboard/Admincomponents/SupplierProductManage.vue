<template>
  <div class="page">
    <div class="card">
      <h2 class="title">Manage Supplier Products</h2>

      <div class="table-wrapper">
        <div class="search-container">
          <input 
            type="text" 
            v-model="search" 
            placeholder="Search products..." 
            class="search-input" 
          />
        </div>

        <div class="table-responsive">
          <table class="custom-category-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Product Name</th>
                <th>SKU</th>
                <th>Category</th>
                <th>Subcategory</th>
                <th>Supplier</th>
                <th>Price</th>
                <th>Status</th>
                <th>Image</th>
                <th>Actions</th>
              </tr>
            </thead>

            <tbody>
              <tr v-for="product in filteredProducts" :key="product.id">
                <td data-label="ID">#{{ product.id }}</td>
                <td data-label="Product Name" class="bold-text">{{ product.product_name }}</td>
                <td data-label="SKU">{{ product.sku || 'N/A' }}</td>
                <td data-label="Category">{{ product.category?.category_name }}</td>
                <td data-label="Subcategory">{{ product.subcategory?.subcategory_name }}</td>
                <td data-label="Supplier">{{ product.supplier?.supplier_name }}</td>
                <td data-label="Price">{{ product.base_price }}</td>
                <td data-label="Status">
                  <span :class="['status-badge', product.status?.toLowerCase()]">
                    {{ product.status }}
                  </span>
                </td>
                <td data-label="Image">
                  <img
                    v-if="product.product_image"
                    :src="imageUrl(product.product_image)"
                    class="category-image"
                  />
                  <span v-else>No Image</span>
                </td>
                <td data-label="Actions">
                  <button @click="toggleDropdown(product.id, $event)" class="dropdown-btn">Actions ▾</button>
                  <teleport to="body">
                    <transition name="fade">
                      <div
                        v-if="dropdownOpen === product.id"
                        class="dropdown-menu-absolute"
                        :style="dropdownPosition"
                      >
                        <button @click="editProduct(product)" class="edit-btn">📝 Edit</button>
                        <button @click="deleteProduct(product.id)" class="delete-btn">🗑️ Delete</button>
                      </div>
                    </transition>
                  </teleport>
                </td>
              </tr>
              <tr v-if="filteredProducts.length === 0">
                <td colspan="10" class="no-data">No products found.</td>
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
const products = ref([])
const dropdownOpen = ref(null)
const dropdownPosition = ref({})
const token = localStorage.getItem('token')

const fetchProducts = async () => {
  try {
    const res = await axios.get('http://127.0.0.1:8000/api/admin/products', {
      headers: { Authorization: `Bearer ${token}` }
    })
    products.value = res.data
  } catch (err) {
    console.error(err)
  }
}

onMounted(fetchProducts)

const filteredProducts = computed(() => {
  if (!search.value.trim()) return products.value
  const s = search.value.toLowerCase()
  return products.value.filter(p =>
    p.product_name.toLowerCase().includes(s) ||
    (p.sku && p.sku.toLowerCase().includes(s)) ||
    p.category?.category_name.toLowerCase().includes(s) ||
    p.subcategory?.subcategory_name.toLowerCase().includes(s) ||
    p.supplier?.supplier_name.toLowerCase().includes(s) ||
    p.status.toLowerCase().includes(s)
  )
})

const imageUrl = (filename) => `http://127.0.0.1:8000/product_images/${filename}`

const toggleDropdown = async (id, event) => {
  if (dropdownOpen.value === id) {
    dropdownOpen.value = null
    return
  }
  dropdownOpen.value = id
  await nextTick()
  const rect = event.target.getBoundingClientRect()
  let leftPos = rect.left + window.scrollX - 80;
  if (leftPos + 150 > window.innerWidth) leftPos = window.innerWidth - 160;
  
  dropdownPosition.value = {
    position: 'absolute',
    top: `${rect.bottom + window.scrollY + 5}px`,
    left: `${leftPos}px`,
    zIndex: 9999
  }
}

const deleteProduct = async (id) => {
  if (!confirm('Are you sure you want to delete?')) return
  try {
    await axios.delete(`http://127.0.0.1:8000/api/admin/products/${id}`, {
      headers: { Authorization: `Bearer ${token}` }
    })
    products.value = products.value.filter(p => p.id !== id)
    dropdownOpen.value = null
  } catch (err) {
    alert('Failed to delete product')
  }
}

const editProduct = (product) => {
  alert(`Edit: ${product.product_name}`)
  dropdownOpen.value = null
}

window.addEventListener('click', (e) => {
  if (!e.target.classList.contains('dropdown-btn')) {
    dropdownOpen.value = null;
  }
});
</script>

<style scoped>
/* 🔒 DESKTOP DESIGN - UNCHANGED */
.page { min-height: 100vh; display: flex; justify-content: center; align-items: flex-start; padding: 40px 15px; background-color: #f8fafc; box-sizing: border-box; font-family: "Segoe UI", sans-serif; }
.card { width: 100%; max-width: 1200px; background: #fff; border-radius: 8px; padding: 2rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); box-sizing: border-box; }
.title { text-align: center; font-size: 24px; font-weight: 600; margin-bottom: 25px; color: #1e293b; }
.search-input { width: 100%; max-width: 300px; padding: 0.6rem 1rem; margin-bottom: 1.2rem; border: 1px solid #e2e8f0; border-radius: 6px; box-sizing: border-box; }
.table-responsive { width: 100%; }
.custom-category-table { width: 100%; border-collapse: collapse; }
.custom-category-table th, .custom-category-table td { padding: 14px 15px; text-align: left; border-bottom: 1px solid #f1f5f9; font-size: 14px; }
.custom-category-table th { background-color: #f8fafc; font-weight: 600; color: #475569; }
.category-image { width: 45px; height: 45px; object-fit: cover; border-radius: 6px; }
.status-badge { padding: 4px 10px; border-radius: 4px; font-size: 12px; font-weight: 600; text-transform: uppercase; }
.status-badge.active { background: #ecfdf5; color: #059669; }
.status-badge.inactive { background: #fef2f2; color: #dc2626; }
.dropdown-btn { padding: 6px 14px; border: none; border-radius: 4px; background: #2563eb; color: white; cursor: pointer; font-weight: 600; font-size: 13px; }
.dropdown-menu-absolute { background: #ffffff; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); border: 1px solid #e2e8f0; border-radius: 6px; min-width: 150px; }
.dropdown-menu-absolute button { display: block; width: 100%; border: none; background: none; padding: 12px 15px; cursor: pointer; text-align: left; font-size: 13px; }
.edit-btn { color: #059669; border-bottom: 1px solid #f1f5f9; }
.delete-btn { color: #dc2626; }

/* 📱 MOBILE RESPONSIVE - EXACTLY LIKE SUPPLIER MANAGE */
@media (max-width: 1024px) {
  .page { padding: 10px 5px; }
  .card { padding: 1rem; border-radius: 12px; }
  .search-input { max-width: 100%; width: 100%; margin-bottom: 1.5rem; }
  .custom-category-table thead { display: none; } /* হাইড হেডার */
  .custom-category-table tr { 
    display: block; border: 1px solid #e2e8f0; margin-bottom: 15px; 
    border-radius: 12px; padding: 8px; background: #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.02);
  }
  .custom-category-table td { 
    display: flex; justify-content: flex-start; align-items: flex-start; 
    padding: 10px 8px; border-bottom: 1px solid #f8fafc; position: relative;
  }
  .custom-category-table td:last-child { border-bottom: none; }
  
  /* এটাই সেই ম্যাজিক যা লেবেল বামে দেখাবে */
  .custom-category-table td::before { 
    content: attr(data-label); 
    font-weight: 700; 
    color: #64748b; 
    font-size: 11px; 
    text-transform: uppercase; 
    width: 40%; /* লেবেলের জায়গা */
    flex-shrink: 0; 
    text-align: left; 
    padding-right: 10px;
    box-sizing: border-box;
  }
  .custom-category-table td { 
    text-align: left; 
    font-size: 13px; 
    color: #1e293b; 
    word-break: break-word; 
    flex: 1; /* ভ্যালুর জায়গা */
  }
  .bold-text { font-weight: 600; }
}

@media (max-width: 480px) {
  .custom-category-table td::before { width: 45%; }
}

.fade-enter-active, .fade-leave-active { transition: opacity 0.2s, transform 0.2s; }
.fade-enter-from, .fade-leave-to { opacity: 0; transform: translateY(-5px); }
</style>