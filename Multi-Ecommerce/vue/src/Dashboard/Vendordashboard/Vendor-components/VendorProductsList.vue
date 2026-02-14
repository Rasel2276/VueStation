<template>
  <div class="page">
    <div class="card">
      <h2 class="title">Admin Product List</h2>

      <div class="table-wrapper">
        <input type="text" v-model="search" placeholder="Search" class="search-input" />

        <div class="table-responsive">
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
                <td data-label="ID">{{ product.id }}</td>
                <td data-label="Product Name" style="font-weight:600">{{ product.product_name }}</td>
                <td data-label="Image">
                  <img v-if="product.product_image" :src="imageUrl(product.product_image)" class="product-image" />
                  <span v-else>No Image</span>
                </td>
                <td data-label="Available Stock">{{ product.available_stock }}</td>
                <td data-label="Price">{{ product.price }}</td>
                <td data-label="Buy Now">
                  <button @click="buyNow(product)" class="buy-btn">Buy Now</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <p v-if="filteredProducts.length === 0" class="no-data">No products found.</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'
import api, { BASE_URL } from '../../../axios';

const search = ref('')
const products = ref([])
const token = localStorage.getItem('token')

const fetchProducts = async () => {
  try {
    const res = await api.get('/vendor/admin-stocks', {
      headers: { Authorization: `Bearer ${token}` }
    })
    products.value = res.data
  } catch (err) {
    console.error(err.response?.data || err)
  }
}

onMounted(fetchProducts)

const filteredProducts = computed(() => {
  if (!search.value.trim()) return products.value
  const s = search.value.toLowerCase()
  return products.value.filter(p =>
    p.product_name.toLowerCase().includes(s)
  )
})

const imageUrl = (filename) => `${BASE_URL}/product_images/${filename}`

const buyNow = (product) => {
  alert(`You clicked Buy Now for "${product.product_name}". Implement purchase logic here.`)
}
</script>

<style scoped>
/* 🔒 DESKTOP DESIGN - 100% UNCHANGED */
.page {
  min-height: 100vh;
  display:flex;
  justify-content:center;
  align-items:flex-start;
  padding:40px 0;
  font-family:"Segoe UI", sans-serif;
  box-sizing: border-box;
}
.card {
  width:100%;
  max-width:1000px;
  background:#fff;
  border-radius:8px;
  padding:2rem;
  box-shadow:0 2px 4px rgba(0,0,0,0.1);
  box-sizing: border-box;
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
  box-sizing: border-box;
}
.table-wrapper { width: 100%; }
.table-responsive {
  width:100%;
  border-radius:8px;
  box-shadow:0 2px 4px rgba(0,0,0,0.05);
  background:white;
  padding:0.5rem;
  box-sizing: border-box;
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

/* 📱 MOBILE RESPONSIVE - ADDED FOR PERFECT FIT */
@media (max-width: 850px) {
  .page { padding: 15px; }
  .card { padding: 15px; border-radius: 12px; }
  .title { font-size: 20px; margin-bottom: 20px; }
  .search-input { max-width: 100%; }

  .custom-category-table { min-width: 100%; }
  .custom-category-table thead { display: none; }
  
  .custom-category-table tr { 
    display: block; 
    border: 1px solid #e2e8f0; 
    margin-bottom: 15px; 
    border-radius: 10px; 
    padding: 10px; 
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
  
  .product-image { width: 40px; height: 40px; }
  .buy-btn { width: auto; padding: 5px 15px; }
}

@media (max-width: 380px) {
  .custom-category-table td { font-size: 12px; padding: 8px 5px; }
}
</style>