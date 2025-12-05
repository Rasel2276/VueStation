<template>
  <div class="task-box">
    <h3>LATEST TASKS</h3>

    <div class="task" v-for="t in tasks" :key="t.id">
      <input type="checkbox" />
      <div class="info">
        <h4>{{ t.title }}</h4>
        <p>{{ t.desc }}</p>
      </div>

      <span class="status" :class="{ done: t.done }">
        {{ t.done ? "100%" : "" }}
      </span>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      tasks: [
        { id: 1, title: "Task Number 1", desc: "Lorem ipsum...", done: true },
        { id: 2, title: "Task Number 2", desc: "Lorem ipsum...", done: false },
        { id: 3, title: "Task Number 3", desc: "Lorem ipsum...", done: false },
      ],
    };
  },
};
</script>

<style scoped>
.task-box {
  background: #fff;
  padding: 20px;
  border-radius: 10px;
}

.task {
  display: flex;
  align-items: flex-start;
  margin: 15px 0;
}

.info {
  margin-left: 10px;
}

.status {
  margin-left: auto;
  font-weight: bold;
}

.done {
  color: green;
}
</style>
