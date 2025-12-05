<template>
  <div class="main-layout">
    <Sidebar />

    <div class="content-area">
      <Topbar />
      <router-view />
    </div>
  </div>
</template>

<script>
import Sidebar from "../components/Sidebar.vue";
import Topbar from "../components/Topbar.vue";

export default {
  components: { Sidebar, Topbar },
};
</script>

<style>
.main-layout {
  display: flex;
  height: 100vh;
  overflow: hidden;
}

.content-area {
  width: 100%;
  background: #e9f8ff;
  padding: 20px;
  overflow-y: auto;
}
</style>
