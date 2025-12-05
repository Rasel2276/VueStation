<template>
  <div :class="['sidebar', { collapsed: isCollapsed }]">
    <!-- Toggle Button (Right Side Floating) -->
    <div class="toggle-btn" @click="toggleSidebar">
      <span v-if="isCollapsed">➤</span>
      <span v-else>❮</span>
    </div>

    <!-- Top Profile Section -->
    <div class="profile-box" v-if="!isCollapsed">
      <p class="welcome-text">WELCOME</p>
      <div class="avatar"></div>
      <h3 class="name">DAVID SMITH</h3>
      <p class="position">POSITION</p>
    </div>

    <!-- Menu Section -->
    <ul class="menu">
      <li>
        <div class="menu-item" @click="toggleMenu('dashboard')">
          <span v-if="!isCollapsed">DASHBOARD</span>
          <span class="arrow">&#9654;</span>
        </div>
      </li>

      <li>
        <div class="menu-item" @click="toggleMenu('tasks')">
          <span v-if="!isCollapsed">TASKS</span>
          <span v-if="!isCollapsed" class="badge">6</span>
          <span class="arrow">&#9654;</span>
        </div>

        <ul v-if="open.tasks && !isCollapsed" class="dropdown">
          <li>Task List</li>
          <li>Create Task</li>
        </ul>
      </li>

      <li>
        <div class="menu-item" @click="toggleMenu('calendar')">
          <span v-if="!isCollapsed">CALENDAR</span>
          <span class="arrow">&#9654;</span>
        </div>
        <ul v-if="open.calendar && !isCollapsed" class="dropdown">
          <li>Events</li>
          <li>Schedules</li>
        </ul>
      </li>

      <li>
        <div class="menu-item" @click="toggleMenu('charts')">
          <span v-if="!isCollapsed">CHARTS</span>
          <span class="arrow">&#9654;</span>
        </div>
        <ul v-if="open.charts && !isCollapsed" class="dropdown">
          <li>Sales</li>
          <li>Performance</li>
        </ul>
      </li>

      <li>
        <div class="menu-item" @click="toggleMenu('forms')">
          <span v-if="!isCollapsed">FORMS</span>
          <span class="arrow">&#9654;</span>
        </div>
        <ul v-if="open.forms && !isCollapsed" class="dropdown">
          <li>Form 1</li>
          <li>Form 2</li>
        </ul>
      </li>

      <li>
        <div class="menu-item" @click="toggleMenu('messages')">
          <span v-if="!isCollapsed">MESSAGES</span>
          <span class="arrow">&#9654;</span>
        </div>
        <ul v-if="open.messages && !isCollapsed" class="dropdown">
          <li>Inbox</li>
          <li>Sent</li>
        </ul>
      </li>

      <li>
        <div class="menu-item" @click="toggleMenu('users')">
          <span v-if="!isCollapsed">USERS</span>
          <span class="arrow">&#9654;</span>
        </div>
        <ul v-if="open.users && !isCollapsed" class="dropdown">
          <li>All Users</li>
          <li>Add User</li>
        </ul>
      </li>
    </ul>

    <!-- Settings Bottom -->
    <div class="settings" v-if="!isCollapsed">⚙ SETTINGS</div>
  </div>
</template>

<script>
export default {
  name: "Sidebar",

  data() {
    return {
      isCollapsed: false,

      open: {
        dashboard: false,
        tasks: false,
        calendar: false,
        charts: false,
        forms: false,
        messages: false,
        users: false,
      },
    };
  },

  methods: {
    toggleMenu(menu) {
      this.open[menu] = !this.open[menu];
    },

    toggleSidebar() {
      this.isCollapsed = !this.isCollapsed;
    },
  },
};
</script>

<style>
body {
  margin: 0px;
  padding: 0px;
}

.sidebar {
  width: 230px;
  height: 100vh;
  background: linear-gradient(to bottom, #0f6bbf, #0aaed8);
  color: white;
  padding: 0;
  display: flex;
  flex-direction: column;
  position: relative;
  font-family: Arial, sans-serif;
  transition: 0.3s ease;
}

.sidebar.collapsed {
  width: 70px;
}

/* Toggle Button Right Side Floating */
.toggle-btn {
  position: absolute;
  top: 15px;
  right: -15px;
  width: 30px;
  height: 30px;

  color: #fdfeffff;
  border-radius: 50%;
  display: flex;
  justify-content: center;
  align-items: center;
  cursor: pointer;
  font-size: 15px;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
  transition: 0.3s;
  margin-right: 20px;
}

/* Profile Box */
.profile-box {
  text-align: center;
  padding: 20px 0;
  border-bottom: 1px solid rgba(255, 255, 255, 0.2);
}

.welcome-text {
  font-size: 12px;
  opacity: 0.8;
}

.avatar {
  width: 70px;
  height: 70px;
  margin: 10px auto;
  background: white;
  border-radius: 50%;
  border: 4px solid #7fd7ff;
}

.name {
  font-size: 16px;
}

.position {
  font-size: 12px;
  opacity: 0.7;
}

/* Menu */
.menu {
  list-style: none;
  padding: 0;
  margin: 0;
}

.menu-item {
  padding: 12px 15px;
  border-bottom: 1px solid rgba(255, 255, 255, 0.2);
  cursor: pointer;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.sidebar.collapsed .menu-item {
  justify-content: center;
}

.menu-item:hover {
  background-color: rgba(0, 0, 0, 0.15);
}

.arrow {
  font-size: 14px;
  opacity: 0.7;
}

/* Badge */
.badge {
  background: #ff6c6c;
  padding: 2px 7px;
  border-radius: 50%;
  font-size: 12px;
  margin-left: 10px;
}

/* Dropdown */
.dropdown {
  background: rgba(255, 255, 255, 0.1);
  list-style: none;
  padding-left: 20px;
  animation: fadeIn 0.3s;
}

.dropdown li {
  padding: 8px 0;
  cursor: pointer;
}

.dropdown li:hover {
  opacity: 0.7;
}

/* Settings Bottom */
.settings {
  position: absolute;
  bottom: 20px;
  left: 20px;
  font-size: 14px;
  cursor: pointer;
  opacity: 0.9;
}

.sidebar.collapsed .settings {
  display: none;
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}
</style>
