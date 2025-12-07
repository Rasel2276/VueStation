<template>
  <div>

    <!-- =================== CARD SECTION =================== -->
    <div class="card-section">
      <div class="income-card" v-for="card in cards" :key="card.title">
        <div class="card-header">
          <span>{{ card.title }}</span>
          <span class="minus">−</span>
        </div>
        <div class="card-body">
          <div class="amount-row">
            <span class="currency">$</span>
            <span class="amount">{{ card.amount }}</span>
          </div>
        </div>
      </div>
    </div>

    <!-- =================== TABLE + GRAPH SECTION =================== -->
    <div class="layout">

      <!-- LEFT: ApexCharts Bar Chart -->
      <div class="graph-container">
        <vue-apex-charts
          type="bar"
          :options="chartOptions"
          :series="series"
          height="350"
        ></vue-apex-charts>
      </div>

      <!-- RIGHT: Vendor Table -->
      <div class="vendor-table-container">
        <h2 class="table-title">Top Vendors</h2>

        <table class="vendor-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Vendor Name</th>
              <th>Shop Name</th>
              <th>Total Sale</th>
            </tr>
          </thead>

          <tbody>
            <tr v-for="vendor in vendors" :key="vendor.id">
              <td>{{ vendor.id }}</td>
              <td>{{ vendor.name }}</td>
              <td>{{ vendor.shop }}</td>
              <td>${{ vendor.total_sale }}</td>
            </tr>
          </tbody>
        </table>
      </div>

    </div>

  </div>
</template>

<script>
import VueApexCharts from "vue3-apexcharts";

export default {
  name: "Dashboard",
  components: { VueApexCharts },
  data() {
    return {
      cards: [
        { title: "Received Order", amount: "800.658" },
        { title: "Pending Order", amount: "800.658" },
        { title: "Total Vendor", amount: "800.658" },
        { title: "Total Income", amount: "800.658" },
      ],
      vendors: [
        { id: 1, name: "Rahim", shop: "Rahim Store", total_sale: 5000 },
        { id: 2, name: "Karim", shop: "Karim Shop", total_sale: 4200 },
        { id: 3, name: "Sabbir", shop: "Sabbir Mart", total_sale: 3800 },
        { id: 4, name: "Nayeem", shop: "Nayeem Bazar", total_sale: 3500 },
        { id: 5, name: "Jamil", shop: "Jamil Store", total_sale: 4700 }
      ],

      series: [
        { data: [44, 55, 57, 56, 61, 58, 63, 60, 66] },
        { data: [76, 85, 101, 98, 87, 105, 91, 114, 94] },
        { data: [35, 41, 36, 26, 45, 48, 52, 53, 41] }
      ],

      chartOptions: {
        chart: {
          type: 'bar',
          height: 350,
          toolbar: { show: false } // remove download/export icons
        },
        plotOptions: {
          bar: {
            horizontal: false,
            columnWidth: '55%',
            borderRadius: 5,
            borderRadiusApplication: 'end'
          },
        },
        dataLabels: { enabled: false },
        stroke: { show: true, width: 2, colors: ['transparent'] },
        xaxis: { categories: ['Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'] },
        yaxis: { show: false }, // hide y-axis labels
        fill: { opacity: 1 },
        tooltip: {
          y: {
            formatter: function (val) { return "$ " + val + " thousands" }
          }
        },
        legend: { show: false } // hide legend
      }
    };
  }
};
</script>

<style scoped>
.card-section {
  display: flex;
  justify-content: space-evenly;
  gap: 20px;
  margin-bottom: 40px;
}

.income-card {
  width: 200px;
  border-radius: 7px;
  background: white;
  box-shadow: 0 4px 12px rgba(0,0,0,0.08);
  overflow: hidden;
}

.card-header {
  background: linear-gradient(to bottom, #0f6bbf, #0aaed8);
  padding: 12px 15px;
  display: flex;
  justify-content: space-between;
  font-size: 16px;
  color: white;
  font-weight: 600;
}

.card-body {
  padding: 18px;
  text-align: center;
}

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

.layout {
  display: flex;
  justify-content: space-between;
  gap: 30px;
  align-items: flex-end; /* chart & table bottom align */
}

.graph-container {
  width: 50%;
  text-align: center;
}

.vendor-table-container {
  width: 50%;
  margin-bottom: 50px;
}

.table-title {
  margin-bottom: 15px;
  font-size: 22px;
  font-weight: 600;
}

.vendor-table {
  width: 100%;
  border-collapse: collapse;
  box-shadow: 0 2px 12px rgba(0,0,0,0.1);
}

.vendor-table thead {
  background: linear-gradient(to bottom, #0f6bbf, #0aaed8);
  color: white;
}

.vendor-table th,
.vendor-table td {
  padding: 12px 15px;
  border-bottom: 1px solid #eee;
}

.vendor-table tr:nth-child(even) {
  background: #CAF4FF;
}
</style>
