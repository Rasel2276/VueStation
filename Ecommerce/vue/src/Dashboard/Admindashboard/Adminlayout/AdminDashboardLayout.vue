<template>
  <div id="admin-dashboard">
    <!-- Sidebar -->
    <Sidebar :collapsed="sidebarCollapsed" />

    <!-- Main Area -->
    <div :class="['main-area', { collapsed: sidebarCollapsed }]">
      <!-- Header -->
      <Header @toggle-sidebar="toggleSidebar" />

      <!-- Scrollable Content -->
      <div class="content">
        <router-view />
      </div>
    </div>
  </div>
</template>

<script>
import Sidebar from "./Sidebar.vue";
import Header from "./Header.vue";

export default {
  name: "AdminDashboard",
  components: { Sidebar, Header },
  data() {
    return {
      sidebarCollapsed: false,
    };
  },
  methods: {
    toggleSidebar() {
      this.sidebarCollapsed = !this.sidebarCollapsed;
    },
  },
};
</script>

<style scoped>
/* Reset */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

html,
body,
#app {
  height: 100%;
  font-family: Arial, sans-serif;
  overflow: hidden;
}

/* Container */
#admin-dashboard {
  display: flex;
  height: 100vh;
  width: 100%;
}

/* Sidebar */
.sidebar {
  width: 230px;
  height: 100%;
  position: fixed;
  top: 0;
  left: 0;
  background: linear-gradient(to bottom, #0f6bbf, #0aaed8);
  display: flex;
  flex-direction: column;
  color: white;
  overflow-y: auto;
  transition: width 0.3s ease;
  z-index: 200;
}

.sidebar.collapsed {
  width: 60px;
}

/* Main Area */
.main-area {
  margin-left: 232px;
  display: flex;
  flex-direction: column;
  width: calc(100% - 232px);
  height: 100%;
  overflow: hidden;
  transition: margin-left 0.3s ease, width 0.3s ease;
}

.main-area.collapsed {
  margin-left: 60px;
  width: calc(100% - 60px);
}

/* Header */
.header {
  height: 60px;
  width: 100%;
  position: sticky;
  top: 0;
  background: linear-gradient(to right, #0f6bbf, #0aaed8);
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0 20px;
  color: white;
  z-index: 150;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
}

/* Scrollable Content */
.content {
  flex: 1;
  overflow-y: auto;
  background-color: #f4f6f8;
  padding: 20px;
}

/* Responsive */
@media screen and (max-width: 1024px) {
  .sidebar {
    width: 200px;
  }
  .main-area {
    margin-left: 200px;
    width: calc(100% - 200px);
  }
  .main-area.collapsed {
    margin-left: 60px;
    width: calc(100% - 60px);
  }
}

@media screen and (max-width: 768px) {
  .sidebar {
    width: 60px;
  }
  .main-area {
    margin-left: 60px;
    width: calc(100% - 60px);
  }
}
</style>
