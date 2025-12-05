<template>
  <div class="stats-card" :style="{ background: color }">
    <div class="circle">
      <span>{{ value }}%</span>
    </div>
    <p>TASKS</p>
  </div>
</template>

<script>
export default {
  props: ["value", "color"],
};
</script>

<style scoped>
.stats-card {
  padding: 25px 20px;
  color: white;
  border-radius: 10px;
  text-align: center;
}

.circle {
  width: 110px;
  height: 110px;
  background: white;
  border-radius: 50%;
  margin: 0 auto 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: black;
  font-size: 24px;
}
</style>
