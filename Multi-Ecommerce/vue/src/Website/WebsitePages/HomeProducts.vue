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
          <img src="https://images.unsplash.com/photo-1546435770-a3e426bf472b?w=1000" class="h-full-bg" alt="Headphone" />
          
          <div class="h-content-flex">
            <div class="h-text-area">
              <span class="h-badge">Limited Series</span>
              <h1 class="hero-title">Pro Audio <br/><span class="text-accent-color">Series Z</span></h1>
              <p class="hero-desc">Experience studio-quality sound with our next-gen acoustic technology. Designed for the professionals.</p>
              <button class="h-btn-premium">Explore Now <span class="btn-arrow">→</span></button>
            </div>
          </div>
        </div>

        <div class="hero-column">
          <div class="hero-item side-item dark-blue">
            <div class="dark-overlay-sm"></div>
            <img src="/assets/product-images/game3.jpg" class="h-full-bg" alt="Watch" />
            <div class="h-content-flex sm-flex">
              <div class="h-text-area">
                <h3 class="side-title">Smart Tech</h3>
                <p class="side-desc">The future of wearable.</p>
                <button class="h-link-premium">Shop Collection</button>
              </div>
            </div>
          </div>
          <div class="hero-item side-item gray-bg">
            <div class="dark-overlay-sm"></div>
            <img src="/assets/product-images/game1.jpg" class="h-full-bg" alt="Mouse" />
            <div class="h-content-flex sm-flex">
              <div class="h-text-area">
                <h3 class="side-title">Accessories</h3>
                <p class="side-desc">Minimal & Essential.</p>
                <button class="h-link-premium">Shop Collection</button>
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
            <div class="p-card" v-for="p in productItems" :key="p.uid">
              <div class="p-image-box">
                <span class="p-tag-new" v-if="p.sale">{{ p.saleText }}</span>
                <img :src="p.img" alt="Product" />
                <div class="p-overlay">
                  <button class="quick-view">👁️ Quick View</button>
                </div>
              </div>
              <div class="p-info">
                <p class="p-category">{{ p.category }}</p>
                <h4 class="p-title">{{ p.name }}</h4>
                <p class="p-short-desc">{{ p.desc }}</p>
                <div class="p-price-row">
                  <div class="price-group">
                    <span class="p-price-val">${{ p.price }}</span>
                    <span class="p-old-price" v-if="p.oldPrice">${{ p.oldPrice }}</span>
                  </div>
                  <span class="p-rating">⭐ {{ p.rating }}</span>
                </div>
                <button class="always-visible-add">
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
export default {
  data() {
    return {
      isAnimating: false,
      productItems: [
        { uid: 1, name: 'Wireless Headphone', price: 129, oldPrice: 150, category: 'Audio', desc: 'High fidelity sound with noise cancellation.', img: 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=500', rating: 4.8, sale: true, saleText: 'Sale' },
        { uid: 2, name: 'Smart Watch Z', price: 199, category: 'Wearables', desc: 'Monitor your health 24/7 with sensors.', img: 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=500', rating: 4.9, sale: false },
        { uid: 3, name: 'Gaming Mouse', price: 59, oldPrice: 80, category: 'Accessories', desc: 'Ultra-fast response for pro gaming.', img: 'https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?w=500', rating: 4.7, sale: true, saleText: 'Best Seller' },
        { uid: 4, name: 'Mechanical Keyboard', price: 149, category: 'Accessories', desc: 'RGB backlit mechanical keys.', img: 'https://images.unsplash.com/photo-1511467687858-23d96c32e4ae?w=500', rating: 4.9, sale: false },
        { uid: 5, name: 'Bluetooth Speaker', price: 89, category: 'Audio', desc: 'Waterproof portable bass speaker.', img: 'https://images.unsplash.com/photo-1608156639585-b3a032ef9689?w=500', rating: 4.6, sale: true, saleText: '10% Off' },
        { uid: 6, name: 'Tech Backpack', price: 110, category: 'Travel', desc: 'Anti-theft bag with USB port.', img: 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=500', rating: 4.5, sale: false },
        { uid: 7, name: 'VR Headset', price: 299, category: 'Gadgets', desc: 'Immersive virtual reality kit.', img: 'https://images.unsplash.com/photo-1622979135225-d2ba269cf1ac?w=500', rating: 4.8, sale: false },
        { uid: 8, name: 'Fast Charger', price: 25, category: 'Power', desc: 'Dual port 65W charging adapter.', img: 'https://images.unsplash.com/photo-1619130771181-a22676839352?w=500', rating: 4.9, sale: true, saleText: 'New' },
      ],
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
  methods: {
    slideRight() {
      if (this.isAnimating) return;
      this.isAnimating = true;
      // Responsive slide: Mobile-e 1ti kore, PC-te 4ti kore move hobe
      const moveCount = window.innerWidth <= 768 ? 1 : 4;
      const itemsToMove = this.productItems.splice(0, moveCount);
      this.productItems.push(...itemsToMove);
      setTimeout(() => { this.isAnimating = false; }, 600);
    },
    slideLeft() {
      if (this.isAnimating) return;
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
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

.store-page { background: #ffffff; font-family: 'Plus Jakarta Sans', sans-serif; color: #001E3C; padding: 40px 0; overflow-x: hidden; }
.main-container { max-width: 1500px; margin: 0 auto; padding: 0 5%; }

/* Service Strip (Same) */
.service-strip { display: flex; justify-content: space-between; background: #f8fafd; padding: 15px 30px; border-radius: 12px; margin-bottom: 35px; border: 1px solid #edf2f7; }
.s-item { display: flex; align-items: center; gap: 10px; }
.s-icon { font-size: 18px; }
.s-info h5 { margin: 0; font-size: 13px; font-weight: 700; }
.s-info p { margin: 0; font-size: 11px; color: #718096; }

/* HERO SECTION - DARK MOOD & ENHANCED CONTENT */
.hero-mosaic { display: grid; grid-template-columns: 1.6fr 1fr; gap: 15px; height: 350px; margin-bottom: 50px; }
.hero-item { position: relative; border-radius: 16px; overflow: hidden; background: #000; height: 100%; }
.h-full-bg { position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; z-index: 1; }

.dark-overlay { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(90deg, rgba(0,0,0,0.85) 0%, rgba(0,0,0,0.4) 60%, rgba(0,0,0,0.1) 100%); z-index: 2; }
.dark-overlay-sm { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.4); z-index: 2; }

.h-content-flex { position: relative; z-index: 3; display: flex; height: 100%; align-items: center; padding: 0 40px; }

/* Hero Text Color Updates */
.h-badge { background: #00e5ff; color: #001E3C; padding: 4px 12px; border-radius: 20px; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; }
.hero-title { font-size: 44px; font-weight: 800; margin: 15px 0; color: #ffffff; line-height: 1.1; letter-spacing: -1.5px; }
.text-accent-color { color: #00e5ff; /* Cyan color for attractive look */ }
.hero-desc { font-size: 15px; color: #e2e8f0; max-width: 380px; margin-bottom: 25px; line-height: 1.6; }

/* Premium Button (Same) */
.h-btn-premium { background: #ffffff; color: #001E3C; border: none; padding: 14px 28px; border-radius: 8px; cursor: pointer; font-size: 14px; font-weight: 700; display: flex; align-items: center; gap: 12px; transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
.h-btn-premium:hover { background: #00e5ff; color: #001E3C; transform: scale(1.05); }
.btn-arrow { transition: 0.3s; }
.h-btn-premium:hover .btn-arrow { transform: translateX(5px); }

/* Side Items Content (Same) */
.side-title { color: #fff; font-size: 22px; font-weight: 800; margin-bottom: 5px; }
.side-desc { color: #e2e8f0; font-size: 13px; margin-bottom: 15px; }
.h-link-premium { background: none; border: none; color: #00e5ff; font-weight: 700; border-bottom: 2px solid #00e5ff; padding: 0 0 2px 0; cursor: pointer; font-size: 12px; transition: 0.3s; }

.hero-column { display: flex; flex-direction: column; gap: 15px; }

/* PRODUCT SECTION & CARD (100% SAME AS ORIGINAL) */
.product-section { margin-top: 20px; }
.section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
.l-title { display: flex; align-items: center; gap: 12px; }
.indicator { width: 4px; height: 20px; background: #001E3C; border-radius: 4px; }
.l-title h2 { font-size: 20px; margin: 0; font-weight: 700; }
.slider-controls { display: flex; gap: 8px; }
.ctrl-btn { width: 40px; height: 40px; border-radius: 50%; border: 1px solid #e2e8f0; background: #fff; cursor: pointer; font-size: 18px; transition: 0.3s; }

.slider-viewport { overflow: hidden; padding: 10px 0; }
.product-row { display: flex; gap: 20px; }

.p-card { 
  flex: 0 0 calc(25% - 15px);
  max-width: 280px; 
  background: #fff; border-radius: 16px; border: 1px solid #f1f5f9; padding-bottom: 15px; transition: 0.3s; 
  box-shadow: 0 2px 10px rgba(0,0,0,0.03);
}
.p-card:hover { transform: translateY(-5px); box-shadow: 0 12px 25px rgba(0,0,0,0.08); }

.p-image-box { 
  position: relative; height: 180px; margin: 8px; 
  background: #f8fafc; overflow: hidden; display: flex; align-items: center; justify-content: center; border-radius: 12px; 
}
.p-image-box img { width: 100%; height: 100%; object-fit: cover; transition: 0.6s cubic-bezier(0.4, 0, 0.2, 1); }
.p-card:hover .p-image-box img { transform: scale(1.1); }

.p-overlay { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 30, 60, 0.3); display: flex; align-items: center; justify-content: center; opacity: 0; transition: 0.3s; }
.p-card:hover .p-overlay { opacity: 1; }
.quick-view { background: #fff; color: #001E3C; border: none; padding: 8px 18px; border-radius: 25px; font-size: 11px; font-weight: 700; cursor: pointer; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }

.p-tag-new { position: absolute; top: 12px; left: 12px; background: #001E3C; color: #fff; font-size: 9px; padding: 3px 12px; border-radius: 20px; font-weight: 700; z-index: 2; }

.p-info { padding: 0 15px 5px 15px; }
.p-category { font-size: 10px; text-transform: uppercase; color: #94a3b8; font-weight: 800; margin-top: 10px; letter-spacing: 0.8px; }
.p-title { font-size: 15px; font-weight: 700; margin: 5px 0; color: #001E3C; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.p-short-desc { font-size: 11px; color: #64748b; margin-bottom: 12px; line-height: 1.5; height: 32px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; }

.p-price-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
.p-price-val { font-weight: 800; color: #001E3C; font-size: 18px; }
.p-old-price { font-size: 12px; color: #94a3b8; text-decoration: line-through; margin-left: 5px; }
.p-rating { font-size: 12px; font-weight: 700; color: #1e293b; background: #f1f5f9; padding: 2px 8px; border-radius: 6px; }

.always-visible-add { width: 100%; padding: 12px; background: #001E3C; color: #ffffff; border: none; border-radius: 10px; font-weight: 700; font-size: 12px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 10px; transition: 0.3s; }
.always-visible-add:hover { background: #002d5a; transform: scale(1.02); }

/* RESPONSIVE */
@media (max-width: 1024px) {
  .p-card { flex: 0 0 calc(33.33% - 15px); }
  .main-container { padding: 0 5%; }
}
@media (max-width: 768px) {
  .hero-mosaic { grid-template-columns: 1fr; height: auto; }
  .h-full-bg { position: relative; height: 220px; }
  .dark-overlay { background: rgba(0,0,0,0.6); }
  .h-content-flex { padding: 30px 20px; background: #001E3C; display: block; height: auto; }
  .hero-title { font-size: 32px; }
  /* Mobile-e 2ti card show korbe jate slide-er baki gulo dekha jay */
  .p-card { flex: 0 0 calc(50% - 10px); max-width: none; }
  .main-container { padding: 0 15px; }
}
</style>