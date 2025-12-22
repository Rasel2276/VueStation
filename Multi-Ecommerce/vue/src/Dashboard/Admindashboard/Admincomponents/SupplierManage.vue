<template>
  <div class="page">
    <div class="card">
      <h2 class="title">Manage Suppliers</h2>

      <div class="table-responsive">
        <input
          type="text"
          v-model="search"
          placeholder="Search"
          class="search-input"
        />

        <table class="custom-category-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Supplier Name</th>
              <th>Email</th>
              <th>Phone</th>
              <th>Contact Person</th>
              <th>Address</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>

          <tbody>
            <tr v-for="sup in filteredSuppliers" :key="sup.id">
              <td>{{ sup.id }}</td>
              <td>{{ sup.supplier_name }}</td>
              <td>{{ sup.email }}</td>
              <td>{{ sup.phone }}</td>
              <td>{{ sup.contact_person }}</td>
              <td>{{ sup.address }}</td>
              <td>{{ sup.status }}</td>
              <td>
                <button
                  class="dropdown-btn"
                  @click="toggleDropdown(sup.id, $event)"
                >
                  Actions ▾
                </button>

                <!-- Dropdown -->
                <teleport to="body">
                  <transition name="fade">
                    <div
                      v-if="dropdownOpen === sup.id"
                      class="dropdown-menu-absolute"
                      :style="dropdownPosition"
                    >
                      <button
                        class="edit-btn"
                        @click="editSupplier(sup)"
                      >
                        Edit
                      </button>
                      <button
                        class="delete-btn"
                        @click="deleteSupplier(sup.id)"
                      >
                        Delete
                      </button>
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
import { ref, computed, onMounted, nextTick } from "vue";
import axios from "axios";

const search = ref("");
const suppliers = ref([]);
const dropdownOpen = ref(null);
const dropdownPosition = ref({});
const token = localStorage.getItem("token");

// Fetch suppliers
const fetchSuppliers = async () => {
  try {
    const res = await axios.get(
      "http://127.0.0.1:8000/api/admin/suppliers",
      { headers: { Authorization: `Bearer ${token}` } }
    );
    suppliers.value = res.data;
  } catch (err) {
    console.error(err.response?.data || err);
  }
};

onMounted(fetchSuppliers);

// Search filter
const filteredSuppliers = computed(() => {
  if (!search.value.trim()) return suppliers.value;
  const s = search.value.toLowerCase();
  return suppliers.value.filter(sup =>
    (sup.supplier_name && sup.supplier_name.toLowerCase().includes(s)) ||
    (sup.email && sup.email.toLowerCase().includes(s)) ||
    (sup.phone && sup.phone.toLowerCase().includes(s)) ||
    (sup.contact_person && sup.contact_person.toLowerCase().includes(s)) ||
    (sup.address && sup.address.toLowerCase().includes(s)) ||
    (sup.status && sup.status.toLowerCase().includes(s))
  );
});

// Dropdown toggle
const toggleDropdown = async (id, event) => {
  if (dropdownOpen.value === id) {
    dropdownOpen.value = null;
    return;
  }
  dropdownOpen.value = id;
  await nextTick();
  const rect = event.target.getBoundingClientRect();
  dropdownPosition.value = {
    position: "absolute",
    top: `${rect.bottom + window.scrollY}px`,
    left: `${rect.left + window.scrollX}px`,
    zIndex: 9999
  };
};

// Delete supplier
const deleteSupplier = async (id) => {
  if (!confirm("Are you sure you want to delete this supplier?")) return;

  try {
    await axios.delete(
      `http://127.0.0.1:8000/api/admin/suppliers/${id}`,
      { headers: { Authorization: `Bearer ${token}` } }
    );
    suppliers.value = suppliers.value.filter(s => s.id !== id);
    dropdownOpen.value = null;
    alert("Supplier deleted successfully");
  } catch (err) {
    console.error(err.response?.data || err);
    alert("Failed to delete supplier");
  }
};

// Edit placeholder
const editSupplier = (supplier) => {
  alert(`Edit "${supplier.supplier_name}" functionality here`);
};
</script>

<style scoped>
.page {
  min-height: 50vh;
  display: flex;
  justify-content: center;
  padding: 40px 0;
  font-family: "Segoe UI", sans-serif;
}
.card {
  width: 100%;
  max-width: 1100px;
  background: #fff;
  border-radius: 8px;
  padding: 2rem;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
.title {
  text-align: center;
  font-size: 26px;
  margin-bottom: 30px;
}
.search-input {
  width: 100%;
  max-width: 220px;
  padding: 0.5rem 1rem;
  margin-bottom: 1rem;
  border: 1px solid #d2d6da;
  border-radius: 6px;
}
.table-responsive {
  overflow-x: auto;
  background: #fff;
  padding: 0.5rem;
}
.custom-category-table {
  width: 100%;
  min-width: 700px;
  border-collapse: collapse;
}
.custom-category-table th,
.custom-category-table td {
  padding: 12px 15px;
  border-bottom: 1px solid #e5e7eb;
  font-size: 14px;
}
.custom-category-table th {
  background: #f3f4f6;
  font-weight: 600;
}
.custom-category-table tbody tr:hover {
  background: #dbeafe;
}

/* Dropdown */
.dropdown-btn {
  padding: 6px 12px;
  background: #2563eb;
  border: none;
  border-radius: 4px;
  color: white;
  font-weight: 600;
  cursor: pointer;
}
.dropdown-menu-absolute {
  background: #f9fafb;
  box-shadow: 0 4px 8px rgba(0,0,0,0.15);
  border-radius: 6px;
  min-width: 140px;
}
.dropdown-menu-absolute button {
  width: 100%;
  padding: 10px;
  border: none;
  background: none;
  text-align: left;
  cursor: pointer;
}
.edit-btn { color: #065f46; }
.delete-btn { color: #b91c1c; }
.dropdown-menu-absolute button:hover {
  background: #e0e7ff;
}

/* Animation */
.fade-enter-active, .fade-leave-active {
  transition: 0.2s ease;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
  transform: translateY(-5px);
}
</style>
