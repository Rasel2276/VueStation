<template>
  <footer class="app-footer">
    © {{ year }} Your Company. All Rights Reserved.
  </footer>
</template>

<script>
export default {
  name: "Footer",
  data() {
    return {
      year: new Date().getFullYear(),
    };
  },
};
</script>

<style scoped>
.app-footer {
  background: linear-gradient(to right, #0f6bbf, #0aaed8);
  color: #fff;
  text-align: center;
  padding: 14px 0;
  width: 100%;
  font-size: 14px;
  margin-top: auto; /* pushes footer to bottom */
  flex-shrink: 0;
  box-shadow: 0 -2px 4px rgba(0, 0, 0, 0.1);
}
</style>
