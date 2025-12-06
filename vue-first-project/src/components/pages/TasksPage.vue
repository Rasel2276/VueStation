<template>
  <div class="card-section">
  <div class="income-card">
    <!-- Top Bar -->
    <div class="card-header">
      <span>Received Order</span>
      <span class="minus">−</span>
    </div>

    <!-- Content Section -->
    <div class="card-body">
     
      <div class="amount-row">
        <span class="currency">$</span>
        <span class="amount">800.658</span>
      </div>
    </div>
  </div>

    <div class="income-card">
    <!-- Top Bar -->
    <div class="card-header">
      <span>Pending Order</span>
      <span class="minus">−</span>
    </div>

    <!-- Content Section -->
    <div class="card-body">
     
      <div class="amount-row">
        <span class="currency">$</span>
        <span class="amount">800.658</span>
      </div>
    </div>
  </div>

    <div class="income-card">
    <!-- Top Bar -->
    <div class="card-header">
      <span>Total Vendor</span>
      <span class="minus">−</span>
    </div>

    <!-- Content Section -->
    <div class="card-body">
     
      <div class="amount-row">
        <span class="currency">$</span>
        <span class="amount">800.658</span>
      </div>
    </div>
  </div>

    <div class="income-card">
    <!-- Top Bar -->
    <div class="card-header">
      <span>Total Income</span>
      <span class="minus">−</span>
    </div>

    <!-- Content Section -->
    <div class="card-body">
     
      <div class="amount-row">
        <span class="currency">$</span>
        <span class="amount">800.658</span>
      </div>
    </div>
  </div>

</div>

</template>

<style scoped>
.card-section{
  display: flex;
  justify-content:space-evenly ;
  gap: 20px;
}

.income-card {
  width: 200px;
  border-radius: 7px;
  background: white;
  box-shadow: 0 4px 12px rgba(0,0,0,0.08);
  overflow: hidden;
  font-family: "Poppins", sans-serif;
}

/* Top header */
.card-header {
  background: linear-gradient(to bottom, #0f6bbf, #0aaed8);
  padding: 12px 15px;
  display: flex;
  justify-content: space-between;
  font-size: 16px;
  color: white;
  font-weight: 600;
}

.minus {
  font-size: 20px;
  font-weight: bold;
}

/* Body */
.card-body {
  padding: 18px;
  text-align: center;
}

/* Circle Icon */
.icon {
  width: 22px;
  height: 22px;
  background: #0b0b38;
  border-radius: 50%;
  display: inline-block;
  margin-bottom: 10px;
}

/* Amount */
.amount-row {
  display: flex;
  justify-content: center;
  align-items: baseline;
  gap: 5px;
}

.currency {
  color: green;
  font-size: 24px;
  font-weight: 700;
}

.amount {
  color: green;
  font-size: 28px;
  font-weight: 700;
}

/* Subtitle text */
.desc {
  margin-top: 5px;
  font-size: 12px;
  color: gray;
}
</style>

