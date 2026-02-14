<template>
  <div class="tracking-wrapper" :class="{ 'expanded': showResult }">
    <div class="tracking-hero">
      <div class="content-limit">
        <h1 class="premium-title">Track Order</h1>
        <p class="premium-subtitle">Enter your details for instant shipment updates</p>
        
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
              <span>{{ loading ? 'Tracking...' : 'Track Now' }}</span>
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
              <div class="meta-unit text-center-desktop">
                <label>Status</label>
                <div class="val status-pill">{{ orderData?.status }}</div>
              </div>
              <div class="meta-unit text-right-desktop">
                <label>Update Date</label>
                <div class="val">{{ formatDate(orderData?.updated_at) }}</div>
              </div>
            </div>

            <div class="stepper-container">
              <div class="stepper-line">
                <div class="fill-line" :style="{ width: progressWidth, backgroundColor: isCancelled ? '#ef4444' : '#003366' }"></div>
              </div>
              <div class="stepper-points">
                <div class="s-point" :class="getStepClass(1)">
                  <div class="s-dot">{{ currentStep >= 1 ? '✓' : '1' }}</div>
                  <span>{{ isCancelled ? 'Cancelled' : 'Pending' }}</span>
                </div>
                <div class="s-point" :class="getStepClass(2)">
                  <div class="s-dot">{{ currentStep >= 2 ? '✓' : '2' }}</div>
                  <span>Confirmed</span>
                </div>
                <div class="s-point" :class="getStepClass(3)">
                  <div class="s-dot">{{ currentStep >= 3 ? '✓' : '3' }}</div>
                  <span>Shipped</span>
                </div>
                <div class="s-point" :class="getStepClass(4)">
                  <div class="s-dot">{{ currentStep >= 4 ? '✓' : '4' }}</div>
                  <span>Delivered</span>
                </div>
              </div>
            </div>

            <div class="summary-bar">
              <div class="p-thumb">
                <img v-if="orderData?.image" :src="'/storage/' + orderData.image" style="width: 100%; border-radius: 8px;" />
                <span v-else>📦</span>
              </div>
              <div class="p-info">
                <h4>{{ orderData?.product_name }}</h4>
                <p>Qty: {{ orderData?.qty }} | {{ orderData?.price }} BDT</p>
              </div>
            </div>
          </div>
        </div>
      </transition>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import axios from 'axios'
import api, { BASE_URL } from '../../axios';

const searchId = ref('')
const searchPhone = ref('')
const showResult = ref(false)
const orderData = ref(null)
const loading = ref(false)

const statusSteps = {
  'Pending': 1,
  'Confirmed': 2,
  'Shipped': 3,
  'Delivered': 4,
  'Cancelled': 1
}

const isCancelled = computed(() => orderData.value?.status === 'Cancelled')

const currentStep = computed(() => {
  return orderData.value ? (statusSteps[orderData.value.status] || 1) : 1
})

const progressWidth = computed(() => {
  if (isCancelled.value) return '0%'
  if (currentStep.value <= 1) return '0%'
  if (currentStep.value === 2) return '33%'
  if (currentStep.value === 3) return '66%'
  if (currentStep.value >= 4) return '100%'
  return '0%'
})

const getStepClass = (step) => {
  if (isCancelled.value && step === 1) return 'done cancelled-step'
  if (currentStep.value > step) return 'done'
  if (currentStep.value === step) return 'active'
  return ''
}

const formatDate = (dateString) => {
  if (!dateString) return ''
  const date = new Date(dateString)
  return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })
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
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;700;800&display=swap');

.tracking-wrapper { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; min-height: auto; transition: all 0.5s ease; }
.tracking-hero { background: linear-gradient(135deg, #001f3f 0%, #003366 100%); padding: 60px 20px 80px; text-align: center; color: white; }
.premium-title { font-size: 2rem; font-weight: 800; margin-bottom: 8px; letter-spacing: -0.5px; }
.premium-subtitle { opacity: 0.7; font-size: 14px; margin-bottom: 30px; }
.content-limit { max-width: 900px; margin: 0 auto; }
.search-glass-container { background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.2); padding: 15px; border-radius: 16px; box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2); }
.premium-form { display: flex; gap: 12px; }
.premium-input { flex: 1; position: relative; display: flex; align-items: center; }
.input-icon { position: absolute; left: 15px; color: #94a3b8; }
.premium-input input { width: 100%; padding: 14px 14px 14px 45px; background: white; border: none; border-radius: 10px; font-size: 15px; color: #1e293b; outline: none; }
.btn-glow { background: #00d2ff; background: linear-gradient(to right, #00d2ff, #3a7bd5); color: white; border: none; padding: 0 30px; border-radius: 10px; font-weight: 700; cursor: pointer; transition: all 0.3s ease; }
.btn-glow:disabled { opacity: 0.7; cursor: not-allowed; }
.btn-glow:hover:not(:disabled) { box-shadow: 0 0 15px rgba(0, 210, 255, 0.5); transform: scale(1.02); }
.container-horizontal { max-width: 900px; margin: -40px auto 40px; padding: 0 20px; }
.premium-result-card { background: white; border-radius: 20px; box-shadow: 0 20px 40px rgba(0,0,0,0.06); border: 1px solid #f1f5f9; overflow: hidden; }
.result-inner { padding: 30px; }

/* Meta Row Styling */
.meta-row { display: flex; justify-content: space-between; border-bottom: 1px solid #f1f5f9; padding-bottom: 20px; margin-bottom: 30px; }
.meta-unit label { font-size: 11px; color: #94a3b8; text-transform: uppercase; font-weight: 700; }
.meta-unit .val { font-weight: 700; color: #0f172a; }
.text-center-desktop { text-align: center; }
.text-right-desktop { text-align: right; }

.status-pill { color: #003366 !important; text-transform: capitalize; }
.stepper-container { position: relative; margin: 40px 0; padding: 0 10px; }
.stepper-line { position: absolute; top: 18px; left: 0; width: 100%; height: 4px; background: #f1f5f9; z-index: 1; }
.fill-line { height: 100%; background: #003366; border-radius: 10px; transition: width 0.8s ease; }
.stepper-points { display: flex; justify-content: space-between; position: relative; z-index: 2; }
.s-point { text-align: center; }
.s-dot { width: 36px; height: 36px; background: white; border: 4px solid #f1f5f9; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 8px; font-weight: 700; font-size: 13px; color: #94a3b8; }
.s-point span { font-size: 12px; font-weight: 600; color: #64748b; }
.s-point.done .s-dot { background: #003366; border-color: #003366; color: white; }
.s-point.active .s-dot { border-color: #003366; color: #003366; box-shadow: 0 0 15px rgba(0, 51, 102, 0.15); }
.s-point.active span { color: #003366; }
.s-point.cancelled-step .s-dot { background: #ef4444 !important; border-color: #ef4444 !important; color: white !important; }
.s-point.cancelled-step span { color: #ef4444 !important; }

.summary-bar { background: #f8fafc; padding: 15px 20px; border-radius: 12px; display: flex; align-items: center; gap: 15px; }
.p-thumb { font-size: 20px; background: white; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; border-radius: 8px; overflow: hidden; }
.p-info h4 { margin: 0; font-size: 14px; color: #1e293b; }
.p-info p { margin: 2px 0 0; font-size: 12px; color: #64748b; }
.expand-fade-enter-active { transition: all 0.4s ease-out; }
.expand-fade-enter-from { opacity: 0; transform: translateY(-20px); }

/* MOBILE RESPONSIVE UPDATES */
@media (max-width: 600px) {
  .meta-row { 
    flex-direction: column; /* একটার নিচে একটা আসবে */
    gap: 15px; /* মাঝখানে গ্যাপ থাকবে */
    text-align: left; /* বাম পাশে এলাইনমেন্ট */
  }
  .text-center-desktop, .text-right-desktop {
    text-align: left; /* মোবাইলে সবাই বামে থাকবে */
  }
}

@media (max-width: 768px) {
  .premium-form { flex-direction: column; }
  .btn-glow { padding: 12px; }
}
</style>