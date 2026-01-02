<template>
  <div class="product-page">
    <div class="wide-container">
      
      <div class="horizontal-card">
        
        <div class="visual-side">
          <div class="image-box">
            <img :src="product.image" :alt="product.name" />
          </div>
        </div>

        <div class="content-side">
          <div class="header-group">
            <span class="brand-tag">{{ product.brand }}</span>
            <h1 class="product-title">{{ product.name }}</h1>
            <div class="rating-row">
              <i class="fa-solid fa-star"></i>
              <i class="fa-solid fa-star"></i>
              <i class="fa-solid fa-star"></i>
              <i class="fa-solid fa-star"></i>
              <i class="fa-solid fa-star-half-stroke"></i>
              <span class="rev-count">12 Reviews</span>
            </div>
          </div>

          <div class="pricing-group">
            <span class="price-label">Special Price</span>
            <div class="price-val">
              <span class="current">৳{{ product.price }}</span>
              <span v-if="product.oldPrice" class="old">৳{{ product.oldPrice }}</span>
            </div>
          </div>

          <div class="interaction-area">
            <div class="qty-box">
              <span class="qty-label">QTY</span>
              <div class="modern-stepper">
                <button @click="qty > 1 ? qty-- : null" class="step-btn">
                  <i class="fa-solid fa-minus"></i>
                </button>
                <input type="number" v-model="qty" readonly />
                <button @click="qty++" class="step-btn">
                  <i class="fa-solid fa-plus"></i>
                </button>
              </div>
            </div>

            <div class="action-btns">
              <button class="add-btn" @click="addToCart">
                <i class="fa-solid fa-cart-plus"></i>
                <span>Add to Cart</span>
              </button>
              <button class="buy-btn" @click="buyNow">
                Buy Now
              </button>
            </div>
          </div>

          <div class="mini-footer">
            <span><i class="fa-solid fa-circle-check"></i> Genuine Product</span>
            <span><i class="fa-solid fa-truck"></i> Fast Delivery</span>
          </div>
        </div>
      </div>

    </div>

    <Transition name="pop">
      <div class="toast-notif" v-if="toast.active">
        <i class="fa-solid fa-bag-shopping"></i>
        <span>{{ toast.msg }}</span>
      </div>
    </Transition>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue';
import { useRouter } from 'vue-router'; // Router ইমপোর্ট করা হয়েছে

const router = useRouter(); // Router ব্যবহার করার জন্য
const qty = ref(1);
const toast = reactive({ active: false, msg: "" });

const product = ref({
  id: 1,
  name: "iPhone 17 Pro Max 1TB Gold Premium",
  brand: "Apple Store",
  price: 150000,
  oldPrice: 165000,
  image: "/assets/product-images/iphone1.jpg"
});

const addToCart = () => {
  toast.msg = "Added to your shopping bag!";
  toast.active = true;
  setTimeout(() => (toast.active = false), 2200);
};

// Buy Now ফাংশন যা checkoutpage এ নিয়ে যাবে
const buyNow = () => {
  router.push('/checkoutpage');
};
</script>

<style scoped>
.product-page {
  background-color: #f0f2f5;
  min-height: 80vh;
  display: flex;
  align-items: center;
  justify-content: center;
  font-family: 'Inter', sans-serif;
  padding: 20px;
}

.wide-container {
  width: 100%;
  max-width: 1100px;
}

.horizontal-card {
  display: flex;
  background: #ffffff;
  border-radius: 20px;
  overflow: hidden;
  box-shadow: 0 10px 40px rgba(0,0,0,0.04);
  max-height: 480px;
}

/* Image Side */
.visual-side {
  flex: 0.9;
  background: #ffffff;
  padding: 30px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-right: 1px solid #f2f2f2;
}

.image-box img {
  max-width: 100%;
  max-height: 350px;
  object-fit: contain;
  transition: transform 0.3s;
}

/* Info Side */
.content-side {
  flex: 1.2;
  padding: 40px;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}

.brand-tag {
  color: #e4002b;
  font-size: 11px;
  font-weight: 800;
  text-transform: uppercase;
  letter-spacing: 1px;
}

.product-title {
  font-size: 26px;
  font-weight: 900;
  color: #1a1a1a;
  margin: 5px 0 10px 0;
}

.rating-row {
  display: flex;
  align-items: center;
  gap: 5px;
  color: #ffb800;
  font-size: 13px;
}

.rev-count {
  color: #999;
  font-size: 12px;
  margin-left: 5px;
}

.pricing-group {
  margin: 15px 0;
}

.price-label {
  display: block;
  font-size: 12px;
  color: #777;
  margin-bottom: 2px;
}

.current {
  font-size: 34px;
  font-weight: 900;
  color: #e4002b;
}

.old {
  font-size: 18px;
  color: #bbb;
  text-decoration: line-through;
  margin-left: 12px;
}

/* Interaction Area */
.interaction-area {
  display: flex;
  align-items: flex-end;
  gap: 20px;
  margin: 20px 0;
}

.qty-label {
  display: block;
  font-size: 11px;
  font-weight: 700;
  color: #555;
  margin-bottom: 8px;
}

.modern-stepper {
  display: flex;
  align-items: center;
  background: #f4f6f8;
  padding: 4px;
  border-radius: 12px;
  border: 1px solid #eee;
}

.step-btn {
  width: 36px;
  height: 36px;
  border: none;
  background: #fff;
  color: #333;
  border-radius: 10px;
  cursor: pointer;
  box-shadow: 0 2px 5px rgba(0,0,0,0.05);
}

.step-btn:hover {
  background: #e4002b;
  color: #fff;
}

.modern-stepper input {
  width: 45px;
  border: none;
  background: transparent;
  text-align: center;
  font-weight: 800;
  font-size: 16px;
}

/* Actions with Full Text Buttons */
.action-btns {
  display: flex;
  gap: 12px;
  flex: 2;
}

.add-btn {
  flex: 1;
  height: 52px;
  border: 2px solid #e4002b;
  background: transparent;
  color: #e4002b;
  border-radius: 14px;
  cursor: pointer;
  font-weight: 700;
  font-size: 14px;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  transition: 0.3s;
}

.add-btn:hover {
  background: #fff5f5;
}

.buy-btn {
  flex: 1;
  height: 52px;
  border: none;
  background: #e4002b;
  color: #fff;
  border-radius: 14px;
  font-weight: 700;
  font-size: 14px;
  cursor: pointer;
  box-shadow: 0 10px 20px rgba(228, 0, 43, 0.15);
  transition: 0.3s;
}

.buy-btn:hover {
  background: #c50025;
  transform: translateY(-2px);
}

.mini-footer {
  margin-top: 15px;
  display: flex;
  gap: 20px;
  border-top: 1px solid #f8f8f8;
  padding-top: 20px;
}

.mini-footer span {
  font-size: 11px;
  color: #666;
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 6px;
}

.mini-footer i {
  color: #2ecc71;
}

/* Toast */
.toast-notif {
  position: fixed;
  top: 30px;
  right: 30px;
  background: #111;
  color: #fff;
  padding: 15px 25px;
  border-radius: 15px;
  display: flex;
  align-items: center;
  gap: 12px;
  z-index: 1000;
  box-shadow: 0 15px 35px rgba(0,0,0,0.2);
}

.pop-enter-active, .pop-leave-active {
  transition: 0.4s ease;
}

.pop-enter-from, .pop-leave-to {
  transform: scale(0.9) translateY(-20px);
  opacity: 0;
}

@media (max-width: 992px) {
  .horizontal-card {
    max-height: none;
    flex-direction: column;
  }
  .visual-side {
    border-right: none;
    border-bottom: 1px solid #f2f2f2;
  }
  .interaction-area {
    flex-direction: column;
    align-items: stretch;
  }
  .action-btns {
    width: 100%;
  }
}
</style>