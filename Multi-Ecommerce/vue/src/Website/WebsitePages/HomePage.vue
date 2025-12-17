<template>
  <div class="home-page">
    <!-- Slider Component -->
     <div class="homeslider">
        <HomeSlider />
     </div>
  

    <!-- Product Slider Component -->
     <div class="productslider">
       <ProductSlider />
     </div>
   


  </div>
</template>

<script setup>
// Step 1: Component import
import HomeSlider from './HomeSlider.vue'
import ProductSlider from './ProductSlider.vue'

// Step 2: Composition API এ component register করতে হলে এখন কোনো extra code লাগে না।
// <script setup> auto register করে।
</script>

<style scoped>
.home-page {
  font-family: "Segoe UI", Arial, sans-serif;
}
.productslider{
  margin: 10px 0;
}
</style>
