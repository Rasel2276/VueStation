<template>
  <div class="home-page">
  
     <div class="homeslider">
        <HomeSlider />
     </div>
  

    
     <div class="productslider">
       <ProductSlider />
     </div>
   


  </div>
</template>

<script setup>

import HomeSlider from './HomeSlider.vue'
import ProductSlider from './ProductSlider.vue'


</script>

<style scoped>
.home-page {
  font-family: "Segoe UI", Arial, sans-serif;
}
.productslider{
  margin: 10px 0;
}
</style>
