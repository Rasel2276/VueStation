<template>
  <div class="page">
    <div class="card">
      <h2 class="title">Manage Categories</h2>

      <div class="table-responsive" ref="tableWrapper">
        <input type="text" v-model="search" placeholder="Search" class="search-input" />

        <table class="custom-category-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Category Name</th>
              <th>Slug</th>
              <th>Description</th>
              <th>Status</th>
              <th>Image</th>
              <th>Actions</th>
            </tr>
          </thead>

          <tbody>
            <tr v-for="category in filteredCategories" :key="category.id">
              <td>{{ category.id }}</td>
              <td>{{ category.category_name }}</td>
              <td>{{ category.slug }}</td>
              <td>{{ category.description }}</td>
              <td>{{ category.status }}</td>
              <td>
                <img v-if="category.category_image" :src="imageUrl(category.category_image)" class="category-image" />
              </td>
              <td>
                <button @click="toggleDropdown(category.id)" class="dropdown-btn">Actions ▾</button>
                <!-- Dropdown rendered outside table for full visibility -->
                <teleport to="body">
                  <transition name="fade">
                    <div
                      v-if="dropdownOpen === category.id"
                      class="dropdown-menu-absolute"
                      :style="dropdownPosition"
                    >
                      <button @click="editCategory(category)" class="edit-btn">Edit</button>
                      <button @click="deleteCategory(category.id)" class="delete-btn">Delete</button>
                    </div>
                  </transition>
                </teleport>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, nextTick } from 'vue'
import axios from 'axios'

const search = ref('')
const categories = ref([])
const dropdownOpen = ref(null)
const dropdownPosition = ref({})
const token = localStorage.getItem('token')
const tableWrapper = ref(null)

// Fetch categories
const fetchCategories = async () => {
  try {
    const res = await axios.get('http://127.0.0.1:8000/api/admin/categories', {
      headers: { Authorization: `Bearer ${token}` }
    })
    categories.value = res.data
  } catch (err) {
    console.error(err.response?.data || err)
  }
}

onMounted(fetchCategories)

// Filtered categories
const filteredCategories = computed(() => {
  if (!search.value.trim()) return categories.value
  const s = search.value.toLowerCase()
  return categories.value.filter(cat =>
    cat.category_name.toLowerCase().includes(s) ||
    cat.slug.toLowerCase().includes(s) ||
    (cat.description && cat.description.toLowerCase().includes(s)) ||
    cat.status.toLowerCase().includes(s)
  )
})

// Image URL
const imageUrl = (filename) => `http://127.0.0.1:8000/category_images/${filename}`

// Dropdown toggle
const toggleDropdown = async (id, event) => {
  if (dropdownOpen.value === id) {
    dropdownOpen.value = null
    return
  }

  dropdownOpen.value = id
  await nextTick()

  const button = event?.target || document.querySelector('.dropdown-btn')
  if (!button) return

  const rect = button.getBoundingClientRect()
  dropdownPosition.value = {
    position: 'absolute',
    top: `${rect.bottom + window.scrollY}px`,
    left: `${rect.left + window.scrollX}px`,
    zIndex: 9999
  }
}

// Delete category
const deleteCategory = async (id) => {
  if (!confirm('Are you sure you want to delete this category?')) return
  try {
    await axios.delete(`http://127.0.0.1:8000/api/admin/categories/${id}`, {
      headers: { Authorization: `Bearer ${token}` }
    })
    categories.value = categories.value.filter(cat => cat.id !== id)
    alert('Category deleted successfully')
    dropdownOpen.value = null
  } catch (err) {
    console.error(err.response?.data || err)
    alert('Failed to delete category')
  }
}

// Edit category placeholder
const editCategory = (category) => {
  alert(`Edit functionality for "${category.category_name}" can be implemented here.`)
}
</script>

<style scoped>
.page {
  min-height: 100vh;
  display:flex;
  justify-content:center;
  align-items:flex-start;
  padding:40px 0;
  font-family:"Segoe UI", sans-serif;
}
.card {
  width:100%;
  max-width:1000px;
  background:#fff;
  border-radius:8px;
  padding:2rem;
  box-shadow:0 2px 4px rgba(0,0,0,0.1);
}
.title {
  text-align:center;
  font-size:26px;
  font-weight:500;
  margin-bottom:30px;
  color:#222;
}
.search-input {
  width:100%;
  max-width:220px;
  padding:0.5rem 1rem;
  margin-bottom:1rem;
  border:1px solid #d2d6da;
  border-radius:.375rem;
}
.table-responsive {
  width:100%;
  overflow-x:auto;
  border-radius:8px;
  box-shadow:0 2px 4px rgba(0,0,0,0.05);
  background:white;
  padding:0.5rem;
}
.custom-category-table {
  width:100%;
  border-collapse:collapse;
  min-width:600px;
}
.custom-category-table th,
.custom-category-table td {
  padding:12px 15px;
  text-align:left;
  border-bottom:1px solid #e5e7eb;
  font-size:14px;
}
.custom-category-table th {
  background-color:#f3f4f6;
  font-weight:600;
}
.custom-category-table tbody tr:hover {
  background-color:#d0e2ff;
}
.category-image {
  width:50px;
  height:50px;
  object-fit:cover;
  border-radius:4px;
}

/* Dropdown absolute */
.dropdown-btn {
  padding:6px 12px;
  border:none;
  border-radius:4px;
  background:#2563eb;
  color:white;
  cursor:pointer;
  font-weight:600;
}
.dropdown-btn:hover {
  background:#1e40af;
}
.dropdown-menu-absolute {
  background:#f9fafb;
  box-shadow:0 4px 8px rgba(0,0,0,0.15);
  border-radius:6px;
  overflow:hidden;
  min-width:140px;
}
.dropdown-menu-absolute button {
  display:block;
  width:100%;
  border:none;
  background:none;
  padding:10px;
  cursor:pointer;
  text-align:left;
  font-size:14px;
  transition:0.2s;
}
.edit-btn {
  color:#065f46;
}
.delete-btn {
  color:#b91c1c;
}
.dropdown-menu-absolute button:hover {
  background:#e0e7ff;
}

/* Transition */
.fade-enter-active, .fade-leave-active {
  transition: all 0.2s ease;
}
.fade-enter-from, .fade-leave-to {
  opacity:0;
  transform: translateY(-5px);
}
</style>
