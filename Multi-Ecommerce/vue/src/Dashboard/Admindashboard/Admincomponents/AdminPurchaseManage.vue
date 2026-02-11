<template>
  <div class="page">
    <div class="card">
      <h2 class="title">Manage Purchases</h2>

      <div class="table-wrapper">
        <div class="search-container">
          <input
            type="text"
            v-model="search"
            placeholder="Search purchases..."
            class="search-input"
          />
        </div>

        <div class="table-responsive">
          <table class="custom-category-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Supplier</th>
                <th>Product</th>
                <th>Image</th>
                <th>Quantity</th>
                <th>Purchase Price</th>
                <th>Vendor Sale Price</th>
                <th>Total</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>

            <tbody>
              <tr v-for="p in filteredPurchases" :key="p.product_id">
                <td data-label="ID">#{{ p.product_id }}</td>
                <td data-label="Supplier">{{ p.supplier?.supplier_name }}</td>
                <td data-label="Product" class="bold-text">{{ p.product?.product_name }}</td>

                <td data-label="Image">
                  <img
                    v-if="p.product?.product_image"
                    :src="imageUrl(p.product.product_image)"
                    class="category-image"
                  />
                  <span v-else>No Image</span>
                </td>

                <td data-label="Quantity">{{ p.totalQuantity }}</td>
                <td data-label="Purchase Price">{{ p.purchase_price }}</td>
                <td data-label="Vendor Sale Price">{{ p.vendor_sale_price }}</td>
                <td data-label="Total" class="total-amount">{{ p.totalAmount }}</td>
                <td data-label="Status">
                  <span :class="['status-badge', p.status?.toLowerCase()]">
                    {{ p.status }}
                  </span>
                </td>

                <td data-label="Actions" class="action-cell">
                  <button
                    class="dropdown-btn"
                    @click="toggleDropdown(p.product_id, $event)"
                  >
                    Actions ▾
                  </button>

                  <teleport to="body">
                    <transition name="fade">
                      <div
                        v-if="dropdownOpen === p.product_id"
                        class="dropdown-menu-absolute"
                        :style="dropdownPosition"
                      >
                        <button class="edit-btn" @click="viewPurchase(p)">
                          👁️ View
                        </button>
                        <button class="delete-btn" @click="deletePurchase(p.product_id)">
                          🗑️ Delete
                        </button>
                      </div>
                    </transition>
                  </teleport>
                </td>
              </tr>
              <tr v-if="filteredPurchases.length === 0">
                <td colspan="10" class="no-data">No purchases found.</td>
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
const purchases = ref([]);
const dropdownOpen = ref(null);
const dropdownPosition = ref({});
const token = localStorage.getItem("token");

const fetchPurchases = async () => {
  try {
    const res = await axios.get(
      "http://127.0.0.1:8000/api/admin/purchase",
      { headers: { Authorization: `Bearer ${token}` } }
    );

    const merged = {};
    res.data.forEach(p => {
      if (!merged[p.product_id]) {
        merged[p.product_id] = { 
          ...p, 
          totalQuantity: p.quantity, 
          totalAmount: p.purchase_price * p.quantity
        };
      } else {
        merged[p.product_id].totalQuantity += p.quantity;
        merged[p.product_id].totalAmount += p.purchase_price * p.quantity;
      }
    });
    purchases.value = Object.values(merged);
  } catch (err) {
    console.error(err.response?.data || err);
  }
};

onMounted(fetchPurchases);

const filteredPurchases = computed(() => {
  if (!search.value.trim()) return purchases.value;
  const s = search.value.toLowerCase();
  return purchases.value.filter(p =>
    p.status.toLowerCase().includes(s) ||
    p.product?.product_name.toLowerCase().includes(s) ||
    p.supplier?.supplier_name.toLowerCase().includes(s)
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
  if (leftPos + 150 > window.innerWidth) leftPos = window.innerWidth - 160;

  dropdownPosition.value = {
    position: "absolute",
    top: `${rect.bottom + window.scrollY + 5}px`,
    left: `${leftPos}px`,
    zIndex: 9999
  };
};

const deletePurchase = async (productId) => {
  if (!confirm("Are you sure you want to delete this purchase?")) return;
  try {
    await axios.delete(
      `http://127.0.0.1:8000/api/admin/purchase/${productId}`,
      { headers: { Authorization: `Bearer ${token}` } }
    );
    purchases.value = purchases.value.filter(p => p.product_id !== productId);
    dropdownOpen.value = null;
    alert("Purchase deleted successfully");
  } catch {
    alert("Failed to delete purchase");
  }
};

const viewPurchase = (purchase) => {
  alert(`Product: ${purchase.product?.product_name}\nTotal Quantity: ${purchase.totalQuantity}`);
};

window.addEventListener('click', (e) => {
  if (!e.target.classList.contains('dropdown-btn')) {
    dropdownOpen.value = null;
  }
});
</script>

<style scoped>
/* 🔒 DESKTOP DESIGN */
.page { min-height: 100vh; display: flex; justify-content: center; align-items: flex-start; padding: 40px 15px; background-color: #f8fafc; font-family: "Segoe UI", sans-serif; }
.card { width: 100%; max-width: 1200px; background: #fff; border-radius: 12px; padding: 2rem; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
.title { text-align: center; font-size: 26px; margin-bottom: 30px; font-weight: 700; color: #1e293b; }

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

.table-responsive { width: 100%; overflow-x: auto; }
.custom-category-table { width: 100%; border-collapse: collapse; }
.custom-category-table th, .custom-category-table td { padding: 14px 15px; text-align: left; border-bottom: 1px solid #f1f5f9; font-size: 14px; }
.custom-category-table th { background-color: #f8fafc; font-weight: 600; color: #475569; }

.category-image { width: 45px; height: 45px; object-fit: cover; border-radius: 6px; }
.status-badge { padding: 4px 10px; border-radius: 4px; font-size: 12px; font-weight: 600; text-transform: uppercase; background: #e2e8f0; }
.total-amount { font-weight: 700; color: #2563eb; }

.dropdown-btn { padding: 6px 14px; border: none; border-radius: 6px; background: #2563eb; color: white; cursor: pointer; font-weight: 600; font-size: 13px; }
.dropdown-menu-absolute { background: #ffffff; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); border: 1px solid #e2e8f0; border-radius: 8px; min-width: 160px; }
.dropdown-menu-absolute button { display: block; width: 100%; border: none; background: none; padding: 12px 15px; cursor: pointer; text-align: left; font-size: 13px; }
.edit-btn { color: #059669; border-bottom: 1px solid #f1f5f9; }
.delete-btn { color: #dc2626; }

/* 📱 MOBILE RESPONSIVE FIX */
@media (max-width: 1024px) {
  .page { padding: 15px 10px; }
  .card { padding: 1.5rem 1rem; }
  
  /* Search Field Fully Responsive */
  .search-input { 
    max-width: 100%; 
    box-sizing: border-box; 
    margin-bottom: 1.2rem;
    padding: 12px;
    font-size: 15px;
  }
  
  .custom-category-table thead { display: none; }
  .custom-category-table tr { 
    display: block; border: 1px solid #e2e8f0; margin-bottom: 15px; 
    border-radius: 12px; padding: 10px; background: #fff;
  }
  .custom-category-table td { 
    display: flex; justify-content: flex-start; align-items: center; 
    padding: 10px 8px; border-bottom: 1px solid #f8fafc;
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
  .category-image { width: 60px; height: 60px; } /* Mobile image size */
}

.fade-enter-active, .fade-leave-active { transition: opacity 0.2s, transform 0.2s; }
.fade-enter-from, .fade-leave-to { opacity: 0; transform: translateY(-5px); }
</style>