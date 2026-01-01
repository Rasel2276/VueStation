<template>
  <div class="product-page">
    <div class="container">
      <div class="image-section">
        <img
          src="/assets/product-images/audio3.jpg"
          alt="Product Image"
          class="product-image"
        />
      </div>

      <div class="content-section">
        <span class="category">CLOTHES</span>
        <h1 class="title">Men's Polo T-shirt</h1>

        <div class="rating">
          <span class="stars">★★★★☆</span>
          <a href="#">Read 3 reviews</a>
        </div>

        <p class="description">
          Investigationes demonstraverunt lectores legere me lius quod ii
          legunt saepius claritas est etiam processus dynamicus, qui sequitur
          mutationem consuetudium.
        </p>

        <div class="price">
          <del>$99.99</del>
          <span>$69.00</span>
        </div>

        <div class="sizes">
          <span>Size:</span>
          <button
            v-for="size in sizes"
            :key="size"
            :class="{ active: selectedSize === size }"
            @click="selectedSize = size"
          >
            {{ size }}
          </button>
        </div>

        <div class="action-row">
          <div class="quantity">
            <button @click="decrease">−</button>
            <span>{{ qty }}</span>
            <button @click="increase">+</button>
          </div>

          <div class="actions">
            <button class="add-cart">ADD TO CART</button>
            <button class="checkout">CHECKOUT</button>
          </div>
        </div>

        <div class="countdown">
          <div class="time-box">
            <strong>01</strong>
            <span>DAY</span>
          </div>
          <div class="time-box">
            <strong>23</strong>
            <span>HRS</span>
          </div>
          <div class="time-box">
            <strong>55</strong>
            <span>MINS</span>
          </div>
          <div class="time-box">
            <strong>32</strong>
            <span>SECS</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "ProductDetails",
  data() {
    return {
      sizes: ["XS", "S", "M", "L", "XL"],
      selectedSize: "M",
      qty: 2,
    };
  },
  methods: {
    increase() {
      this.qty++;
    },
    decrease() {
      if (this.qty > 1) this.qty--;
    },
  },
};
</script>

<style scoped>
.product-page {
  min-height: auto; /* Fixed height ke auto kora hoyeche */
  background: radial-gradient(circle at center, #2b6f8f, #1f4f66);
  display: flex;
  align-items: center;
  padding: 20px; /* Padding komano hoyeche */
  box-sizing: border-box;
}

.container {
  max-width: 1000px; /* Container ektu narrow kora hoyeche */
  margin: 0 auto;
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 30px; /* Gap komano hoyeche */
  align-items: center;
}

/* IMAGE */
.image-section {
  display: flex;
  justify-content: center;
}
.product-image {
  max-width: 70%; /* Image ratio adjustment */
  height: auto;
  border-radius: 15px;
}

/* CONTENT */
.content-section {
  color: #fff;
}
.category {
  font-size: 11px;
  letter-spacing: 1.5px;
  opacity: 0.7;
}
.title {
  font-size: 32px; /* Title font choto kora hoyeche */
  margin: 5px 0;
}
.rating {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 14px;
}
.stars {
  color: #ffd700;
}
.rating a {
  color: #9fd6ff;
  text-decoration: none;
  font-size: 12px;
}
.description {
  margin: 12px 0;
  line-height: 1.4;
  font-size: 14px;
  opacity: 0.8;
}

/* PRICE */
.price {
  margin: 15px 0;
}
.price del {
  opacity: 0.6;
  margin-right: 10px;
  font-size: 16px;
}
.price span {
  color: #48c774;
  font-size: 24px;
  font-weight: bold;
}

/* SIZE */
.sizes {
  display: flex;
  align-items: center;
  gap: 8px;
  margin: 15px 0;
  font-size: 14px;
}
.sizes button {
  width: 32px; /* Button size komano hoyeche */
  height: 32px;
  border-radius: 50%;
  border: none;
  background: #fff;
  color: #333;
  cursor: pointer;
  font-size: 12px;
  font-weight: bold;
  transition: 0.3s;
}
.sizes button.active {
  background: #48c774;
  color: #fff;
}

/* ACTION ROW (QTY + BUTTONS) */
.action-row {
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  gap: 15px;
  margin-bottom: 20px;
}

/* QTY */
.quantity {
  display: inline-flex;
  align-items: center;
  background: #fff;
  border-radius: 20px;
  padding: 5px 12px;
  color: #333;
}
.quantity button {
  border: none;
  background: none;
  font-size: 18px;
  cursor: pointer;
  padding: 0 10px;
}
.quantity span {
  font-weight: bold;
  min-width: 20px;
  text-align: center;
}

/* COUNTDOWN */
.countdown {
  display: flex;
  gap: 10px;
}
.time-box {
  background: rgba(255, 255, 255, 0.15); /* Glassmorphism look */
  backdrop-filter: blur(5px);
  color: #fff;
  padding: 8px;
  border-radius: 8px;
  text-align: center;
  min-width: 55px;
  border: 1px solid rgba(255, 255, 255, 0.2);
}
.time-box strong {
  display: block;
  font-size: 18px;
}
.time-box span {
  font-size: 9px;
  text-transform: uppercase;
}

/* BUTTONS */
.actions {
  display: flex;
  gap: 10px;
}
.add-cart, .checkout {
  padding: 10px 20px;
  border-radius: 20px;
  border: none;
  font-size: 13px;
  font-weight: bold;
  cursor: pointer;
  transition: 0.3s;
}
.add-cart {
  background: #48c774;
  color: #fff;
}
.checkout {
  background: #fff;
  color: #1f4f66;
}
.add-cart:hover, .checkout:hover {
    opacity: 0.9;
    transform: translateY(-2px);
}

/* RESPONSIVE */
@media (max-width: 768px) {
  .container {
    grid-template-columns: 1fr;
    text-align: center;
    gap: 20px;
  }
  .sizes, .action-row, .countdown {
    justify-content: center;
  }
  .product-image {
    max-width: 50%;
  }
}
</style>