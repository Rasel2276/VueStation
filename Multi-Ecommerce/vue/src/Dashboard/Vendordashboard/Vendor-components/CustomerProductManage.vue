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
              <th>Category</th> <th>Quantity</th>
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
              <td><span class="category-badge">{{ product.category || 'N/A' }}</span></td>
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
              <td colspan="7" style="text-align:center;padding:20px">No products found.</td>
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
                <label>Category</label>
                <input type="text" v-model="editForm.category" required />
              </div>
              <div class="form-group flex-1">
                <label>Theme Color</label>
                <div class="color-palette">
                  <button 
                    v-for="color in presetColors" 
                    :key="color" 
                    type="button"
                    class="color-circle" 
                    :style="{ backgroundColor: color }"
                    :class="{ active: editForm.theme_color === color }"
                    @click="editForm.theme_color = color"
                  ></button>
                </div>
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
const presetColors = ['#1abc9c', '#ff4d4d', '#f39c12', '#000000'];
const token = localStorage.getItem('vendortoken') || localStorage.getItem('token')

const showEditModal = ref(false)
const updating = ref(false)
const editForm = ref({ id: null, name: '', brand: '', category: '', price: '', quantity: '', details: '', image: null, theme_color: '' })

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

const toggleDropdown = async (id, event) => {
  if (dropdownOpen.value === id) { dropdownOpen.value = null; return; }
  dropdownOpen.value = id
  await nextTick()
  const rect = event.target.getBoundingClientRect()
  dropdownPosition.value = { position: 'absolute', top: `${rect.bottom + window.scrollY}px`, left: `${rect.left + window.scrollX - 50}px`, zIndex: 9999 }
}

const openEditModal = (product) => {
  editForm.value = { 
    id: product.id, 
    name: product.name, 
    brand: product.brand, 
    category: product.category || '',
    price: product.price, 
    quantity: product.quantity, 
    details: product.details, 
    theme_color: product.theme_color || '#1abc9c',
    image: null 
  }
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
.page { min-height: 100vh; display:flex; justify-content:center; align-items:flex-start; padding:40px 0; font-family:"Segoe UI", sans-serif; }
.card { width:100%; max-width:1100px; background:#fff; border-radius:8px; padding:2rem; box-shadow:0 2px 10px rgba(0,0,0,0.1); }
.title { text-align:center; font-size:26px; font-weight:500; margin-bottom:30px; color:#222; }
.search-input { width:100%; max-width:300px; padding:0.6rem 1rem; margin-bottom:1.5rem; border:1px solid #d2d6da; border-radius:6px; outline:none; }
.search-input:focus { border-color: #2563eb; }
.table-responsive { width:100%; overflow-x:auto; border-radius:8px; background:white; }
.custom-category-table { width:100%; border-collapse:collapse; }
.custom-category-table th, .custom-category-table td { padding:12px 15px; text-align:left; border-bottom:1px solid #e5e7eb; font-size:14px; }
.custom-category-table th { background-color:#f8f9fa; font-weight:600; color: #444; }
.category-badge { background: #eef2ff; color: #4338ca; padding: 4px 10px; border-radius: 4px; font-size: 12px; font-weight: 600; }
.category-image { width:45px; height:45px; object-fit:cover; border-radius:4px; border: 1px solid #eee; }
.dropdown-btn { padding:6px 12px; border:none; border-radius:4px; background:#2563eb; color:white; cursor:pointer; font-weight:600; }
.dropdown-menu-absolute { background:white; box-shadow:0 10px 25px rgba(0,0,0,0.1); border-radius:8px; overflow:hidden; min-width:150px; border: 1px solid #eee; }
.dropdown-menu-absolute button { display:block; width:100%; border:none; background:none; padding:12px; cursor:pointer; text-align:left; font-size:14px; transition: 0.2s; }
.dropdown-menu-absolute button:hover { background: #f8f9fa; }
.edit-btn { color:#2563eb; } .delete-btn { color:#dc2626; }

/* Color Palette in Modal */
.color-palette { display: flex; gap: 8px; align-items: center; margin-top: 5px; }
.color-circle { width: 24px; height: 24px; border-radius: 50%; border: 2px solid transparent; cursor: pointer; transition: 0.2s; padding: 0; }
.color-circle.active { border-color: #000; transform: scale(1.1); }

.modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 10000; }
.modal-content { background: white; padding: 25px; border-radius: 12px; width: 95%; max-width: 550px; }
.modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
.close-modal { background: none; border: none; font-size: 24px; cursor: pointer; }
.form-group { margin-bottom: 15px; display: flex; flex-direction: column; }
.form-group label { font-size: 13px; font-weight: 600; margin-bottom: 5px; color: #555; }
.form-group input, .form-group textarea { padding: 8px 12px; border: 1px solid #ddd; border-radius: 6px; outline: none; }
.form-row { display: flex; gap: 15px; }
.flex-1 { flex: 1; } .flex-2 { flex: 2; }
.modal-footer { display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px; }
.cancel-btn { background: #f3f4f6; border: none; padding: 8px 16px; border-radius: 6px; cursor: pointer; }
.save-btn { background: #2563eb; color: white; border: none; padding: 8px 20px; border-radius: 6px; cursor: pointer; }
.fade-enter-active, .fade-leave-active { transition: opacity 0.2s; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>