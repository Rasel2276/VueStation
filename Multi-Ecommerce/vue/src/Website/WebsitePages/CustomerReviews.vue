<template>
  <section class="reviews-section">
    <div class="main-container">
      <div class="section-header">
        <span class="pre-title">Testimonials</span>
        <h2 class="premium-title">What Our <span class="highlight">Community</span> Says</h2>
      </div>

      <div class="slider-viewport">
        <div class="reviews-container" :style="sliderStyle">
          <div class="review-card-wrapper" v-for="rev in reviews" :key="rev.id">
            <div class="review-card">
              <div class="avatar-box">
                <img :src="rev.avatar" :alt="rev.user" class="avatar" />
                <div class="verified-badge">✓</div>
              </div>

              <div class="card-body">
                <h4 class="user-name">{{ rev.user }}</h4>
                <p class="review-text">"{{ rev.comment }}"</p>
                
                <div class="card-footer">
                  <div class="rating-stars">
                    <span v-for="n in 5" :key="n" :class="{ 'filled': n <= rev.rating }">★</span>
                  </div>
                  <div class="verified-text">Verified</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="pagination">
        <button 
          v-for="(dot, index) in totalDots" 
          :key="index"
          class="dot"
          :class="{ active: currentGroup === index }"
          @click="goToGroup(index)"
        ></button>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  data() {
    return {
      currentIndex: 0,
      visibleCards: 4, 
      reviews: [
        { id: 1, user: 'Samiul Islam', rating: 5, comment: 'Series Z spatial audio is pure magic. Best tech buy!', avatar: 'https://i.pravatar.cc/150?u=11' },
        { id: 2, user: 'Anika T.', rating: 5, comment: 'Record delivery time. Packaging was super premium.', avatar: 'https://i.pravatar.cc/150?u=12' },
        { id: 3, user: 'Rakib Hasan', rating: 4, comment: 'Coder’s dream keyboard. Minimalist and very responsive.', avatar: 'https://i.pravatar.cc/150?u=13' },
        { id: 4, user: 'Zarin Subah', rating: 5, comment: 'Switching devices is now flawless. Love the ecosystem.', avatar: 'https://i.pravatar.cc/150?u=14' },
        { id: 5, user: 'Tanvir Ahmed', rating: 5, comment: 'Support team is very helpful and knows their tech well.', avatar: 'https://i.pravatar.cc/150?u=15' },
        { id: 6, user: 'Nabila K.', rating: 5, comment: 'Authentic products. My backpack is both stylish and durable.', avatar: 'https://i.pravatar.cc/150?u=16' },
        { id: 7, user: 'Asif Khan', rating: 5, comment: 'The VR experience is unreal. Value for every penny spent.', avatar: 'https://i.pravatar.cc/150?u=17' },
        { id: 8, user: 'Mehedi H.', rating: 4, comment: 'Solid build quality. Highly recommended for pros.', avatar: 'https://i.pravatar.cc/150?u=18' }
      ]
    }
  },
  computed: {
    totalDots() {
      return Math.ceil(this.reviews.length / this.visibleCards);
    },
    currentGroup() {
      return Math.floor(this.currentIndex / this.visibleCards);
    },
    sliderStyle() {
      // Eikhane movement-ta screen size er upor base kore width adjust kora hoyeche
      const move = (this.currentGroup * 100);
      return {
        // Container width must be total sets of cards * 100%
        width: `${(this.reviews.length / this.visibleCards) * 100}%`,
        transform: `translateX(-${move / (this.reviews.length / this.visibleCards)}%)`,
        transition: 'transform 0.8s cubic-bezier(0.25, 1, 0.5, 1)'
      };
    }
  },
  mounted() {
    this.updateVisibleCards();
    window.addEventListener('resize', this.updateVisibleCards);
  },
  methods: {
    updateVisibleCards() {
      const width = window.innerWidth;
      if (width <= 768) {
        this.visibleCards = 2; // Fixed 2 for mobile
      } else {
        this.visibleCards = 4; // Fixed 4 for desktop
      }
      this.currentIndex = 0;
    },
    goToGroup(index) {
      this.currentIndex = index * this.visibleCards;
    }
  }
}
</script>

<style scoped>
/* Main Background White */
.reviews-section {
  padding-bottom: 55px;
  padding-top: 35px;
  background: #ffffff;
  overflow: hidden;
  font-family: 'Plus Jakarta Sans', sans-serif;
}

.main-container {
  max-width: 1400px;
  margin: 0 auto;
  padding: 0 50px;
}

.section-header { text-align: center; margin-bottom: 20px; }
.pre-title { color: #00e5ff; font-weight: 800; font-size: 12px; text-transform: uppercase; letter-spacing: 2px; }
.premium-title { font-size: 34px; font-weight: 800; color: #010d1a; margin-top: 10px; }
.highlight { color: #00e5ff; }

.slider-viewport {
  overflow: hidden;
  padding-top: 50px;
}

.reviews-container {
  display: flex;
}

/* Card - Dark Blue */
.review-card-wrapper {
  /* Desktop e total viewport er 1/4 (25%) */
  flex: 0 0 calc(100% / v-bind('reviews.length')); 
  padding: 0 10px;
  box-sizing: border-box;
}

.review-card {
  background: #001E3C; 
  border-radius: 24px;
  padding: 50px 20px 25px 20px;
  position: relative;
  text-align: center;
  transition: 0.3s ease;
  height: 100%;
}

.review-card:hover {
  transform: translateY(-8px);
  background: #002a54;
}

.avatar-box {
  position: absolute;
  top: -35px;
  left: 50%;
  transform: translateX(-50%);
  z-index: 10;
}
.avatar {
  width: 70px;
  height: 70px;
  border-radius: 20px;
  border: 4px solid #ffffff;
  box-shadow: 0 8px 20px rgba(0,0,0,0.15);
  object-fit: cover;
}
.verified-badge {
  position: absolute;
  bottom: 0;
  right: -5px;
  background: #00e5ff;
  color: #001E3C;
  width: 20px;
  height: 20px;
  border-radius: 50%;
  font-size: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  border: 2px solid #001E3C;
  font-weight: 900;
}

.user-name { color: #ffffff; margin: 0 0 10px 0; font-size: 16px; font-weight: 700; }
.review-text {
  font-size: 13px;
  line-height: 1.6;
  color: #cbd5e1;
  margin-bottom: 25px;
  min-height: 60px;
  font-style: italic;
}

.card-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding-top: 15px;
  border-top: 1px solid rgba(255,255,255,0.1);
}
.rating-stars { display: flex; gap: 2px; color: #475569; font-size: 12px; }
.rating-stars .filled { color: #ffc107; }
.verified-text { font-size: 10px; color: #00e5ff; font-weight: 700; text-transform: uppercase; }

.pagination { display: flex; justify-content: center; gap: 10px; margin-top: 40px; }
.dot {
  width: 10px; height: 6px;
  background: #e2e8f0;
  border: none; border-radius: 10px;
  cursor: pointer; transition: 0.4s;
}
.dot.active { width: 30px; background: #001E3C; }

/* --- FULLY MOBILE RESPONSIVE FIX (ONLY FOR MOBILE) --- */
@media (max-width: 768px) {
  .main-container { padding: 0 15px; }

  /* Mobile-e container width calculate korbe jate 2-ta fit thake */
  .review-card-wrapper {
    flex: 0 0 calc(100% / v-bind('reviews.length')) !important; 
    padding: 0 5px;
  }
  
  .premium-title { font-size: 24px; }
  .avatar { width: 60px; height: 60px; }
  .review-text { font-size: 11px; min-height: 90px; }
}
</style>