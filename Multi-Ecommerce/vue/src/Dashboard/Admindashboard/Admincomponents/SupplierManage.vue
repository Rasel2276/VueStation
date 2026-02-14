<template>
  <div class="page">
    <div class="card">
      <h2 class="title">Manage Suppliers</h2>

      <div class="table-wrapper">
        <div class="search-container">
          <input
            type="text"
            v-model="search"
            placeholder="Search suppliers..."
            class="search-input"
          />
        </div>

        <div class="table-responsive">
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
                <td data-label="ID">#{{ sup.id }}</td>
                <td data-label="Supplier Name" style="font-weight:600">{{ sup.supplier_name }}</td>
                <td data-label="Email">{{ sup.email || '—' }}</td>
                <td data-label="Phone">{{ sup.phone || '—' }}</td>
                <td data-label="Contact Person">{{ sup.contact_person || '—' }}</td>
                <td data-label="Address" class="desc-cell">{{ sup.address || '—' }}</td>
                <td data-label="Status">
                  <span :class="['status-badge', sup.status?.toLowerCase()]">
                    {{ sup.status }}
                  </span>
                </td>
                <td data-label="Actions">
                  <button
                    class="dropdown-btn"
                    @click="toggleDropdown(sup.id, $event)"
                  >
                    Actions ▾
                  </button>

                  <teleport to="body">
                    <transition name="fade">
                      <div
                        v-if="dropdownOpen === sup.id"
                        class="dropdown-menu-absolute"
                        :style="dropdownPosition"
                      >
                        <button class="edit-btn" @click="editSupplier(sup)">📝 Edit</button>
                        <button class="delete-btn" @click="deleteSupplier(sup.id)">🗑️ Delete</button>
                      </div>
                    </transition>
                  </teleport>
                </td>
              </tr>
              <tr v-if="filteredSuppliers.length === 0">
                <td colspan="8" class="no-data">No suppliers found.</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, nextTick } from "vue";
import axios from "axios";
import api, { BASE_URL } from '../../../axios';

const search = ref("");
const suppliers = ref([]);
const dropdownOpen = ref(null);
const dropdownPosition = ref({});
const token = localStorage.getItem("token");

const fetchSuppliers = async () => {
  try {
    const res = await api.get(
      "/admin/suppliers",
      { headers: { Authorization: `Bearer ${token}` } }
    );
    suppliers.value = res.data;
  } catch (err) {
    console.error(err);
  }
};

onMounted(fetchSuppliers);

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

const toggleDropdown = async (id, event) => {
  if (dropdownOpen.value === id) {
    dropdownOpen.value = null;
    return;
  }
  dropdownOpen.value = id;
  await nextTick();
  const rect = event.target.getBoundingClientRect();
  let leftPos = rect.left + window.scrollX - 80;
  if (leftPos + 150 > window.innerWidth) leftPos = window.innerWidth - 160;
  dropdownPosition.value = {
    position: "absolute",
    top: `${rect.bottom + window.scrollY + 5}px`,
    left: `${leftPos}px`,
    zIndex: 9999
  };
};

const deleteSupplier = async (id) => {
  if (!confirm("Are you sure you want to delete?")) return;
  try {
    await api.delete(`/admin/suppliers/${id}`, {
      headers: { Authorization: `Bearer ${token}` }
    });
    suppliers.value = suppliers.value.filter(s => s.id !== id);
    dropdownOpen.value = null;
  } catch (err) {
    alert("Failed to delete supplier");
  }
};

const editSupplier = (supplier) => {
  alert(`Edit: ${supplier.supplier_name}`);
  dropdownOpen.value = null;
};

window.addEventListener('click', (e) => {
  if (!e.target.classList.contains('dropdown-btn')) {
    dropdownOpen.value = null;
  }
});
</script>

<style scoped>
/* 🔒 DESKTOP DESIGN - MAINTAINED */
.page { min-height: 100vh; display: flex; justify-content: center; align-items: flex-start; padding: 40px 15px; background-color: #f8fafc; box-sizing: border-box; font-family: "Segoe UI", sans-serif; }
.card { width: 100%; max-width: 1100px; background: #fff; border-radius: 8px; padding: 1.5rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); box-sizing: border-box; }
.title { text-align: center; font-size: 24px; font-weight: 600; margin-bottom: 25px; color: #1e293b; }
.search-container { width: 100%; display: flex; justify-content: flex-start; }
.search-input { width: 100%; max-width: 300px; padding: 0.6rem 1rem; margin-bottom: 1.2rem; border: 1px solid #e2e8f0; border-radius: .375rem; outline: none; box-sizing: border-box; }
.table-responsive { width: 100%; border-radius: 8px; }
.custom-category-table { width: 100%; border-collapse: collapse; }
.custom-category-table th, .custom-category-table td { padding: 14px 15px; text-align: left; border-bottom: 1px solid #f1f5f9; font-size: 14px; }
.custom-category-table th { background-color: #f8fafc; font-weight: 600; color: #475569; }
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
    display: block; border: 1px solid #e2e8f0; margin-bottom: 15px; 
    border-radius: 12px; padding: 8px; background: #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.02);
  }
  .custom-category-table td { 
    display: flex; justify-content: flex-start; align-items: flex-start; 
    padding: 10px 8px; border-bottom: 1px solid #f8fafc; position: relative; min-height: 35px;
  }
  .custom-category-table td:last-child { border-bottom: none; }
  .custom-category-table td::before { 
    content: attr(data-label); font-weight: 700; color: #64748b; font-size: 11px; 
    text-transform: uppercase; width: 40%; flex-shrink: 0; text-align: left; padding-right: 10px; box-sizing: border-box;
  }
  .custom-category-table td { text-align: left; font-size: 13px; color: #1e293b; word-break: break-word; overflow-wrap: break-word; flex: 1; }
}

@media (max-width: 380px) {
  .card { padding: 0.7rem; }
  .custom-category-table td::before { width: 45%; }
}

.fade-enter-active, .fade-leave-active { transition: opacity 0.2s, transform 0.2s; }
.fade-enter-from, .fade-leave-to { opacity: 0; transform: translateY(-5px); }
</style>