<template>
    <div class="dashboard-wrapper">
        <div class="container">
            <div class="page-header">
                <div class="header-content">
                    <h2 class="title">📊 Welcome back, <span class="vendor-name">{{ vendorName }}!</span></h2>
                    <p class="subtitle"><i class="far fa-calendar-alt"></i> {{ today }}</p>
                </div>
            </div>

            <div class="stats-grid">
                <div class="stat-card color-1">
                    <div class="stat-icon">💰</div>
                    <div class="stat-info">
                        <h3>Sales</h3>
                        <p>৳ {{ formatNumber(totalSales) }}</p>
                    </div>
                </div>

                <div class="stat-card color-2">
                    <div class="stat-icon">📥</div>
                    <div class="stat-info">
                        <h3>Purchase</h3>
                        <p>৳ {{ formatNumber(totalPurchase) }}</p>
                    </div>
                </div>

                <div class="stat-card color-4">
                    <div class="stat-icon">✅</div>
                    <div class="stat-info">
                        <h3>Complete Order</h3>
                        <p>{{ completeOrdersCount }}</p>
                    </div>
                </div>

                <div class="stat-card color-5">
                    <div class="stat-icon">⏳</div>
                    <div class="stat-info">
                        <h3>Pending Order</h3>
                        <p>{{ pendingOrdersCount }}</p>
                    </div>
                </div>
            </div>

            <div class="main-content-grid">
                <section class="dashboard-section chart-box">
                    <div class="section-header">
                        <h3>📈 Monthly Sales Analysis</h3>
                    </div>
                    <div class="chart-placeholder">
                        <div class="bar-chart-mockup">
                            <div class="bar" style="height: 40%"></div>
                            <div class="bar" style="height: 70%"></div>
                            <div class="bar" style="height: 50%"></div>
                            <div class="bar" style="height: 90%"></div>
                            <div class="bar" style="height: 60%"></div>
                        </div>
                        <p>Real-time data visualization coming soon</p>
                    </div>
                </section>

                <section class="dashboard-section progress-box">
                    <div class="section-header">
                        <h3>🎯 Sales Goal</h3>
                    </div>
                    <div class="progress-container">
                        <p class="progress-label">Based on Delivered Sales <span>{{ goalPercentage }}%</span></p>
                        <div class="progress-bar">
                            <div class="progress-fill" :style="{ width: goalPercentage + '%' }"></div>
                        </div>
                        <p class="goal-helper">Target: ৳ {{ formatNumber(targetAmount) }}</p>
                    </div>
                </section>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'

const today = new Date().toLocaleDateString(undefined, {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric'
})

const orders = ref([])
const purchases = ref([])
const targetAmount = ref(100000)
const vendorName = ref('Vendor') // ডিফল্ট নাম
const token = localStorage.getItem('token') || localStorage.getItem('vendortoken')

const formatNumber = (num) => {
    return new Intl.NumberFormat('en-IN').format(num)
}

const fetchOrders = async () => {
    try {
        const response = await axios.get("http://127.0.0.1:8000/api/vendor/orders", {
            headers: { Authorization: `Bearer ${token}` }
        });
        orders.value = Array.isArray(response.data) ? response.data : [];
    } catch (error) {
        console.error("Orders API Error:", error);
    }
}

const fetchPurchases = async () => {
    try {
        const res = await axios.get('http://127.0.0.1:8000/api/vendor/purchases', {
            headers: { Authorization: `Bearer ${token}` }
        })
        purchases.value = Array.isArray(res.data) ? res.data : [];
    } catch (err) {
        console.error("Purchases API Error:", err);
    }
}

const totalSales = computed(() => {
    return orders.value
        .filter(order => order.status?.toLowerCase() === 'delivered')
        .reduce((sum, order) => sum + parseFloat(order.price || 0), 0)
})

const totalPurchase = computed(() => {
    return purchases.value.reduce((sum, item) => sum + (parseFloat(item.quantity || 0) * parseFloat(item.price || 0)), 0)
})

const completeOrdersCount = computed(() => {
    return orders.value.filter(o => o.status?.toLowerCase() === 'delivered').length
})

const pendingOrdersCount = computed(() => {
    return orders.value.filter(o => o.status?.toLowerCase() === 'pending').length
})

const goalPercentage = computed(() => {
    if (targetAmount.value <= 0) return 0;
    const percent = (totalSales.value / targetAmount.value) * 100;
    return percent > 100 ? 100 : percent.toFixed(1);
})

onMounted(() => {
    // Navbar এর মতো লজিক এখানেও ব্যবহার করা হয়েছে
    const userData = localStorage.getItem('user')
    if (userData) {
        try {
            const user = JSON.parse(userData)
            vendorName.value = user.name
        } catch (e) {
            console.error("User data parse error in dashboard", e)
        }
    }
    
    fetchOrders()
    fetchPurchases()
})
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
.vendor-name { color: #4318FF; }
.subtitle { color: #a3aed0; font-size: 15px; margin-top: 5px; font-weight: 500; }

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

.progress-container { margin-top: 20px; }
.progress-label { display: flex; justify-content: space-between; font-weight: 700; color: #2b3674; margin-bottom: 12px; }
.progress-bar { background: #eff4fb; height: 12px; border-radius: 10px; width: 100%; overflow: hidden; }
.progress-fill { height: 100%; background: linear-gradient(90deg, #4318FF, #b099ff); transition: width 1s ease-in-out; }
.goal-helper { font-size: 12px; color: #a3aed0; margin-top: 10px; }

@media (max-width: 1024px) {
    .stats-grid { grid-template-columns: repeat(2, 1fr); }
    .main-content-grid { grid-template-columns: 1fr; }
}

@media (max-width: 600px) {
    .container { padding: 0 15px; }
    .stats-grid { 
        grid-template-columns: 1fr; 
        gap: 15px; 
    }
    .stat-card {
        padding: 18px;
        flex-direction: row; 
        justify-content: flex-start;
        gap: 20px;
    }
    .stat-icon {
        width: 50px;
        height: 50px;
        font-size: 22px;
    }
    .stat-info p {
        font-size: 20px;
    }
}
</style>