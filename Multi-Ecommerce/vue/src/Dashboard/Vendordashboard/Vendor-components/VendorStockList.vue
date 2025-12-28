<template>
  <div class="page">
    <div class="card">
      <h2 class="title">Manage Stock</h2>

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
              <td>{{ stock.id }}</td>
              <td>{{ stock.product?.product_name }}</td>
              <td>
                <img
                  v-if="stock.product?.product_image"
                  :src="imageUrl(stock.product.product_image)"
                  class="category-image"
                />
              </td>
              <td>{{ stock.totalQuantity }}</td>
              <td>{{ stock.purchase_price }}</td>
              <td>{{ stock.vendor_sale_price }}</td>
              <td>{{ stock.status }}</td>
              <td>
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
              <td colspan="8" style="text-align:center;padding:20px">
                No stock available
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
const stocks = ref([]);
const dropdownOpen = ref(null);
const dropdownPosition = ref({});
const token = localStorage.getItem("vendortoken") || localStorage.getItem("token");

// FETCH VENDOR STOCK
const fetchStocks = async () => {
  try {
    const res = await axios.get(
      "http://127.0.0.1:8000/api/vendor/my-stocks",
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

// SEARCH FILTER
const filteredStocks = computed(() => {
  if (!search.value.trim()) return stocks.value;
  const s = search.value.toLowerCase();
  return stocks.value.filter(stock =>
    stock.product?.product_name.toLowerCase().includes(s) ||
    stock.status.toLowerCase().includes(s)
  );
});

// IMAGE PATH
const imageUrl = (file) =>
  `http://127.0.0.1:8000/product_images/${file}`;

// DROPDOWN
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

// VIEW STOCK
const viewStock = (stock) => {
  alert(
    `Product: ${stock.product.product_name}\nQuantity: ${stock.totalQuantity}`
  );
  dropdownOpen.value = null;
};

// DELETE STOCK (Vendor Only)
const deleteStock = async (id) => {
  if (!confirm("Are you sure you want to delete this stock?")) return;

  try {
    await axios.delete(
      `http://127.0.0.1:8000/api/vendor/stocks/${id}`,
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
.page {
  min-height:50vh;
  display:flex;
  justify-content:center;
  padding:40px 0;
  font-family:"Segoe UI",sans-serif;
}
.card {
  width:100%;
  max-width:1100px;
  background:#fff;
  border-radius:8px;
  padding:2rem;
  box-shadow:0 2px 4px rgba(0,0,0,0.1);
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
}
.table-responsive {
  overflow-x:auto;
}
.custom-category-table {
  width:100%;
  min-width:700px;
  border-collapse:collapse;
}
.custom-category-table th,
.custom-category-table td {
  padding:12px 15px;
  border-bottom:1px solid #e5e7eb;
  font-size:14px;
}
.custom-category-table th {
  background:#f3f4f6;
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
}
.edit-btn { color:#065f46; }
.delete-btn { color:#b91c1c; }
.dropdown-menu-absolute button:hover {
  background:#e0e7ff;
}
.fade-enter-active,
.fade-leave-active {
  transition:0.2s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity:0;
  transform:translateY(-5px);
}
</style>
