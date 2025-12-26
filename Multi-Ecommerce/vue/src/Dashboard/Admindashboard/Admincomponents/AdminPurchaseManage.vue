<template>
  <div class="page">
    <div class="card">
      <h2 class="title">Manage Purchases</h2>

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
              <td>{{ p.product_id }}</td>
              <td>{{ p.supplier?.supplier_name }}</td>
              <td>{{ p.product?.product_name }}</td>

              <!-- IMAGE -->
              <td>
                <img
                  v-if="p.product?.product_image"
                  :src="imageUrl(p.product.product_image)"
                  class="category-image"
                />
              </td>

              <td>{{ p.totalQuantity }}</td>
              <td>{{ p.purchase_price }}</td>
              <td>{{ p.vendor_sale_price }}</td>
              <td>{{ p.totalAmount }}</td>
              <td>{{ p.status }}</td>

              <td>
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
                        View
                      </button>
                      <button class="delete-btn" @click="deletePurchase(p.product_id)">
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
const purchases = ref([]);
const dropdownOpen = ref(null);
const dropdownPosition = ref({});
const token = localStorage.getItem("token");

// FETCH PURCHASES
const fetchPurchases = async () => {
  try {
    const res = await axios.get(
      "http://127.0.0.1:8000/api/admin/purchase",
      { headers: { Authorization: `Bearer ${token}` } }
    );

    // Merge same product purchases
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

// SEARCH FILTER
const filteredPurchases = computed(() => {
  if (!search.value.trim()) return purchases.value;
  const s = search.value.toLowerCase();
  return purchases.value.filter(p =>
    p.status.toLowerCase().includes(s) ||
    p.product?.product_name.toLowerCase().includes(s) ||
    p.supplier?.supplier_name.toLowerCase().includes(s)
  );
});

// IMAGE PATH
const imageUrl = (file) => `http://127.0.0.1:8000/product_images/${file}`;

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

// DELETE LOGIC
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

// VIEW
const viewPurchase = (purchase) => {
  alert(`Product ID: ${purchase.product_id}`);
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
  background:#fff; 
  padding:0.5rem;
}
.custom-category-table { 
  width:100%; 
  min-width:700px; 
  border-collapse:collapse;
}
.custom-category-table th, .custom-category-table td { 
  padding:12px 15px; 
  border-bottom:1px solid #e5e7eb; 
  font-size:14px;
}
.custom-category-table th { 
  background:#f3f4f6; 
  font-weight:600;
}
.custom-category-table tbody tr:hover { 
  background:#dbeafe;
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
.edit-btn { color:#065f46;}
.delete-btn { color:#b91c1c;}
.dropdown-menu-absolute button:hover { background:#e0e7ff;}
.fade-enter-active, .fade-leave-active { transition:0.2s ease;}
.fade-enter-from, .fade-leave-to { opacity:0; transform:translateY(-5px);}
</style>
