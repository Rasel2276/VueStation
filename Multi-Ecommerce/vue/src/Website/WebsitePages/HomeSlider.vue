<template>
  <div class="slider-wrapper">
    <div class="slider">
      <div
        class="slides"
        :class="{ noTransition }"
        :style="{ transform: `translateX(-${currentIndex * 100}%)` }"
      >
        <div
          class="slide"
          v-for="(slide, index) in slides"
          :key="index"
          :style="{ backgroundImage: `url(${slide.image})` }"
        >
          <div class="overlay">
            <div class="content-animator" :key="currentIndex">
              <h1>{{ slide.title }}</h1>
              <p>{{ slide.text }}</p>
              <button>Shop Now</button>
            </div>
          </div>
        </div>

        <div
          class="slide"
          :style="{ backgroundImage: `url(${slides[0].image})` }"
        >
          <div class="overlay">
            <div class="content-animator" :key="currentIndex">
              <h1>{{ slides[0].title }}</h1>
              <p>{{ slides[0].text }}</p>
              <button>Shop Now</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "HomeSlider",
  data() {
    return {
      currentIndex: 0,
      interval: null,
      noTransition: false,
      slides: [
        {
          image: "https://images.unsplash.com/photo-1505740420928-5e560c06d30e",
          title: "Best Electronics",
          text: "Discover the latest gadgets & devices"
        },
        {
          image: "https://images.unsplash.com/photo-1523275335684-37898b6baf30",
          title: "Fashion Collection",
          text: "Trending styles for everyone"
        },
        {
          image: "https://images.unsplash.com/photo-1512820790803-83ca734da794",
          title: "Books & Knowledge",
          text: "Read, learn & grow smarter"
        }
      ]
    }
  },
  mounted() {
    this.startAutoSlide()
  },
  beforeUnmount() {
    clearInterval(this.interval)
  },
  methods: {
    startAutoSlide() {
      this.interval = setInterval(() => {
        this.nextSlide(true)
      }, 3500)
    },
    nextSlide(isAuto = false) {
      if (!isAuto) {
        clearInterval(this.interval)
      }
      this.currentIndex++
      if (this.currentIndex === this.slides.length) {
        setTimeout(() => {
          this.noTransition = true
          this.currentIndex = 0
          requestAnimationFrame(() => {
            this.noTransition = false
          })
        }, 1000)
      }
      if (!isAuto) {
        this.startAutoSlide()
      }
    },
    prevSlide() {
      clearInterval(this.interval)
      if (this.currentIndex === 0) {
        this.noTransition = true
        this.currentIndex = this.slides.length - 1
        requestAnimationFrame(() => {
          this.noTransition = false
        })
      } else {
        this.currentIndex--
      }
      this.startAutoSlide()
    }
  }
}
</script>

<style scoped>
/* APNAR ASOL LAYOUT - NO CHANGE */
.slider-wrapper {
  max-width: 1500px; 
  margin: 0 auto;
  padding: 0 5%; 
  background-color: #ffffff; 
}

.slider {
  width: 100%;
  height: 65vh; 
  overflow: hidden;
  position: relative;
  margin-top: 1px;
}

.slides {
  display: flex;
  height: 100%;
  transition: transform 1s ease-in-out;
}

.slides.noTransition {
  transition: none;
}

.slide {
  min-width: 100%;
  height: 100%; 
  background-size: 100% 100%; 
  background-repeat: no-repeat;
  background-position: center;
  position: relative;
}

/* OVERLAY EBONG TEXT IMPROVEMENTS */
.overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(to right, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.2) 100%);
  display: flex;
  flex-direction: column;
  justify-content: center;
  padding-left: 8%;
  color: #fff;
}

.overlay h1 {
  font-size: 3.5rem;
  margin-bottom: 15px;
  font-weight: 800;
  letter-spacing: -1px;
  line-height: 1.1;
}

.overlay p {
  font-size: 1.2rem;
  margin-bottom: 25px;
  max-width: 500px;
  color: rgba(255,255,255,0.9);
}

.overlay button {
  width: 170px;
  padding: 14px;
  background: #3b82f6;
  border: none;
  color: white;
  border-radius: 8px;
  cursor: pointer;
  font-weight: 700;
  font-size: 1rem;
  transition: 0.3s ease;
  box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
}

.overlay button:hover {
  background: #2563eb;
  transform: translateY(-2px);
}

/* ===== ANIMATION LOGIC ===== */
@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.content-animator h1 {
  animation: fadeInUp 0.8s ease backwards;
}

.content-animator p {
  animation: fadeInUp 0.8s ease 0.2s backwards;
}

.content-animator button {
  animation: fadeInUp 0.8s ease 0.4s backwards;
}

/* ===== RESPONSIVE SAME THAKBE ===== */
@media (max-width: 1024px) {
  .overlay h1 { font-size: 2.5rem; }
}

@media (max-width: 768px) {
  .slider-wrapper { padding: 0 15px; }
  .slider { height: 28vh; }
  .overlay h1 { font-size: 1.6rem; }
  .overlay p { font-size: 0.9rem; }
  .overlay button { width: 130px; padding: 10px; font-size: 12px; }
}

@media (max-width: 480px) {
  .slider { height: 24vh; }
  .overlay h1 { font-size: 1.3rem; }
}
</style>