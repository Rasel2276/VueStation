<template>
  <div class="dash-grid">
    <div class="left">
      <ChartCard />
      <LatestTasks />
      <Messages />
    </div>

    <div class="right">
      <EarningsCard />
      <StatsCard :value="75" color="#ff6b6b" />
      <StatsCard :value="50" color="#66b3ff" />
      <CalendarCard />
    </div>
  </div>
</template>

<script>
import ChartCard from "../components/ChartCard.vue";
import LatestTasks from "../components/LatestTasks.vue";
import Messages from "../components/Messages.vue";
import EarningsCard from "../components/EarningsCard.vue";
import StatsCard from "../components/StatsCard.vue";
import CalendarCard from "../components/CalendarCard.vue";

export default {
  components: {
    ChartCard,
    LatestTasks,
    Messages,
    EarningsCard,
    StatsCard,
    CalendarCard,
  },
};
</script>

<style>
.dash-grid {
  display: grid;
  grid-template-columns: 3fr 1fr;
  gap: 20px;
}

.left,
.right {
  display: flex;
  flex-direction: column;
  gap: 20px;
}
</style>
