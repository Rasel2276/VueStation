<template>
  <div class="earn-card">
    <h3>EARNINGS</h3>

    <h1 class="amount">$15845.00</h1>

    <img src="https://i.ibb.co/56FDxQF/line.png" class="chart" />

    <p>OBJECTIVE</p>

    <div class="progress">
      <div class="bar"></div>
    </div>

    <div class="target">$25,000</div>
  </div>
</template>

<style scoped>
.earn-card {
  background: #0c9ddd;
  color: white;
  padding: 20px;
  border-radius: 10px;
}

.amount {
  margin: 10px 0;
}

.chart {
  width: 100%;
  margin: 15px 0;
}

.progress {
  width: 100%;
  height: 10px;
  border-radius: 50px;
  background: white;
}

.bar {
  width: 45%;
  height: 10px;
  background: yellow;
  border-radius: 10px;
}

.target {
  text-align: right;
  margin-top: 5px;
}
</style>
