
<template>
  <div id="app">
    <!-- Navbar (Home component) -->
    <Home />

    <!-- Page content -->
    <router-view />
  </div>
</template>

<script>
import Home from "@/components/Home.vue";

export default {
  name: "App",
  components: {
    Home,
  },
};
</script>

