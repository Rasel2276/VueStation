<template>
  <div class="page">
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
      <aside :class="['sidebar', { open: mobileMenuOpen && isMobile }]">
        <div class="sidebar-header">
          <div class="logo"><span class="logo-red">Z</span>aptro</div>
          <button class="close" v-if="isMobile" @click="mobileMenuOpen = false">
            <i class="fa-solid fa-xmark"></i>
          </button>
        </div>

        <div class="widget">
          <input v-model="filters.search" id="search-bar" placeholder="Search..." @input="applyFilters" />
        </div>

        <div class="widget">
          <h4>Category</h4>
          <label class="chk"><input type="checkbox" v-model="filters.categories" value="ALL" @change="onCategoryAll('ALL')"> <span>ALL</span></label>
          <label v-for="c in categories" :key="c" class="chk">
            <input type="checkbox" :value="c" v-model="filters.categories" @change="applyFilters" /> <span>{{ c }}</span>
          </label>
        </div>

        <div class="widget">
          <h4>Brand</h4>
          <select v-model="filters.brand" @change="applyFilters">
            <option value="">ALL</option>
            <option v-for="b in brands" :key="b" :value="b">{{ b }}</option>
          </select>
        </div>

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

      <main class="products-area">
        <div class="grid">
          <div v-for="product in paginatedProducts" :key="product.id" class="card">
            <div class="img-wrap">
              <button class="floating-add-cart" @click.stop="addToCart(product)" title="Add to Cart">
                <i class="fa-solid fa-cart-plus"></i>
              </button>
              <img :src="product.image" :alt="product.name" />
            </div>
            
            <h3 class="title" :title="product.name">{{ product.name }}</h3>
            <p class="price">৳{{ product.price }}</p>
            
            <router-link to="/product_details" class="order-btn">
              Order Now
            </router-link>
          </div>
        </div>

        <div class="pagination" v-if="pages > 1">
          <button :disabled="page === 1" @click="page--">Prev</button>
          <button v-for="p in pages" :key="p" :class="{ active: p === page }" @click="page = p">{{ p }}</button>
          <button :disabled="page === pages" @click="page++">Next</button>
        </div>
      </main>
    </div>

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
  { id: 1, name: "Redmi 15C", price: 11500, image: "/assets/product-images/phone3.webp", category: "PHONE", brand: "Sony" },
  { id: 2, name: "Hoco W46 Headphone", price: 2700, image: "/assets/product-images/audio3.jpg", category: "AUDIO", brand: "Microsoft" },
  { id: 3, name: "Xbox One Controller", price: 5300, image: "/assets/product-images/game1.jpg", category: "GAMING", brand: "Logitech" },
  { id: 4, name: "Pico 4 Ultra", price: 9000, image: "/assets/product-images/game3.jpg", category: "GAMING", brand: "Sony" },
  { id: 5, name: "Xiaomi 55″ QLED A Pro Smart 4K Google TV 2025", price: 6500, image: "/assets/product-images/tv3.webp", category: "TV", brand: "Urbanista" },
  { id: 6, name: "Xiaomi Wired in-Ear Earphones", price: 2100, image: "/assets/product-images/audio1.jpg", category: "AUDIO", brand: "Xiaomi" },
  { id: 7, name: "Hisense 85″ 4K QLED 85Q6N Google TV 2025", price: 7500, image: "/assets/product-images/tv4.webp", category: "TV", brand: "boAt" },
  { id: 8, name: "Casio modern watch", price: 750, image: "/assets/product-images/watch2.jpg", category: "WATCH", brand: "Samsung" },
  { id: 9, name: "iphone 17 pro max", price: 150000, image: "/assets/product-images/iphone1.jpg", category: "PHONE", brand: "Apple" },
  { id: 10, name: "JBL Wave Flex 2 Earbuds", price: 2300, image: "/assets/product-images/audio2.jpg", category: "AUDIO", brand: "JBL" },
  { id: 11, name: "Winter New Hudi", price: 1300, image: "/assets/product-images/hudi.jpg", category: "CLOTH", brand: "BrandY" },
  { id: 12, name: "Baggy pant for Men", price: 950, image: "/assets/product-images/pant.jpg", category: "CLOTH", brand: "BrandY" },
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

function applyFilters() { page.value = 1; }
function onCategoryAll() { if (filters.categories.includes("ALL")) filters.categories = ["ALL"]; applyFilters(); }
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
    if (!filters.categories.includes("ALL") && filters.categories.length && !filters.categories.includes(p.category)) return false;
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
function addToCart(product) {
  toast.text = `${product.name} is added to cart!`;
  toast.show = true;
  setTimeout(() => (toast.show = false), 2200);
}

const rangePercent = computed(() => (Math.min(filters.maxPrice, priceMax) / priceMax) * 100);

onMounted(() => {
  const check = () => (isMobile.value = window.innerWidth <= 768);
  check();
  window.addEventListener("resize", check);
});
</script>

<style scoped>
/* ----------------------------------------------------------
   BASIC RESET & COMMON
---------------------------------------------------------- */
* { box-sizing: border-box; margin: 0; padding: 0; }
.page { font-family: Inter, system-ui, sans-serif; color: #222; }
#search-bar { padding: 7px; }

/* MOBILE TOP BAR */
.mobile-top { display: none; padding: 10px 16px; background: #fff; align-items: center; gap: 12px; border-bottom: 1px solid #eee; }
.mobile-top .filter-btn { background: #e4002b; color: #fff; border: none; padding: 8px 12px; border-radius: 6px; display: flex; align-items: center; gap: 6px; cursor: pointer; }
.mobile-top .mobile-search-quick { flex: 1; position: relative; }
.mobile-top input { width: 100%; padding: 8px 36px 8px 12px; border-radius: 8px; border: 1px solid #ddd; }
.mobile-top i { position: absolute; right: 10px; top: 50%; transform: translateY(-50%); color: #999; }

/* MAIN CONTENT */
.content { display: flex; gap: 24px; max-width: 1200px; margin: 24px auto; padding: 0 16px; }

/* SIDEBAR */
.sidebar { width: 260px; background: #f8f9fb; padding: 18px; border-radius: 8px; height: fit-content; position: sticky; top: 20px; flex-shrink: 0; transition: 0.3s; }
.sidebar-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; }
.logo { font-weight: 700; font-size: 20px; color: #222; }
.logo-red { color: #e4002b; }
.sidebar .widget { margin: 12px 0; }
.sidebar select, #search-bar { width: 100%; padding: 8px 12px; border-radius: 8px; border: 1px solid #ddd; }
.widget h4 { margin: 6px 0 8px; font-size: 14px; color: #333; }
.chk { display: flex; align-items: center; gap: 10px; margin: 6px 0; font-size: 13px; cursor: pointer; }
.reset { width: 100%; background: #e4002b; color: #fff; border: none; padding: 10px; border-radius: 8px; cursor: pointer; font-weight: 600; }

/* PRODUCTS AREA */
.products-area { flex: 1; }
.grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 18px; }

/* PRODUCT CARD */
.card { background: #fff; border-radius: 12px; padding: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); display: flex; flex-direction: column; gap: 8px; position: relative; transition: transform 0.2s; }
.card:hover { transform: translateY(-3px); }

.img-wrap { width: 100%; height: 160px; background: #fafafa; border-radius: 8px; overflow: hidden; position: relative; display: flex; align-items: center; justify-content: center; }
.img-wrap img { max-width: 90%; max-height: 90%; object-fit: contain; }

/* FLOATING ADD TO CART ICON (Top Right) */
.floating-add-cart {
  position: absolute;
  top: 8px;
  right: 8px;
  width: 34px;
  height: 34px;
  background: #fff;
  border: none;
  border-radius: 50%;
  color: #e4002b;
  box-shadow: 0 2px 8px rgba(0,0,0,0.15);
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 5;
  transition: 0.2s;
}
.floating-add-cart:hover { background: #e4002b; color: #fff; }
.floating-add-cart i { font-size: 16px; }

.title { font-size: 14px; color: #333; height: 38px; overflow: hidden; text-align: center; line-height: 1.3; }
.price { font-weight: 700; font-size: 16px; color: #e4002b; text-align: center; }

/* ORDER NOW BUTTON */
.order-btn {
  margin-top: auto;
  background: #e4002b;
  color: #fff;
  text-decoration: none;
  padding: 10px;
  border-radius: 8px;
  text-align: center;
  font-size: 14px;
  font-weight: 600;
  transition: 0.2s;
}
.order-btn:hover { background: #c30025; }

/* PAGINATION & TOAST */
.pagination { display: flex; gap: 6px; margin-top: 24px; justify-content: center; }
.pagination button { padding: 8px 12px; border-radius: 6px; border: 1px solid #ddd; background: #fff; cursor: pointer; }
.pagination button.active { background: #e4002b; color: #fff; border-color: #e4002b; }
.toast { position: fixed; right: 20px; bottom: 20px; background: #fff; border-left: 4px solid #2ecc71; padding: 12px 16px; border-radius: 8px; box-shadow: 0 5px 20px rgba(0,0,0,0.15); display: flex; align-items: center; gap: 10px; z-index: 9999; color: #2ecc71; font-weight: 500; }

/* ----------------------------------------------------------
   RESPONSIVE (MOBILE OPTIMIZATION)
---------------------------------------------------------- */
@media (max-width: 1100px) { .grid { grid-template-columns: repeat(3, 1fr); } }

@media (max-width: 768px) {
  .mobile-top { display: flex; }
  .content { flex-direction: column; padding: 12px; margin: 0; }
  
  /* SIDEBAR MOBILE OVERLAY */
  .sidebar { position: fixed; left: -100%; top: 0; height: 100vh; z-index: 1000; width: 80%; max-width: 300px; box-shadow: 5px 0 15px rgba(0,0,0,0.2); }
  .sidebar.open { left: 0; }
  
  /* 2 PRODUCTS IN ONE ROW ON MOBILE */
  .grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
  .img-wrap { height: 130px; }
  .title { font-size: 13px; height: 34px; }
  .price { font-size: 14px; }
  .order-btn { padding: 8px; font-size: 13px; }
}

@media (max-width: 400px) {
  .grid { gap: 8px; }
  .card { padding: 8px; }
}
</style>