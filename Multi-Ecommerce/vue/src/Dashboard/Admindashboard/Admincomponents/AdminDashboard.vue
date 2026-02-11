<template>
    <div class="dashboard-wrapper">
        <div class="container">
            <div class="page-header">
                <div class="header-content">
                    <h2 class="title">📊 Welcome, <span class="admin-name">{{ adminDisplayName }}!</span></h2>
                    <p class="subtitle"><i class="far fa-calendar-alt"></i> {{ today }}</p>
                </div>
            </div>

            <div class="stats-grid">
                <div class="stat-card color-1">
                    <div class="stat-icon">💰</div>
                    <div class="stat-info">
                        <h3>Total Sales</h3>
                        <p>৳ {{ formatNumber(totalSalesAmount) }}</p> 
                    </div>
                </div>

                <div class="stat-card color-2">
                    <div class="stat-icon">📥</div>
                    <div class="stat-info">
                        <h3>Total Purchase</h3>
                        <p>৳ {{ formatNumber(totalPurchaseAmount) }}</p>
                    </div>
                </div>

                <div class="stat-card color-4">
                    <div class="stat-icon">🏪</div>
                    <div class="stat-info">
                        <h3>Total Vendors</h3>
                        <p>{{ totalVendorsCount }}</p>
                    </div>
                </div>

                <div class="stat-card color-5">
                    <div class="stat-icon">💎</div>
                    <div class="stat-info">
                        <h3>Net Profit</h3>
                        <p>৳ {{ formatNumber(revenueAmount) }}</p>
                    </div>
                </div>
            </div>

            <div class="main-content-grid">
                <section class="dashboard-section chart-box">
                    <div class="section-header">
                        <h3>📈 Overall Sales Analysis</h3>
                    </div>
                    <div class="chart-placeholder">
                        <div class="bar-chart-mockup">
                            <div class="bar" style="height: 40%"></div>
                            <div class="bar" style="height: 70%"></div>
                            <div class="bar" style="height: 50%"></div>
                            <div class="bar" style="height: 90%"></div>
                            <div class="bar" style="height: 60%"></div>
                        </div>
                        <p>Real-time system data visualization</p>
                    </div>
                </section>

                <section class="dashboard-section progress-box">
                    <div class="section-header">
                        <h3>🎯 Monthly Target</h3>
                    </div>
                    <div class="progress-container">
                        <p class="progress-label">Performance <span>{{ targetPercentage }}%</span></p>
                        <div class="progress-bar">
                            <div class="progress-fill" :style="{ width: targetPercentage + '%' }"></div>
                        </div>
                        <p class="goal-helper">Target: ৳ {{ formatNumber(targetGoal) }}</p>
                    </div>
                </section>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import axios from 'axios'

// State variables
const token = localStorage.getItem('token')
const adminDisplayName = ref('Admin') // ডাইনামিক নামের জন্য
const totalSalesAmount = ref(0)
const totalPurchaseAmount = ref(0)
const totalVendorsCount = ref(0)
const targetGoal = 1000000 // উদাহরণস্বরূপ ১ লক্ষ টাকা টার্গেট

const today = new Date().toLocaleDateString(undefined, {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric'
})

// ডাটা ফেচ এবং নামের লজিক
const fetchDashboardData = async () => {
    // ১. লোকাল স্টোরেজ থেকে নাম সেট করা (Navbar-এর মতো)
    const userData = localStorage.getItem('user')
    if (userData) {
        try {
            const user = JSON.parse(userData)
            adminDisplayName.value = user.name
        } catch (e) {
            console.error("User name parse error:", e)
        }
    }

    try {
        const headers = { Authorization: `Bearer ${token}` }

        // ২. Sales Report থেকে Total Sales ক্যালকুলেশন
        const salesRes = await axios.get('http://127.0.0.1:8000/api/sales-report', { headers })
        totalSalesAmount.value = salesRes.data.reduce((acc, curr) => acc + parseFloat(curr.total || 0), 0)

        // ৩. Purchases থেকে Total Purchase ক্যালকুলেশন
        const purchaseRes = await axios.get('http://127.0.0.1:8000/api/admin/purchase', { headers })
        totalPurchaseAmount.value = purchaseRes.data.reduce((acc, curr) => acc + (parseFloat(curr.purchase_price) * parseInt(curr.quantity)), 0)

        // ৪. Vendors Directory থেকে ভেন্ডর সংখ্যা
        const vendorRes = await axios.get('http://127.0.0.1:8000/api/admin/vendors', { headers })
        totalVendorsCount.value = vendorRes.data.filter(user => user.role === 'vendor').length

    } catch (err) {
        console.error("API Fetch Error:", err.response?.data || err)
    }
}

onMounted(fetchDashboardData)

// Profit ক্যালকুলেশন
const revenueAmount = computed(() => {
    return totalSalesAmount.value - totalPurchaseAmount.value
})

// Progress percentage ক্যালকুলেশন
const targetPercentage = computed(() => {
    if (totalSalesAmount.value === 0) return 0
    const percent = (totalSalesAmount.value / targetGoal) * 100
    return percent > 100 ? 100 : Math.round(percent)
})

// নাম্বার ফরম্যাটিং ফাংশন
const formatNumber = (num) => {
    return new Intl.NumberFormat('en-IN').format(num)
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');

.dashboard-wrapper {
    min-height: 100vh;
    font-family: 'Plus Jakarta Sans', sans-serif;
    padding: 20px 0;
}

.container {
    max-width: 1250px;
    margin: 0 auto;
    padding: 0 20px;
}

.page-header { margin-bottom: 30px; }
.title { font-size: 26px; font-weight: 800; color: #2b3674; margin: 0; }
.admin-name { color: #4318FF; text-transform: capitalize; }
.subtitle { color: #a3aed0; font-size: 15px; margin-top: 5px; font-weight: 500; }

/* Stats Cards */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: #fff;
    border-radius: 20px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 15px;
    transition: all 0.3s ease;
    border: 1px solid rgba(0,0,0,0.02);
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0px 20px 40px rgba(112, 144, 176, 0.15);
}

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
}

.stat-info h3 { font-size: 13px; color: #a3aed0; margin: 0; font-weight: 600; text-transform: uppercase; }
.stat-info p { font-size: 18px; font-weight: 800; color: #2b3674; margin: 0; }

.color-1 .stat-icon { background: #e7e9ff; }
.color-2 .stat-icon { background: #e6faf5; }
.color-4 .stat-icon { background: #e2f5fa; }
.color-5 .stat-icon { background: #feebeb; }

/* Main Content Layout */
.main-content-grid {
    display: grid;
    grid-template-columns: 1.8fr 1.2fr;
    gap: 25px;
}

.dashboard-section {
    background: #fff;
    border-radius: 20px;
    padding: 25px;
}

.section-header h3 { font-size: 18px; font-weight: 700; color: #2b3674; margin-bottom: 20px; }

/* Chart Area */
.chart-placeholder {
    background: #f8f9fe;
    height: 220px;
    border-radius: 15px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #a3aed0;
}

.bar-chart-mockup { display: flex; align-items: flex-end; gap: 12px; height: 80px; margin-bottom: 15px; }
.bar { width: 25px; background: #4318FF; border-radius: 4px; opacity: 0.7; }

/* Progress Area */
.progress-container { margin-top: 20px; }
.progress-label { display: flex; justify-content: space-between; font-weight: 700; color: #2b3674; margin-bottom: 12px; }
.progress-bar { background: #eff4fb; height: 12px; border-radius: 10px; width: 100%; overflow: hidden; }
.progress-fill { height: 100%; background: linear-gradient(90deg, #4318FF, #b099ff); transition: width 1s ease-in-out; }
.goal-helper { font-size: 12px; color: #a3aed0; margin-top: 10px; }

/* Responsive Media Queries */
@media (max-width: 1024px) {
    .stats-grid { grid-template-columns: repeat(2, 1fr); }
    .main-content-grid { grid-template-columns: 1fr; }
}

@media (max-width: 600px) {
    .container { padding: 0 15px; }
    .stats-grid { grid-template-columns: 1fr; gap: 15px; }
    .stat-card { padding: 18px; gap: 20px; }
    .stat-info p { font-size: 20px; }
}
</style>