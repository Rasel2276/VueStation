<template>
  <div class="product-slider">

    <!-- LEFT ARROW -->
    <button class="nav left" @click="prevSlide">❮</button>

    <!-- SLIDER WINDOW -->
    <div class="slider-window">
      <div class="slider-track" 
           :style="{ transform: `translateX(-${currentIndex * (cardWidth + gap)}px)`, transition: transitioning ? 'transform 0.5s ease-in-out' : 'none' }"
           @transitionend="handleTransitionEnd">
        <div class="product-card" v-for="(product, index) in loopedProducts" :key="index">
          <img :src="product.image" :alt="product.name" />
          <h3>{{ product.name }}</h3>
          <p>{{ product.price }}</p>
          <button>Add to Cart</button>
        </div>
      </div>
    </div>

    <!-- RIGHT ARROW -->
    <button class="nav right" @click="nextSlide">❯</button>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const currentIndex = ref(0)
const gap = 12
const cardWidth = ref(160) // desktop small card
const transitioning = ref(true)

const products = ref([
  { name: "Smartwatch", price: "$299", image: "https://images.unsplash.com/photo-1522312346375-d1a52e2b99b3" },
  { name: "Camera", price: "$499", image: "https://images.unsplash.com/photo-1509395176047-4a66953fd231" },
  { name: "Shoes", price: "$129", image: "https://images.unsplash.com/photo-1513104890138-7c749659a591" },
  { name: "Smartwatch", price: "$299", image: "https://images.unsplash.com/photo-1522312346375-d1a52e2b99b3" },
  { name: "Camera", price: "$499", image: "https://images.unsplash.com/photo-1509395176047-4a66953fd231" },
  { name: "Shoes", price: "$129", image: "https://images.unsplash.com/photo-1513104890138-7c749659a591" },
  { name: "Smartwatch", price: "$299", image: "https://images.unsplash.com/photo-1522312346375-d1a52e2b99b3" },
  { name: "Camera", price: "$499", image: "https://images.unsplash.com/photo-1509395176047-4a66953fd231" },
  { name: "Shoes", price: "$129", image: "https://images.unsplash.com/photo-1513104890138-7c749659a591" },
])

// Duplicate products for seamless loop
const loopedProducts = ref([...products.value, ...products.value])
let maxIndex = ref(products.value.length)

const updateCardWidth = () => {
  const windowWidth = window.innerWidth
  if (windowWidth >= 1024) cardWidth.value = 160 // desktop
  else if (windowWidth >= 768) cardWidth.value = 120 // tablet
  else cardWidth.value = 100 // mobile
}

// NEXT
const nextSlide = () => {
  transitioning.value = true
  currentIndex.value++
}

// PREV
const prevSlide = () => {
  transitioning.value = true
  currentIndex.value--
}

// Loop reset after transition
const handleTransitionEnd = () => {
  if(currentIndex.value >= maxIndex.value){
    transitioning.value = false
    currentIndex.value = 0
  }
  if(currentIndex.value < 0){
    transitioning.value = false
    currentIndex.value = maxIndex.value - 1
  }
}

onMounted(() => {
  updateCardWidth()
  window.addEventListener('resize', updateCardWidth)
})
</script>

<style scoped>
.product-slider {
  position: relative;
  width: 100%;
  display: flex;
  align-items: center;
  gap: 10px;
  overflow: hidden;
  padding: 15px 0;
  box-sizing: border-box;
}

/* NAV BUTTONS */
.nav {
  background: #3b82f6;
  color: white;
  border: none;
  font-size: 22px;
  padding: 6px 10px;
  cursor: pointer;
  border-radius: 6px;
  transition: 0.3s;
  flex-shrink: 0;
}

.nav:hover {
  background: #2563eb;
}

/* SLIDER WINDOW */
.slider-window {
  overflow: hidden;
  flex: 1;
}

/* SLIDER TRACK */
.slider-track {
  display: flex;
  transition: transform 0.5s ease-in-out;
  gap: 12px;
}

/* PRODUCT CARD */
.product-card {
  flex: 0 0 auto;
  width: 160px; /* desktop small card */
  background: #fff;
  border-radius: 10px;
  box-shadow: 0 5px 12px rgba(0,0,0,0.1);
  text-align: center;
  padding: 10px;
  transition: transform 0.3s, box-shadow 0.3s;
}

.product-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 10px 18px rgba(0,0,0,0.12);
}

.product-card img {
  width: 100%;
  border-radius: 6px;
  margin-bottom: 6px;
  object-fit: cover;
  aspect-ratio: 1 / 1;
}

.product-card h3 {
  font-size: 0.9rem;
  margin-bottom: 3px;
  color: #222;
}

.product-card p {
  color: #3b82f6;
  font-weight: 600;
  margin-bottom: 6px;
}

.product-card button {
  padding: 4px 8px;
  border: none;
  background: #3b82f6;
  color: #fff;
  font-weight: 600;
  border-radius: 5px;
  cursor: pointer;
  transition: 0.3s;
}

.product-card button:hover {
  background: #2563eb;
}

/* RESPONSIVE */
@media (max-width: 1024px) {
  .product-card { width: 120px; }
}

@media (max-width: 768px) {
  .product-card { width: 100px; }
}
</style>
