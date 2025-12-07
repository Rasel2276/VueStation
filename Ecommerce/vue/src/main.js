import { createApp } from "vue";
import App from "./App.vue";
import router from "./router";

import VueApexCharts from "vue3-apexcharts"; // <-- ADD THIS

const app = createApp(App);

app.use(router);
app.use(VueApexCharts); // <-- REGISTER CHARTS

app.mount("#app");

