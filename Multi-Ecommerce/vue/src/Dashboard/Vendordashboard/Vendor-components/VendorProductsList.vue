<template>
  <div class="page">
    <div class="card">
      <h2 class="title">Admin Product List</h2>

      <div class="table-responsive">
        <input type="text" v-model="search" placeholder="Search" class="search-input" />

        <table class="custom-category-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Product Name</th>
              <th>Image</th>
              <th>Available Stock</th>
              <th>Price</th>
              <th>Buy Now</th>
            </tr>
          </thead>

          <tbody>
            <tr v-for="product in filteredProducts" :key="product.id">
              <td>{{ product.id }}</td>
              <td>{{ product.product_name }}</td>
              <td>
                <img v-if="product.product_image" :src="imageUrl(product.product_image)" class="product-image" />
              </td>
              <td>{{ product.available_stock }}</td>
              <td>{{ product.price }}</td>
              <td>
                <button @click="buyNow(product)" class="buy-btn">Buy Now</button>
              </td>
            </tr>
          </tbody>
        </table>
        <p v-if="filteredProducts.length === 0" class="no-data">No products found.</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'

const search = ref('')
const products = ref([])
const token = localStorage.getItem('token')

// Fetch vendor stock (directly use response)
const fetchProducts = async () => {
  try {
    const res = await axios.get('http://127.0.0.1:8000/api/vendor/admin-stocks', {
      headers: { Authorization: `Bearer ${token}` }
    })
    // Directly assign, no mapping needed
    products.value = res.data
  } catch (err) {
    console.error(err.response?.data || err)
  }
}

onMounted(fetchProducts)

// Filtered products
const filteredProducts = computed(() => {
  if (!search.value.trim()) return products.value
  const s = search.value.toLowerCase()
  return products.value.filter(p =>
    p.product_name.toLowerCase().includes(s)
  )
})

// Image URL
const imageUrl = (filename) => `http://127.0.0.1:8000/product_images/${filename}`

// Buy Now placeholder
const buyNow = (product) => {
  alert(`You clicked Buy Now for "${product.product_name}". Implement purchase logic here.`)
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
.product-image {
  width:50px;
  height:50px;
  object-fit:cover;
  border-radius:4px;
}
.buy-btn {
  padding:6px 12px;
  border:none;
  border-radius:4px;
  background:#10b981;
  color:white;
  cursor:pointer;
  font-weight:600;
}
.buy-btn:hover {
  background:#059669;
}
.no-data {
  margin-top:15px;
  color:#555;
  text-align:center;
  font-style:italic;
}
</style>
