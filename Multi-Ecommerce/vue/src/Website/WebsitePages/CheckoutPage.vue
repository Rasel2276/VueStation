<template>
  <div class="checkout-wrapper">
    <div class="container">
      <div v-if="cartItems.length > 0" class="wide-checkout-card">
        
        <div class="cart-column">
          <div class="column-header">
            <h3>
              <i class="fa-solid fa-cart-shopping"></i> 
              Order Items
            </h3>
            <span class="count-badge">{{ totalItems }} Units</span>
          </div>

          <div class="scroll-area">
            <div v-for="(item, index) in cartItems" :key="item.id" class="p-card">
              <div class="p-thumb">
                <img :src="getImageUrl(item.image)" :alt="item.name" />
              </div>
              <div class="p-info">
                <div class="p-main">
                  <h4 class="p-name">{{ item.name }}</h4>
                  <button @click="removeItem(index)" class="trash-btn">
                    <i class="fa-regular fa-trash-can"></i>
                  </button>
                </div>
                <div class="p-actions">
                  <div class="price-tag">৳{{ item.price }}</div>
                  <div class="stepper-modern">
                    <button @click="updateQty(index, -1)">-</button>
                    <span>{{ item.qty }}</span>
                    <button @click="updateQty(index, 1)">+</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="form-column">
          <div class="column-header">
            <h3>
              <i class="fa-solid fa-truck-fast"></i> 
              Delivery Address
            </h3>
          </div>

          <div class="modern-input-grid">
            <div class="field-group full">
              <label>Full Name</label>
              <div class="input-with-icon">
                <i class="fa-regular fa-user"></i>
                <input type="text" v-model="form.name" placeholder="Enter receiver name" />
              </div>
            </div>

            <div class="field-group full">
              <label>Phone Number</label>
              <div class="input-with-icon">
                <i class="fa-solid fa-phone-flip"></i>
                <input type="tel" v-model="form.phone" placeholder="01XXXXXXXXX" />
              </div>
            </div>

            <div class="field-group half">
              <label>Thana / Upazila</label>
              <div class="input-with-icon">
                <i class="fa-solid fa-city"></i>
                <input type="text" v-model="form.thana" placeholder="e.g. Dhanmondi" />
              </div>
            </div>

            <div class="field-group half">
              <label>Area / Village</label>
              <div class="input-with-icon">
                <i class="fa-solid fa-map-pin"></i>
                <input type="text" v-model="form.area" placeholder="e.g. Sector 4" />
              </div>
            </div>

            <div class="field-group full">
              <label>Home / Road / Apartment</label>
              <div class="input-with-icon">
                <i class="fa-solid fa-house-chimney"></i>
                <input type="text" v-model="form.homeInfo" placeholder="House #12, Road #5, Flat 2B" />
              </div>
            </div>
          </div>
        </div>

        <div class="payment-column">
          <div class="summary-content">
            <h3 class="side-title">Checkout Summary</h3>

            <div class="cost-summary-card">
              <div class="cost-row">
                <span>Subtotal</span>
                <span>৳{{ subtotal }}</span>
              </div>
              <div class="cost-row">
                <span>Shipping Fee</span>
                <span class="free-text" v-if="shipping === 0">Free</span>
                <span v-else>+ ৳{{ shipping }}</span>
              </div>
              <div class="line-divider"></div>
              <div class="cost-row grand-total">
                <span>Payable Amount</span>
                <span>৳{{ grandTotal }}</span>
              </div>
            </div>

            <div class="payment-select">
              <p class="section-hint">Select Payment Method</p>
              <div class="method-stack">
                <label :class="{ active: form.payment === 'cod' }">
                  <input type="radio" v-model="form.payment" value="cod" />
                  <div class="method-info">
                    <i class="fa-solid fa-hand-holding-dollar"></i>
                    <span>Cash on Delivery</span>
                  </div>
                  <i v-if="form.payment === 'cod'" class="fa-solid fa-circle-check"></i>
                </label>

                <label :class="{ active: form.payment === 'online' }">
                  <input type="radio" v-model="form.payment" value="online" />
                  <div class="method-info">
                    <i class="fa-solid fa-bolt-lightning"></i>
                    <span>Digital Payment</span>
                  </div>
                  <i v-if="form.payment === 'online'" class="fa-solid fa-circle-check"></i>
                </label>
              </div>
            </div>

            <button class="confirm-order-btn" @click="handleCheckout" :disabled="loading">
              {{ loading ? 'Processing...' : 'Place Order Now ৳' + grandTotal }}
            </button>

            <p class="trust-note">
              <i class="fa-solid fa-lock"></i> 
              SSL Secure Payment System
            </p>
          </div>
        </div>
      </div>

      <div v-else class="empty-ui">
        <i class="fa-solid fa-cart-flatbed"></i>
        <h2>No items in your bag</h2>
        <button class="back-shop-btn" @click="goShopping">
          Continue Shopping
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, reactive, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';
import api, { BASE_URL } from '../../axios';

const router = useRouter();
const cartItems = ref([]);
const loading = ref(false);

const form = reactive({ 
  name: "", 
  phone: "", 
  thana: "", 
  area: "", 
  homeInfo: "", 
  payment: "cod" 
});

const shipping = ref(100); 

const getImageUrl = (img) => {
  return img ? `${BASE_URL}/ui_product_images/${img}` : '/assets/no-image.jpg';
};

const loadCart = () => {
  const saved = localStorage.getItem('shopping_cart');
  if (saved) {
    cartItems.value = JSON.parse(saved);
  }
};

const saveCart = () => {
  localStorage.setItem('shopping_cart', JSON.stringify(cartItems.value));
  window.dispatchEvent(new CustomEvent('cart-updated'));
};

onMounted(() => {
  loadCart();
});

const subtotal = computed(() => {
  return cartItems.value.reduce((acc, item) => acc + (item.price * item.qty), 0);
});

const totalItems = computed(() => {
  return cartItems.value.reduce((acc, item) => acc + item.qty, 0);
});

const grandTotal = computed(() => {
  return subtotal.value + shipping.value;
});

const updateQty = (index, val) => {
  const newQty = cartItems.value[index].qty + val;
  if (newQty >= 1) {
    cartItems.value[index].qty = newQty;
    saveCart();
  }
};

const removeItem = (index) => {
  if(confirm("Remove this item from bag?")) {
    cartItems.value.splice(index, 1);
    saveCart();
  }
};

// logic updated to match your exact controller requirements
const handleCheckout = async () => {
  if(!form.name || !form.phone || !form.thana || !form.area || !form.homeInfo) {
    return alert("Please provide full delivery information!");
  }

  loading.value = true;
  
  try {
    const token = localStorage.getItem('token');
    
    const orderData = {
      customer_name: form.name,
      phone: form.phone,
      thana: form.thana,
      area: form.area,
      address: form.homeInfo,
      payment_method: form.payment === 'cod' ? 'Cash On Delivery' : 'Online Payment',
      cartItems: cartItems.value 
    };

    await api.post("/customer/place-order", orderData, {
      headers: { Authorization: `Bearer ${token}` }
    });

    alert("Order Placed Successfully!");
    
    cartItems.value = [];
    saveCart();
    router.push('/');
    
  } catch (error) {
    console.error("Order error:", error);
    if (error.response && error.response.status === 422) {
        alert("Validation Error: Please check all fields.");
    } else {
        alert(error.response?.data?.message || "Something went wrong!");
    }
  } finally {
    loading.value = false;
  }
};

const goShopping = () => router.push('/'); 
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
.checkout-wrapper { background-color: #f4f7fa; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; font-family: 'Plus Jakarta Sans', sans-serif; }
.container { width: 100%; max-width: 1200px; }
.wide-checkout-card { display: grid; grid-template-columns: 1fr 1.2fr 1fr; background: #ffffff; border-radius: 40px; box-shadow: 0 40px 100px rgba(0,0,0,0.05); overflow: hidden; min-height: 600px; }
.cart-column, .form-column, .payment-column { padding: 45px 35px; }
.column-header { margin-bottom: 30px; display: flex; align-items: center; justify-content: space-between; }
.column-header h3 { font-size: 19px; font-weight: 800; color: #1e293b; display: flex; align-items: center; gap: 12px; }
.column-header h3 i { color: #e4002b; }
.count-badge { background: #fef2f2; color: #e4002b; padding: 4px 12px; border-radius: 30px; font-size: 12px; font-weight: 700; }
.scroll-area { max-height: 420px; overflow-y: auto; padding-right: 10px; }
.p-card { background: #f8fafc; border-radius: 24px; padding: 18px; display: flex; gap: 15px; margin-bottom: 15px; border: 1px solid #f1f5f9; transition: 0.3s; }
.p-card:hover { border-color: #e2e8f0; transform: scale(1.02); }
.p-thumb { width: 75px; height: 75px; background: #fff; border-radius: 15px; padding: 5px; flex-shrink: 0; }
.p-thumb img { width: 100%; height: 100%; object-fit: contain; }
.p-info { flex: 1; display: flex; flex-direction: column; justify-content: space-between; }
.p-main { display: flex; justify-content: space-between; align-items: flex-start; }
.p-name { font-size: 13px; font-weight: 700; color: #334155; max-width: 140px; line-height: 1.4; }
.trash-btn { border: none; background: none; color: #94a3b8; cursor: pointer; transition: 0.2s; }
.trash-btn:hover { color: #ef4444; }
.p-actions { display: flex; justify-content: space-between; align-items: center; margin-top: 10px; }
.price-tag { font-weight: 800; color: #e4002b; font-size: 15px; }
.stepper-modern { display: flex; align-items: center; gap: 12px; background: #fff; padding: 4px 10px; border-radius: 12px; border: 1px solid #e2e8f0; }
.stepper-modern button { border: none; background: none; font-weight: bold; color: #64748b; cursor: pointer; font-size: 16px; }
.stepper-modern span { font-size: 13px; font-weight: 800; color: #1e293b; min-width: 15px; text-align: center; }
.form-column { border-left: 1px solid #f1f5f9; border-right: 1px solid #f1f5f9; }
.modern-input-grid { display: flex; flex-wrap: wrap; gap: 18px; }
.field-group { display: flex; flex-direction: column; gap: 8px; }
.field-group.full { width: 100%; }
.field-group.half { width: calc(50% - 9px); }
.field-group label { font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; margin-left: 5px; }
.input-with-icon { display: flex; align-items: center; background: #f8fafc; border: 1.5px solid #edf2f7; border-radius: 16px; padding: 0 15px; transition: 0.3s; }
.input-with-icon i { color: #cbd5e1; font-size: 14px; }
.input-with-icon input { border: none; background: none; width: 100%; padding: 14px 12px; font-family: inherit; font-size: 14px; color: #1e293b; font-weight: 600; }
.input-with-icon input:focus { outline: none; }
.input-with-icon:focus-within { border-color: #e4002b; background: #fff; box-shadow: 0 10px 25px rgba(228,0,43,0.04); }
.input-with-icon:focus-within i { color: #e4002b; }
.side-title { font-size: 22px; font-weight: 800; color: #0f172a; margin-bottom: 25px; }
.cost-summary-card { background: #0f172a; color: #fff; padding: 25px; border-radius: 28px; margin-bottom: 25px; }
.cost-row { display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 14px; font-weight: 500; opacity: 0.8; }
.free-text { color: #2ecc71; font-weight: 700; }
.line-divider { height: 1px; background: rgba(255,255,255,0.1); margin: 15px 0; }
.grand-total { font-size: 20px; font-weight: 800; opacity: 1; }
.grand-total span:last-child { color: #e4002b; }
.section-hint { font-size: 13px; font-weight: 700; color: #64748b; margin-bottom: 15px; }
.method-stack { display: flex; flex-direction: column; gap: 10px; margin-bottom: 30px; }
.method-stack label { display: flex; align-items: center; justify-content: space-between; padding: 16px 20px; background: #fff; border: 2px solid #f1f5f9; border-radius: 20px; cursor: pointer; transition: 0.3s; }
.method-info { display: flex; align-items: center; gap: 12px; }
.method-info i { font-size: 18px; color: #94a3b8; }
.method-info span { font-size: 14px; font-weight: 700; color: #475569; }
.method-stack input { display: none; }
.method-stack label.active { border-color: #e4002b; background: #fff5f5; }
.method-stack label.active .method-info i, .method-stack label.active .method-info span { color: #e4002b; }
.method-stack label.active > i { color: #e4002b; }
.confirm-order-btn { width: 100%; padding: 20px; border: none; background: #e4002b; color: #fff; border-radius: 20px; font-weight: 800; font-size: 16px; cursor: pointer; transition: 0.3s; box-shadow: 0 10px 30px rgba(228,0,43,0.2); }
.confirm-order-btn:hover { background: #c50025; transform: translateY(-3px); }
.trust-note { text-align: center; font-size: 11px; color: #cbd5e1; margin-top: 15px; font-weight: 600; }
.empty-ui { text-align: center; padding: 100px; background: #fff; border-radius: 40px; }
.empty-ui i { font-size: 80px; color: #f1f5f9; margin-bottom: 25px; display: block; }
.back-shop-btn { padding: 15px 40px; background: #0f172a; color: #fff; border: none; border-radius: 18px; font-weight: 700; cursor: pointer; }
@media (max-width: 1050px) { .wide-checkout-card { grid-template-columns: 1fr; border-radius: 30px; } .form-column { border: none; border-top: 1px solid #f1f5f9; border-bottom: 1px solid #f1f5f9; } }
@media (max-width: 768px) { .checkout-wrapper { padding: 10px; } .cart-column, .form-column, .payment-column { padding: 30px 15px; } .wide-checkout-card { border-radius: 25px; } .modern-input-grid { gap: 12px; } .field-group.half { width: 100%; } .p-card { padding: 12px; gap: 10px; } .p-name { max-width: 100%; } }
@media (max-width: 330px) { .cart-column, .form-column, .payment-column { padding: 20px 10px; } .grand-total { font-size: 18px; } .confirm-order-btn { padding: 15px; font-size: 14px; } }
.scroll-area::-webkit-scrollbar { width: 4px; }
.scroll-area::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
</style>