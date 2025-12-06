<template>
  <div class="cards-wrapper">
    <div class="card blue">
      <p class="title">Orders Received</p>
      <div class="icon-row">
        <i class="icon">🛒</i>
        <span class="number">486</span>
      </div>
      <p class="subtitle">Completed Orders <span>351</span></p>
    </div>

    <div class="card green">
      <p class="title">Orders Received</p>
      <div class="icon-row">
        <i class="icon">🚀</i>
        <span class="number">486</span>
      </div>
      <p class="subtitle">Completed Orders <span>351</span></p>
    </div>

    <div class="card yellow">
      <p class="title">Orders Received</p>
      <div class="icon-row">
        <i class="icon">🔄</i>
        <span class="number">486</span>
      </div>
      <p class="subtitle">Completed Orders <span>351</span></p>
    </div>

    <div class="card red">
      <p class="title">Orders Received</p>
      <div class="icon-row">
        <i class="icon">💳</i>
        <span class="number">486</span>
      </div>
      <p class="subtitle">Completed Orders <span>351</span></p>
    </div>
  </div>
</template>

<style scoped>
.cards-wrapper {
  display: flex;
  gap: 30px;
  margin: 15px;
  justify-content: center;
}

.card {
  width: 260px;
  padding: 15px;
  border-radius: 7px;
  color: white;
  font-family: "Poppins", sans-serif;
  box-shadow: 0 4px 15px rgba(0,0,0,0.1);
  transition: 0.3s;
}

.card:hover {
  transform: translateY(-5px);
}

/* Text styles */
.title {
  font-size: 18px;
  opacity: 0.95;
  margin-bottom: 10px;
}

.icon-row {
  display: flex;
  align-items: center;
  gap: 15px;
}

.icon {
  font-size: 36px;
}

.number {
  font-size: 32px;
  font-weight: bold;
}

.subtitle {
  margin-top: 15px;
  font-size: 15px;
  opacity: 0.9;
}

.subtitle span {
  margin-left: 10px;
  font-weight: bold;
}

/* Gradient Colors */
.blue {
  background: linear-gradient(135deg, #3ba4ff 0%, #4c7efd 100%);
}

.green {
  background: linear-gradient(135deg, #24d4b5 0%, #19cfa2 100%);
}

.yellow {
  background: linear-gradient(135deg, #ffcf66 0%, #f7a836 100%);
}

.red {
  background: linear-gradient(135deg, #ff6f91 0%, #ff5670 100%);
}
</style>
