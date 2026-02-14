<template>
  <div class="page">
    <div class="card">
      <h2 class="title">Manage Categories</h2>

      <div class="table-wrapper">
        <div class="search-container">
          <input 
            type="text" 
            v-model="search" 
            placeholder="Search categories..." 
            class="search-input" 
          />
        </div>

        <div class="table-responsive">
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
                <td data-label="ID">#{{ category.id }}</td>
                <td data-label="Category Name" style="font-weight:600">{{ category.category_name }}</td>
                <td data-label="Slug">{{ category.slug }}</td>
                <td data-label="Description" class="desc-cell">{{ category.description || '—' }}</td>
                <td data-label="Status">
                  <span :class="['status-badge', category.status.toLowerCase()]">
                    {{ category.status }}
                  </span>
                </td>
                <td data-label="Image">
                  <img v-if="category.category_image" :src="imageUrl(category.category_image)" class="category-image" />
                  <span v-else>No Image</span>
                </td>
                <td data-label="Actions">
                  <button @click="toggleDropdown(category.id, $event)" class="dropdown-btn">
                    Actions ▾
                  </button>
                  
                  <teleport to="body">
                    <transition name="fade">
                      <div
                        v-if="dropdownOpen === category.id"
                        class="dropdown-menu-absolute"
                        :style="dropdownPosition"
                      >
                        <button @click="editCategory(category)" class="edit-btn">📝 Edit</button>
                        <button @click="deleteCategory(category.id)" class="delete-btn">🗑️ Delete</button>
                      </div>
                    </transition>
                  </teleport>
                </td>
              </tr>

              <tr v-if="filteredCategories.length === 0">
                <td colspan="7" class="no-data">No categories found.</td>
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
import api, { BASE_URL } from '../../../axios';

const search = ref('')
const categories = ref([])
const dropdownOpen = ref(null)
const dropdownPosition = ref({})
const token = localStorage.getItem('token')

const fetchCategories = async () => {
  try {
    const res = await api.get('/admin/categories', {
      headers: { Authorization: `Bearer ${token}` }
    })
    categories.value = res.data
  } catch (err) {
    console.error(err)
  }
}

onMounted(fetchCategories)

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

const imageUrl = (filename) => `${BASE_URL}/category_images/${filename}`

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

const deleteCategory = async (id) => {
  if (!confirm('Are you sure you want to delete this category?')) return
  try {
    await api.delete(`/admin/categories/${id}`, {
      headers: { Authorization: `Bearer ${token}` }
    })
    categories.value = categories.value.filter(cat => cat.id !== id)
    dropdownOpen.value = null
  } catch (err) {
    alert('Failed to delete category')
  }
}

const editCategory = (category) => {
  alert(`Edit: ${category.category_name}`);
  dropdownOpen.value = null;
}

window.addEventListener('click', (e) => {
  if (!e.target.classList.contains('dropdown-btn')) {
    dropdownOpen.value = null;
  }
});
</script>

<style scoped>
/* 🔒 DESKTOP DESIGN - UNCHANGED */
.page { min-height: 100vh; display: flex; justify-content: center; align-items: flex-start; padding: 40px 15px; background-color: #f8fafc; box-sizing: border-box; }
.card { width: 100%; max-width: 1100px; background: #fff; border-radius: 8px; padding: 1.5rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); box-sizing: border-box; }
.title { text-align: center; font-size: 24px; font-weight: 600; margin-bottom: 25px; color: #1e293b; }

.search-container { width: 100%; display: flex; justify-content: flex-start; }
.search-input { width: 100%; max-width: 300px; padding: 0.6rem 1rem; margin-bottom: 1.2rem; border: 1px solid #e2e8f0; border-radius: .375rem; outline: none; box-sizing: border-box; }

.table-responsive { width: 100%; border-radius: 8px; }
.custom-category-table { width: 100%; border-collapse: collapse; }
.custom-category-table th, .custom-category-table td { padding: 14px 15px; text-align: left; border-bottom: 1px solid #f1f5f9; font-size: 14px; }
.custom-category-table th { background-color: #f8fafc; font-weight: 600; color: #475569; }
.category-image { width: 45px; height: 45px; object-fit: cover; border-radius: 6px; border: 1px solid #e2e8f0; }
.status-badge { padding: 4px 10px; border-radius: 4px; font-size: 12px; font-weight: 600; text-transform: uppercase; }
.status-badge.active { background: #ecfdf5; color: #059669; }
.status-badge.inactive { background: #fef2f2; color: #dc2626; }
.dropdown-btn { padding: 6px 14px; border: none; border-radius: 4px; background: #1e293b; color: white; cursor: pointer; font-weight: 600; font-size: 13px; }
.dropdown-menu-absolute { background: #ffffff; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); border: 1px solid #e2e8f0; border-radius: 6px; overflow: hidden; min-width: 150px; }
.dropdown-menu-absolute button { display: block; width: 100%; border: none; background: none; padding: 12px 15px; cursor: pointer; text-align: left; font-size: 13px; }
.dropdown-menu-absolute button:hover { background: #f8fafc; }
.edit-btn { color: #059669; border-bottom: 1px solid #f1f5f9; }
.delete-btn { color: #dc2626; }

/* 📱 MOBILE RESPONSIVE - LABEL LEFT, CONTENT RIGHT */
@media (max-width: 850px) {
  .page { padding: 10px 5px; }
  .card { padding: 1rem; border-radius: 12px; }
  .search-input { max-width: 100%; width: 100%; margin-bottom: 1.5rem; }
  
  .custom-category-table thead { display: none; }
  
  .custom-category-table tr { 
    display: block; 
    border: 1px solid #e2e8f0; 
    margin-bottom: 15px; 
    border-radius: 12px; 
    padding: 8px; 
    background: #fff;
    box-shadow: 0 2px 4px rgba(0,0,0,0.02);
  }
  
  .custom-category-table td { 
    display: flex; 
    justify-content: flex-start; 
    align-items: flex-start; 
    padding: 10px 8px; 
    border-bottom: 1px solid #f8fafc;
    position: relative;
    min-height: 35px;
  }
  
  .custom-category-table td:last-child { border-bottom: none; }
  
  /* Field Name (Left side) */
  .custom-category-table td::before { 
    content: attr(data-label); 
    font-weight: 700; 
    color: #64748b; 
    font-size: 11px; 
    text-transform: uppercase; 
    width: 35%; /* লেবেল বামে থাকবে ৩৫% জায়গায় */
    flex-shrink: 0;
    text-align: left;
    padding-right: 10px;
    box-sizing: border-box;
  }
  
  /* Content (Right side) */
  .custom-category-table td {
    text-align: left;
    font-size: 13px;
    color: #1e293b;
    word-break: break-word; /* লম্বা লেখা যেন ওভারল্যাপ না করে */
    overflow-wrap: break-word;
    flex: 1; /* বাকি জায়গা কন্টেন্ট নিবে */
  }

  /* ইমেজ এবং ব্যাজ এডজাস্টমেন্ট */
  .category-image { width: 45px; height: 45px; }
  .status-badge { font-size: 11px; padding: 2px 8px; }
}

/* অতি ক্ষুদ্র স্ক্রিন (iPhone SE) */
@media (max-width: 380px) {
  .card { padding: 0.7rem; }
  .title { font-size: 19px; }
  .custom-category-table td::before { width: 40%; } /* ছোট ফোনে লেবেলকে একটু বেশি জায়গা দেওয়া */
}

.fade-enter-active, .fade-leave-active { transition: opacity 0.2s, transform 0.2s; }
.fade-enter-from, .fade-leave-to { opacity: 0; transform: translateY(-5px); }
</style>