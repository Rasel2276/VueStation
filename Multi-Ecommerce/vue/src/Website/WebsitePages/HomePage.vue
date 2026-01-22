<template>
  <div class="home-page">
  
     <div class="homeslider">
        <HomeSlider />
     </div>
  

    
     <div class="homeproduct">
       <HomeProducts />
     </div>
   


  </div>
</template>

<script setup>

import HomeSlider from './HomeSlider.vue'
import HomeProducts from './HomeProducts.vue'


</script>

<style scoped>
.home-page {
  font-family: "Segoe UI", Arial, sans-serif;
}
.productslider{
  margin: 10px 0;
}
</style>
