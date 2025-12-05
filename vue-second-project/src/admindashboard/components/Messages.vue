<template>
  <div class="msg-box">
    <h3>MESSAGES</h3>

    <div class="msg" v-for="m in messages" :key="m.id">
      <h4>{{ m.title }}</h4>
      <p>{{ m.desc }}</p>
      <span class="date">{{ m.date }}</span>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      messages: [
        {
          id: 1,
          title: "Message 1",
          desc: "Lorem ipsum text...",
          date: "25/12/2020",
        },
        {
          id: 2,
          title: "Message 2",
          desc: "Lorem ipsum text...",
          date: "26/12/2020",
        },
      ],
    };
  },
};
</script>

<style scoped>
.msg-box {
  background: #fff;
  padding: 20px;
  border-radius: 10px;
}

.msg {
  margin: 15px 0;
}

.date {
  float: right;
  opacity: 0.6;
}
</style>
