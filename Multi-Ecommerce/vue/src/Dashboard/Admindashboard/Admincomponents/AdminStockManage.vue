<template>
  <div class="page">
    <div class="card">
      <h2 class="title">Manage Stock</h2>

      <div class="table-wrapper">
        <div class="search-container">
          <input 
            type="text" 
            v-model="search" 
            placeholder="Search stock..." 
            class="search-input"
          />
        </div>

        <div class="table-responsive">
          <table class="custom-category-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Product</th>
                <th>Image</th>
                <th>Quantity</th>
                <th>Purchase Price</th>
                <th>Vendor Sale Price</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>

            <tbody>
              <tr v-for="stock in filteredStocks" :key="stock.product_id">
                <td data-label="ID">#{{ stock.product_id }}</td>
                <td data-label="Product" class="bold-text">{{ stock.product?.product_name }}</td>
                <td data-label="Image">
                  <img 
                    v-if="stock.product?.product_image" 
                    :src="imageUrl(stock.product.product_image)" 
                    class="category-image"
                  />
                  <span v-else>No Image</span>
                </td>
                <td data-label="Quantity">
                  <span class="qty-badge">{{ stock.totalQuantity }}</span>
                </td>
                <td data-label="Purchase Price">{{ stock.purchase_price }}</td>
                <td data-label="Vendor Sale Price">{{ stock.vendor_sale_price }}</td>
                <td data-label="Status">
                  <span :class="['status-badge', stock.status?.toLowerCase()]">
                    {{ stock.status }}
                  </span>
                </td>
                <td data-label="Actions" class="action-cell">
                  <button 
                    class="dropdown-btn" 
                    @click="toggleDropdown(stock.product_id, $event)"
                  >
                    Actions ▾
                  </button>

                  <teleport to="body">
                    <transition name="fade">
                      <div 
                        v-if="dropdownOpen === stock.product_id" 
                        class="dropdown-menu-absolute" 
                        :style="dropdownPosition"
                      >
                        <button class="edit-btn" @click="viewStock(stock)">👁️ View</button>
                        <button class="delete-btn" @click="deleteStock(stock.product_id)">🗑️ Delete</button>
                      </div>
                    </transition>
                  </teleport>
                </td>
              </tr>
              <tr v-if="filteredStocks.length === 0">
                <td colspan="8" class="no-data">No stock records found.</td>
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

const search = ref("");
const stocks = ref([]);
const dropdownOpen = ref(null);
const dropdownPosition = ref({});
const token = localStorage.getItem("token");

const fetchStocks = async () => {
  try {
    const res = await axios.get(
      "http://127.0.0.1:8000/api/admin/stock",
      { headers: { Authorization: `Bearer ${token}` } }
    );

    const merged = {};
    res.data.forEach(s => {
      if (!merged[s.product_id]) {
        merged[s.product_id] = { ...s, totalQuantity: s.quantity };
      } else {
        merged[s.product_id].totalQuantity += s.quantity;
      }
    });
    stocks.value = Object.values(merged);
  } catch (err) {
    console.error(err.response?.data || err);
  }
};

onMounted(fetchStocks);

const filteredStocks = computed(() => {
  if (!search.value.trim()) return stocks.value;
  const s = search.value.toLowerCase();
  return stocks.value.filter(stock =>
    stock.status?.toLowerCase().includes(s) ||
    stock.product?.product_name.toLowerCase().includes(s)
  );
});

const imageUrl = (file) => `http://127.0.0.1:8000/product_images/${file}`;

const toggleDropdown = async (id, event) => {
  if (dropdownOpen.value === id) {
    dropdownOpen.value = null;
    return;
  }
  dropdownOpen.value = id;
  await nextTick();
  const rect = event.target.getBoundingClientRect();
  
  let leftPos = rect.left + window.scrollX - 80;
  if (leftPos + 160 > window.innerWidth) leftPos = window.innerWidth - 170;

  dropdownPosition.value = {
    position: "absolute",
    top: `${rect.bottom + window.scrollY + 5}px`,
    left: `${leftPos}px`,
    zIndex: 9999
  };
};

const deleteStock = async (productId) => {
  if (!confirm("Are you sure you want to delete this stock?")) return;
  try {
    await axios.delete(
      `http://127.0.0.1:8000/api/admin/stock/${productId}`,
      { headers: { Authorization: `Bearer ${token}` } }
    );
    stocks.value = stocks.value.filter(s => s.product_id !== productId);
    dropdownOpen.value = null;
    alert("Stock deleted successfully");
  } catch {
    alert("Failed to delete stock");
  }
};

const viewStock = (stock) => {
  alert(`Product: ${stock.product?.product_name}\nTotal Stock: ${stock.totalQuantity}`);
  dropdownOpen.value = null;
};

// Outside click to close dropdown
window.addEventListener('click', (e) => {
  if (!e.target.classList.contains('dropdown-btn')) {
    dropdownOpen.value = null;
  }
});
</script>

<style scoped>
/* 🔒 DESKTOP DESIGN */
.page { 
  min-height: 100vh; 
  display: flex; 
  justify-content: center; 
  align-items: flex-start; 
  padding: 40px 15px; 
  background-color: #f8fafc; 
  font-family: "Segoe UI", sans-serif;
}

.card { 
  width: 100%; 
  max-width: 1100px; 
  background: #fff; 
  border-radius: 12px; 
  padding: 2rem; 
  box-shadow: 0 4px 15px rgba(0,0,0,0.05);
}

.title { 
  text-align: center; 
  font-size: 26px; 
  margin-bottom: 30px;
  font-weight: 700;
  color: #1e293b;
}

.search-input { 
  width: 100%; 
  max-width: 300px; 
  padding: 0.7rem 1rem; 
  margin-bottom: 1.5rem; 
  border: 1px solid #e2e8f0; 
  border-radius: 8px;
  outline: none;
  font-size: 14px;
}

.table-responsive { 
  width: 100%;
  overflow-x: auto; 
}

.custom-category-table { 
  width: 100%; 
  border-collapse: collapse;
}

.custom-category-table th, .custom-category-table td { 
  padding: 14px 15px; 
  text-align: left;
  border-bottom: 1px solid #f1f5f9; 
  font-size: 14px;
}

.custom-category-table th { 
  background: #f8fafc; 
  font-weight: 600;
  color: #475569;
}

.category-image { 
  width: 45px; 
  height: 45px; 
  object-fit: cover; 
  border-radius: 6px;
}

.qty-badge {
  background: #f1f5f9;
  padding: 4px 10px;
  border-radius: 4px;
  font-weight: 700;
  color: #2563eb;
}

.status-badge {
  padding: 4px 8px;
  border-radius: 4px;
  font-size: 11px;
  font-weight: 700;
  text-transform: uppercase;
  background: #e2e8f0;
}

.dropdown-btn { 
  padding: 6px 14px; 
  background: #2563eb; 
  border: none; 
  border-radius: 6px; 
  color: white; 
  font-weight: 600; 
  cursor: pointer;
  font-size: 13px;
}

.dropdown-menu-absolute { 
  background: #ffffff; 
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); 
  border: 1px solid #e2e8f0; 
  border-radius: 8px; 
  min-width: 160px;
}

.dropdown-menu-absolute button { 
  display: block;
  width: 100%; 
  padding: 12px 15px; 
  border: none; 
  background: none; 
  text-align: left; 
  cursor: pointer;
  font-size: 13px;
}

.edit-btn { color: #059669; border-bottom: 1px solid #f1f5f9; }
.delete-btn { color: #dc2626; }

/* 📱 MOBILE RESPONSIVE FIX (Label-Value Format) */
@media (max-width: 950px) {
  .page { padding: 15px 10px; }
  .card { padding: 1.5rem 1rem; }

  /* Search Input fits 100% on mobile */
  .search-input { 
    max-width: 100%; 
    box-sizing: border-box; 
    margin-bottom: 1.2rem;
    padding: 12px;
  }

  .custom-category-table thead { display: none; }
  
  .custom-category-table tr { 
    display: block; 
    border: 1px solid #e2e8f0; 
    margin-bottom: 15px; 
    border-radius: 12px; 
    padding: 10px; 
    background: #fff;
  }

  .custom-category-table td { 
    display: flex; 
    justify-content: flex-start; 
    align-items: center; 
    padding: 10px 8px; 
    border-bottom: 1px solid #f8fafc;
  }

  .custom-category-table td:last-child { border-bottom: none; }

  .custom-category-table td::before { 
    content: attr(data-label); 
    font-weight: 700; 
    color: #64748b; 
    font-size: 11px; 
    text-transform: uppercase; 
    width: 40%; 
    flex-shrink: 0;
  }

  .bold-text { font-weight: 600; color: #1e293b; }
  .category-image { width: 55px; height: 55px; }
}

.fade-enter-active, .fade-leave-active { transition: 0.2s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; transform: translateY(-5px); }
</style>