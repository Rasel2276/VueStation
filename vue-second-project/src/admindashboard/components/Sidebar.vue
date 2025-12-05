<template>
  <div class="sidebar">
    <div class="profile-box">
      <p class="welcome">WELCOME</p>
      <div class="avatar"></div>
      <h3 class="name">DAVID SMITH</h3>
      <p class="role">POSITION</p>
    </div>

    <ul class="menu">
      <li v-for="item in menu" :key="item.name">
        <div class="menu-item" @click="toggle(item)">
          <span>{{ item.name }}</span>

          <span v-if="item.badge" class="badge">{{ item.badge }}</span>
          <span class="arrow">&#9654;</span>
        </div>

        <ul v-if="item.open" class="dropdown">
          <li v-for="sub in item.children" :key="sub">{{ sub }}</li>
        </ul>
      </li>
    </ul>

    <div class="settings">⚙ SETTINGS</div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      menu: [
        { name: "DASHBOARD", children: [], open: false },
        {
          name: "TASKS",
          badge: 6,
          children: ["Task List", "Create Task"],
          open: false,
        },
        { name: "CALENDAR", children: ["Events", "Schedules"], open: false },
        {
          name: "CHARTS",
          children: ["Sales Chart", "Performance"],
          open: false,
        },
        { name: "FORMS", children: ["Form 1", "Form 2"], open: false },
        { name: "MESSAGES", children: ["Inbox", "Sent"], open: false },
        { name: "USERS", children: ["All Users", "Add User"], open: false },
      ],
    };
  },
  methods: {
    toggle(item) {
      item.open = !item.open;
    },
  },
};
</script>

<style scoped>
.sidebar {
  width: 230px;
  background: linear-gradient(to bottom, #0f6bbf, #0aaed8);
  color: white;
  height: 100vh;
  position: relative;
}

.profile-box {
  text-align: center;
  padding: 20px 0;
}

.welcome {
  opacity: 0.7;
}

.avatar {
  width: 70px;
  height: 70px;
  background: #fff;
  border-radius: 50%;
  margin: 10px auto;
  border: 4px solid #7fd7ff;
}

.name {
  margin: 5px 0;
}

.role {
  opacity: 0.7;
  font-size: 12px;
}

/* Menu */
.menu {
  list-style: none;
  padding: 0;
  margin-top: 10px;
}

.menu-item {
  padding: 12px 15px;
  border-bottom: 1px solid rgba(255, 255, 255, 0.3);
  cursor: pointer;
  display: flex;
  align-items: center;
}

.badge {
  background: #ff6c6c;
  padding: 2px 7px;
  border-radius: 50%;
  margin-left: 10px;
}

.arrow {
  margin-left: auto;
}

.dropdown {
  list-style: none;
  padding-left: 25px;
  background: rgba(255, 255, 255, 0.1);
}

.dropdown li {
  padding: 8px 0;
}

.settings {
  position: absolute;
  bottom: 20px;
  left: 20px;
  cursor: pointer;
}
</style>
