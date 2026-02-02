<template>
  <div class="page">
    <div class="card">
      <h2 class="title">Manage Customer Products</h2>

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
              <th>Image</th>
              <th>Brand</th>
              <th>Details</th>
              <th>Quantity</th>
              <th>Price</th>
              <th>Actions</th>
            </tr>
          </thead>

          <tbody>
            <tr v-for="product in filteredProducts" :key="product.id">
              <td>#{{ product.id }}</td>
              <td style="font-weight:600">{{ product.name }}</td>
              <td>
                <img v-if="product.image" :src="imageUrl(product.image)" class="category-image" />
                <span v-else>No Image</span>
              </td>
              <td>{{ product.brand || 'N/A' }}</td>
              <td class="details-text" :title="product.details">
                {{ truncateText(product.details, 30) }}
              </td>
              <td>{{ product.quantity }}</td>
              <td style="font-weight:bold; color:#2563eb">৳ {{ product.price }}</td>
              <td>
                <button class="dropdown-btn" @click="toggleDropdown(product.id, $event)">
                  Actions ▾
                </button>

                <teleport to="body">
                  <transition name="fade">
                    <div v-if="dropdownOpen === product.id" class="dropdown-menu-absolute" :style="dropdownPosition">
                      <button class="edit-btn" @click="openEditModal(product)">Edit Product</button>
                      <button class="delete-btn" @click="deleteProduct(product.id)">Delete Product</button>
                    </div>
                  </transition>
                </teleport>
              </td>
            </tr>
            <tr v-if="filteredProducts.length === 0">
              <td colspan="8" style="text-align:center;padding:20px">No products found.</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <transition name="fade">
      <div v-if="showEditModal" class="modal-overlay" @click.self="showEditModal = false">
        <div class="modal-content">
          <div class="modal-header">
            <h3>Edit Product Details</h3>
            <button class="close-modal" @click="showEditModal = false">&times;</button>
          </div>
          
          <form @submit.prevent="updateProduct">
            <div class="form-row">
              <div class="form-group flex-2">
                <label>Product Name</label>
                <input type="text" v-model="editForm.name" required />
              </div>
              <div class="form-group flex-1">
                <label>Brand</label>
                <input type="text" v-model="editForm.brand" />
              </div>
            </div>

            <div class="form-row">
              <div class="form-group flex-1">
                <label>Sale Price (৳)</label>
                <input type="number" step="0.01" v-model="editForm.price" required />
              </div>
              <div class="form-group flex-1">
                <label>Quantity</label>
                <input type="number" v-model="editForm.quantity" required />
              </div>
            </div>

            <div class="form-group">
              <label>Product Details</label>
              <textarea v-model="editForm.details" rows="3"></textarea>
            </div>

            <div class="form-group">
              <label>Product Image (Optional)</label>
              <input type="file" @change="handleEditImage" accept="image/*" />
            </div>

            <div class="modal-footer">
              <button type="button" class="cancel-btn" @click="showEditModal = false">Cancel</button>
              <button type="submit" class="save-btn" :disabled="updating">
                {{ updating ? 'Updating...' : 'Save Changes' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </transition>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, nextTick } from 'vue'
import axios from 'axios'

const search = ref('')
const products = ref([])
const dropdownOpen = ref(null)
const dropdownPosition = ref({})
const token = localStorage.getItem('vendortoken') || localStorage.getItem('token')

const showEditModal = ref(false)
const updating = ref(false)
const editForm = ref({ id: null, name: '', brand: '', price: '', quantity: '', details: '', image: null })

const fetchProducts = async () => {
  try {
    const res = await axios.get('http://127.0.0.1:8000/api/vendor/customer-products', {
      headers: { Authorization: `Bearer ${token}` }
    })
    products.value = res.data
  } catch (err) { console.error(err) }
}

onMounted(fetchProducts)

const imageUrl = img => `http://127.0.0.1:8000/ui_product_images/${img}`
const truncateText = (text, length) => (text && text.length > length) ? text.substring(0, length) + '...' : (text || '---')

const toggleDropdown = async (id, event) => {
  if (dropdownOpen.value === id) { dropdownOpen.value = null; return; }
  dropdownOpen.value = id
  await nextTick()
  const rect = event.target.getBoundingClientRect()
  dropdownPosition.value = { position: 'absolute', top: `${rect.bottom + window.scrollY}px`, left: `${rect.left + window.scrollX - 50}px`, zIndex: 9999 }
}

const openEditModal = (product) => {
  editForm.value = { id: product.id, name: product.name, brand: product.brand, price: product.price, quantity: product.quantity, details: product.details, image: null }
  showEditModal.value = true
  dropdownOpen.value = null
}

const handleEditImage = (e) => { editForm.value.image = e.target.files[0] }

const updateProduct = async () => {
  updating.value = true
  const formData = new FormData()
  formData.append('_method', 'PUT')
  Object.keys(editForm.value).forEach(key => {
    if (editForm.value[key] !== null) formData.append(key, editForm.value[key])
  })

  try {
    await axios.post(`http://127.0.0.1:8000/api/vendor/customer-products/${editForm.value.id}`, formData, {
      headers: { Authorization: `Bearer ${token}`, 'Content-Type': 'multipart/form-data' }
    })
    alert('Updated successfully!')
    showEditModal.value = false
    fetchProducts()
  } catch (err) { alert('Update failed!') } finally { updating.value = false }
}

const deleteProduct = async (id) => {
  if (!confirm('Are you sure you want to delete this product?')) return
  try {
    await axios.delete(`http://127.0.0.1:8000/api/vendor/customer-products/${id}`, {
      headers: { Authorization: `Bearer ${token}` }
    })
    products.value = products.value.filter(p => p.id !== id)
    dropdownOpen.value = null
    alert('Deleted successfully!')
  } catch (err) { alert('Delete failed!') }
}

const filteredProducts = computed(() => {
  if (!search.value.trim()) return products.value
  return products.value.filter(p => p.name.toLowerCase().includes(search.value.toLowerCase()))
})
</script>

<style scoped>
/* 🔒 EXACT SAME DESIGN - NO CHANGES */
.page { min-height: 100vh; display:flex; justify-content:center; align-items:flex-start; padding:40px 0; font-family:"Segoe UI", sans-serif; }
.card { width:100%; max-width:1000px; background:#fff; border-radius:8px; padding:2rem; box-shadow:0 2px 4px rgba(0,0,0,0.1); }
.title { text-align:center; font-size:26px; font-weight:500; margin-bottom:30px; color:#222; }
.search-input { width:100%; max-width:220px; padding:0.5rem 1rem; margin-bottom:1rem; border:1px solid #d2d6da; border-radius:.375rem; }
.table-responsive { width:100%; overflow-x:auto; border-radius:8px; box-shadow:0 2px 4px rgba(0,0,0,0.05); background:white; padding:0.5rem; }
.custom-category-table { width:100%; border-collapse:collapse; min-width:600px; }
.custom-category-table th, .custom-category-table td { padding:12px 15px; text-align:left; border-bottom:1px solid #e5e7eb; font-size:14px; }
.custom-category-table th { background-color:#f3f4f6; font-weight:600; }
.custom-category-table tbody tr:hover { background-color:#d0e2ff; }
.category-image { width:50px; height:50px; object-fit:cover; border-radius:4px; }
.details-text { color: #666; font-size: 13px; max-width: 150px; }
.dropdown-btn { padding:6px 12px; border:none; border-radius:4px; background:#2563eb; color:white; cursor:pointer; font-weight:600; }
.dropdown-menu-absolute { background:#f9fafb; box-shadow:0 4px 8px rgba(0,0,0,0.15); border-radius:6px; overflow:hidden; min-width:140px; }
.dropdown-menu-absolute button { display:block; width:100%; border:none; background:none; padding:10px; cursor:pointer; text-align:left; font-size:14px; }
.edit-btn { color:#065f46; } .delete-btn { color:#b91c1c; }
.modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); display: flex; align-items: center; justify-content: center; z-index: 10000; }
.modal-content { background: white; padding: 30px; border-radius: 12px; width: 90%; max-width: 500px; position: relative; }
.modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
.close-modal { background: none; border: none; font-size: 28px; cursor: pointer; color: #888; }
.form-group { margin-bottom: 15px; display: flex; flex-direction: column; }
.form-group label { font-size: 14px; font-weight: 600; margin-bottom: 6px; color: #444; }
.form-group input, .form-group textarea { padding: 10px; border: 1px solid #ddd; border-radius: 6px; }
.form-row { display: flex; gap: 15px; }
.flex-1 { flex: 1; } .flex-2 { flex: 2; }
.modal-footer { display: flex; justify-content: flex-end; gap: 12px; margin-top: 25px; }
.cancel-btn { background: #f3f4f6; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; }
.save-btn { background: #2563eb; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; }
.fade-enter-active, .fade-leave-active { transition: opacity 0.2s, transform 0.2s; }
.fade-enter-from, .fade-leave-to { opacity: 0; transform: translateY(-10px); }
</style>