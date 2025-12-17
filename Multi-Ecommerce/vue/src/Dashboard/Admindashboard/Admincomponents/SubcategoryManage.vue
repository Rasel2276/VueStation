<template>
  <div class="page">
    <div class="card">
      <h2 class="title">Manage Subcategories</h2>

      <div class="table-responsive">
        <!-- SEARCH -->
        <input
          type="text"
          v-model="search"
          placeholder="Search"
          class="search-input"
        />

        <!-- TABLE -->
        <table class="custom-category-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Parent Category</th>
              <th>Subcategory Name</th>
              <th>Slug</th>
              <th>Description</th>
              <th>Status</th>
              <th>Image</th>
              <th>Actions</th>
            </tr>
          </thead>

          <tbody>
            <tr
              v-for="sub in filteredSubcategories"
              :key="sub.id"
            >
              <td>{{ sub.id }}</td>
              <td>{{ sub.parent }}</td>
              <td>{{ sub.name }}</td>
              <td>{{ sub.slug }}</td>
              <td>{{ sub.description }}</td>
              <td>{{ sub.status }}</td>
              <td>{{ sub.image }}</td>
              <td>
                <button class="action-btn edit">Edit</button>
                <button class="action-btn delete">Delete</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const search = ref('')

const subcategories = ref([
  {
    id: 1,
    parent: 'Electronics',
    name: 'Mobile Phones',
    slug: 'mobile-phones',
    description: 'All smartphone devices',
    status: 'Active',
    image: 'mobile.jpg'
  },
  {
    id: 2,
    parent: 'Clothing',
    name: 'T-Shirts',
    slug: 't-shirts',
    description: 'Men & Women T-Shirts',
    status: 'Inactive',
    image: 'tshirt.jpg'
  },
  {
    id: 3,
    parent: 'Books',
    name: 'Novels',
    slug: 'novels',
    description: 'Fiction & story books',
    status: 'Active',
    image: 'novel.jpg'
  }
])

const filteredSubcategories = computed(() => {
  if (!search.value.trim()) return subcategories.value

  const s = search.value.toLowerCase()

  return subcategories.value.filter(sub =>
    sub.name.toLowerCase().includes(s) ||
    sub.slug.toLowerCase().includes(s) ||
    sub.parent.toLowerCase().includes(s) ||
    sub.description.toLowerCase().includes(s) ||
    sub.status.toLowerCase().includes(s)
  )
})
</script>

<style scoped>
/* ===== PAGE ===== */
.page {
  min-height: 100vh;
  display: flex;
  justify-content: center;
  align-items: flex-start;
  padding: 40px 0;
  font-family: "Segoe UI", system-ui, Arial, sans-serif;
}

/* ===== CARD ===== */
.card {
  width: 100%;
  max-width: 1000px;
  background: #ffffff;
  border-radius: 8px;
  padding: 2rem;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

/* ===== TITLE ===== */
.title {
  text-align: center;
  font-size: 26px;
  font-weight: 500;
  margin-bottom: 30px;
  color: #222;
}

/* ===== SEARCH INPUT ===== */
.search-input {
  width: 100%;
  max-width: 220px;
  padding: 0.5rem 1rem;
  margin-bottom: 1rem;
  border: 1px solid #d2d6da;
  border-radius: .375rem;
  line-height: 1.3;
}

.search-input:focus {
  outline: none;
  border-color: #3b82f6;
}

/* ===== TABLE RESPONSIVE ===== */
.table-responsive {
  width: 100%;
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.05);
  background: white;
  padding: 0.5rem;
}

/* ===== TABLE ===== */
.custom-category-table {
  width: 100%;
  border-collapse: collapse;
  min-width: 700px;
}

.custom-category-table th,
.custom-category-table td {
  padding: 12px 15px;
  text-align: left;
  font-size: 14px;
  border-bottom: 1px solid #e5e7eb;
}

.custom-category-table th {
  background-color: #f3f4f6;
  font-weight: 600;
}

.custom-category-table tbody tr {
  transition: background-color 0.25s ease;
}

.custom-category-table tbody tr:hover {
  background-color: #d0e2ff;
  cursor: pointer;
}

/* ===== ACTION BUTTONS ===== */
.action-btn {
  padding: 6px 10px;
  border: none;
  border-radius: 3px;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
  margin-right: 5px;
  transition: 0.3s;
}

.action-btn.edit {
  background-color: #c5d18a;
  color: #333;
}

.action-btn.edit:hover {
  background-color: #b7c775;
}

.action-btn.delete {
  background-color: #f77373;
  color: #fff;
}

.action-btn.delete:hover {
  background-color: #e05555;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 900px) {
  .custom-category-table {
    min-width: 100%;
  }
}

@media (max-width: 480px) {
  .search-input {
    max-width: 100%;
    margin-bottom: 1rem;
  }
}
</style>
