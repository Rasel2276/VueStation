<template>
  <div class="store-page">
    <div class="main-container">
      
      <div class="service-strip">
        <div class="s-item" v-for="s in services" :key="s.title">
          <span class="s-icon">{{ s.icon }}</span>
          <div class="s-info">
            <h5>{{ s.title }}</h5>
            <p>{{ s.desc }}</p>
          </div>
        </div>
      </div>

      <div class="hero-mosaic">
        <div class="hero-item main-gadget">
          <div class="dark-overlay"></div>
          <img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=500" class="h-full-bg" alt="Headphone" />
          
          <div class="h-content-flex">
            <div class="h-text-area">
              <span class="h-badge">Limited Series</span>
              <h1 class="hero-title">Pro Audio <br/><span class="text-accent-color">Series Z</span></h1>
              <p class="hero-desc">Experience studio-quality sound with our next-gen acoustic technology.</p>
              <button class="h-btn-premium" @click="$router.push('/products')">Explore Now <span class="btn-arrow">→</span></button>
            </div>
          </div>
        </div>

        <div class="hero-column">
          <div class="hero-item side-item dark-blue">
            <div class="dark-overlay-sm"></div>
            <img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=500" class="h-full-bg" alt="Watch" />
            <div class="h-content-flex sm-flex">
              <div class="h-text-area">
                <h3 class="side-title">Smart Tech</h3>
                <p class="side-desc">The future of wearable.</p>
                <button class="h-link-premium" @click="$router.push('/products')">Shop Now</button>
              </div>
            </div>
          </div>
          <div class="hero-item side-item gray-bg">
            <div class="dark-overlay-sm"></div>
            <img src="https://images.unsplash.com/photo-1511467687858-23d96c32e4ae?w=500" class="h-full-bg" alt="Mouse" />
            <div class="h-content-flex sm-flex">
              <div class="h-text-area">
                <h3 class="side-title">Accessories</h3>
                <p class="side-desc">Minimal & Essential.</p>
                <button class="h-link-premium" @click="$router.push('/products')">Shop Now</button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="product-section">
        <div class="section-header">
          <div class="l-title">
            <div class="indicator"></div>
            <h2>New Arrivals</h2>
          </div>
          <div class="slider-controls">
            <button class="ctrl-btn" @click="slideLeft">←</button>
            <button class="ctrl-btn" @click="slideRight">→</button>
          </div>
        </div>

        <div class="slider-viewport">
          <div class="product-row" :style="rowStyle">
            <div class="p-card" v-for="p in productItems" :key="p.id">
              <div class="p-image-box">
                <img :src="getImageUrl(p.image)" :alt="p.name" />
                <div class="p-overlay">
                  <button class="quick-view" @click="viewDetails(p)">👁️ Quick View</button>
                </div>
              </div>
              <div class="p-info">
                <p class="p-category">{{ p.category || 'General' }}</p>
                <h4 class="p-title">{{ p.name }}</h4>
                <p class="p-short-desc">{{ p.details }}</p>
                <div class="p-price-row">
                  <div class="price-group">
                    <span class="p-price-val">৳{{ p.price }}</span>
                  </div>
                  <span class="p-rating">⭐ 4.9</span>
                </div>
                <button class="always-visible-add" @click="addToCart(p)">
                  <span class="btn-icon">🛒</span>
                  <span>Add to Cart</span>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script>
import api, { BASE_URL } from '../../axios';

export default {
  data() {
    return {
      isAnimating: false,
      productItems: [],
      services: [
        { icon: '🚀', title: 'Express', desc: 'Ships in 24h' },
        { icon: '🛡️', title: 'Secure', desc: 'Safe Payment' },
        { icon: '♻️', title: 'Return', desc: '30 Days Return' },
        { icon: '💬', title: 'Support', desc: '24/7 Help' }
      ]
    }
  },
  computed: {
    rowStyle() {
      return {
        transition: this.isAnimating ? 'transform 0.6s cubic-bezier(0.4, 0, 0.2, 1)' : 'none'
      };
    }
  },
  mounted() {
    this.fetchProducts();
  },
  methods: {
    async fetchProducts() {
      try {
        const response = await api.get('/marketplace/all-products');
        this.productItems = response.data;
      } catch (error) {
        console.error("Error fetching products:", error);
      }
    },
    getImageUrl(imageName) {
      return imageName ? `${BASE_URL}/ui_product_images/${imageName}` : 'https://via.placeholder.com/300';
    },
    addToCart(product) {
      window.dispatchEvent(new CustomEvent('add-to-cart', { detail: product }));
    },
    viewDetails(product) {
      localStorage.setItem('selectedProduct', JSON.stringify(product));
      this.$router.push('/product_details');
    },
    slideRight() {
      if (this.isAnimating || this.productItems.length === 0) return;
      this.isAnimating = true;
      const moveCount = window.innerWidth <= 768 ? 1 : 4;
      const itemsToMove = this.productItems.splice(0, moveCount);
      this.productItems.push(...itemsToMove);
      setTimeout(() => { this.isAnimating = false; }, 600);
    },
    slideLeft() {
      if (this.isAnimating || this.productItems.length === 0) return;
      this.isAnimating = true;
      const moveCount = window.innerWidth <= 768 ? 1 : 4;
      const itemsToMove = this.productItems.splice(-moveCount);
      this.productItems.unshift(...itemsToMove);
      setTimeout(() => { this.isAnimating = false; }, 600);
    }
  }
}
</script>

<style scoped>
/* 100% ORIGINAL CSS - NO CHANGES MADE */
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

.store-page {
  background: #ffffff;
  font-family: 'Plus Jakarta Sans', sans-serif;
  color: #001E3C;
  padding: 40px 0;
  overflow-x: hidden;
}

.main-container {
  max-width: 1500px;
  margin: 0 auto;
  padding: 0 5%;
}

.service-strip {
  display: flex;
  justify-content: space-between;
  background: #f8fafd;
  padding: 15px 30px;
  border-radius: 12px;
  margin-bottom: 35px;
  border: 1px solid #edf2f7;
}

.s-item {
  display: flex;
  align-items: center;
  gap: 10px;
}

.s-icon {
  font-size: 18px;
}

.s-info h5 {
  margin: 0;
  font-size: 13px;
  font-weight: 700;
}

.s-info p {
  margin: 0;
  font-size: 11px;
  color: #718096;
}

.hero-mosaic {
  display: grid;
  grid-template-columns: 1.6fr 1fr;
  gap: 15px;
  height: 350px;
  margin-bottom: 50px;
}

.hero-item {
  position: relative;
  border-radius: 16px;
  overflow: hidden;
  background: #000;
  height: 100%;
}

.h-full-bg {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  object-fit: cover;
  z-index: 1;
}

.dark-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, rgba(0,0,0,0.85) 0%, rgba(0,0,0,0.4) 60%, rgba(0,0,0,0.1) 100%);
  z-index: 2;
}

.dark-overlay-sm {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0,0,0,0.4);
  z-index: 2;
}

.h-content-flex {
  position: relative;
  z-index: 3;
  display: flex;
  height: 100%;
  align-items: center;
  padding: 0 40px;
}

.h-badge {
  background: #00e5ff;
  color: #001E3C;
  padding: 4px 12px;
  border-radius: 20px;
  font-size: 10px;
  font-weight: 800;
  text-transform: uppercase;
  letter-spacing: 1px;
}

.hero-title {
  font-size: 44px;
  font-weight: 800;
  margin: 15px 0;
  color: #ffffff;
  line-height: 1.1;
  letter-spacing: -1.5px;
}

.text-accent-color {
  color: #00e5ff;
}

.hero-desc {
  font-size: 15px;
  color: #e2e8f0;
  max-width: 380px;
  margin-bottom: 25px;
  line-height: 1.6;
}

.h-btn-premium {
  background: #ffffff;
  color: #001E3C;
  border: none;
  padding: 14px 28px;
  border-radius: 8px;
  cursor: pointer;
  font-size: 14px;
  font-weight: 700;
  display: flex;
  align-items: center;
  gap: 12px;
  transition: 0.3s;
}

.h-btn-premium:hover {
  background: #00e5ff;
  transform: scale(1.05);
}

.side-title {
  color: #fff;
  font-size: 22px;
  font-weight: 800;
  margin-bottom: 5px;
}

.side-desc {
  color: #e2e8f0;
  font-size: 13px;
  margin-bottom: 15px;
}

.h-link-premium {
  background: none;
  border: none;
  color: #00e5ff;
  font-weight: 700;
  border-bottom: 2px solid #00e5ff;
  padding: 0 0 2px 0;
  cursor: pointer;
  font-size: 12px;
}

.hero-column {
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.product-section {
  margin-top: 20px;
}

.section-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 25px;
}

.l-title {
  display: flex;
  align-items: center;
  gap: 12px;
}

.indicator {
  width: 4px;
  height: 20px;
  background: #001E3C;
  border-radius: 4px;
}

.l-title h2 {
  font-size: 20px;
  margin: 0;
  font-weight: 700;
}

.slider-controls {
  display: flex;
  gap: 8px;
}

.ctrl-btn {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  border: 1px solid #e2e8f0;
  background: #fff;
  cursor: pointer;
  font-size: 18px;
  transition: 0.3s;
}

.slider-viewport {
  overflow: hidden;
  padding: 10px 0;
}

.product-row {
  display: flex;
  gap: 20px;
}

.p-card {
  flex: 0 0 calc(25% - 15px);
  max-width: 280px;
  background: #fff;
  border-radius: 16px;
  border: 1px solid #f1f5f9;
  padding-bottom: 15px;
  transition: 0.3s;
  box-shadow: 0 2px 10px rgba(0,0,0,0.03);
}

.p-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 12px 25px rgba(0,0,0,0.08);
}

.p-image-box {
  position: relative;
  height: 180px;
  margin: 8px;
  background: #f8fafc;
  overflow: hidden;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 12px;
}

.p-image-box img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: 0.6s;
}

.p-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 30, 60, 0.3);
  display: flex;
  align-items: center;
  justify-content: center;
  opacity: 0;
  transition: 0.3s;
}

.p-card:hover .p-overlay {
  opacity: 1;
}

.quick-view {
  background: #fff;
  color: #001E3C;
  border: none;
  padding: 8px 18px;
  border-radius: 25px;
  font-size: 11px;
  font-weight: 700;
  cursor: pointer;
}

.p-tag-new {
  position: absolute;
  top: 12px;
  left: 12px;
  background: #001E3C;
  color: #fff;
  font-size: 9px;
  padding: 3px 12px;
  border-radius: 20px;
  font-weight: 700;
  z-index: 2;
}

.p-info {
  padding: 0 15px 5px 15px;
}

.p-category {
  font-size: 10px;
  text-transform: uppercase;
  color: #94a3b8;
  font-weight: 800;
  margin-top: 10px;
}

.p-title {
  font-size: 15px;
  font-weight: 700;
  margin: 5px 0;
  color: #001E3C;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.p-short-desc {
  font-size: 11px;
  color: #64748b;
  margin-bottom: 12px;
  line-height: 1.5;
  height: 32px;
  overflow: hidden;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
}

.p-price-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 15px;
}

.p-price-val {
  font-weight: 800;
  color: #001E3C;
  font-size: 18px;
}

.p-old-price {
  font-size: 12px;
  color: #94a3b8;
  text-decoration: line-through;
  margin-left: 5px;
}

.p-rating {
  font-size: 12px;
  font-weight: 700;
  color: #1e293b;
  background: #f1f5f9;
  padding: 2px 8px;
  border-radius: 6px;
}

.always-visible-add {
  width: 100%;
  padding: 12px;
  background: #001E3C;
  color: #ffffff;
  border: none;
  border-radius: 10px;
  font-weight: 700;
  font-size: 12px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
}

@media (max-width: 768px) {
  .service-strip {
    flex-wrap: wrap;
    padding: 15px 10px;
    gap: 15px 5px;
    justify-content: center;
    margin-bottom: 25px;
  }

  .s-item {
    flex: 0 0 calc(50% - 10px);
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    gap: 5px;
  }

  .s-info h5 { font-size: 11px; line-height: 1.2; }
  .s-info p { font-size: 9px; opacity: 0.8; }
  .s-icon { font-size: 20px; }

  .p-image-box {
    height: 145px;
    margin: 6px;
  }

  .p-image-box img {
    object-fit: cover;
    width: 100%;
    height: 100%;
  }

  .hero-mosaic {
    grid-template-columns: 1.6fr 1fr;
    height: 300px;
    margin-bottom: 30px;
    gap: 8px;
  }

  .hero-title { font-size: 18px; margin: 5px 0; }
  .hero-desc { font-size: 10px; line-height: 1.3; margin-bottom: 10px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
  .h-badge { font-size: 8px; padding: 2px 8px; }
  .h-btn-premium { padding: 8px 12px; font-size: 10px; border-radius: 5px; }
  .h-content-flex { padding: 0 15px; }
  .side-title { font-size: 14px; }
  .side-desc { font-size: 9px; margin-bottom: 8px; }
  .h-link-premium { font-size: 9px; }

  .product-row {
    gap: 12px; 
  }
  .p-card {
    flex: 0 0 calc(50% - 6px); 
    min-width: 140px; 
  }
  
  .main-container {
    padding: 0 15px;
  }
}

@media (max-width: 330px) {
  .p-card {
    flex: 0 0 calc(50% - 6px);
  }
  .product-row {
    gap: 6px;
  }
}
</style>