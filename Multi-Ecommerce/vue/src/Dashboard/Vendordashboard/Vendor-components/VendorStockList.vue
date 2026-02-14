<template>
  <div class="page">
    <div class="card">
      <h2 class="title">Manage Stock</h2>

      <div class="table-wrapper">
        <input
          type="text"
          v-model="search"
          placeholder="Search"
          class="search-input"
        />

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
              <tr v-for="stock in filteredStocks" :key="stock.id">
                <td data-label="ID">{{ stock.id }}</td>
                <td data-label="Product" style="font-weight:600">{{ stock.product?.product_name }}</td>
                <td data-label="Image">
                  <img
                    v-if="stock.product?.product_image"
                    :src="imageUrl(stock.product.product_image)"
                    class="category-image"
                  />
                  <span v-else>No Image</span>
                </td>
                <td data-label="Quantity">{{ stock.totalQuantity }}</td>
                <td data-label="Purchase Price">{{ stock.purchase_price }}</td>
                <td data-label="Vendor Sale Price">{{ stock.vendor_sale_price }}</td>
                <td data-label="Status">{{ stock.status }}</td>
                <td data-label="Actions">
                  <button
                    class="dropdown-btn"
                    @click="toggleDropdown(stock.id, $event)"
                  >
                    Actions ▾
                  </button>

                  <teleport to="body">
                    <transition name="fade">
                      <div
                        v-if="dropdownOpen === stock.id"
                        class="dropdown-menu-absolute"
                        :style="dropdownPosition"
                      >
                        <button class="edit-btn" @click="viewStock(stock)">
                          View
                        </button>
                        <button
                          class="delete-btn"
                          @click="deleteStock(stock.id)"
                        >
                          Delete
                        </button>
                      </div>
                    </transition>
                  </teleport>
                </td>
              </tr>

              <tr v-if="filteredStocks.length === 0">
                <td colspan="8" class="no-data">
                  No stock available
                </td>
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
const stocks = ref([]);
const dropdownOpen = ref(null);
const dropdownPosition = ref({});
const token = localStorage.getItem("vendortoken") || localStorage.getItem("token");

const fetchStocks = async () => {
  try {
    const res = await api.get(
      "/vendor/my-stocks",
      { headers: { Authorization: `Bearer ${token}` } }
    );

    const merged = {};
    res.data.forEach(s => {
      const id = s.id;

      if (!merged[id]) {
        merged[id] = {
          id: id,
          product_id: s.admin_stock.product_id,
          product: s.admin_stock.product,
          totalQuantity: s.quantity,
          purchase_price: s.price,
          vendor_sale_price: s.price,
          status: s.status
        };
      } else {
        merged[id].totalQuantity += s.quantity;
        merged[id].purchase_price += s.price;
        merged[id].vendor_sale_price += s.price;
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
    stock.product?.product_name.toLowerCase().includes(s) ||
    stock.status.toLowerCase().includes(s)
  );
});

const imageUrl = (file) =>
  `${BASE_URL}/product_images/${file}`;

const toggleDropdown = async (id, event) => {
  if (dropdownOpen.value === id) {
    dropdownOpen.value = null;
    return;
  }
  dropdownOpen.value = id;
  await nextTick();
  const rect = event.target.getBoundingClientRect();
  const dropdownWidth = 140;
  let leftPos = rect.left + window.scrollX - 50;

  if (leftPos + dropdownWidth > window.innerWidth) {
    leftPos = window.innerWidth - dropdownWidth - 15;
  }
  if (leftPos < 15) leftPos = 15;

  dropdownPosition.value = {
    position: "absolute",
    top: `${rect.bottom + window.scrollY}px`,
    left: `${leftPos}px`,
    zIndex: 9999
  };
};

const viewStock = (stock) => {
  alert(
    `Product: ${stock.product.product_name}\nQuantity: ${stock.totalQuantity}`
  );
  dropdownOpen.value = null;
};

const deleteStock = async (id) => {
  if (!confirm("Are you sure you want to delete this stock?")) return;
  try {
    await api.delete(
      `/vendor/stocks/${id}`,
      { headers: { Authorization: `Bearer ${token}` } }
    );
    stocks.value = stocks.value.filter(s => s.id !== id);
    dropdownOpen.value = null;
    alert("Stock deleted successfully");
  } catch (err) {
    alert("Failed to delete stock");
    console.error(err.response?.data || err);
  }
};
</script>

<style scoped>
/* 🔒 DESKTOP DESIGN - 100% UNCHANGED */
.page {
  min-height:50vh;
  display:flex;
  justify-content:center;
  padding:40px 0;
  font-family:"Segoe UI",sans-serif;
  box-sizing: border-box;
}
.card {
  width:100%;
  max-width:1100px;
  background:#fff;
  border-radius:8px;
  padding:2rem;
  box-shadow:0 2px 4px rgba(0,0,0,0.1);
  box-sizing: border-box;
}
.title {
  text-align:center;
  font-size:26px;
  margin-bottom:30px;
}
.search-input {
  width:100%;
  max-width:220px;
  padding:0.5rem 1rem;
  margin-bottom:1rem;
  border:1px solid #d2d6da;
  border-radius:6px;
  box-sizing: border-box;
}
.table-wrapper { width: 100%; }
.table-responsive {
  width: 100%;
  border-radius: 8px;
  background: white;
  box-sizing: border-box;
}
.custom-category-table {
  width:100%;
  min-width:700px;
  border-collapse:collapse;
}
.custom-category-table th,
.custom-category-table td {
  padding:12px 15px;
  text-align:left;
  border-bottom:1px solid #e5e7eb;
  font-size:14px;
}
.custom-category-table th {
  background:#f3f4f6;
  font-weight: 600;
}
.category-image {
  width:50px;
  height:50px;
  object-fit:cover;
  border-radius:4px;
}
.dropdown-btn {
  padding:6px 12px;
  background:#2563eb;
  border:none;
  border-radius:4px;
  color:white;
  font-weight:600;
  cursor:pointer;
}
.dropdown-menu-absolute {
  background:#f9fafb;
  box-shadow:0 4px 8px rgba(0,0,0,0.15);
  border-radius:6px;
  min-width:140px;
}
.dropdown-menu-absolute button {
  width:100%;
  padding:10px;
  border:none;
  background:none;
  text-align:left;
  cursor:pointer;
  font-size: 14px;
}
.edit-btn { color:#065f46; }
.delete-btn { color:#b91c1c; }
.no-data { text-align: center; padding: 20px; }

.fade-enter-active, .fade-leave-active { transition:0.2s ease; }
.fade-enter-from, .fade-leave-to { opacity:0; transform:translateY(-5px); }

/* 📱 MOBILE RESPONSIVE - PERFECT FIT */
@media (max-width: 850px) {
  .page { padding: 15px; } 
  .card { 
    padding: 15px; 
    border-radius: 12px; 
    max-width: 100%; 
  }
  .title { font-size: 20px; margin-bottom: 20px; }
  .search-input { max-width: 100%; }
  
  .table-wrapper { overflow: hidden; }
  .table-responsive { overflow: visible; }

  .custom-category-table { min-width: 100%; }
  .custom-category-table thead { display: none; }
  
  .custom-category-table tr { 
    display: block; 
    border: 1px solid #e2e8f0; 
    margin-bottom: 15px; 
    border-radius: 10px; 
    padding: 8px; 
    background: #fff;
  }
  
  .custom-category-table td { 
    display: flex; 
    justify-content: space-between; 
    align-items: center; 
    text-align: right; 
    border-bottom: 1px solid #f1f5f9; 
    padding: 10px 8px; 
    font-size: 13px;
  }
  
  .custom-category-table td:last-child { border-bottom: none; }
  
  .custom-category-table td::before { 
    content: attr(data-label); 
    font-weight: 700; 
    color: #64748b; 
    text-transform: uppercase; 
    font-size: 10px; 
    text-align: left; 
    flex: 1; 
    margin-right: 10px;
  }
  
  .category-image { width: 40px; height: 40px; }
  .dropdown-btn { width: auto; padding: 5px 12px; }
}

@media (max-width: 380px) {
  .custom-category-table td { font-size: 12px; padding: 8px 5px; }
  .custom-category-table td::before { font-size: 9px; }
}
</style>