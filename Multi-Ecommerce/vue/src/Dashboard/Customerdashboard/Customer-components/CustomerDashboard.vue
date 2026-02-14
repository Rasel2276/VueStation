<template>
  <div class="dashboard-wrapper">
    <div class="container">
      <div class="page-header">
        <div class="header-content">
          <h2 class="title">Welcome back, <span class="user-name">{{ customerName }}!</span></h2>
          <p class="subtitle"><i class="far fa-calendar-alt"></i> {{ today }}</p>
        </div>
      </div>

      <div class="stats-grid">
        <div class="stat-card blue">
          <div class="stat-icon"><i class="fas fa-box"></i></div>
          <div class="stat-info"><h3>Total Orders</h3><p>24</p></div>
        </div>
        <div class="stat-card orange">
          <div class="stat-icon"><i class="fas fa-clock"></i></div>
          <div class="stat-info"><h3>Pending</h3><p>03</p></div>
        </div>
        <div class="stat-card green">
          <div class="stat-icon"><i class="fas fa-wallet"></i></div>
          <div class="stat-info"><h3>Spent</h3><p>৳ 12,540</p></div>
        </div>
        <div class="stat-card purple">
          <div class="stat-icon"><i class="fas fa-star"></i></div>
          <div class="stat-info"><h3>Points</h3><p>450</p></div>
        </div>
      </div>

      <div class="tracking-wrapper" :class="{ 'expanded': showResult }">
        <div class="tracking-hero">
          <div class="content-limit">
            <h1 class="premium-title">Track Order</h1>
            <p class="premium-subtitle">Enter details for instant updates</p>
            
            <div class="search-glass-container">
              <form @submit.prevent="handleTracking" class="premium-form">
                <div class="premium-input">
                  <span class="input-icon">#</span>
                  <input type="text" v-model="searchId" placeholder="Order ID" required />
                </div>
                <div class="premium-input">
                  <span class="input-icon">📞</span>
                  <input type="text" v-model="searchPhone" placeholder="Phone Number" required />
                </div>
                <button type="submit" class="btn-glow" :disabled="loading">
                  <span v-if="!loading">Track Now</span>
                  <span v-else><i class="fas fa-spinner fa-spin"></i></span>
                </button>
              </form>
            </div>
          </div>
        </div>

        <div class="container-horizontal">
          <transition name="expand-fade">
            <div v-if="showResult" class="premium-result-card">
              <div class="result-inner">
                <div class="meta-row">
                  <div class="meta-unit">
                    <label>Order ID</label>
                    <div class="val">#{{ orderData?.order_id }}</div>
                  </div>
                  <div class="meta-unit status-unit">
                    <label>Status</label>
                    <div class="val status-pill">{{ orderData?.status }}</div>
                  </div>
                  <div class="meta-unit date-unit">
                    <label>Update Date</label>
                    <div class="val">{{ formatDate(orderData?.updated_at) }}</div>
                  </div>
                </div>

                <div class="stepper-container">
                  <div class="stepper-line">
                    <div class="fill-line" :style="{ [isMobile ? 'height' : 'width']: progressWidth, backgroundColor: isCancelled ? '#ef4444' : '#4318FF' }"></div>
                  </div>
                  <div class="stepper-points">
                    <div class="s-point" :class="getStepClass(1)">
                      <div class="s-dot">{{ currentStep >= 1 ? '✓' : '1' }}</div>
                      <div class="s-text">
                        <span class="label">{{ isCancelled ? 'Cancelled' : 'Pending' }}</span>
                        <span class="desc" v-if="currentStep >= 1">Order Received</span>
                      </div>
                    </div>
                    <div class="s-point" :class="getStepClass(2)">
                      <div class="s-dot">{{ currentStep >= 2 ? '✓' : '2' }}</div>
                      <div class="s-text">
                        <span class="label">Confirmed</span>
                        <span class="desc" v-if="currentStep >= 2">Order Verified</span>
                      </div>
                    </div>
                    <div class="s-point" :class="getStepClass(3)">
                      <div class="s-dot">{{ currentStep >= 3 ? '✓' : '3' }}</div>
                      <div class="s-text">
                        <span class="label">Shipped</span>
                        <span class="desc" v-if="currentStep >= 3">On the way</span>
                      </div>
                    </div>
                    <div class="s-point" :class="getStepClass(4)">
                      <div class="s-dot">{{ currentStep >= 4 ? '✓' : '4' }}</div>
                      <div class="s-text">
                        <span class="label">Delivered</span>
                        <span class="desc" v-if="currentStep >= 4">Handed over</span>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="summary-bar-simple">
                  <div class="p-info-simple">
                    <i class="fas fa-shopping-bag"></i>
                    <div>
                      <h4>{{ orderData?.product_name }}</h4>
                      <p>Qty: {{ orderData?.qty }} | {{ orderData?.price }} BDT</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </transition>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import api, { BASE_URL } from '../../../axios';

const today = new Date().toLocaleDateString(undefined, {
  weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
})

const searchId = ref('')
const searchPhone = ref('')
const showResult = ref(false)
const orderData = ref(null)
const loading = ref(false)
const windowWidth = ref(window.innerWidth)
const customerName = ref('Customer') // ডিফল্ট নাম

const updateWidth = () => windowWidth.value = window.innerWidth

onMounted(() => {
  window.addEventListener('resize', updateWidth)
  
  // লকাল স্টোরেজ থেকে কাস্টমারের নাম নেওয়া (আপনার Navbar এর মতো)
  const userData = localStorage.getItem('user')
  if (userData) {
    try {
      const user = JSON.parse(userData)
      customerName.value = user.name
    } catch (e) {
      console.error("User data parse error", e)
    }
  }
})

onUnmounted(() => window.removeEventListener('resize', updateWidth))

const isMobile = computed(() => windowWidth.value <= 768)

const statusSteps = { 'Pending': 1, 'Confirmed': 2, 'Shipped': 3, 'Delivered': 4, 'Cancelled': 1 }
const isCancelled = computed(() => orderData.value?.status === 'Cancelled')
const currentStep = computed(() => orderData.value ? (statusSteps[orderData.value.status] || 1) : 1)

const progressWidth = computed(() => {
  if (isCancelled.value || currentStep.value <= 1) return '0%'
  const percentages = { 2: '33%', 3: '66%', 4: '100%' }
  return percentages[currentStep.value] || '0%'
})

const getStepClass = (step) => {
  if (isCancelled.value && step === 1) return 'done cancelled-step'
  if (currentStep.value > step) return 'done'
  if (currentStep.value === step) return 'active'
  return ''
}

const formatDate = (dateString) => {
  if (!dateString) return ''
  return new Date(dateString).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })
}

const handleTracking = async () => {
  loading.value = true
  try {
    const response = await api.get('track-order', {
      params: { order_id: searchId.value, phone: searchPhone.value }
    })
    if (response.data.success) {
      orderData.value = response.data.order
      showResult.value = true
    }
  } catch (error) {
    alert(error.response?.data?.message || "Order not found!")
    showResult.value = false
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
/* স্টাইল একদম অপরিবর্তিত রাখা হয়েছে */
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;700;800&display=swap');

.dashboard-wrapper { width: 100%; min-height: 100vh; background-color: #f8f9fe; font-family: 'Plus Jakarta Sans', sans-serif; overflow-x: hidden; }
.container { max-width: 1200px; margin: 0 auto; padding: 20px; box-sizing: border-box; }

.page-header { margin-bottom: 25px; }
.title { font-size: 24px; font-weight: 800; color: #2b3674; margin: 0; }
.user-name { color: #4318FF; }
.subtitle { color: #a3aed0; font-size: 14px; margin-top: 5px; }

.stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 25px; }
.stat-card { background: white; padding: 18px; border-radius: 20px; display: flex; align-items: center; gap: 15px; border: 1px solid #f1f1f1; }
.stat-icon { width: 42px; height: 42px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0; }
.blue .stat-icon { background: #E7E9FF; color: #4318FF; }
.orange .stat-icon { background: #FFF5E9; color: #FFB547; }
.green .stat-icon { background: #E6FAF5; color: #05CD99; }
.purple .stat-icon { background: #F4EFFF; color: #6932F9; }
.stat-info h3 { font-size: 11px; color: #a3aed0; margin: 0; text-transform: uppercase; }
.stat-info p { font-size: 16px; color: #2b3674; font-weight: 800; margin: 0; }

.tracking-hero { 
  background: linear-gradient(135deg, #2b3674 0%, #4318FF 100%); 
  padding: 35px 20px 60px;
  text-align: center; 
  color: white; 
  border-radius: 24px; 
}
.premium-title { font-size: 1.6rem; font-weight: 800; margin-bottom: 4px; }
.premium-subtitle { opacity: 0.8; font-size: 13px; margin-bottom: 20px; }
.search-glass-container { background: rgba(255, 255, 255, 0.15); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); padding: 12px; border-radius: 16px; max-width: 850px; margin: 0 auto; }
.premium-form { display: flex; gap: 10px; }
.premium-input { flex: 1; position: relative; display: flex; align-items: center; }
.input-icon { position: absolute; left: 15px; color: #4318FF; font-size: 14px; }
.premium-input input { width: 100%; padding: 12px 12px 12px 42px; background: white; border: none; border-radius: 10px; font-size: 14px; color: #2b3674; outline: none; }

.btn-glow { 
  background: #ffffff; 
  color: #4318FF; 
  border: none; 
  padding: 0 30px; 
  border-radius: 10px; 
  font-weight: 800; 
  cursor: pointer; 
  font-size: 14px; 
  transition: all 0.3s ease;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}
.btn-glow:hover { background: #f8f9fe; transform: translateY(-2px); }

.container-horizontal { max-width: 850px; margin: -35px auto 40px; padding: 0 20px; box-sizing: border-box; }
.premium-result-card { background: white; border-radius: 20px; box-shadow: 0 15px 35px rgba(0,0,0,0.06); border: 1px solid #f1f5f9; }
.result-inner { padding: 25px; }

.meta-row { display: flex; justify-content: space-between; border-bottom: 1px solid #f1f5f9; padding-bottom: 15px; margin-bottom: 25px; }
.meta-unit label { font-size: 10px; color: #a3aed0; text-transform: uppercase; font-weight: 700; display: block; }
.meta-unit .val { font-weight: 700; color: #2b3674; font-size: 14px; margin-top: 4px; }
.status-pill { color: #4318FF !important; }

.stepper-container { position: relative; margin: 40px 0; }
.stepper-line { position: absolute; top: 18px; left: 0; width: 100%; height: 4px; background: #f4f7fe; z-index: 1; border-radius: 10px; }
.fill-line { height: 100%; transition: all 0.8s ease; border-radius: 10px; }
.stepper-points { display: flex; justify-content: space-between; position: relative; z-index: 2; }
.s-point { display: flex; flex-direction: column; align-items: center; text-align: center; }
.s-dot { width: 36px; height: 36px; background: white; border: 4px solid #f4f7fe; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 13px; color: #a3aed0; transition: 0.3s; }
.s-text { margin-top: 10px; }
.s-text .label { font-size: 12px; font-weight: 700; color: #a3aed0; display: block; }
.s-text .desc { font-size: 10px; color: #cbd5e0; }

.s-point.done .s-dot { background: #4318FF; border-color: #4318FF; color: white; }
.s-point.done .label { color: #2b3674; }
.s-point.active .s-dot { border-color: #4318FF; color: #4318FF; }
.s-point.active .label { color: #4318FF; }

.summary-bar-simple { background: #f4f7fe; padding: 15px; border-radius: 12px; border-left: 4px solid #4318FF; }
.p-info-simple { display: flex; align-items: center; gap: 15px; }
.p-info-simple i { color: #4318FF; font-size: 18px; }
.p-info-simple h4 { margin: 0; font-size: 14px; color: #2b3674; }
.p-info-simple p { margin: 2px 0 0; font-size: 12px; color: #a3aed0; }

@media (max-width: 768px) {
  .stats-grid { grid-template-columns: 1fr 1fr; gap: 12px; }
  .stat-card { padding: 20px 10px; flex-direction: column; text-align: center; }
  .premium-form { flex-direction: column; gap: 15px; }
  .btn-glow { 
    padding: 15px; 
    width: 100%; 
    background: #4318FF; 
    color: #ffffff;
    font-size: 15px;
    letter-spacing: 0.5px;
    box-shadow: 0 8px 20px rgba(67, 24, 255, 0.3);
  }
  .container-horizontal { padding: 0 10px; margin-top: -30px; }
  .meta-row { flex-wrap: wrap; gap: 15px; }
  .meta-unit { flex: 1 1 40%; }
  .stepper-container { padding-left: 20px; }
  .stepper-line { left: 38px; top: 0; width: 4px; height: 100%; }
  .stepper-points { flex-direction: column; gap: 30px; align-items: flex-start; }
  .s-point { flex-direction: row; text-align: left; gap: 15px; }
  .s-dot { width: 32px; height: 32px; flex-shrink: 0; }
  .s-text { margin-top: 0; }
}

@media (max-width: 480px) {
  .meta-unit.date-unit { flex: 1 1 100%; border-top: 1px dashed #f1f5f9; padding-top: 10px; }
}
</style>