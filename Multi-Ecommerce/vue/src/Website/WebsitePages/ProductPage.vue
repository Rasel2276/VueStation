<template>
  <div class="page">
    <!-- TOP BAR for mobile: Filter button + Search quick -->
    <div class="mobile-top" v-if="isMobile">
      <button class="filter-btn" @click="mobileMenuOpen = true">
        <i class="fa-solid fa-sliders"></i> Filters
      </button>
      <div class="mobile-search-quick">
        <input v-model="filters.search" placeholder="Search..." @input="applyFilters" />
        <i class="fa-solid fa-magnifying-glass"></i>
      </div>
    </div>

    <div class="content">
      <!-- SIDEBAR -->
      <aside :class="['sidebar', { open: mobileMenuOpen && isMobile }]">
        <div class="sidebar-header">
          <div class="logo"><span class="logo-red">Z</span>aptro</div>
          <button class="close" v-if="isMobile" @click="mobileMenuOpen = false">
            <i class="fa-solid fa-xmark"></i>
          </button>
        </div>

        <!-- Search (in sidebar) -->
        <div class="widget" >
          <input v-model="filters.search" id="search-bar" placeholder="Search..." @input="applyFilters" />
        </div>

        <!-- Category -->
        <div class="widget">
          <h4>Category</h4>
          <label class="chk"><input type="checkbox" v-model="filters.categories" value="ALL" @change="onCategoryAll('ALL')"> <span>ALL</span></label>
          <label v-for="c in categories" :key="c" class="chk">
            <input type="checkbox" :value="c" v-model="filters.categories" @change="applyFilters" /> <span>{{ c }}</span>
          </label>
        </div>

        <!-- Brand -->
        <div class="widget">
          <h4>Brand</h4>
          <select v-model="filters.brand" @change="applyFilters">
            <option value="">ALL</option>
            <option v-for="b in brands" :key="b" :value="b">{{ b }}</option>
          </select>
        </div>

        <!-- Price Range -->
        <div class="widget">
          <h4>Price Range</h4>
          <p class="price-range-text">Price Range: ৳{{ priceMin }} - ৳{{ filters.maxPrice }}</p>
          <div class="range-wrap">
            <input type="range" min="0" :max="priceMax" v-model.number="filters.maxPrice" @input="applyFilters" />
            <div class="range-line"><span :style="{ width: rangePercent + '%' }"></span></div>
          </div>
        </div>

        <button class="reset" @click="resetFilters">Reset Filters</button>
      </aside>

      <!-- PRODUCTS GRID -->
      <main class="products-area">
        <div class="grid">
          <div v-for="product in paginatedProducts" :key="product.id" class="card">
            <div class="img-wrap">
              <img :src="product.image" :alt="product.name" />
            </div>
            <h3 class="title" :title="product.name">{{ product.name }}</h3>
            <p class="price">৳{{ product.price }}</p>
            <button class="add-btn" @click="addToCart(product)">
              <i class="fa-solid fa-cart-shopping"></i> Add to Cart
            </button>
          </div>
        </div>

        <!-- pagination simple -->
        <div class="pagination" v-if="pages > 1">
          <button :disabled="page === 1" @click="page--">Prev</button>
          <button v-for="p in pages" :key="p" :class="{ active: p === page }" @click="page = p">{{ p }}</button>
          <button :disabled="page === pages" @click="page++">Next</button>
        </div>
      </main>
    </div>

    <!-- TOAST -->
    <div class="toast" v-if="toast.show">
      <i class="fa-solid fa-circle-check"></i> {{ toast.text }}
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from "vue";

const isMobile = ref(false);
const mobileMenuOpen = ref(false);
const page = ref(1);
const perPage = 8;

const products = ref([
  { id: 1, name: "Redmi 15C", price: 11500, image: "/assets/product-images/phone3.webp", category: "PHONE",brand: "Sony" },
  { id: 2, name: "Hoco W46  Headphone", price: 2700, image: "/assets/product-images/audio3.jpg", category: "AUDIO", brand: "Microsoft" },
  { id: 3, name: "Xbox One Controller", price: 5300, image: "/assets/product-images/game1.jpg", category: "GAMING", brand: "Logitech" },
  { id: 4, name: "Pico 4 Ultra", price: 9000, image: "/assets/product-images/game3.jpg", category: "GAMING", brand: "Sony" },
  { id: 5, name: "Xiaomi 55″ QLED A Pro Smart 4K Google TV 2025", price: 6500, image: "/assets/product-images/tv3.webp", category: "TV", brand: "Urbanista" },
  { id: 6, name: "Xiaomi Wired in-Ear Earphones", price: 2100, image: "/assets/product-images/audio1.jpg", category: "AUDIO", brand: "Xiaomi" },
  { id: 7, name: "Hisense 85″ 4K QLED 85Q6N Latest Model Smart Google TV 2025", price: 7500, image: "/assets/product-images/tv4.webp", category: "TV", brand: "boAt" },
  { id: 8, name: "Casio modern watch", price: 750, image: "/assets/product-images/watch2.jpg", category: "WATCH", brand: "Samsung" },
  { id: 9, name: "iphone 17 pro max", price: 150000, image: "/assets/product-images/iphone1.jpg", category: "PHONE", brand: "BrandX" },
  { id: 10, name: "JBL Wave Flex 2 True Wireless Earbuds", price: 2300, image: "/assets/product-images/audio2.jpg", category: "AUDIO", brand: "BrandY" },
  { id: 10, name: "Winter New Hudi", price: 1300, image: "/assets/product-images/hudi.jpg", category: "CLOTH", brand: "BrandY" },
  { id: 10, name: "Baggy pant for Men", price: 950, image: "/assets/product-images/pant.jpg", category: "CLOTH", brand: "BrandY" },
  { id: 10, name: "Winter cap for women", price: 250, image: "/assets/product-images/cap.jpg", category: "CLOTH", brand: "BrandY" },
  { id: 10, name: "Hand Gloves h52", price: 750, image: "/assets/product-images/gloves.jpg", category: "CLOTH", brand: "BrandY" },
  { id: 10, name: "Gents Jacket", price: 3700, image: "/assets/product-images/jacket.jpg", category: "CLOTH", brand: "BrandY" },
]);

const categories = computed(() => Array.from(new Set(products.value.map(p => p.category))));
const brands = computed(() => Array.from(new Set(products.value.map(p => p.brand))));

const priceMin = 0;
const priceMax = 500000;

const filters = reactive({
  search: "",
  categories: ["ALL"],
  brand: "",
  maxPrice: priceMax,
});

function applyFilters() {
  page.value = 1;
}

function onCategoryAll(val) {
  if (filters.categories.includes("ALL")) {
    filters.categories = ["ALL"];
  }
  applyFilters();
}

function resetFilters() {
  filters.search = "";
  filters.categories = ["ALL"];
  filters.brand = "";
  filters.maxPrice = priceMax;
  applyFilters();
}

const filtered = computed(() => {
  return products.value.filter(p => {
    const s = filters.search.trim().toLowerCase();
    if (s && !(p.name.toLowerCase().includes(s) || p.brand.toLowerCase().includes(s))) return false;
    if (!filters.categories.includes("ALL")) {
      if (filters.categories.length && !filters.categories.includes(p.category)) return false;
    }
    if (filters.brand && p.brand !== filters.brand) return false;
    if (p.price > filters.maxPrice) return false;
    return true;
  });
});

const pages = computed(() => Math.ceil(filtered.value.length / perPage));
const paginatedProducts = computed(() => {
  const start = (page.value - 1) * perPage;
  return filtered.value.slice(start, start + perPage);
});

const toast = reactive({ show: false, text: "" });
let toastTimer = null;
function addToCart(product) {
  toast.text = `${product.name} is added to cart!`;
  toast.show = true;
  clearTimeout(toastTimer);
  toastTimer = setTimeout(() => (toast.show = false), 2200);
}

const rangePercent = computed(() => {
  const val = Math.min(filters.maxPrice, priceMax);
  return (val / priceMax) * 100;
});

onMounted(() => {
  const check = () => (isMobile.value = window.innerWidth <= 768);
  check();
  window.addEventListener("resize", check);
});
</script>

<style scoped>
/* ----------------------------------------------------------
   BASIC RESET
---------------------------------------------------------- */
* {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
}

#search-bar{
  padding: 7px;
}

.page {
  font-family: Inter, system-ui, Arial, sans-serif;
  color: #222;
}

/* ----------------------------------------------------------
   MOBILE TOP BAR
---------------------------------------------------------- */
.mobile-top {
  display: none;
  padding: 10px 16px;
  background: #fff;
  align-items: center;
  gap: 12px;
}

.mobile-top .filter-btn {
  background: #e4002b;
  color: #fff;
  border: none;
  padding: 8px 12px;
  border-radius: 6px;
  cursor: pointer;
  display: flex;
  gap: 8px;
  align-items: center;
}

.mobile-top .mobile-search-quick {
  flex: 1;
  position: relative;
}

.mobile-top input {
  width: 100%;
  padding: 8px 36px 8px 12px;
  border-radius: 8px;
  border: 1px solid #ddd;
}

.mobile-top i {
  position: absolute;
  right: 10px;
  top: 50%;
  transform: translateY(-50%);
  color: #999;
}

/* ----------------------------------------------------------
   MAIN LAYOUT
---------------------------------------------------------- */
.content {
  display: flex;
  gap: 24px;
  max-width: 1200px;
  margin: 24px auto;
  padding: 0 16px;
}

/* ----------------------------------------------------------
   SIDEBAR
---------------------------------------------------------- */
.sidebar {
  width: 260px;
  background: #f8f9fb;
  padding: 18px;
  border-radius: 8px;
  height: fit-content;
  flex-shrink: 0;
  position: sticky;
  top: 20px;
}

.sidebar-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 8px;
}

.logo {
  font-weight: 700;
  font-size: 20px;
  color: #222;
}

.logo-red {
  color: #e4002b;
}

.sidebar .widget {
  margin: 12px 0;
}

.sidebar input[type="text"],
.sidebar input[type="search"],
.sidebar select {
  width: 100%;
  padding: 8px 12px;
  border-radius: 8px;
  border: 1px solid #ddd;
}

.widget h4 {
  margin: 6px 0 8px;
  font-size: 14px;
  color: #333;
}

/* Checkbox list */
.chk {
  display: flex;
  align-items: center;
  gap: 10px;
  margin: 6px 0;
  font-size: 14px;
  color: #333;
}

.chk input {
  width: 16px;
  height: 16px;
}

/* ----------------------------------------------------------
   RANGE SLIDER
---------------------------------------------------------- */
.range-wrap {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.range-wrap input[type="range"] {
  -webkit-appearance: none;
  width: 100%;
  height: 6px;
  background: transparent;
}

.range-wrap input[type="range"]::-webkit-slider-runnable-track {
  height: 6px;
  background: #eee;
  border-radius: 6px;
}

.range-wrap input[type="range"]::-webkit-slider-thumb {
  -webkit-appearance: none;
  width: 16px;
  height: 16px;
  border-radius: 50%;
  background: #e4002b;
  margin-top: -5px;
  cursor: pointer;
  box-shadow: 0 2px 6px rgba(0,0,0,0.2);
}

.range-line {
  width: 100%;
  height: 6px;
  background: #eee;
  border-radius: 6px;
  position: relative;
}

.range-line span {
  position: absolute;
  left: 0;
  top: 0;
  bottom: 0;
  background: #e4002b;
  display: block;
  border-radius: 6px;
}

.price-range-text {
  color: black;
}

/* Reset button */
.reset {
  width: 100%;
  background: #e4002b;
  border: 1px solid #e4002b;
  color: #fff;
  padding: 8px;
  border-radius: 8px;
  cursor: pointer;
  font-weight: 600;
}

/* ----------------------------------------------------------
   PRODUCTS AREA
---------------------------------------------------------- */
.products-area {
  flex: 1;
  min-height: 200px;
}

/* Product grid */
.grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 18px;
}

/* Product card */
.card {
  background: #fff;
  border-radius: 8px;
  padding: 12px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.05);
  display: flex;
  flex-direction: column;
  gap: 10px;
  align-items: center; /* CENTER CONTENT */
}

.img-wrap {
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  height: 140px;
  background: #fafafa;
  border-radius: 6px;
  overflow: hidden;
}

.img-wrap img {
  max-width: 100%;
  max-height: 100%;
  object-fit: contain;
}

.title {
  font-size: 14px;
  color: #222;
  margin: 0;
  text-align: center; /* CENTER NAME */
}

.price {
  font-weight: 700;
  color: #111;
  text-align: center; /* CENTER PRICE */
}

.add-btn {
  margin-top: auto;
  background: #e4002b;
  color: #fff;
  border: none;
  padding: 10px 14px;
  border-radius: 8px;
  cursor: pointer;
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
}

.add-btn i {
  font-size: 14px;
}

/* Pagination */
.pagination {
  display: flex;
  gap: 8px;
  margin-top: 18px;
  justify-content: center;
  align-items: center;
}

.pagination button {
  padding: 6px 10px;
  border-radius: 6px;
  border: 1px solid #ddd;
  background: #fff;
  cursor: pointer;
}

.pagination button.active {
  background: #e4002b;
  color: #fff;
  border-color: #e4002b;
}

/* ----------------------------------------------------------
   TOAST POPUP
---------------------------------------------------------- */
.toast {
  position: fixed;
  right: 20px;
  bottom: 20px;
  background: #fff;
  color: #0a0;
  border-left: 4px solid #2ecc71;
  padding: 10px 14px;
  border-radius: 6px;
  box-shadow: 0 6px 18px rgba(0,0,0,0.12);
  display: flex;
  gap: 10px;
  align-items: center;
  z-index: 9999;
}

/* ----------------------------------------------------------
   RESPONSIVE
---------------------------------------------------------- */
@media (max-width: 1100px) {
  .grid {
    grid-template-columns: repeat(3, 1fr);
  }
}

@media (max-width: 820px) {
  .grid {
    grid-template-columns: repeat(2, 1fr);
  }
  .content {
    gap: 16px;
    margin: 16px auto;
  }
}

@media (max-width: 768px) {
  .content {
    flex-direction: column;
    padding: 12px;
    max-width: 100%;
    margin: 0;
  }

  .sidebar {
    position: fixed;
    left: -100%;
    top: 0;
    height: 100vh;
    z-index: 220;
    box-shadow: 6px 0 24px rgba(0,0,0,0.2);
    width: 85%;
    max-width: 320px;
  }

  .sidebar.open {
    left: 0;
    transition: left .25s ease;
  }

  .mobile-top {
    display: flex;
  }

  .grid {
    grid-template-columns: repeat(2, 1fr);
  }

  .mobile-search-quick input {
    padding-left: 10px;
  }

  .mobile-top .filter-btn {
    padding: 8px 10px;
  }

  .mobile-top input {
    padding: 8px 36px 8px 12px;
    width: 100%;
    border-radius: 8px;
  }
}

@media (max-width: 420px) {
  .grid {
    grid-template-columns: 1fr;
  }

  .sidebar {
    width: 92%;
  }
}
</style>
